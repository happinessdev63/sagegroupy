@extends('layouts.lander')

@section('body')
<body data-spy="scroll" data-target="#navbar-menu">
@endsection

    @section('nav')
        @include('lander.nav-external')
    @endsection

    @section('content')

        <!-- Header  -->
            <section class="bg-custom header-title-box">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h3 class="text-white">Thank You</h3>
                            <h5 class="text-light">You will be notified when we officially launch.</h5>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Header -->

            <section class="section">
                <div class="container">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <div class="title-box-icon title-about text-white">
                                    <i class="pe-7s-gift"></i>
                                    <h3 class="title">Free Amazon Gift Card</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="feat-description text-center">
                                <h4 class="text-light">You have been automatically entered in our contest. For every person you signup you can get additional entries to win an Amazon gift card. <br/><br/>If you know of anyone else who might be interested in our service please forward this link to them to be entered in a draw for an Amazon gift card!</h4>
                                <div class="form-group margin-top-30">
                                    <h3 class="text-white">Share this link with your friends</h3>
                                    <input class="form-control"  value="{{ url('/share/' . trim($email)) }}" readonly>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </section>



            <div class="clearfix"></div>

            <section class="section bg-light">
                <div class="container">

                    @if (!$surveyComplete)
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <div class="title-box-icon title-about">
                                    <i class="pe-7s-micro"></i>
                                    <h3 class="title">Make Your Voice Heard, Take Our Quick Survey!</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ($surveyComplete)
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="text-center">
                                    <div class="title-box-icon title-about">
                                        <i class="pe-7s-micro"></i>
                                        <h3 class="title">Thank you for completing our survey. Your feedback is appreciated!</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!$surveyComplete)
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">
                            <form role="form" action="/prelaunchSurvey" method="post" class="contact-form" >
                                <input type="hidden" name="referrer" value="{{ $referrer }}"/>
                                <input type="hidden" name="email" value="{{ $email }}"/>
                                <div class="form-group">
                                    <input type="hidden" name="responses[0][question]" value="What would like to see most in a new freelancing website?"/>
                                    <label>What would like to see most in a new freelancing website?</label>
                                    <textarea class="form-control" name="responses[0][answer]" rows="5" placeholder="Please answer the question." required></textarea>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="responses[1][question]" value="Is there anything you would NOT like to see on a freelancing website?"/>
                                    <label>Is there anything you would NOT like to see on a freelancing website?</label>
                                    <textarea class="form-control" name="responses[1][answer]" rows="5" placeholder="Please answer the question." required></textarea>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="responses[2][question]" value="How can a freelancing site help you the most?"/>
                                    <label>How can a freelancing site help you the most?</label>
                                    <textarea class="form-control" name="responses[2][answer]" rows="5" placeholder="Please answer the question." required></textarea>
                                </div>


                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="error text-center" id="err-form"></div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-custom" id="send">Submit</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                    @endif

                </div>
            </section>


        @include('lander.footer')

            <!-- Back to top -->
            <a href="#" class="back-to-top" id="back-to-top"> <i class="pe-7s-angle-up"> </i> </a>

    @endsection


@section('scripts')
@endsection
