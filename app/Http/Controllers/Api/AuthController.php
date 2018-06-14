<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Skill;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends Controller
{
    // use AuthenticatesUsers;

    protected $defaultRedirect = "/";

    /* Handle an ajax based login request */
    public function login(Request $request)
    {
        $this->validate( $request, [
            'email'    => 'required',
            'password' => 'required',
        ] );

        if ( Auth::attempt( [ 'email' => $request->email, 'password' => $request->password ] ) ) {
          // print_r(Auth()->getSession()->all());
            // return redirect('/dashboard');
            return response()->json( [
                'status'   => 'success',
                'message'  => "Login Successful",
                'redirect' => $request->has("redirect") && !empty($request->redirect) ? $request->redirect : $this->defaultRedirect
            ]);
        }

        //
        // /* Invalid login, return 422 response */
        return response()->json( [
            'login'  => Array( "Invalid username or password." ),
            'status' => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
