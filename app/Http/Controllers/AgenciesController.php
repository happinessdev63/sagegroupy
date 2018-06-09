<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Agency;

class AgenciesController extends Controller
{
    /**
     * Create a new agency
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view( 'agencies.createAgency', [
            'user' => \Auth::user()
        ] );
    }

    /**
     * View all agencies
     *
     * @return \Illuminate\Http\Response
     */
    public function userAgencies()
    {
        $user = \App\User::where( "id", \Auth::user()->id )->with(
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

        return view( 'agencies', [
            'user' => $user
        ] );
    }

    /**
     * View agency listings
     *
     * @return \Illuminate\Http\Response
     */
    public function find( Request $request )
    {
        return view( 'agencies.findAgency', [
            'user'   => \Auth::user(),
        ] );
    }
    

    /**
     * View an existing agency
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request, Agency $agency )
    {
        $agency->load( "owner", "freelancers", "jobs", "jobs.media", "jobs.references" ,"media","links" );
        return view( 'agencies.index', [
            'user' => \Auth::user(),
            'agency'  => $agency
        ] );
    }

    /**
     * Edit an existing agency
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Agency $agency )
    {

        return view( 'agencies.editAgency', [
            'user' => \Auth::user(),
            'agency' => $agency
        ] );
    }

}
