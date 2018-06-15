<?php

namespace App\Http\Controllers\Api;

use App\Notification;
use App\Recommendation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth:api");
    }

    /**
     * Return users for freelancer search
     * Supported filters: none
     * @param Request $request ['filter','search','sort','pager','page']
     * @return mixed
     */
    public function index(Request $request  )
    {
        $query =  \App\User::select( "id", "name", "email", "role", "created_at" );

        if ($request->has("sort")) {
            $sortBy = $request->sort['name'];
            $sortDirection = $request->sort['type'];
            $query->orderBy( $sortBy, $sortDirection );
        }

        if ( $request->has( "pager" ) ) {
            $page = $request->pager['page'];
            $size = $request->pager['size'];
        }

        if ($request->has("search") && !empty($request->search)) {
            $query->where( function ( $query ) use ($request) {
                $query->where( "name", "like", "%" . $request->search . "%" );
                $query->orWhere( "email", "like", "%" . $request->search . "%" );
            } );
        }

        if ($request->has("filter") && !empty($request->filter)) {
            switch ($request->filter) {
                case "filter-clients":
                    $query->where("role","client");
                    break;
                case "filter-freelancers":
                    $query->where("role","like","%freelancer%");
                    break;
                case "filter-clients_freelancers":
                    $query->where("role","client_freelancer");
                    break;
                case "filter-admins":
                    $query->where("role","admin");
                    break;
            }
        }

        return $query->paginate($size);
    }

    public function freelancerSearch( Request $request )
    {

        /* Return cached results if available */
        $cacheId = md5( @json_encode( $request->all() ) );

        if ( \Cache::has( $cacheId ) ) {
            return \Cache::get( $cacheId );
        }

        $query = \App\User::with("minimalSkills","agencies");

        if ( $request->has( "sort" ) ) {
            $sortBy = $request->sort['name'];
            $sortDirection = $request->sort['type'];
            $query->orderBy( $sortBy, $sortDirection );
        } else {
            $query->orderBy("search_score", 'DESC');
        }

        if ( $request->has( "pager" ) ) {
            $page = $request->pager['page'];
            $size = $request->pager['size'];
        } else {
            $page = 1;
            $size = 100;
        }

        if ( $request->has( "search" ) && !empty( $request->search ) ) {
            $query->where( function ( $query ) use ( $request ) {
                $query->where( "name", "like", "%" . $request->search . "%" );
                $query->orWhere( "email", "like", "%" . $request->search . "%" );
            } );
        }

        if ($request->has("country")) {
            $query->where( "country", "like", "%" . $request->country . "%" );
        }

        if ( $request->has( "city" ) ) {
            $query->where( "city", "like", "%" . $request->city . "%" );
        }

        if ( $request->has( "skills" ) && count($request->skills) > 0) {
            $query->whereHas('skills', function ($query) use ($request) {
                $curIndex = 0;
                foreach ($request->skills as $skill) {
                    if ($curIndex == 0) {
                        $query->where( function ( $query ) use ( $skill ) {
                            $query->where( "skill_id", $skill['id'] );
                            if ( $skill['level'] != 'any' ) {
                                $query->where( "level", $skill['level'] );
                            }
                        } );
                    } else {
                        $query->orWhere( function ( $query ) use ( $skill ) {
                            $query->where( "skill_id", $skill['id'] );
                            if ( $skill['level'] != 'any' ) {
                                $query->where( "level", $skill['level'] );
                            }
                        } );
                    }


                    $curIndex++;
                }

            });
        }

        $query->where( "role", "like", "%freelancer%" );

        $results = $query->paginate( $size );

        /**
         * If there's no matching freelancers return the top scoring freelancers
         * Only return default results if the user is not searching for a specific freelancer
         */
        if ($results->total() == 0 && !$request->has( "search" ) ) {
            $query = $query = \App\User::with( "minimalSkills", "agencies" );
            $query->where( "role", "like", "%freelancer%" );
            $query->orderBy( "search_score", 'DESC' );
            $results = $query->paginate( $size );
        }

        /* Store results in cache */
        \Cache::put($cacheId, $results, 1440);

        return $results;
    }

    /**
     * Fetch an individual user with relations
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    public function fetch(Request $request, User $user) {
        $fullUser = User::where("id", $user->id)->with(
            "clientJobs",
            "freelancerJobs",
            "agencies",
            "skills",
            "references",
            "ownedAgencies",
            "agencies.freelancers",
            "ownedAgencies.freelancers",
            'recommendedUsers',
            'recommendedUsers.owner',
            'recommendedUsers.freelancer',
            'recommendations.owner',
            'recommendations.freelancer',
            'links'
        )->first();

        if (!$fullUser) {
            return response()->json( [
                'message' => "Error loading user details. Please try again.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return response()->json( [
            'message' => "User details loaded successfully.",
            'status'  => "success",
            'user'    => $fullUser
        ] );
    }

    /**
     * Update a users profile
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    public function update(Request $request, User $user )
    {

        $this->validate($request, [
            'name'     => 'required|min:2|max:35',
            'email'    => [
                'required',
                'email',
                'max:255',
                Rule::unique( 'users' )->ignore( $user->id ),
            ],
            'city'  => 'required|min:1|not_in:Your City',
            'country'  => 'required|min:1|not_in:Your Country',
        ]);

        if (\Auth::user()->id != $user->id && !\Auth::user()->isAdmin()) {
            return response()->json( [
                'message' => "You do not have permission to update this user.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

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
                    'public'      => $file['public'],
                    'description' => $file['description']
                ];
            }
        }

        if ( $user->update( [
            'name'                => $request->get( 'name', $user->name ),
            'email'               => $request->get( 'email', $user->email ),
            'tagline'             => $request->get( 'tagline', $user->tagline ),
            'city'                => $request->get( 'city', $user->city ),
            'country'             => $request->get( 'country', $user->country ),
            'bio'                 => $request->get( 'bio', $user->bio ),
            'company'             => $request->get( 'company', $user->company ),
            'company_description' => $request->get( 'company_description', $user->company_description ),
            'phone'               => $request->get( 'phone', $user->phone ),
            'role'                => $request->role
        ] )
        ) {
            $user->syncMediaExtra( $fileIds, "files", $fileAttributes );
            $user->updateScore();
            return response()->json( [
                'message' => "Profile saved successfully.",
                'status'  => "success"
            ]);
        }

        return response()->json( [
            'message' => "Error saving profile. Please Try Again.",
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
    public function delete( Request $request, User $user )
    {

        if ( \Auth::user()->id != $user->id && !\Auth::user()->isAdmin() ) {
            return response()->json( [
                'message' => "You do not have permission to delete this user.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ( $user->delete() ) {
            return response()->json( [
                'message' => "User deleted successfully.",
                'status'  => "success"
            ] );
        }

        return response()->json( [
            'message' => "Error deleting user. Please Try Again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    /**
     * Update a users profile, without validation
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    public function updateAll( Request $request, User $user )
    {
        if ( \Auth::user()->id != $user->id && !\Auth::user()->isAdmin() ) {
            return response()->json( [
                'message' => "You do not have permission to update this user.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Do not allow users to make themselves admins */
        if (!\Auth::user()->isAdmin() && $request->role == "admin") {
            $request->role = \Auth::user()->role;
        }

        if ( $user->update( [
            'name'                => $request->get( 'name', $user->name ),
            'email'               => $request->get( 'email', $user->email ),
            'tagline'             => $request->get( 'tagline', $user->tagline ),
            'city'                => $request->get( 'city', $user->city ),
            'country'             => $request->get( 'country', $user->country ),
            'bio'                 => $request->get( 'bio', $user->bio ),
            'company'             => $request->get( 'company', $user->company ),
            'company_description' => $request->get( 'company_description', $user->company_description ),
            'phone'               => $request->get( 'phone', $user->phone ),
            'role'                => $request->role
        ] )
        ) {
            $user->updateScore();
            return response()->json( [
                'message' => "Profile saved successfully.",
                'status'  => "success",
                'user' => $user
            ] );
        }

        return response()->json( [
            'message' => "Error saving profile. Please Try Again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    /**
     * Update a users role
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    public function updateRole( Request $request, User $user )
    {
        if ( \Auth::user()->id != $user->id && !\Auth::user()->isAdmin() ) {
            return response()->json( [
                'message' => "You do not have permission to perform this action.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Do not allow users to make themselves admins */
        if ( !\Auth::user()->isAdmin() && $request->role == "admin" ) {
            $request->role = \Auth::user()->role;
        }

        if ( $user->update( [
            'role' => $request->role
            ])
        ) {
            return response()->json( [
                'message' => "Your user role has been updated successfully.",
                'status'  => "success",
                'user'    => $user
            ] );
        }

        return response()->json( [
            'message' => "Error updating your user role. Please try again",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    /**
     * Update a users meta data
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    public function updateMeta( Request $request, User $user )
    {
        if ( \Auth::user()->id != $user->id && !\Auth::user()->isAdmin() ) {
            return response()->json( [
                'message' => "You do not have permission to perform this action.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $userMeta = $user->meta;
        if ($request->has('meta')) {
            foreach ($request->meta as $meta) {
                $userMeta[$meta['field']] = $meta['value'];
            }
        }

        $user->meta = $userMeta;

        if ( $user->save() ) {
            return response()->json( [
                'message' => "Meta has been updated successfully.",
                'status'  => "success",
                'meta'    => $userMeta
            ] );
        }

        return response()->json( [
            'message' => "Error updating users meta. Please try again",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    /**
     * Add a new recommended freelancer relation to a user
     * @param Request $request
     * @return mixed
     */
    public function addRecommendation(Request $request )
    {

        if (!$request->has("to_user_id") || !$request->has('from_user_id') || $request->to_user_id == \Auth::user()->id ) {
            return response()->json( [
                'message' => "Error adding recommendation, invalid user selected.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $recommended = \App\User::find($request->to_user_id);
        if (!$recommended) {
            return response()->json( [
                'message' => "Error adding recommendation, invalid user selected.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        Recommendation::create([
            'to_user_id' => $request->to_user_id,
            'from_user_id' => $request->from_user_id,
            'snippet' => $request->get('snippet')
        ]);

        /* Send recieving user a notificatino */
         Notification::create( [
            'type'         => "recommendation",
            'to_user_id'   => $request->to_user_id,
            'from_user_id' => null,
            'owner_id'     => $request->to_user_id,
            'status'       => 'unread',
            'title'        => \Auth::user()->name . " Added You As a Recommended Freelancer",
            'message'      => "If you don't want to be listed as a recommended freelancer you can remove the recommendation by editing your profile.",
            'owner_type'   => 'user'
        ] );

        return response()->json( [
            'message' => "Successfully added recommended freelancer.",
            'status'  => "success"
        ] );

    }

    /**
     * Add a new recommended freelancer relation to a user
     * @param Request $request
     * @return mixed
     */
    public function deleteRecommendation( Request $request, Recommendation $rec )
    {

        if ( \Auth::user()->id != $rec->from_user_id && !\Auth::user()->isAdmin() ) {
            return response()->json( [
                'message' => "You do not have permission to delete this recommendation.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ( $rec->delete() ) {
            return response()->json( [
                'message' => "Recommendation deleted successfully.",
                'status'  => "success"
            ] );
        }

        return response()->json( [
            'message' => "Error deleting recommendation. Please try again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );


    }

}
