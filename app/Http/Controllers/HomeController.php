<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Newsletter;

class HomeController extends Controller
{
    public function previewEmail(Request $request, $email  )
    {
        return view("emails." . $email, $request->all());
    }

    public function prelaunch(  )
    {
        return view( "lander.prelaunch" );
    }

    public function prelaunchSignup( Request $request )
    {

        if ( $request->get( "email" ) && $request->get( "vf" ) == 'true') {

            /* Subscribe to mailchimp list */
            Newsletter::subscribe($request->email);

            /* Send out welcome email */
            \Mail::to( $request->get( "email" ) )
                ->send( new \App\Mail\PrelaunchWelcome( $request->email, $request->get( 'referrer' ) ) );

            /* Send admin email */
            \Mail::to( env( "ADMIN_EMAIL" ) )
                ->send( new \App\Mail\PrelaunchAdminNotification( $request->email, $request->get( 'referrer' ) ) );

        }

        return view( "lander.prelaunch", [
            'email' => $request->get( "email" ),
            'referrer' => $request->get( 'referrer' ),
            'surveyComplete' => false
        ] );

    }

    public function prelaunchSurvey(Request $request )
    {

        if ($request->get("email") && $request->get("responses")) {
            /* Send admin email */
            \Mail::to( env( "ADMIN_EMAIL" ) )
                ->send( new \App\Mail\PrelaunchAdminSurveyNotification( $request->responses, $request->email, $request->get( 'referrer' ) ) );

        }

        return view( "lander.prelaunch", [
            'email' => $request->get( "email" ),
            'referrer' => $request->get( 'referrer' ),
            'surveyComplete' => true
        ] );

    }

    public function share(Request $request, $email = '') {
        if (!empty($email)) {
            return response()->redirectTo('/')->withCookie(cookie('referrer', $email, 60 * 24 * 30));
        } else {
            response()->redirectTo( '/' );
        }
    }
}
