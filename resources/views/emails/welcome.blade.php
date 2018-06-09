@extends('emails.layout')

@section('header')
    <h4 class="text-center">Welcome to Sagegroupy, {{ $user->first_name }}!</h4>
    <p class="text-center">The Freelancer's Friend</p>
@endsection

@section('content')
    <p>SageGroupy is a free to use freelancing platform. To get the best results from SageGroupy, we recommend filling the following:</p>
    <ul>
        <li>Complete your profile details. </li>
        <li>Add references for past work you have completed, including screenshots & links.</li>
        <li>Search for & join agencies that compliment your skills on the SageGroupy platform.</li>
        <li>Add your relevant skills to your SageGroupy profile and set your rates based on your experience level.</li>
        <li>Create reference jobs and invite previous clients to provide feedback.</li>
    </ul>
    <p>If you have any questions please contact us and we will be happy to help.</p>
@endsection

@section('button')
    <center data-parsed="">
        <table class="button success float-center">
            <tr>
                <td>
                    <table>
                        <tr>
                            <td><a href="{{ url('/login') }}">Login</a></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
@endsection
