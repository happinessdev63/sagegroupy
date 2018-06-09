@extends('emails.layout')

@section('header')
    <h4 class="text-center">Welcome to Sagegroupy</h4>
    <p class="text-center">Thank you for signing up for early access!</p>
@endsection

@section('content')
    <p>We will keep this short and quick as we are hard at work getting Sagegroupy ready to launch. You will recieve an email when our platform is ready for early access. We have a lot of great stuff in store so stay tuned!</p>
    <p>Remember to use your custom sharing link to get additional entries in our contest.</p>
@endsection

@section('button')
    <center data-parsed="">
        <table class="button success float-center">
            <tr>
                <td>
                    <table>
                        <tr>
                            <td><a href="{{ url('/share/' . $email) }}">{{ url('/share' . $email) }}</a></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
@endsection
