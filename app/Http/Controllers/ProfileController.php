<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except("viewProfile", "viewProfileShareId","viewGeneralReference");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        dd("OK");die();
        $user = \App\User::where("id",\Auth::user()->id)->with(
            "clientJobs",
            "freelancerJobs",
            "agencies",
            "links",
            "skills",
            "references",
            "ownedAgencies",
            "agencies.freelancers",
            "ownedAgencies.freelancers",
            'recommendedUsers',
            'recommendedUsers.owner',
            'recommendedUsers.freelancer',
            'recommendations.owner',
            'recommendations.freelancer'
        )->first();


        /* If the user is logging in for the first time and they are a freelancer, redirect them to the profile wizard */
        if (isset( $user->meta['first_login']) && $user->meta['first_login'] == true) {
            $user->setMeta( 'first_login', false );
            $user->save();
            return redirect('/profileWizard');
        }

        return view('dashboard', [
            'user' => $user
            ]
        );
    }

    /**
     * Show the freelancer wizard.
     *
     * @return \Illuminate\Http\Response
     */
    public function profileWizard()
    {
        $user = \App\User::where( "id", \Auth::user()->id )->with(
            "clientJobs",
            "freelancerJobs",
            "agencies",
            "references",
            "ownedAgencies",
            "agencies.freelancers",
            "ownedAgencies.freelancers",
            'recommendedUsers',
            'recommendedUsers.owner',
            'recommendedUsers.freelancer',
            'recommendations.owner',
            'recommendations.freelancer'
        )->first();

        $user->setMeta( 'first_login', false );
        $user->save();

        return view( 'wizard.profile', [
                'user' => $user
            ]
        );
    }

    /**
     * Show a public profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewProfile(Request $request, User $user)
    {
        $user->load(
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
        );
        /* Fire event */
        event( new \App\Events\ProfileViewedEvent( $user, \Auth::user() ?: "anon" ) );

        return view( 'profile', [
            'user' => $user
        ]);
    }

    public function viewProfileShareId(Request $request, $shareId )
    {

        $userId = \Hashids::decode( $shareId );
        $user = \App\User::find($userId)->first();

        if (!$user) {
            abort( 404, 'User not found.' );
        }

        return $this->viewProfile($request, $user);

    }

    /**
     * Show a users own profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile( )
    {

        $user = \Auth::user();
        $user->load(
            "clientJobs",
            "freelancerJobs",
            "agencies",
            "skills",
            "references",
            "generalReferences",
            "ownedAgencies",
            "agencies.freelancers",
            "ownedAgencies.freelancers",
            'recommendedUsers',
            'recommendedUsers.owner',
            'recommendedUsers.freelancer',
            'recommendations.owner',
            'recommendations.freelancer',
            'links'
        );

        return view( 'profile', [
            'user' => $user
        ] );
    }

    /**
     * Show a general reference
     *
     * @return \Illuminate\Http\Response
     */
    public function viewGeneralReference( Request $request, \App\GeneralReference $generalReference )
    {

        $user = \Auth::user();
        $generalReference->load('user','client');

        return view( 'generalReference', [
            'user' => $user,
            'reference' => $generalReference
        ] );
    }

    /**
     * Show a users own profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        $user = \Auth::user();
        $user->load(
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
        );

        if (\Request::has('jobId')) {
            $job = \App\Job::where('id', $request->jobId)->first();
        } else {
            $job = null;
        }
        // dd($user);die();
        return view( 'findFreelancer', [
            'user' => $user,
            'job'  => $job
        ] );
    }

    /**
     * Show user profile editing.
     *
     * @return \Illuminate\Http\Response
     */
    public function profileEdit( Request $request, User $user)
    {

        /* Only allow users (or admins) to edit their own profile */
        if ( $user->id != \Auth::user()->id && !\Auth::user()->isAdmin() ) {
            return redirect( "/dashboard" );
        }

        $user->load(
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
            'recommendations.freelancer'

        );
        return view( 'profile.editProfile', [
            'user' => $user
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

    /**
     * Download user profile.
     *
     */

    public function downloadToPdf( Request $request, $userId){
        // /* Only allow admin users to download any user's profile */
        if ( !\Auth::user()->isAdmin() ) {
            return redirect( "/dashboard" );
        }
        //
        $user = \App\User::where("id", $userId)->with(
            "clientJobs",
            "freelancerJobs",
            "agencies",
            "links",
            "skills",
            "references",
            "ownedAgencies",
            "agencies.freelancers",
            "ownedAgencies.freelancers",
            'recommendedUsers',
            'recommendedUsers.owner',
            'recommendedUsers.freelancer',
            'recommendations.owner',
            'recommendations.freelancer'
        )->first();
        // // return view("admin.agencies");
        // // return view( 'profile', [
        // //     'user' => $user
        // // ]);
        // $html = view('profile.pdfTemplate', [ 'user'->$user ])->render();
        // dd($html);die();
        // // return response()->json( [
        // //     'message' => "Error deleting recommendation. Please try again."
        // // ],
        // //     Response::HTTP_UNPROCESSABLE_ENTITY
        // // );
        // $items = DB::table("items")->get();
        // view()->share('items',$items);
        // dd($user->avatar);die();
        // $pdf = PDF::loadView('pdfview', [ 'user' => $user ]);
        // return $pdf->download('pdfview.pdf');

        return view('pdfview', [ 'user' => $user ]);
    }
}
