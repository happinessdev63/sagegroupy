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
                            <h3 class="text-white">Contact Us</h3>
                            <h5 class="text-light">We Are Happy to Help!</h5>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Header -->


            <!-- Contact Form -->
            <section class="section">
                <div class="container">

                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="title text-white">Send us a Message</h3>
                            <p class="text-light sub-title">We will get back to you as soon as possible.</p>
                            <div id="success-form" class="text-white font-size-18 margin-bottom-10" style="display: none;">Thank you, your message has been sent!</div>
                        </div>
                    </div> <!-- end row -->

                    <div class="row">

                        <!-- Contact form -->
                        <div class="col-sm-6">
                            <form role="form" name="ajax-form" id="contact-form"
                                  action="/contact" method="post"
                                  class="contact-form" data-parsley-validate novalidate>

                                <div class="form-group">
                                    <input class="form-control" id="name2" name="name" placeholder="Your name" type="text" value="" parsley-trigger="change" required>
                                </div>


                                <div class="form-group">
                                    <input class="form-control" id="email2" name="email" type="email" placeholder="Your email" value="" parsley-trigger="change" required>
                                </div>

                                <div class="form-group">
                                    <input class="form-control" id="subject" name="subject" type="text" placeholder="Message subject" value="" parsley-trigger="change" required>
                                </div>


                                <div class="form-group">
                                    <textarea class="form-control" id="message2" name="message" rows="5" placeholder="Message" parsley-trigger="change" required></textarea>
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

                        <div class="col-sm-4 col-sm-offset-1">
                            <div class="contact-box text-light">

                                <div class="contact-detail">
                                    <i class="pe-7s-mail-open-file"></i>
                                    <p>
                                        <a href="">support@sagegroupy.com</a>
                                    </p>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </section>
            <!-- End Contact Form -->

            <div class="clearfix"></div>

            @include("lander.cta");

            @include('lander.footer')

            <!-- Back to top -->
            <a href="#" class="back-to-top" id="back-to-top"> <i class="pe-7s-angle-up"> </i> </a>

    @endsection


@section('scripts')
@endsection
