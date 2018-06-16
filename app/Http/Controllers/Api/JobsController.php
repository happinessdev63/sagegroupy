<?php

namespace App\Http\Controllers\Api;

use App\Reference;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Job;
use App\Agency;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class JobsController extends Controller
{

    public function __construct()
    {
        $this->middleware( "auth:api" )->except("fetch","index");
    }

    /**
     * Return jobs for admin users management.
     * Supported filters: filter-active, filter-completed, filter-reference
     * @param Request $request ['filter','search','sort','pager','page']
     * @return mixed
     */
    public function index( Request $request )
    {
        $query = Job::with( "client", "agency", "freelancer", "references" )->withMedia();

        /* Handle custom sorting on relations */
        if ( $request->has( "sort" ) ) {

            switch ($request->sort['name']) {

                case "type":
                case "status":
                case "completed":
                case "title":
                case "created_at":
                    $query->orderBy( $request->sort['name'], $request->sort['type']);
                    break;
                case "client":
                    $query->leftJoin( 'users', 'users.id', '=', 'client_id' )
                        ->orderBy( 'users.name', $request->sort['type'] );

                    break;
                case "freelancer":
                    $query->leftJoin( 'users', 'users.id', '=', 'freelancer_id' )
                          ->orderBy( 'users.name', $request->sort['type'] );
                    break;
                case "agency":
                    $query->leftJoin( 'agencies', 'agencies.id', '=', 'jobs.agency_id' )
                          ->orderBy( 'agencies.name', $request->sort['type'] )
                          ->select("jobs.*","agencies.name");
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
            $query->where( "title", "like", "%" . $request->search . "%" );
            $query->orWhere( "description", "like", "%" . $request->search . "%" );
        }

        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->orWhere(function($query) use($request) {
                $query->where( "client_id", $request->user_id );
                $query->orWhere( "freelancer_id", $request->user_id );
            });
        }

        if ( $request->has( 'agency_id' ) && !empty( $request->agency_id ) ) {
            $query->orWhere( function ( $query ) use ( $request ) {
                $query->where( "agency_id", $request->agency_id);
            } );
        }

        if ( $request->has( "filter" ) && !empty( $request->filter ) ) {
            switch ($request->filter) {
                case "filter-active":
                    $query->where( "completed", false );
                    break;
                case "filter-completed":
                    $query->where( "completed", true);
                    break;
                case "filter-reference":
                    $query->where( "type", "reference" );
                    break;
                case "filter-standard":
                    $query->where( "type", "standard" );
                    break;
            }
        }
        // print_r($request->admin);
        if( $request->admin === 'false'){
          $query->where("public", true);
        }
        
        return $query->paginate( $size );
    }

    /**
     * Create a new job
     * @param Request $request
     */
    public function create( Request $request )
    {
        if ( !\Auth::user()->isClient && !\Auth::user()->isAdmin() ) {
            return response()->json( [
                'message' => "Only clients can create new jobs.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->validate( $request, [
            'title'         => 'required|min:5|max:180',
            'description'   => 'required|min:5|max:2048',
            'payment_terms' => 'required',
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
                    'description' => $file['description']
                ];
            }
        }

        $newJob = $request->except( "token", "files" );
        $newJob['job_id'] = str_random( 5 );
        $newJob['status'] = "new";
        $newJob['type'] = "standard";
        $newJob['end_reason'] = "";
        $newJob['payment_terms'] = "";

        $job = \Auth::user()->clientJobs()->create( $newJob );
        $job->attachMediaExtra( $fileIds, "files", $fileAttributes );

        return response()->json( [
            'message' => "Job saved successfully. Loading freelancers...",
            'new'     => true,
            'status'  => "success",
            'results' => $job
        ] );

    }

    /**
     * Create a new reference job.
     * @param Request $request
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
            'description' => 'required|min:30|max:2048'
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

        /* If this is an agency reference job */
        if ($request->get('agency_id',false)) {
            $agency = Agency::find($request->agency_id);
            $job = $agency->jobs()->create( $newJob );
            $job->attachMediaExtra( $fileIds, "files", $fileAttributes );
        } else {
            $job = \Auth::user()->freelancerJobs()->create( $newJob );
            $job->attachMediaExtra( $fileIds, "files", $fileAttributes );
        }


        if ($job) {
            /* Add the reference */
            $reference = new Reference([
                "job_id" => $job->id,
                "freelancer_id" => \Auth::user()->id,
                "name" => "Reference For Job " . $job->id,
                "rating" => 5,
                "review" => '',
                "type" => "self-reference",
                "status" => "active"
            ]);

            /* Set agency details if an agency is creating this job */
            if ( $request->get( 'agency_id', false ) ) {
                $reference->agency_id = $request->agency_id;
                $reference->freelancer_id = null;
            }

            $reference->save();

            /* Fire off client reference request invite event */
            if ($job->invite_client) {
                $client = new \App\User([
                    'email' => $request->client_email
                ]);
                if ( $request->get( 'agency_id', false ) ) {
                    event( new \App\Events\JobReferenceRequestEvent( $job, $client, $agency->owner, $request->client_message ) );
                } else {
                    event( new \App\Events\JobReferenceRequestEvent( $job, $client, $job->freelancer, $request->client_message ) );
                }
            }
        }

        return response()->json( [
            'message' => "Reference job saved successfully.",
            'new'     => true,
            'status'  => "success",
            'results' => $job
        ] );

    }

    /**
     * Edit an existing job
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch( Request $request, Job $job )
    {

        /*
        if (
            \Auth::user()->id != $job->client_id
            && !\Auth::user()->isAdmin()
            && $job->type != "reference" ) {
            return response()->json( [
                'message' => "You do not have permission to edit this job.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        */

        $job = Job::with("client","agency","freelancer","references")->where( "id", $job->id )->first();
        $jobData = $job->toArray();
        $jobMedia = $job->getMedia( "files" );

        $jobData['files'] = [];
        foreach ($jobMedia as $media) {
            $jobData['files'][] = [
                'name'        => $media->pivot->name,
                'description' => $media->pivot->description,
                'id'          => $media->id,
                'path'        => $media->getDiskPath(),
                'url'         => $media->getUrl(),
                'is_image'       => $media->aggregate_type == "image"
            ];
        }

        return response()->json( [
            'message' => "Job loaded for editing.",
            'status'  => "success",
            'results' => $jobData
        ] );
    }

    /**
     * Update an existing job
     * @param Request $request
     * @param Job $job
     * @return mixed
     */
    public function update( Request $request, Job $job )
    {
        $canEdit = false;
        if (\Auth::user()->id == $job->client_id
            || ($job->hasAgency() && $job->agency->owner->id == \Auth::user()->id)
            || \Auth::user()->isAdmin()
            || ($job->type == "reference" && \Auth::user()->id == $job->freelancer_id)
        ) {
            $canEdit = true;
        }

        if ( !$canEdit ) {
            return response()->json( [
                'message' => "You do not have permission to edit this job.",
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
        if ( $request->invite_client && $job->type == "reference" ) {
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
        $update = $request->except(
            "id",
            "role",
            "updated_at",
            "created_at",
            "files",
            "freelancer",
            "agency",
            "client",
            "references",
            "status",
            "type",
            "agency_id",
            "client_id",
            "freelancer_id",
            "client_message",
            "client_email",
            "short_description",
            "media",
            'end_date',
            'start_date',
            'nice_payment_terms'
        );


        if ( $job->update( $update ) ) {
            $job->syncMediaExtra( $fileIds, "files", $fileAttributes );

            /* Fire off client reference request invite event */
            if ( $job->invite_client ) {
                $client = new \App\User( [
                    'email' => $request->client_email
                ] );
                if ( $request->get( 'agency_id', false ) ) {
                    $agency = Agency::find( $request->agency_id );
                    event( new \App\Events\JobReferenceRequestEvent( $job, $client, $agency->owner, $request->client_message ) );
                } else {
                    event( new \App\Events\JobReferenceRequestEvent( $job, $client, $job->freelancer, $request->client_message ) );
                }
            }

            return response()->json( [
                'message' => "Job updated successfully.",
                'new'     => false,
                'status'  => "success",
                'results' => $job
            ] );
        }

        return response()->json( [
            'message' => "Error saving job. Please Try Again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    /**
     * End / close an existing job
     * @todo - Save ratings and associate them with the job
     * @param Request $request
     * @param Job $job
     * @return mixed
     */
    public function end( Request $request, Job $job )
    {

        /**
         * @todo - Add support/checks for agencies
         * @todo - Centralize job permission checks
         * Make sure user has permission to end the job.
         * Clients & admins can end the job.
         * Freelancers can end the job if it is a reference job.
         */
        $canEdit = false;
        if (
            \Auth::user()->id == $job->client_id
            || \Auth::user()->isAdmin()
            || (\Auth::user()->id == $job->freelancer_id && $job->type == "reference")
            || ($job->agency && \Auth::user()->id == $job->agency->owner->id && $job->type == "reference")
        ) {
            $canEdit = true;
        }

        if (!$canEdit) {
            return response()->json( [
                'message' => "You do not have permission to end this job.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Validate required fields */
        if ($job->type != "reference") {
            $this->validate( $request, [
                'end_reason'         => 'required|min:5|max:255',
                'freelancer_rating'    => 'required|integer|min:1|max:5',
                'freelancer_feedback'  => 'required|min:5|max:255',
            ] );
        }

        /* Update job */
        $job->completed = true;
        $job->completed_at = date("Y-m-d H:i:s");
        $job->end_reason = $request->end_reason;
        if ($job->save()) {

            /* Create the new feedback/reference */
            if ( $job->type != "reference" && $job->hasFreelancer() ) {
                $job->references()->create( [
                    'name'          => 'Client Feedback for ' . $job->name,
                    'review'        => $request->freelancer_feedback,
                    'rating'        => $request->freelancer_rating,
                    'freelancer_id' => $job->freelancer->id,
                    'client_id'     => $job->client->id,
                    'type'          => 'client',
                    'status'        => 'active'
                ] );
            }

            /* Create the new feedback/reference */
            if ( $job->type != "reference" && $job->hasAgency() ) {
                $job->references()->create( [
                    'name'          => 'Client Feedback for ' . $job->name,
                    'review'        => $request->freelancer_feedback,
                    'rating'        => $request->freelancer_rating,
                    'freelancer_id' => $job->agency->owner->id,
                    'agency_id'     => $job->agency->id,
                    'type'          => 'client',
                    'status'        => 'active'
                ] );
            }

            if ( $job->agency ) {
                event( new \App\Events\JobEndedEvent( $job, $job->client, $job->agency->owner, $job->agency ) );
            } else {
                event( new \App\Events\JobEndedEvent( $job, $job->client, $job->freelancer ) );
            }

            return response()->json( [
                'message' => "Job has been ended successfully.",
                'new'     => false,
                'status'  => "success",
                'results' => $job
            ] );
        }



        return response()->json( [
            'message' => "Error closing job. Please Try Again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    public function clientFeedback( Request $request, Job $job )
    {

        if ( !\Auth::user() || (!empty($job->client_id && $job->client_id != \Auth::user()->id))) {
            return response()->json( [
                'message' => "You do not have permission to update this job.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Update job and set client to the logged in user */
        $job->completed = true;
        $job->client_id = \Auth::user()->id;
        $job->completed_at = date( "Y-m-d H:i:s" );
        $job->end_reason = $request->end_reason;
        if ( $job->save() ) {

            /* Update the associated reference */
            $reference = $job->references()->first();

            if (!$reference) {
                return response()->json( [
                    'message' => "Error updating job feedback. Please Try Again.",
                    'status'  => "error"
                ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            /* Update reference with the clients feedback */
            $reference->name = 'Client Feedback for ' . $job->name;
            $reference->review = $request->freelancer_feedback;
            $reference->rating = $request->freelancer_rating;
            $reference->client_id = \Auth::user()->id;
            $reference->type == 'client';
            $reference->save();

            if ($job->agency) {
                event( new \App\Events\JobEndedEvent( $job, $job->client, $job->agency->owner, $job->agency ) );
            } else {
                event( new \App\Events\JobEndedEvent( $job, $job->client, $job->freelancer ) );
            }


            return response()->json( [
                'message' => "Feedback has been recorded successfully.",
                'new'     => false,
                'status'  => "success",
                'results' => $job
            ] );
        }

        return response()->json( [
            'message' => "Error updating job feedback. Please Try Again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    /**
     * Delete a users profile
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    public function delete( Request $request, Job $job )
    {

        /* Confirm user has permission to delete the job */
        $canDelete= false;
        if (
            (\Auth::user()->id == $job->client_id && empty($job->freelancer_id))
            || \Auth::user()->isAdmin()
            || (\Auth::user()->id == $job->freelancer_id && $job->type == "reference")
        ) {
            $canDelete = true;
        }

        if ( !$canDelete ) {
            return response()->json( [
                'message' => "This job can not be deleted. It may have an active freelancer or you may not have permission to delete it.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ( $job->delete() ) {
            return response()->json( [
                'message' => "Job deleted successfully.",
                'status'  => "success"
            ] );
        }

        return response()->json( [
            'message' => "Error deleting job. Please Try Again.",
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
    public function inviteUser( Request $request, Job $job )
    {
        $canEdit = false;
        if ( \Auth::user()->id == $job->client_id
            || \Auth::user()->isAdmin()
        ) {
            $canEdit = true;
        }

        if ( !$canEdit ) {
            return response()->json( [
                'message' => "You do not have permission to invite a user to this job.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Handle freelancer invitiations */
        if (!$request->has('agency_id')) {
            $freelancer = User::find( $request->get( "freelancer_id" ) );
            $user = \Auth::user();
            if ( !$request->has( "freelancer_id" ) || !$user || !$freelancer ) {
                return response()->json( [
                    'message' => "Invalid user ID provided. No users were invited.",
                    'status'  => "error"
                ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            /* Fire invite event */
            event( new \App\Events\JobInviteEvent( $job, $user, $freelancer, $request->get( 'message' ) ) );

            $userName = $freelancer->name;
        }

        /* Handle agency invitations */
        if ( $request->has( 'agency_id' ) ) {
            $agency = \App\Agency::find( $request->get( "agency_id" ) );
            $user = \Auth::user();
            if ( !$request->has( "agency_id" ) || !$user || !$agency ) {
                return response()->json( [
                    'message' => "Invalid agency ID provided. No agencies were invited.",
                    'status'  => "error"
                ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            /* Fire invite event */
            event( new \App\Events\AgencyJobInviteEvent( $job, $user, $agency, $request->get( 'message' ) ) );

            $userName = $agency->name;
        }


        return response()->json( [
            'message' => $userName . " has been invited to your job.",
            'new'     => false,
            'status'  => "success",
            'results' => $job->toArray()
        ] );
    }


}
