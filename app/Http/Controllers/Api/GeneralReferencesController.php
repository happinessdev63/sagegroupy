<?php

namespace App\Http\Controllers\Api;

use App\Reference;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\GeneralReference;
use App\Agency;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class GeneralReferencesController extends Controller
{

    public function __construct()
    {
        $this->middleware( "auth:api" )->except("fetch","index");
    }

    /**
     * Return generalReferences for admin users management.
     * Supported filters: filter-active, filter-completed, filter-reference
     * @param Request $request ['filter','search','sort','pager','page']
     * @return mixed
     */
    public function index( Request $request )
    {
        $query = GeneralReference::with( "user" )->withMedia();

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
            $query->where( "user_id", $request->user_id );
        }

        if ( $request->has( "filter" ) && !empty( $request->filter ) ) {
            switch ($request->filter) {
                case "filter-active":
                    $query->where( "active", false );
                    break;
            }
        }

        return $query->paginate( $size );
    }

    /**
     * Create a new generalReference
     * @param Request $request
     */
    public function create( Request $request )
    {
        if ( !\Auth::guard('api')->user()->is_freelancer && !\Auth::guard('api')->user()->isAdmin() ) {
            return response()->json( [
                'message' => "Only freelancers can create new references.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validators = [
            'title'       => 'required|min:5|max:180',
            'description' => 'required|min:5|max:2048'
        ];

        if ($request->get('type') != 'imported') {
            $validators['client_email'] = 'required|email';
            $validators['client_message'] = 'required|min:30|max:2048';
        }

        $this->validate( $request, $validators);

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

        $newGeneralReference = $request->except( "token", "files" );
        $newGeneralReference['status'] = "active";
        $newGeneralReference['type'] = $request->type;

        $generalReference = \Auth::guard('api')->user()->generalReferences()->create( $newGeneralReference );
        $generalReference->attachMediaExtra( $fileIds, "files", $fileAttributes );

        /* Send client reference request if this is a general reference and not an imported reference */
        if ($request->type == 'reference') {
            $client = new \App\User( [
                'email' => $request->client_email
            ] );

            event( new \App\Events\GeneralReferenceRequestEvent( $generalReference, $client, \Auth::guard('api')->user(), $request->client_message ) );
        }


        return response()->json( [
            'message' => "Reference saved successfully.",
            'new'     => true,
            'status'  => "success",
            'results' => $generalReference
        ] );

    }

    /**
     * Edit an existing generalReference
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch( Request $request, GeneralReference $generalReference )
    {

        $generalReference = GeneralReference::with("user")->where( "id", $generalReference->id )->first();
        $generalReferenceData = $generalReference->toArray();
        $generalReferenceMedia = $generalReference->getMedia( "files" );

        $generalReferenceData['files'] = [];
        foreach ($generalReferenceMedia as $media) {
            $generalReferenceData['files'][] = [
                'name'        => $media->pivot->name,
                'description' => $media->pivot->description,
                'id'          => $media->id,
                'path'        => $media->getDiskPath(),
                'url'         => $media->getUrl(),
                'is_image'       => $media->aggregate_type == "image"
            ];
        }

        return response()->json( [
            'message' => "Reference loaded for editing.",
            'status'  => "success",
            'results' => $generalReferenceData
        ] );
    }

    /**
     * Update an existing generalReference
     * @param Request $request
     * @param GeneralReference $generalReference
     * @return mixed
     */
    public function update( Request $request, GeneralReference $generalReference )
    {
        $canEdit = false;
        if (\Auth::guard('api')->user()->id == $generalReference->user_id
            || \Auth::guard('api')->user()->isAdmin()
        ) {
            $canEdit = true;
        }

        if ( !$canEdit ) {
            return response()->json( [
                'message' => "You do not have permission to edit this reference.",
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

        $update = [
            'title' => $request->title,
            'description' => $request->description
        ];

        if ($request->type == 'imported') {
            $update['url'] = $request->url;
            $update['url_description'] = $request->url_description;
        }

        if ( $generalReference->update( $update ) ) {
            $generalReference->syncMediaExtra( $fileIds, "files", $fileAttributes );


            return response()->json( [
                'message' => "Reference updated successfully.",
                'new'     => false,
                'status'  => "success",
                'results' => $generalReference
            ] );
        }

        return response()->json( [
            'message' => "Error saving reference. Please try again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    /**
     * Update an existing generalReference with new client feedback
     * @param Request $request
     * @param GeneralReference $generalReference
     * @return mixed
     */
    public function clientFeedback( Request $request, GeneralReference $generalReference )
    {
        $canEdit = false;
        if ( \Auth::guard('api')->user()->id == $generalReference->user_id
            || \Auth::guard('api')->user()->isAdmin()
        ) {
            $canEdit = true;
        }

        /* If no client is assigned the user can edit the reference */
        if (\Auth::guard('api')->user() && empty($generalReference->client_id)) {
            $canEdit = true;
        }

        if ( !$canEdit ) {
            return response()->json( [
                'message' => "You do not have permission to edit this reference.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Default validators */
        $validators = [
            'review'       => 'required|min:20|max:2048',
        ];

        $this->validate( $request, $validators );

        $update = [
            'review'       => $request->review,
            'client_id' => \Auth::guard('api')->user()->id
        ];

        if ( $generalReference->update( $update ) ) {
            return response()->json( [
                'message' => "Reference updated successfully.",
                'new'     => false,
                'status'  => "success",
                'results' => $generalReference
            ] );
        }

        return response()->json( [
            'message' => "Error saving reference. Please try again.",
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
    public function delete( Request $request, GeneralReference $generalReference )
    {

        /* Confirm user has permission to delete the generalReference */
        $canDelete= false;
        if (
            \Auth::guard('api')->user()->id == $generalReference->user_id
            || \Auth::guard('api')->user()->isAdmin()
        ) {
            $canDelete = true;
        }

        if ( !$canDelete ) {
            return response()->json( [
                'message' => "Reference can not be deleted or you may not have permission to delete it.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ( $generalReference->delete() ) {
            return response()->json( [
                'message' => "Reference deleted successfully.",
                'status'  => "success"
            ] );
        }

        return response()->json( [
            'message' => "Error deleting reference. Please Try Again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

}
