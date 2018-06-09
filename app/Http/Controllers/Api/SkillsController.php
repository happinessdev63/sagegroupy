<?php

namespace App\Http\Controllers\Api;

use App\Reference;
use App\Services\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Skill;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class SkillsController extends Controller
{

    const HIGH_RATE_WARN_THRESHOLD = "1.3";
    const LOW_RATE_WARN_THRESHOLD = "0.7";
    const LOW_USER_COUNT_HRESHOLD = "5";

    public function __construct()
    {
        $this->middleware( "auth" )->except("fetch");
    }

    /**
     * Return skills for admin users management.
     * Supported filters: filter-active, filter-completed, filter-reference
     * @param Request $request ['filter','search','sort','pager','page']
     * @return mixed
     */
    public function index( Request $request )
    {
        $query = Skill::withCount("users");

        if ($request->has('includeUsers')) {
            $query->with("users");
        }

        /* Handle custom sorting on relations */
        if (!$request->has("countOnly")) {
            if ( $request->has( "sort" ) ) {

                switch ($request->sort['name']) {

                    case "name":
                    case "category":
                    case "created_at":
                        $query->orderBy( $request->sort['name'], $request->sort['type'] );
                        break;
                    case "users":
                    case "users_count":
                        $query->orderBy( "users_count", $request->sort['type'] );
                        break;
                    default:
                        $query->orderBy( "users_count", $request->sort['type'] );
                        break;
                }

            } else {
                $query->orderBy( "users_count", "desc" );
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
            $query->where( "name", "like", "%" . $request->search . "%" );
        }


        if ( $request->has( "filter" ) && !empty( $request->filter ) && $request->get('paginate','true') != 'true'  ) {
            switch ($request->filter) {
                case "filter-default":
                    $query->where( "category", "default" );
                    break;
                case "filter-members":
                    //$query->where( "users_count",'>',0 );
                    break;
                case "filter-no-members":
                    //$query->where( "users_count",0 );
                    break;
            }
        }

        if ( $request->has( "countOnly" ) && $request->countOnly == 'true' ) {
            return $query->count();
        }

        if ($request->has("paginate") && $request->paginate == 'false') {
            return $query->get();
        }

        return $query->paginate( $size );

    }

    public function removeSkill(Request $request, Skill $skill) {

        /* Remove the skill from the user */
        \Auth::user()->skills()->detach($skill->id);

        /**
         * @todo - Potentially delete skill if no more users are associated with it?
         */

        return response()->json( [
            'message'     => "Skill has been deleted successfully.",
            'status'      => "success",
            'results'     => $skill
        ] );

    }

    public function addSkill(Request $request, Settings $settings)
    {
        $this->validate( $request, [
            'name'       => 'required|min:3|max:180',
            'rate'       => 'required|integer|min:1|max:1000',
            'experience' => 'required|integer|min:0|max:40',
            'level'      => 'required|in:Entry,Junior,Intermediate,Senior,Expert',
        ] );

        /* If skill exists load it, otherwise create new one */
        $newSkill = [
            'name'     => $request->name,
            'slug'     => str_slug( $request->name ),
            'category' => 'default',
            'keywords' => str_replace( " ", ",", $request->name )
        ];


        $skill = Skill::firstOrCreate(["slug" => $newSkill['slug']], $newSkill);

        /* If freelancer has skill update pivot, otherwise attach new skill and save pivot data */
        $userWithSkills = \App\User::where('id', \Auth::user()->id)
                    ->whereHas( 'skills', function ( $query ) use ($skill) {
                        $query->where( 'skill_id', $skill->id  );
                     } )->with('skills')->first();

        /* User has skill, updating pivot data */
        if ($userWithSkills) {
            $userWithSkills->skills()->updateExistingPivot($skill->id, [
                'rate' => $request->rate,
                'level' => $request->level,
                'experience' => $request->experience
            ]);

            return response()->json( [
                'message' => "Existing skill updated successfully.",
                'status'  => "success",
                'suggestions' => $this->getSkillSuggestions($skill, $request->rate, $request->level),
                'results' => $skill
            ] );
        }

        /* Make sure user hasn't added more than 12 skills */
        $userSkills = \App\User::where( "id", \Auth::user()->id )->withCount( "skills" )->first();
        if ( $userSkills->skills_count >= $settings->get("max_skills", 12) ) {
            return response()->json( [
                'message'     => "This skill was not saved. You have added the maximum number of skills.",
                'status'      => "error",
                'suggestions' => [
                    'Maximum of ' . $settings->get( "max_skills", 12 ) . ' skills allowed. If you want to add this skill please remove an existing skill first.',
                ],
                'results'     => []
            ] );
        }

        /* Suggestions should be loaded before we add the new skill */
        $suggestions = $this->getSkillSuggestions( $skill, $request->rate, $request->level );

        /* No skill added yet, add new skill and relation */
        \Auth::user()->skills()->syncWithoutDetaching( [ $skill->id => [
            'rate'       => $request->rate,
            'level'      => $request->level,
            'experience' => $request->experience
        ] ] );

        return response()->json( [
            'message'     => "New skill saved successfully.",
            'status'      => "success",
            'suggestions' => $suggestions,
            'results'     => $skill
        ] );
    }

    /**
     * @param Skill $skill
     * @param $rate
     * @param $skillLevel
     * @return array
     * @internal param $skillId
     * @internal param int $experience
     */
    public function getSkillSuggestions(Skill $skill, $rate, $skillLevel) {
        $avgRate = $skill->averageRateByLevel( $skillLevel, \Auth::user()->id );
        return $skill->skillSuggestions($rate, $avgRate);
    }

    /**
     * Create a new skill
     * @param Request $request
     */
    public function create( Request $request )
    {
        $this->validate( $request, [
            'type'         => 'required|min:5|max:180',
            'message'      => 'required|min:5|max:2048',
            'status'       => 'required',
        ] );

        $newSkill = $request->except( "token");
        $skill = Skill::create($newSkill);

        return response()->json( [
            'message' => "Skill saved successfully.",
            'new'     => true,
            'status'  => "success",
            'results' => $skill
        ] );

    }

    /**
     * Delete a skill
     * @param Request $request
     * @param Skill $skill
     * @return mixed
     */
    public function delete( Request $request, Skill $skill )
    {
        /**
         * @todo Confirm user has permission to delete the skill
         */
        $canDelete = true;

        if ( $skill->delete() ) {
            return response()->json( [
                'message' => "Skill deleted successfully.",
                'status'  => "success"
            ] );
        }

        return response()->json( [
            'message' => "Error deleting skill. Please Try Again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

}

