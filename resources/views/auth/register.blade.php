@extends('layouts.app')

@section('header-styles')
    <link href="/css/sage.css" rel="stylesheet" type="text/css">
    <link href="/css/vue-material.css" rel="stylesheet" type="text/css">
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
            <h4 class="font-weight-300">Create a New Account</h4>
            <sage-register redirect-url="{{ Request::get("r",Session::get('url.intended', url('/dashboard'))) }}"></sage-register>
        </div>
        <div class="clearfix"></div>
    </md-whiteframe>

@endsection

@section('outside-footer')
    <div class="margin-top-60">
        @include('lander.footer')
    </div>
@endsection