<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Newsletter;

class RegisterController extends Controller
{

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function redirectPath()
    {
        return property_exists( $this, 'redirectTo' ) ? $this->redirectTo : '/dashboard';
    }

    protected function registered( Request $request, $user )
    {
        var_dump($user);exit("OK");
        // return response()->json( [
        //     'status'   => 'success',
        //     'message'  => "Registration Successful",
        //     'redirect' => $request->has( "redirect" ) && !empty( $request->redirect ) ? $request->redirect : $this->redirectTo
        // ] );
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['type'],
            'meta' => [
                'first_login' => true,
                'profile_wizard_complete' => false,
                'dashboard_tour_compelte' => false,
                'freelancer_tour_complete' => false,
                'client_tour_complete' => false,
            ]
        ]);

        /* Generate new welcome notification */
        $admin = User::where('role', 'admin')->first();
        $message = "Thank you for joining Sagegroupy! We just wanted to send you a quick welcome message to introduce you to the notifcations dashboard. We hope you enjoy our platform!";
        event( new \App\Events\UserMessageEvent( $admin, $user, 'Welcome to SageGroupy', $message, "user-message" ) );

        /* Send admin email */
        \Mail::to( env( "ADMIN_EMAIL" ) )
            ->send( new \App\Mail\PrelaunchAdminNotification( $data['email'], "Not Available" ) );

        /* Subscribe to mailchimp list */
        Newsletter::subscribe( $data['email'] );

        return $user;
    }
}
