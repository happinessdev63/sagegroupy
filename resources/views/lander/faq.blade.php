@extends('layouts.lander')

@section('body')
    <body data-spy="scroll" data-target="#navbar-menu">
    @endsection

    @section('nav')
        @include('lander.nav-external')
    @endsection

    @section('content')

        <!-- Header-->
            <section class="bg-custom header-title-box">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h3 class="text-white">Frequently Asked Questions</h3>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END Header-->


            <!-- FAQ -->
            <section class="section padding-top-30">
                <div class="container">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <div class="title-box-icon title-about">
                                    <p class="text-muted sub-title">You can contact us with your questions at any time. <br/>Who knows, we might even add your question to our FAQ!<br/><a href="/contact" class="btn btn-white-bordered m-t-20">Email us your question</a></p>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white">General Questions</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2">
                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> What do you charge for using the site?</h4>
                                <p class="answer text-muted"><b>A. </b>Nothing, it's FREE! </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> No seriously, how do you make money to keep operating?</h4>
                                <p class="answer text-muted"><b>A. </b>We make money from the ads you see on the site. We may add premium features in the future, and we do offer a pro service for clients that would like to take advantage of it, but otherwise everything is FREE! </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span>How does Sagegroupy work?</h4>
                                <p class="answer text-muted"><b>A. </b>The platform is free to log in and free to use so one of the best ways to see how it works is to just use it! Additionally, we have our free guides for Clients and Freelancers that covers how to get the most out of your
                                                                       experience using the platform. </p>
                                <p class="answer text-muted"> We also have a resources page that provides a variety of general freelancing and client resources. If you have questions after reviewing the FAQ and the guide please feel free to <a href="/contact">contact us</a> (perhaps we missed
                                                              something!). </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> How do I make and receive payments on the Sagegroupy platform?</h4>
                                <p class="answer text-muted"><b>A. </b>You don't. As a Freelancer or a Client you establish that with the other party. There are really good payment systems out there that are more than capable of handling payments through efficient and economical methods. You can
                                                                       find recommendations for some of those on our resources page. </p>
                                <p class="answer text-muted"> We have also compiled a short payments guide to payment options to help you decide on an approach if you need assistance. The good news is we don't take a cut of the payment! </p>
                            </div>


                        </div>
                        <!--/col-md-5 -->

                        <div class="col-md-5">

                        </div>
                        <!--/col-md-5-->
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white margin-top-20">Freelancer Questions</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2">
                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> How do I get started with Sagegroupy?</h4>
                                <p class="answer text-muted"><b>A. </b> It is easy, signup and create a profile. If you would like some help to get you started please review our freelancing guide for more details. </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> How do I get work through Sagegroupy?</h4>
                                <p class="answer text-muted"><b>A. </b>On Sagegroupy clients find you. This cuts down on unsolicited job inquiries for them and ensures that you are only putting bids in on work where clients are directly interested in you. </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> How do I rank at the top of client searches?</h4>
                                <p class="answer text-muted"><b>A. </b>While we keep our exact search algorithm confidential, the most straightforward way is to have a complete profile and regularly use the platform. If you do that you will find that you rise to the top of your skill category over time. If you would like more information about getting the most out of your experience please check out our freelancer guide. </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> What can I do to promote my services better?</h4>
                                <p class="answer text-muted"><b>A. </b> There are many things you can do to promote your services which are identified in our freelancer guide for reference.  Also our platform enables you to promote your services to those outside of the platform and we definitely suggest you take advantage of those features!</p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> What makes Sagegroupy better than other freelancing sites?</h4>
                                <p class="answer text-muted"><b>A. </b> Good question - we are free, you can use our platform on its own or in combination with other platforms, and we do not get between you and your clients either in the payment process or work patterns you like to establish and use.That and we are genuinely interested in your success and not on maximizing the dollars we can extract from you.</p>

                                <p class="answer text-muted"> You can also team up easily with other freelancers to go after larger projects that require multiple separate skill sets or many individuals with similar skills, exposing you to more business opportunities, which I am sure you will agree is always a good thing). </p>
                            </div>


                        </div>
                        <!--/col-md-5 -->

                        <div class="col-md-5">

                        </div>
                        <!--/col-md-5-->
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white margin-top-20">Client Questions</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2">
                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> How do I get started with Sagegroupy?</h4>
                                <p class="answer text-muted"><b>A. </b> It is easy, signup and create a profile. If you would like some help to get you started please review our client guide for more details. </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> How do I get work done through Sagegroupy?</h4>
                                <p class="answer text-muted"><b>A. </b>Simply create a job and search our system for a few freelancers you would like to complete your work. Let them know you are interested in their talents and they will work with you to get the job done. </p>

                                <p class="answer text-muted">
                                    Our client guide provides more information on this as well as some best practices for working with freelancers.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span>How do I make sure that my IP is safe and that I own the work that is completed?</h4>
                                <p class="answer text-muted"><b>A. </b>Many freelancers that you might work with have standard contracts that outline IP rights and ownership of deliverables. If not our resources page can help you find the right contract for your needs. </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> How much will hiring a freelancer cost?</h4>
                                <p class="answer text-muted"><b>A. </b>The cost of hiring a freelancer will vary significantly depending on the type of skills and services desired, the originating location and deliverables associated with your project. However you can be assured that Sagegroupy does not charge any fees for transactions between you and your freelancer which helps you get a great rate. Other freelancing sites can charge as much as 25%-30% combined transaction costs for transactions through their system. </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> How do I select the right freelancer for my job?</h4>
                                <p class="answer text-muted"><b>A. </b> Selecting the right freelancer should be relatively straight forward for most clients and jobs. The combination of past work examples, references, recommendations and links supplied by your prospective freelancer are all great indicators of the type of service you will receive. However if you would like assistance with freelancer selection you can use our pro service to help you with your freelancing project to the extent you wish. </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> What if I would like help selecting a freelancer or help with managing my project?</h4>
                                <p class="answer text-muted"><b>A. </b> We offer our pro service for those clients that wish for some assistance with their freelancing project. We can provide freelancing options for selection, run a selection process for you, and manage your freelancing project on your behalf. You can use any combination of these support services you deem suitable for your project. You can access our pro service here. </p>
                            </div>

                        </div>
                        <!--/col-md-5 -->

                        <div class="col-md-5">

                        </div>
                        <!--/col-md-5-->
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white margin-top-20">Agency Questions</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2">
                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> What are Agencies on the Sagegroupy platform?</h4>
                                <p class="answer text-muted"><b>A. </b>Agencies are groups of freelancers that work together. They are particularly well suited for jobs that require multiple skill sets. They can also offer multiple resources of the same skill set for large projects as well as grouping senior, intermediate and junior freelancers together to spread tasks efficiently and cost effectively. With agencies a client can get the same level of service as they would receive from a brick and mortar agency but at a fraction of the cost. Sometimes savings will be greater than 50-60%! </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> How can I leverage agencies to get more work as a freelancer? </h4>
                                <p class="answer text-muted"><b>A. </b>As a freelancer you can create an agency or join other agencies created by other freelancers.  By grouping with other freelancers you can offer a wider range and greater depth of services suitable for projects you would not be able to handle on your own.  You can gain experience working with more senior freelancers or help guide those with less experience while leveraging your skills across more projects than you would otherwise be able to complete.  </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> How can I leverage agencies to get work done as a client?</h4>
                                <p class="answer text-muted"><b>A. </b>As a client, using agencies is particularly useful when you have a project that requires multiple skillsets.  Agencies are also useful when you have a tight timeline and need the support of a group of freelancers to make sure everything is done within the time you have.  As well, agencies help clients leverage the experience of senior freelancers to oversea less experienced freelancers.  This can all add up to huge cost savings for some projects!  </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> Why would I want to be part of an agency as a freelancer?</h4>
                                <p class="answer text-muted"><b>A. </b>As a freelancer creating or joining an agency can help you get more work, help increase your experience, establish professional relationships with mentors and help leverage your skills across more projects than would otherwise be possible.   </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> Why would I hire an agency instead of a freelancer as a client?</h4>
                                <p class="answer text-muted"><b>A. </b>As a client you would hire an agency for projects that require multiple skill sets, are large or have tight timelines.  Agencies are also normally better suited to provide support after the project is completed as they have multiple individuals to draw on at any given time. </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question"><span class="text-custom">Q.</span> I have a question that wasn't on your list, where can I go for more answers?</h4>
                                <p class="answer text-muted"><b>A. </b>You can <a href="/contact">contact us</a> with your questions at any time.  Who knows, we might even add your question to our FAQ! </p>
                            </div>


                        </div>
                        <!--/col-md-5 -->

                        <div class="col-md-5">

                        </div>
                        <!--/col-md-5-->
                    </div>

                </div> <!-- end container -->
            </section>
            <!-- END FAQ -->

            @include("lander.cta");

        @include('lander.footer')

        <!-- Back to top -->
            <a href="#" class="back-to-top" id="back-to-top"> <i class="pe-7s-angle-up"> </i> </a>

@endsection


@section('scripts')
@endsection