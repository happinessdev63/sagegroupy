@extends('layouts.app')

@section('header-styles')
    <link href="/css/sage.css" rel="stylesheet" type="text/css">
@endsection

@section('body')
    <body style="background-image: url('/images/bg3.jpg') !important; background-size: cover !important;">
@endsection

@section('nav')
    @include('lander.nav-external')
@endsection

<?php $contentClass = 'auth-content' ?>
@section('content')

    <md-whiteframe md-elevation="3" class="margin-top-55 text-center padding-50 col-lg-4 col-lg-offset-4 col-sm-12 bg-white-transparent">
    <div>
        <img src="/img/logo_xs.png"/>
        <!-- <sage-login redirect-url="{{ Request::get("r",Session::get('url.intended', url('/dashboard'))) }}"></sage-login> -->
        <form novalidate method="POST" antion={{ route('login') }}>
          @csrf
            <!-- <md-input-container v-bind:class="{ 'md-input-invalid' : errors.email || errors.login }"> -->
            <md-input-container>
                <label>Email</label>
                <md-input type="email" required name="email"></md-input>
            </md-input-container>
            <md-input-container>
                <label>Password</label>
                <md-input type="password" required name="password"></md-input>
            </md-input-container>

            <p class="margin-top-10"> <a href="/password/reset" class="link-primary">Forgot your password?</a></p>

            <!-- <md-button class="md-raised md-primary">
                Login
                <md-spinner :md-size="10" md-indeterminate class="md-accent margin-top-10 margin-left-5"></md-spinner>
            </md-button> -->
            <md-button type="submit" class="md-raised md-primary">Login</md-button>

            <p class="margin-top-10">Don't have an account? <a href="/register" class="link-primary">Sign Up</a></p>
        </form>
    </div>
    <div class="clearfix"></div>
    </md-whiteframe>

@endsection

@section('outside-footer')
    <div class="margin-top-60">
        @include('lander.footer')
    </div>
@endsection
