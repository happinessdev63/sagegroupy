<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;

class JobsController extends Controller
{
    /**
     * Create a new job
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view( 'jobs.createJob', [
            'user' => \Auth::user()
        ] );
    }

    /**
     * View all jobs
     *
     * @return \Illuminate\Http\Response
     */
    public function userJobs()
    {
        return view( 'jobs', [
            'user' => \Auth::user()
        ] );
    }

    /**
     * Create a new reference job
     *
     * @return \Illuminate\Http\Response
     */
    public function createReference()
    {
        return view( 'jobs.createReferenceJob', [
            'user' => \Auth::user()
        ] );
    }

    /**
     * Create agency reference job
     *
     * @return \Illuminate\Http\Response
     */
    public function createAgencyReference(Request $request, \App\Agency $agency)
    {
        $agency->load('owner','jobs','links','references');

        return view( 'agencies.createReferenceJob', [
            'user' => \Auth::user(),
            'agency' => $agency
        ] );
    }

    /**
     * View an existing job
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request, Job $job )
    {
        $job->load( "agency", "freelancer", "client", "references", "media", "invites", "invites.client", "invites.freelancer" );

        /* Fire event */
        event( new \App\Events\JobViewedEvent( $job, "anon" ) );

        return view( 'jobs.index', [
            'user' => \Auth::user(),
            'job'  => $job
        ] );
    }

    /**
     * Edit an existing job
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Job $job )
    {

        if (\Auth::user()->id != $job->client_id && !\Auth::user()->isAdmin()) {
            abort( 403, 'Unauthorized action.' );
        }

        return view( 'jobs.editJob', [
            'user' => \Auth::user(),
            'job' => $job
        ] );
    }

    /**
     * Edit an existing job
     *
     * @return \Illuminate\Http\Response
     */
    public function editReference( Request $request, Job $job )
    {
        if ($job->hasAgency() && $job->agency->owner->id == \Auth::user()->id ) {
            $isAgencyOwner = true;
        } else {
            $isAgencyOwner = false;
        }

        if ($job->hasAgency()) {
            $agency = $job->agency()->first();
            $agency->load( 'owner', 'jobs', 'links', 'references' );
        } else {
            $agency = null;
        }

        if ( \Auth::user()->id != $job->freelancer_id && !\Auth::user()->isAdmin() && !$isAgencyOwner) {
            return response( '', 404 );
        }

        return view( 'jobs.editReferenceJob', [
            'user' => \Auth::user(),
            'job'  => $job,
            'agency' => $agency
        ] );
    }
}
