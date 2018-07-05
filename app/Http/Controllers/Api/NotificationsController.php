<?php

namespace App\Http\Controllers\Api;

use App\Reference;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Notification;
use App\Invite;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Auth;

class NotificationsController extends Controller
{

    public function __construct()
    {
        $this->middleware( "auth:api" )->except("fetch");
    }

    /**
     * Return notifications for admin users management.
     * Supported filters: filter-active, filter-completed, filter-reference
     * @param Request $request ['filter','search','sort','pager','page']
     * @return mixed
     */
    public function index( Request $request )
    {
        // dd(Auth::guard('api')->user());die();
        $query = Notification::with( "sender", "receiver", "agency", "fromAgency", "job" );

        /* Handle custom sorting on relations */
        if (!$request->has("countOnly")) {
            if ( $request->has( "sort" ) ) {

                switch ($request->sort['name']) {

                    case "type":
                    case "status":
                    case "created_at":
                        $query->orderBy( $request->sort['name'], $request->sort['type'] );
                        break;
                    case "sender":
                        $query->leftJoin( 'users', 'users.id', '=', 'from_user_id' )
                            ->orderBy( 'users.name', $request->sort['type'] );

                        break;
                    case "receiver":
                        $query->leftJoin( 'users', 'users.id', '=', 'to_user_id' )
                            ->orderBy( 'users.name', $request->sort['type'] );
                        break;
                    case "agency":
                        $query->leftJoin( 'agencies', 'agencies.id', '=', 'notifications.agency_id' )
                            ->orderBy( 'agencies.name', $request->sort['type'] )
                            ->select( "notifications.*", "agencies.name" );
                        break;
                    case "job":
                        $query->leftJoin( 'jobs', 'jobs.id', '=', 'notifications.job_id' )
                            ->orderBy( 'jobs.title', $request->sort['type'] )
                            ->select( "notifications.*", "jobs.title" );
                        break;
                    default:
                        $query->orderBy( "created_at", "desc" );
                        break;
                }

            } else {
                $query->orderBy( "created_at", "desc" );
            }
        }




        if ( $request->has( "pager" ) ) {
            $page = $request->pager['page'];
            $size = $request->pager['size'];
        } else {
            $page = 1;
            $size = 50;
        }

        if ( $request->has( "search" ) && !empty( $request->search ) ) {
            $query->where( "title", "like", "%" . $request->search . "%" );
            $query->orWhere( "description", "like", "%" . $request->search . "%" );
        }

        if ( $request->has( 'owner' ) && !empty( $request->owner ) && !$request->has( "chats" ) && $request->filter != "filter-sent") {
                $query->where( "owner_id", $request->owner );
                $query->where( "owner_type", "user" );

                if (empty($request->agency)) {
                    $query->where( "to_user_id", $request->owner );
                }
        }

        if ( $request->has( 'agency' ) && !empty( $request->agency ) ) {
            $query->where( "agency_id", $request->agency );
            $query->where( "owner_type", "agency" );
        }

        if ( $request->has( 'status' ) && !empty( $request->status ) && !$request->has("filter") ) {
            $query->where( "status", $request->status );
        }

        if ( $request->has( 'type' ) && !empty( $request->type ) ) {
            $query->where( "type", $request->type );
        }

        if ($request->has('sender') && !empty($request->sender) && !$request->has( "chats" )) {
                $query->where( "from_user_id", $request->sender );
        }

        if ( $request->has( 'receiver' ) && !empty( $request->receiver ) && !$request->has( "chats" )) {
                $query->where( "to_user_id", $request->receiver );
        }

        if ($request->has("chats")) {

            $query->where( function ( $query ) use ( $request ) {
                $query->orWhere( function ( $query ) use ( $request ) {
                    $query->Where( "to_user_id", $request->owner);
                    $query->Where( "from_user_id", $request->sender );
                });
                $query->orWhere( function ( $query ) use ( $request ) {
                    $query->Where( "to_user_id", $request->sender );
                    $query->Where( "from_user_id", $request->owner );
                } );
            } );

        }

        if ( $request->has( "filter" ) && !empty( $request->filter ) ) {
            switch ($request->filter) {
                case "filter-unread":
                    $query->where( "status", "unread" );
                    break;
                case "filter-read":
                    $query->where( "status", "read" );
                    break;
                case "filter-archived":
                    $query->where( "status", "archived" );
                    break;
                case "filter-sent":
                    $query->where( "from_user_id", \Auth::guard('api')->user()->id );
                    break;
            }
        }


        if ( $request->has( "countOnly" ) && $request->countOnly == 'true' ) {
            return $query->count();
        }

        if ($request->has("paginate") && $request->paginate == 'false') {
            $results = $query->get();

            /* Group multiple notifications from same user into a single notification */
            if ($request->get('group','false') == 'true' && $request->filter != 'filter-sent') {
                $userGroups = $results->groupBy('from_user_id');
                return array_values( $userGroups->toArray());
            }

            /* Group multiple notifications from same user into a single notification */
            if ( $request->get( 'group', 'false' ) == 'true' && $request->filter == 'filter-sent' ) {
                $userGroups = $results->groupBy( 'to_user_id' );
                return array_values($userGroups->toArray());
            }

            return $results;

        }

        return $query->paginate( $size );
    }

    /**
     * Create a new notification
     * @param Request $request
     */
    public function create( Request $request )
    {
        $this->validate( $request, [
            'type'         => 'required|min:5|max:180',
            'message'   => 'required|min:5|max:2048',
            'status' => 'required',
        ] );

        $newNotification = $request->except( "token");
        $notification = Notification::create($newNotification);

        return response()->json( [
            'message' => "Notification saved successfully.",
            'new'     => true,
            'status'  => "success",
            'results' => $notification
        ] );

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function sendMessage( Request $request )
    {
        $this->validate( $request, [
            'message' => 'required|min:1|max:2048'
        ] );

        $sender = \App\User::find($request->from_user_id);
        $receiver = \App\User::find($request->to_user_id);

        if (!$sender || !$receiver) {
            return response()->json( [
                'message' => "Invalid sender or recipient, please try again.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        event(new \App\Events\UserMessageEvent($sender, $receiver, $request->title, $request->message, $request->type));

        return response()->json( [
            'message' => "Message sent successfully.",
            'new'     => true,
            'status'  => "success",
            'results' => []
        ] );

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function sendBulkMessages( Request $request )
    {
        if (!\Auth::guard('api')->user()->isAdmin()) {
            return response()->json( [
                'message' => "You do not have permission to perform this action.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        switch ($request->segment) {
            case "all":
            default:
                $users = \App\User::where("role","!=","admin")->get();
                break;
            case "active":
                $users = \App\User::where( "role", "!=", "admin" )->get();
                break;
            case "freelancers":
                $users = \App\User::where( "role", "LIKE", "%freelancer%" )->get();
                break;
            case "clients":
                $users = \App\User::where( "role", "LIKE", "%client%" )->get();
                break;
            case "agency_owners":
                $users = \App\User::has('ownedAgencies')->get();
                break;
        }

        foreach ($users as $user) {
            event( new \App\Events\UserMessageEvent( \Auth::guard('api')->user(), $user, $request->title, $request->message, "user-message" ) );
        }


        return response()->json( [
            'message' => $users->count() . " messages sent successfully.",
            'new'     => true,
            'status'  => "success",
            'results' => []
        ] );

    }

    /**
     * Edit an existing notification
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch( Request $request, Notification $notification )
    {
        $notification = Notification::with( "sender", "receiver", "agency", "fromAgency", "job")->where( "id", $notification->id )->first();
        $notificationData = $notification->toArray();

        return response()->json( [
            'message' => "Notification loaded for editing.",
            'status'  => "success",
            'results' => $notificationData
        ] );
    }

    /**
     * Update an existing notification
     * @param Request $request
     * @param Notification $notification
     * @return mixed
     */
    public function update( Request $request, Notification $notification )
    {
        $canEdit = true;

        if ( !$canEdit ) {
            return response()->json( [
                'message' => "You do not have permission to edit this notification.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->validate( $request, [
            'type'    => 'required|min:5|max:180',
            'message' => 'required|min:5|max:2048',
            'status'  => 'required',
        ] );


        $update = $request->except(
            "id",
            "sender",
            "receiver",
            "agency",
            "job"
        );


        if ( $notification->update( $update ) ) {
            return response()->json( [
                'message' => "Notification updated successfully.",
                'new'     => false,
                'status'  => "success",
                'results' => $notification
            ] );
        }

        return response()->json( [
            'message' => "Error saving notification. Please Try Again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    /**
     * Update a notification status
     * @param Request $request
     * @param Notification $notification
     * @return mixed
     */
    public function updateStatus( Request $request, Notification $notification, $status )
    {
        $notification->status = $status;
        //
        if ($notification->save()) {
            return response()->json( [
                'message' => "Notification has been updated successfully.",
                'new'     => false,
                'status'  => "success",
                'results' => $notification
            ] );
        }

        return response()->json( [
            'message' => "Error archiving notification. Please Try Again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    /**
     * Report user feed back / contact request.
     */
    public function userFeedback(Request $request )
    {
        $this->validate( $request, [
            'message' => 'required|min:5|max:2048',
        ] );


        $admin = \App\User::where('role','admin')->orderBy('id','ASC')->first();
        $user = \Auth::guard('api')->user();
        /* Send feedback email to admin */

        $admin->notify( new \App\Notifications\UserFeedbackMessage( $user, $request->message, $request->skills) );

        return response()->json( [
            'message' => "Your feedback has been sent successfully.",
            'new'     => false,
            'status'  => "success",
        ] );

    }

    /**
     * Invite friends (up to 5 names/emails)
     * @param Request $request
     * @return mixed
     */
    public function inviteFriends( Request $request )
    {

        $this->validate( $request, [
            'friendInvites' => 'required|array',
        ] );

        $user = \Auth::guard('api')->user();
        foreach ($request->friendInvites as $friend) {
            if ( !filter_var( $friend['email'], FILTER_VALIDATE_EMAIL ) ) {
                continue;
            }

            /* Create a mock user */
            $friend = new \App\User([
                'name' => $friend['name'],
                'email' => trim($friend['email'])
            ]);

            /* Send invite message */
            $friend->notify( new \App\Notifications\UserInvited( $friend, $user) );
        }

        return response()->json( [
            'message' => "Your invitations were sent successfully.",
            'new'     => false,
            'status'  => "success",
        ] );

    }

    /**
     * Accept an agency invite
     * @param Request $request
     * @param Notification $notification
     * @return mixed
     */
    public function acceptInvite( Request $request, Notification $notification )
    {

        if (empty($notification->agency_id)) {
            return response()->json( [
                'message' => "Error accepting invite, invalid agency.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $notification->load("agency","receiver");
        if (!$notification->agency || !$notification->receiver) {
            return response()->json( [
                'message' => "Error accepting invite, invalid agency.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        event(new \App\Events\AgencyInviteResponseEvent($notification->agency, $notification->receiver, "accept"));

        return response()->json( [
            'message' => "Invitation has been accepted, you are now a member of " . $notification->agency->name,
            'new'     => false,
            'status'  => "success",
            'results' => $notification
        ] );

    }

    /**
     * @param Request $request
     * @param Notification $notification
     * @return mixed
     */
    public function rejectInvite( Request $request, Notification $notification )
    {

        // if ( empty( $notification->agency_id ) ) {
        //     return response()->json( [
        //         'message' => "Error rejecting invite, invalid agency.",
        //         'status'  => "error"
        //     ],
        //         Response::HTTP_UNPROCESSABLE_ENTITY
        //     );
        // }
        //
        // $notification->load( "agency", "receiver" );
        // if ( !$notification->agency || !$notification->receiver ) {
        //     return response()->json( [
        //         'message' => "Error rejecting invite, invalid agency.",
        //         'status'  => "error"
        //     ],
        //         Response::HTTP_UNPROCESSABLE_ENTITY
        //     );
        // }
        //
        // event( new \App\Events\AgencyInviteResponseEvent( $notification->agency, $notification->receiver, "reject" ) );

        return response()->json( [
            'message' => "Invitation has been rejected and the agency has been notified",
            'new'     => false,
            'status'  => "success",
            'results' => $notification
        ] );

    }

    /**
     * @param Request $request
     * @param Notification $notification
     * @return mixed
     */
    public function revokeInvite( Request $request, Notification $notification )
    {

        if ( empty( $notification->agency_id ) ) {
            return response()->json( [
                'message' => "Error revoking invite, invalid agency.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $notification->load( "agency", "receiver" );
        if ( !$notification->agency || !$notification->receiver ) {
            return response()->json( [
                'message' => "Error revoking invite, invalid agency.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        event( new \App\Events\AgencyInviteResponseEvent( $notification->agency, $notification->receiver, "revoke" ) );

        return response()->json( [
            'message' => "Invitation has been revoked successfully.",
            'new'     => false,
            'status'  => "success",
            'results' => $notification
        ] );

    }

    /**
     * Accept an job invite
     * @param Request $request
     * @param Notification $notification
     * @return mixed
     */
    public function acceptJobInvite( Request $request, Notification $notification )
    {

        if ( empty( $notification->job_id ) ) {
            return response()->json( [
                'message' => "Error accepting invite, invalid job id provided.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $notification->load( "job", "receiver", "sender" );

        /* Agency Job Invites */
        if ($notification->agency_id) {
            $notification->load( "agency","agency.owner" );
            if ( !$notification->job || !$notification->agency || !$notification->sender || !$notification->agency->owner ) {
                return response()->json( [
                    'message' => "Error accepting invite, invalid job or client.",
                    'status'  => "error"
                ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            event( new \App\Events\JobInviteResponseEvent( $notification->job, $notification->sender, $notification->agency->owner, "accept", true, $notification->agency->id ) );

        /* Standard Job Invites */
        } else {
            if ( !$notification->job || !$notification->receiver || !$notification->sender ) {
                return response()->json( [
                    'message' => "Error accepting invite, invalid job or client.",
                    'status'  => "error"
                ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            event( new \App\Events\JobInviteResponseEvent( $notification->job, $notification->sender, $notification->receiver, "accept" ) );
        }


        return response()->json( [
            'message' => "Invitation has been accepted, you will offer this job from client soon",
            'new'     => false,
            'status'  => "success",
            'results' => $notification
        ] );

    }

    /**
     * Reject a job invitiation.
     * @param Request $request
     * @param Notification $notification
     * @return mixed
     */
    public function rejectJobInvite( Request $request, Notification $notification )
    {

        if ( empty( $notification->job_id ) ) {
            return response()->json( [
                'message' => "Error accepting invite, invalid job id provided.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $notification->load( "job", "receiver", "sender" );
        /* Agency Job Invites */
        if ( $notification->agency_id ) {
            $notification->load( "agency", "agency.owner" );
            if ( !$notification->job || !$notification->agency || !$notification->sender || !$notification->agency->owner ) {
                return response()->json( [
                    'message' => "Error accepting invite, invalid job or client.",
                    'status'  => "error"
                ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            event( new \App\Events\JobInviteResponseEvent( $notification->job, $notification->sender, $notification->agency->owner, "reject", true, $notification->agency->id ) );

        /* Standard Job Invites */
        } else {
            if ( !$notification->job || !$notification->receiver || !$notification->sender ) {
                return response()->json( [
                    'message' => "Error accepting invite, invalid job or client.",
                    'status'  => "error"
                ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            event( new \App\Events\JobInviteResponseEvent( $notification->job, $notification->sender, $notification->receiver, "reject" ) );
        }

        return response()->json( [
            'message' => "Invitation has been rejected and the client has been notified.",
            'new'     => false,
            'status'  => "success",
            'results' => $notification
        ] );

    }
    /**
     * Award a job to freelancer.
     * @param Request $request
     * @param Notification $notification
     * @return mixed
     */
    public function awardJob( Request $request, Notification $notification )
    {
        if ( empty( $notification->job_id ) ) {
            return response()->json( [
                'message' => "Error accepting invite, invalid job id provided.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $notification->load( "job", "receiver", "sender" );

        if ( !$notification->job || !$notification->receiver || !$notification->sender ) {
            return response()->json( [
                'message' => "Error accepting invite, invalid job or client.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $query = DB::table('invites')
                  ->where('job_id', $notification->job_id)
                  ->select('freelancer_id')
                  ->first();
        $freelancer = User::where('id', $query->freelancer_id)->first();

        event( new \App\Events\JobInviteResponseEvent( $notification->job, $notification->sender, $freelancer, "award" ) );

        return response()->json( [
            'message' => "This job was awarded to your freelancer successfully.",
            'new'     => false,
            'status'  => "success",
            'results' => $notification
        ] );

    }

    /**
     * Mark all notifications as read.
     * @param Request $request
     * @param Notification $notification
     * @return mixed
     */
    public function markAllRead( Request $request )
    {
        if (!\Auth::guard('api')->user()) {
            return response()->json( [
                'message' => "Error updating notifications.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        Notification::where("owner_id",\Auth::guard('api')->user()->id)->where("status","unread")->update(['status' => 'read']);

        return response()->json( [
            'message' => "All notifications have been marked as read.",
            'new'     => false,
            'status'  => "success",
            'results' => []
        ] );

    }

    /**
     * Delete a notification
     * @param Request $request
     * @param Notification $notification
     * @return mixed
     */
    public function delete( Request $request, Notification $notification )
    {

        /* Confirm user has permission to delete the notification */
        $canDelete = true;

        if ( $notification->delete() ) {
            return response()->json( [
                'message' => "Notification deleted successfully.",
                'status'  => "success"
            ] );
        }

        return response()->json( [
            'message' => "Error deleting notification. Please Try Again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

}
