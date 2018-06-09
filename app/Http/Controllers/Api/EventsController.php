<?php

namespace App\Http\Controllers\Api;

use App\Reference;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Job;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{

   public function recordView(Request $request) {

       switch ($request->type) {
           case "job":
               $job = \App\Job::find($request->job_id);
               if ($job) {
                   event( new \App\Events\JobViewedEvent(
                       $job,
                       \Auth::user() ?: "anon",
                       $request->get('source','sagegroupy.com'),
                       $request->get('view_type', 'view' )
                   ) );
               }
               break;
           case "user":
               $user = \App\User::find( $request->user_id );
               if ( $user ) {
                   event( new \App\Events\ProfileViewedEvent(
                       $user,
                       \Auth::user() ?: "anon",
                       $request->get( 'source', 'sagegroupy.com' ),
                       $request->get( 'view_type', 'view' )
                   ) );
               }
               break;
       }


   }

}

