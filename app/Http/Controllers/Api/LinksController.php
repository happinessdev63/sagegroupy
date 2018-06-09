<?php

namespace App\Http\Controllers\Api;

use App\Reference;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Link;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class LinksController extends Controller
{

    public function __construct()
    {
        $this->middleware( "auth" )->except("fetch","index");
    }

    /**
     * Return links for admin users management.
     * Supported filters: filter-active, filter-completed, filter-reference
     * @param Request $request ['filter','search','sort','pager','page']
     * @return mixed
     */
    public function index( Request $request )
    {
        $query = Link::with( "user", "agency")->withMedia();

        /* Handle custom sorting on relations */
        if ( $request->has( "sort" ) ) {

            switch ($request->sort['name']) {

                case "type":
                case "status":
                case "created_at":
                    $query->orderBy( $request->sort['name'], $request->sort['type']);
                    break;
                case "agency":
                    $query->leftJoin( 'agencies', 'agencies.id', '=', 'links.agency_id' )
                          ->orderBy( 'agencies.name', $request->sort['type'] )
                          ->select("links.*","agencies.name");
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

        if ( $request->has( 'agency_id' ) && !empty( $request->agency_id ) ) {
            $query->where( "agency_id", $request->agency_id );
        }

        if ( $request->has( "filter" ) && !empty( $request->filter ) ) {
            switch ($request->filter) {
                case "filter-active":
                    $query->where( "status", "active" );
                    break;
            }
        }

        return $query->paginate( $size );
    }

    /**
     * Create a new link
     * @param Request $request
     */
    public function create( Request $request )
    {
        if ( !\Auth::user() ) {
            return response()->json( [
                'message' => "You do not have permission to perform this action.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Auto add http:// if not present */
        if ($request->has('url')) {
            $input = $request->all();
            if (stristr($request->url,"http://") == false && stristr( $request->url, "https://" ) == false) {
                $input['url'] = "http://" . $request->url;
                $request->replace($input);
            }
        }

        $this->validate( $request, [
            'url' => 'required|url',
            'title'       => 'required|min:5|max:255',
            'description' => 'nullable|min:5|max:2048',
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

        if (!$request->has('agency_id')) {
            $link = \Auth::user()->links()->create( [
                'title'       => $request->title,
                'url'         => $request->url,
                'description' => $request->description,
                'status'      => 'active'
            ] );
        } else {
            $agency = \App\Agency::find($request->agency_id);
            if (!$agency) {
                return response()->json( [
                    'message' => "Invalid agency ID provided.",
                    'status'  => "error"
                ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
            $link = $agency->links()->create( [
                'title'       => $request->title,
                'url'         => $request->url,
                'description' => $request->description,
                'status'      => 'active'
            ] );
        }

        $link->attachMediaExtra( $fileIds, "files", $fileAttributes );

        return response()->json( [
            'message' => "Link added successfully.",
            'new'     => true,
            'status'  => "success",
            'results' => $link
        ] );

    }

    /**
     * Edit an existing link
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch( Request $request, Link $link )
    {

        $link = Link::with("user","agency")->where( "id", $link->id )->first();
        $linkData = $link->toArray();
        $linkMedia = $link->getMedia( "files" );

        $linkData['files'] = [];
        foreach ($linkMedia as $media) {
            $linkData['files'][] = [
                'name'        => $media->pivot->name,
                'description' => $media->pivot->description,
                'id'          => $media->id,
                'path'        => $media->getDiskPath(),
                'url'         => $media->getUrl(),
                'is_image'       => $media->aggregate_type == "image"
            ];
        }

        return response()->json( [
            'message' => "Link loaded for editing.",
            'status'  => "success",
            'results' => $linkData
        ] );
    }

    /**
     * Update an existing link
     * @param Request $request
     * @param Link $link
     * @return mixed
     */
    public function update( Request $request, Link $link )
    {
        $canEdit = true;

        if ( !$canEdit ) {
            return response()->json( [
                'message' => "You do not have permission to edit this link.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Default validators */
        $this->validate( $request, [
            'url'         => 'required|url',
            'title'       => 'required|min:5|max:255',
            'description' => 'nullable|min:5|max:2048',
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

        if ( $link->update( [
            'title'       => $request->title,
            'url'         => $request->url,
            'description' => $request->description,
            'status'      => $request->get('status',$link->status)
            ] ) ) {
            $link->syncMediaExtra( $fileIds, "files", $fileAttributes );
            return response()->json( [
                'message' => "Link updated successfully.",
                'new'     => false,
                'status'  => "success",
                'results' => $link
            ] );
        }

        return response()->json( [
            'message' => "Error saving link. Please Try Again.",
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
    public function delete( Request $request, Link $link )
    {

        /* Confirm user has permission to delete the link */
        $canDelete= true;

        if ( !$canDelete ) {
            return response()->json( [
                'message' => "This link can not be deleted. You may not have permission to delete it.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ( $link->delete() ) {
            return response()->json( [
                'message' => "Link deleted successfully.",
                'status'  => "success"
            ] );
        }

        return response()->json( [
            'message' => "Error deleting link. Please try again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }


}

