@extends('layouts.lander')

@section('body')
    <body data-spy="scroll" data-target="#navbar-menu">
    @endsection

    @section('content')


        <!-- HOME -->
            <section class="home bg-img-1" id="home">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <div class="home-fullscreen" id="home-fullscreen">
                                <div class="full-screen">
                                    <div class="home-wrapper home-wrapper-alt">
                                        <h1 class="text-white font-size-40 margin-bottom-20">The Freelancer's Friend</h1>
                                        <h4 class="text-light font-size-18">
                                            Whether you’re a freelancer, consultant, or an agency, we’re here to <br/> help you get more business, more easily and for free.<br/><br/> If you are a client looking for expertise for your next project  <br/> you will find it here, with no added fees!
                                        </h4>
                                        <a href="/register" class="btn btn-custom">Get Started</a>

                                        <div class="clearfix"></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END HOME -->


            <!-- Intro -->
            <section class="section padding-top-50" id="howitworks">
                <div class="container">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <div class="title-box-icon text-white">

                                    <h3 class="title"><img src="/img/logo_xs.png"></h3>
                                </div>
                            </div>
                        </div>
                    </div><!-- end row -->

                    <div class="row">
                        <div class="col-sm-5">
                            <div class="feat-description">
                                <h4 class="text-light">We’ve built an innovative new platform to provide freelancers like you and clients the ability to work together at no cost.</h4>
                                <p class="text-muted"> No hidden fees, no upcharges, nothing off the top, nada, zip. Plus, we’ll make it easy for you to find partners and
                                                      create virtual agencies where you can showcase your work and referrals in one place. </p>

                                <a href="/about" class="btn btn-custom">Learn More</a>
                            </div>
                        </div>

                        <div class="col-sm-7">
                            <img src="images/preview2.png" alt="img" class="img-responsive">
                        </div>

                    </div><!-- end row -->

                </div> <!-- end container -->
            </section>
            <!-- Intro -->


            <!-- Freelancers -->
            <section class="section bg-light" id="freelancers">
                <div class="container">

                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h3 class="title">Freelancers</h3>
                            <p class="text-muted sub-title">Boost your revenue with our easy to use platform.</p>
                        </div>
                    </div> <!-- end row -->

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="features-box">
                                <i class="pe-7s-rocket"></i>
                                <h4>Find New Clients</h4>
                                <p class="text-muted">Easily promote your services and establish working relationships with new clients.</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="features-box">
                                <i class="pe-7s-users"></i>
                                <h4>Form New Relationships</h4>
                                <p class="text-muted">Join "Agencies" and form new relationships with other freelancers that have complimentary skills.</p>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="features-box">
                                <i class="pe-7s-photo-gallery"></i>
                                <h4>Collect Your References</h4>
                                <p class="text-muted">Easily collect all of your references, portfolio items and example work in one place.</p>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="features-box">
                                <i class="pe-7s-like"></i>
                                <h4>100% Free</h4>
                                <p class="text-muted">No fees or long term obligations, <em>everything</em> is FREE!</p>
                            </div>
                        </div>

                    </div> <!-- end row -->


                </div> <!-- end container -->
            </section>
            <!-- end freelancers -->

            <!-- @todo - Remove for launch Testimonials
            <section class="section" id="testimonials">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h3 class="title text-white">Our Customers</h3>
                            <p class="text-muted sub-title">Don't just take our word, hear what our loyal customers, both freelancers and clients, have to say about Sage Groupy.</p>
                        </div>
                    </div>


                    <div class="row text-center">
                        <div class="col-sm-12">
                            <ul class="list-inline client-list">
                                <li><a href="" title="Microsoft"><img src="images/clients/microsoft.png" alt="clients"></a></li>
                                <li><a href="" title="Google"><img src="images/clients/google.png" alt="clients"></a></li>
                                <li><a href="" title="Instagram"><img src="images/clients/instagram.png" alt="clients"></a></li>
                            </ul>


                            <div class="row">
                                <div class="col-md-4">
                                    <div class="testimonial-description text-left">
                                        <p class="text-muted">
                                            “ Just a simple example testimonial highlighting the ease of use, power and flexibility of the SageGroupy platform. This is a placeholder for later.”
                                        </p>
                                    </div>

                                    <div class="testimonial-user-info user-info text-left">
                                        <div class="testimonial-user-thumb user-thumb">
                                            <img src="images/user.jpg" alt="user-thumb">
                                        </div>
                                        <div class="testimonial-user-txt user-text">
                                            <label class="testimonial-user-name user-name">Jason</label>
                                            <p class="testimonial-user-position user-position text-muted">Some Company, Los Angeles</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="testimonial-description text-left">
                                        <p class="text-muted">
                                            “ Just a simple example testimonial highlighting the ease of use, power and flexibility of the SageGroupy platform. This is a placeholder for later.”
                                        </p>
                                    </div>

                                    <div class="testimonial-user-info user-info text-left">
                                        <div class="testimonial-user-thumb user-thumb">
                                            <img src="images/user2.jpg" alt="user-thumb">
                                        </div>
                                        <div class="testimonial-user-txt user-text">
                                            <label class="testimonial-user-name user-name">Daniel</label>
                                            <p class="testimonial-user-position user-position text-muted">Some Client, New York</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="testimonial-description text-left">
                                        <p class="text-muted">
                                            “ Just a simple example testimonial highlighting the ease of use, power and flexibility of the SageGroupy platform. This is a placeholder for later.”
                                        </p>
                                    </div>

                                    <div class="testimonial-user-info user-info text-left">
                                        <div class="testimonial-user-thumb user-thumb">
                                            <img src="images/user3.jpg" alt="user-thumb">
                                        </div>
                                        <div class="testimonial-user-txt user-text">
                                            <label class="testimonial-user-name user-name">Michael</label>
                                            <p class="testimonial-user-position user-position text-muted">Some Freelancer, Vancouver</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </section>
            -->


            <!-- Clients
                 @todo - add bg-light class and remove text-white classes when going live
            -->
            <section class="section" id="clients">
                <div class="container">

                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h3 class="title text-white">Clients</h3>
                            <p class="text-muted sub-title">Manage your projects and find the best Freelancers and Agencies.</p>
                        </div>
                    </div> <!-- end row -->

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="features-box">
                                <i class="pe-7s-diamond"></i>
                                <h4 class="text-white">Large Talent Pool</h4>
                                <p class="text-muted">Easily find the talent that you need for your project.</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="features-box">
                                <i class="pe-7s-map"></i>
                                <h4 class="text-white">View Full Portfolios</h4>
                                <p class="text-muted">View Freelancers' previous work experience, references, reviews and ratings by other clients to be sure you are getting the right person for your project.</p>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="features-box">
                                <i class="pe-7s-id"></i>
                                <h4 class="text-white">Accountability</h4>
                                <p class="text-muted">Provide public reviews and ratings for a job well done, holding the freelancer accountable for their work.</p>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="features-box">
                                <i class="pe-7s-gift"></i>
                                <h4 class="text-white">100% Free</h4>
                                <p class="text-muted">No fees or long term obligations, <em>everything</em> is FREE!</p>
                            </div>
                        </div>

                    </div> <!-- end row -->


                </div> <!-- end container -->
            </section>
            <!-- end Clients -->


            <!-- Subscribe -->
            <div id="subscribe"></div>
            <section class="section bg-custom" id="signup">
                <div class="container">

                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h3 class="title text-white">Join SageGroupy <br/>The Freelancers Friend</h3>
                            <p class="text-light sub-title">Register to join SageGroupy, it's completely free.</p>
                        </div>
                    </div><!-- End row -->

                    <div class="row text-center">
                        <!--
                        <div class="col-sm-6 col-sm-offset-3">
                            <form class="text-center" action="/prelaunch" method="post">
                                <input type="hidden" name="referrer" value="{{ Cookie::get('referrer','No Referrer') }}" />
                                <div class="form-group m-b-0">
                                    <input type="hidden" name="vf" value=""/>
                                    <input type="email" class="form-control" name="email"
                                           placeholder="Enter your e-mail address" required>
                                    <label ></label>
                                </div>

                                <button type="submit" class="btn btn-white-fill">Signup For Early Access</button>
                                <p class="text-light margin-top-5">
                                    <small>We will never spam you. <br/> You can unsubsscribe at any time.</small>
                                </p>
                            </form>
                        </div>
                        -->
                           <a class="btn btn-white-fill navbar-btn outgoing-link" href="/register">Signup Now</a></button>
                    </div><!-- End row -->

                </div> <!-- end container -->
            </section>
            <!-- End Subscribe -->


            @include('lander.footer')

            <!-- Back to top -->
            <a href="#" class="back-to-top" id="back-to-top"> <i class="pe-7s-angle-up"> </i> </a>

    @endsection


    @section('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
            	$('input[name="vf"]').val('true');
            });
        </script>
    @endsection
