<?php

namespace App\Http\Controllers\Api;

use App\Agency;
use App\Reference;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Job;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AgenciesController extends Controller
{

    public function __construct()
    {
        $this->middleware( "auth" )->except("fetch");
    }

    /**
     * Return agencies for admin agencies management and agency listings.
     * Supported filters: filter-own-agencies,filter-members, filter-no-members,filter-eligible-agencies
     * @param Request $request ['filter','search','sort','pager','page']
     * @return mixed
     */
    public function index( Request $request )
    {
        $query = Agency::with( "owner", "freelancers", "jobs", "media","links" );

        /* Handle custom sorting on relations */
        if ( $request->has( "sort" ) ) {

            switch ($request->sort['name']) {

                case "name":
                case "status":
                case "created_at":
                    $query->orderBy( $request->sort['name'], $request->sort['type']);
                    break;
                case "owner":
                    $query->leftJoin( 'users', 'users.id', '=', 'owner_id' )
                        ->orderBy( 'users.name', $request->sort['type'] )
                        ->select( "agencies.*");

                    break;
                case "members":
                    $query->withCount('freelancers');
                    $query->orderBy('freelancers_count', $request->sort['type']);
                    break;
                default:
                    $query->orderBy( "created_at", "desc" );
                    break;
            }

        } else {
            $query->orderBy( "created_at", "desc" );
        }



        if ( $request->has( "pager" ) ) {
            $page = $request->pager['page'];
            $size = $request->pager['size'];
        } else {
            $page = 1;
            $size = 25;
        }

        if ( $request->has( "search" ) && !empty( $request->search ) ) {
            $query->where( "name", "like", "%" . $request->search . "%" );
            $query->orWhere( "description", "like", "%" . $request->search . "%" );
        }

        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->orWhere(function($query) use($request) {
                $query->where( "owner_id", $request->user_id );
            });

        }

        if ( $request->has( "filter" ) && !empty( $request->filter ) ) {
            switch ($request->filter) {
                case "filter-own-agencies":
                    $query->where( "owner_id", \Auth::user()->id );
                    break;
                case "filter-members":
                    $query->has("freelancers");
                    break;
                case "filter-no-members":
                    $query->doesntHave( "freelancers" );
                    break;
                case "filter-eligible-agencies":
                    $query->where( "owner_id", "!=", \Auth::user()->id );
                    break;
            }
        }

        return $query->paginate( $size );
    }

    /**
     * Create a new agency
     * @param Request $request
     * @return mixed
     */
    public function create( Request $request )
    {
        if ( !\Auth::user()->isFreelancer&& !\Auth::user()->isAdmin() ) {
            return response()->json( [
                'message' => "Only freelancers can create new agencies.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->validate( $request, [
            'name'         => 'required|min:5|max:180',
            'description'   => 'required|min:5|max:2048'
        ] );

        /* Validate all files at least have a name */
        $fileIds = [];
        $fileAttributes = [];
        if ( $request->has( "files" ) ) {
            foreach ($request->get( "files" ) as $file) {
                if ( empty( $file['name'] ) ) {
                    return response()->json( [
                        'message' => "Please make sure all uploaded files have a name set.",
                        'status'  => "error"
                    ],
                        Response::HTTP_UNPROCESSABLE_ENTITY
                    );
                }

                $fileIds[] = $file['id'];
                $fileAttributes[$file['id']] = [
                    'name'        => $file['name'],
                    'description' => $file['description'],
                    'public'      => $file['public'],
                    'featured'    => $file['featured']
                ];
            }
        }

        $newAgency = $request->except( "token", "files", "media", "references","jobs","links","freelancers","clients","owner","avatar", "short_description","links" );
        $newAgency['agency_id'] = str_random( 5 );
        $newAgency['type'] = "standard";
        $newAgency['status'] = "active";

        $agency = \Auth::user()->ownedAgencies()->create($newAgency);
        $agency->attachMediaExtra( $fileIds, "files", $fileAttributes );

        $agency = Agency::with( "owner", "freelancers" )->where( "id", $agency->id )->first();
        $agencyData = $agency->toArray();
        $agencyData['files'] = $agency->getFiles();

        return response()->json( [
            'message' => "Agency saved successfully. Loading details for editing.",
            'new'     => true,
            'status'  => "success",
            'results' => $agencyData
        ] );

    }

    /**
     * Create a new reference job.
     * @param Request $request
     * @return mixed
     */
    public function createReference( Request $request )
    {
        if ( !\Auth::user()->isFreelancer && !\Auth::user()->isAdmin() ) {
            return response()->json( [
                'message' => "Only freelancers can create reference jobs.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Default validators */
        $validators = [
            'title'       => 'required|min:5|max:180',
            'description' => 'required|min:5|max:2048'
        ];

        /* Validate client email and message to client if requesting a reference */
        if ($request->invite_client) {
            $validators['client_email'] = 'required|email';
            $validators['client_message'] = 'required|min:80|max:2048';
        }

        $this->validate( $request, $validators );

        /* Validate all files at least have a name */
        $fileIds = [];
        $fileAttributes = [];
        if ( $request->has( "files" ) ) {
            foreach ($request->get( "files" ) as $file) {
                if ( empty( $file['name'] ) ) {
                    return response()->json( [
                        'message' => "Please make sure all uploaded files have a name set.",
                        'status'  => "error"
                    ],
                        Response::HTTP_UNPROCESSABLE_ENTITY
                    );
                }

                $fileIds[] = $file['id'];
                $fileAttributes[$file['id']] = [
                    'name'        => $file['name'],
                    'description' => $file['description']
                ];
            }
        }

        $newJob = $request->except( "token", "files", "media", "client_email","client_message" );
        $newJob['job_id'] = str_random( 5 );
        $newJob['status'] = "new";
        $newJob['end_reason'] = "";
        $newJob['completed'] = true;
        $newJob['payment_terms'] = "";

        $job = \Auth::user()->freelancerJobs()->create( $newJob );
        $job->attachMediaExtra( $fileIds, "files", $fileAttributes );

        /* Add the reference */
        if ($job) {
            $reference = new Reference([
                "job_id" => $job->id,
                "freelancer_id" => \Auth::user()->id,
                "name" => "Reference For Job " . $job->id,
                "rating" => 5,
                "review" => '',
                "type" => "self-reference",
                "status" => "active"
            ]);

            $reference->save();
        }

        return response()->json( [
            'message' => "Reference job saved successfully.",
            'new'     => true,
            'status'  => "success",
            'results' => $job
        ] );

    }

    /**
     * Load an existing agency
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch( Request $request, Agency $agency )
    {

        $agency = Agency::with( "owner", "freelancers", "links" )->where( "id", $agency->id )->first();
        $agencyData = $agency->toArray();
        $agencyData['files'] = $agency->getFiles();


        return response()->json( [
            'message' => "Agency details have been synced.",
            'status'  => "success",
            'results' => $agencyData
        ] );
    }

    /**
     * Update an existing agency
     * @param Request $request
     * @param Agency $agency
     * @return mixed
     */
    public function update( Request $request, Agency $agency )
    {
        $canEdit = false;
        if (\Auth::user()->id == $agency->owner_id
            || \Auth::user()->isAdmin()
        ) {
            $canEdit = true;
        }

        if ( !$canEdit ) {
            return response()->json( [
                'message' => "You do not have permission to edit this agency.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Default validators */
        $validators = [
            'name'       => 'required|min:5|max:180',
            'description' => 'required|min:5|max:2048'
        ];

        $this->validate( $request, $validators );

        /* Validate all files at least have a name */
        $fileIds = [];
        $fileAttributes = [];
        if ( $request->has( "files" ) ) {
            foreach ($request->get( "files" ) as $file) {
                if ( empty( $file['name'] ) ) {
                    return response()->json( [
                        'message' => "Please make sure all uploaded files have a name set.",
                        'status'  => "error"
                    ],
                        Response::HTTP_UNPROCESSABLE_ENTITY
                    );
                }

                $fileIds[] = $file['id'];
                $fileAttributes[$file['id']] = [
                    'name'        => $file['name'],
                    'description' => $file['description'],
                    'public'   => $file['public'],
                    'featured' => $file['featured']
                ];
            }
        }
        $update =
            $request->except( "token", "files", "references", "media", "jobs", "links", "freelancers", "clients", "owner", "avatar", "short_description","links" );


        if ( $agency->update( $update ) ) {
            $agency->syncMediaExtra( $fileIds, "files", $fileAttributes );

            $agency = Agency::with( "owner","freelancers", "links" )->where( "id", $agency->id )->first();
            $agencyData = $agency->toArray();
            $agencyData['files'] = $agency->getFiles();

            return response()->json( [
                'message' => "Agency updated successfully.",
                'new'     => false,
                'status'  => "success",
                'results' => $agencyData
            ] );
        }

        return response()->json( [
            'message' => "Error updating agency. Please Try Again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    /**
     * Update an existing agency owner
     * @param Request $request
     * @param Agency $agency
     * @return mixed
     */
    public function updateOwner( Request $request, Agency $agency )
    {
        $canEdit = false;
        if ( \Auth::user()->id == $agency->owner_id
            || \Auth::user()->isAdmin()
        ) {
            $canEdit = true;
        }

        if ( !$canEdit ) {
            return response()->json( [
                'message' => "You do not have permission to edit this agency.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!$request->has("new_owner_id")) {
            return response()->json( [
                'message' => "Invalid user id provided. The agency owner was not changed.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Assign the new owner, detach the new owner from mebers, attach the previous owner to members */
        $agency->freelancers()->detach( $request->get( "new_owner_id" ));
        $agency->freelancers()->attach( $agency->owner_id );
        $agency->owner_id = $request->get( "new_owner_id" );

        /**
         * @todo - Load new owner and set the agencies location to the owners lcation
         */

        if ( $agency->save() ) {
            return response()->json( [
                'message' => "Agency owner has been changed successfully.",
                'new'     => false,
                'status'  => "success",
                'results' => $agency->toArray()
            ] );
        }

        return response()->json( [
            'message' => "Error updating agency. The agency owner was not changed.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    /**
     * Delete an existing user from an agency
     * @param Request $request
     * @param Agency $agency
     * @return mixed
     */
    public function deleteUser( Request $request, Agency $agency )
    {
        $canEdit = false;
        if ( \Auth::user()->id == $agency->owner_id
            || \Auth::user()->isAdmin()
        ) {
            $canEdit = true;
        }

        if ( !$canEdit ) {
            return response()->json( [
                'message' => "You do not have permission to edit this agency.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ( !$request->has( "user_id" ) ) {
            return response()->json( [
                'message' => "Invalid user id provided. The user has not been removed.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Detach the user */
        $agency->freelancers()->detach( $request->get( "user_id" ) );

        if ( $agency->save() ) {
            return response()->json( [
                'message' => "User has been deleted successfully.",
                'new'     => false,
                'status'  => "success",
                'results' => $agency->toArray()
            ] );
        }

        return response()->json( [
            'message' => "Error updating agency. The agency owner was not changed.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    /**
     * Invite a user to join an agency
     * @param Request $request
     * @param Agency $agency
     * @return mixed
     */
    public function inviteUser( Request $request, Agency $agency ) {
        $canEdit = false;
        if ( \Auth::user()->id == $agency->owner_id
            || \Auth::user()->isAdmin()
        ) {
            $canEdit = true;
        }

        if ( !$canEdit ) {
            return response()->json( [
                'message' => "You do not have permission to edit this agency.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user = User::find( $request->get( "user_id" ) );
        if ( !$request->has( "user_id" ) || !$user ) {
            return response()->json( [
                'message' => "Invalid user id provided. No users were invited.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Fire invite event */
        event(new \App\Events\AgencyInviteEvent($agency, $user));


        return response()->json( [
            'message' => $user->name . " has been invited to your agency.",
            'new'     => false,
            'status'  => "success",
            'results' => $agency->toArray()
        ] );
    }

    /**
     * Confirm invitation to join an agency
     * @param Request $request
     * @param Agency $agency
     * @return mixed
     */
    public function joinAgency( Request $request, Agency $agency )
    {
        $agency->load("freelancers","owner");
        $members = $agency->freelancers->filter(function($value, $key) use ($request) {
            return $value->id == $request->get("user_id");
        });

        if ( count($members) > 0 || $agency->owner_id == $request->get("user_id") ) {
            return response()->json( [
                'message' => "You are already a member of this agency.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user = User::find( $request->get( "user_id" ) );
        if ( !$request->has( "user_id" ) || !$user ) {
            return response()->json( [
                'message' => "Invalid user id provided. Please try again.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        event( new \App\Events\AgencyInviteRequestEvent( $agency, $user ) );

        return response()->json( [
            'message' => "The leader of this agency has been notified of your request to join.",
            'new'     => false,
            'status'  => "success",
            'results' => $agency->toArray()
        ] );
    }

    /**
     * Delete an agency
     * @param Request $request
     * @param Agency $agency
     * @return mixed
     */
    public function delete( Request $request, Agency $agency )
    {

        /* Confirm user has permission to delete the job */
        $canDelete= false;
        if (
            \Auth::user()->id == $agency->owner_id
            || \Auth::user()->isAdmin()
        ) {
            $canDelete = true;
        }

        if ( !$canDelete ) {
            return response()->json( [
                'message' => "This agency can not be deleted. You may not have permission to delete it.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ( $agency->delete() ) {
            return response()->json( [
                'message' => "Agency deleted successfully.",
                'status'  => "success"
            ] );
        }

        return response()->json( [
            'message' => "Error deleting agency. Please Try Again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }


}

