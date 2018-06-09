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
                            <h3 class="text-white">About Sagegroupy</h3>
                            <h5 class="text-light">The Freelancer's Friend</h5>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Header -->


            <!-- STORY -->
            <section class="section">
                <div class="container">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <div class="title-box-icon title-about text-white">
                                    <i class="pe-7s-note2"></i>
                                    <h3 class="title">Our Story</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="feat-description">
                                <h4 class="text-light text-center">Hi, my name is Ian. Years of experience working with freelancers inspired me to create this site for them and their clients. It is my hope that Sagegroupy will be beneficial in helping freelancers and their clients build positive
                                                                   working relationships with each other, as well as foster the growth of the freelance community.</h4>
                                <p class="text-muted"> I created this site because I believe that freelancing platforms should be free and supportive of freelancers and their clients. Other freelancing sites are too focused on extracting as much value as possible from freelancer-client interactions and are not concerned with helping both parties connect and work together in a manner that works for them. Many people starting out in freelancing need support to be successful and I have created this site in such a way as to make that easier where possible.</p>


 <p class="text-muted">I believe that many people today demand a change to their working conditions that enables them to work how they want, where they want and for who they want, without being chained to a desk somewhere in a 9-5 job punching the clock everyday. It is my hope that this site helps freelancers on their way to a life of flexibility and freedom while simultaneously enabling them to do their best work for their clients with enthusiasm.</p>

                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- end  story -->


            <div class="clearfix"></div>


            <!-- MISSION -->
            <section>
                <div class="row wide-img-showcase-row">
                    <div class="col-sm-6 no-padding img">
                    </div>
                    <div class="col-sm-6 col-sm-offset-6 bg-light">
                        <div class="about-detail-img-box">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="text-center">
                                        <div class="title-box-icon title-about">
                                            <i class="pe-7s-rocket"></i>
                                            <h3 class="title">Our Mission</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <div class="feat-description text-center">
                                        <h4 class="text-muted">Connecting Freelancers & Clients</h4>
                                        <p class="text-muted">With the Sagegroupy platform it is our mission to make it as easy as possible for freelancers and clients to connect with each other. We also want to achieve this at no cost to either the freelaner or client. We aim to provide an open platform that is free, easy to use and includes all the features for both freelancers and clients to be successful. </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </section>
            <!--  END MISSION -->

            <div class="clearfix"></div>


           @include("lander.cta");

            @include('lander.footer')

            <!-- Back to top -->
            <a href="#" class="back-to-top" id="back-to-top"> <i class="pe-7s-angle-up"> </i> </a>

    @endsection


    @section('scripts')
    @endsection
