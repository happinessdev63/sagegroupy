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
                            <h3 class="text-white">Helpful Resources</h3>
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
                                    <p class="text-muted sub-title">You can contact us with your questions at any time. <br/><br/><a href="/contact" class="btn btn-white-bordered m-t-20">Email us your question</a></p>
                                    <p>Read our <a href="/res/SagegroupyUserGuide.pdf" target="_blank">Sagegroupy user guide.</a> </p>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white">Online Courses</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2">
                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.udemy.com/?siteID=eLimnBuxJu0-qWQ3ejSJE1VCeiE7CYae4Q&LSNPUBID=eLimnBuxJu0"
                                       class="text-custom"
                                       target="_blank">
                                        Udemy
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Join millions of students and instructors in the world’s largest online learning marketplace. Courses are as low as $15 and cover a wide range of topics from Java to Growth Hacking.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.coursera.org/"
                                       class="text-custom"
                                       target="_blank">
                                        Coursera
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Coursera provides universal access to the world’s best education, partnering with top universities and organizations to offer courses online.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.lynda.com"
                                       class="text-custom"
                                       target="_blank">
                                        Lynda
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Lynda.com is a leading online learning platform that helps anyone learn business, software, technology and creative skills to achieve personal and professional goals.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.udacity.com/"
                                       class="text-custom"
                                       target="_blank">
                                        Udacity
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Accessible, affordable, engaging, and highly effective higher education courses and nano degrees.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.codecademy.com/"
                                       class="text-custom"
                                       target="_blank">
                                        Codeacademy
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    A free online course that teaches you how to code.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.skillshare.com/"
                                       class="text-custom"
                                       target="_blank">
                                        Skillshare
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Skillshare is an online learning community where anyone can discover, take, or even teach a class.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.khanacademy.org/"
                                       class="text-custom"
                                       target="_blank">
                                        KhanAcademy
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    KhanAcademy is a nonprofit e-learning portal that aims to provide excellent lectures on different topics via Youtube Videos.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://learn.pluralsight.com/programs/pluralsight"
                                       class="text-custom"
                                       target="_blank">
                                        Plural Sight
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    A professional technology learning platform, specializing in software development.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.simplilearn.com/"
                                       class="text-custom"
                                       target="_blank">
                                        Simpli Learn
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Simplilearn is one of the world’s leading certification training providers.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.creativelive.com/design"
                                       class="text-custom"
                                       target="_blank">
                                        Creative Live
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Online courses on art and design.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.codeschool.com/"
                                       class="text-custom"
                                       target="_blank">
                                        Code School
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Code School is an online learning destination for existing and aspiring developers that teaches through entertaining content. Each course is built around a creative theme and storyline so that it feels like you’re playing a game, not sitting in a classroom.
                                </p>
                            </div>


                        </div>
                        <!--/col-md-5 -->

                        <div class="col-md-5">

                        </div>
                        <!--/col-md-5-->
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white">Social Media For Freelancers</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2">
                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://dailyfemme.teachable.com/p/social-media-101"
                                       class="text-custom"
                                       target="_blank">
                                        The Daily Femme
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Grow your social media reach organically. Free course.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.nickykriel.com/free-twitter-mini-course/"
                                       class="text-custom"
                                       target="_blank">
                                        Nickey Kriel.
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Twitter for Business. Free Course
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.momsmakecents.com/free-pinterest-course"
                                       class="text-custom"
                                       target="_blank">
                                        Moms Make Cents
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    How to excel with Pinterest. Free Course
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://alextooby.com/free-instagram-tips/"
                                       class="text-custom"
                                       target="_blank">
                                        Alex Tooby
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    How to master Instagram. Free course.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://business.tutsplus.com/articles/15-ways-new-freelancers-can-use-social-media-to-boost-business--fsw-35092"
                                       class="text-custom"
                                       target="_blank">
                                        Envatos Tuts
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    15 Ways New Freelancers Can Use Social Media to Boost Business
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://socialmediaweek.org/blog/2015/09/freelancers-social-new-clients/"
                                       class="text-custom"
                                       target="_blank">
                                        Social Media Week
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    How freelancers can use social media to find new clients.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.adweek.com/digital/how-freelancers-should-be-using-social-media/"
                                       class="text-custom"
                                       target="_blank">
                                        Adweek
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    How freelancers should be using social media.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.freelancer.ca/community/articles/social-media-strategies-to-win-more-freelance-clients"
                                       class="text-custom"
                                       target="_blank">
                                        Freelancer
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Social media strategies that help get you clients.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.webhostingsecretrevealed.net/blog/socialmedia-marketing/8-unusual-but-powerful-social-networks-for-freelance-bloggers/"
                                       class="text-custom"
                                       target="_blank">
                                        Web Hosting Secrets Revealed
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    8 unusual but powerful social networks for freelance bloggers
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.forbes.com/sites/abdullahimuhammed/2016/08/05/5-social-media-strategies-to-help-freelancers-land-better-clients/"
                                       class="text-custom"
                                       target="_blank">
                                        Forbes
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    5 Social Media Strategies To Help Freelancers Land Better Clients
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://doubleyourfreelancing.com/get-high-value-clients-with-social-media/"
                                       class="text-custom"
                                       target="_blank">
                                        Double Your Freelancing
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    How to get high value clients with social media.
                                </p>
                            </div>


                        </div>
                        <!--/col-md-5 -->

                        <div class="col-md-5">

                        </div>
                        <!--/col-md-5-->
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white">Freelance Accounting</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2">
                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.waveapps.com"
                                       class="text-custom"
                                       target="_blank">
                                        Wave App
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Free Financial Software for Small Businesses. Track your expenses, send invoices, get paid and balance your books.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.tycoonapp.com/"
                                       class="text-custom"
                                       target="_blank">
                                        Tycoon App
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Tycoon develops financial management products for freelancers, helping them become modern-day tycoons. It was founded by Jess Perez, a fashion model with over fifteen years of freelancing experience.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.hellobonsai.com/a/freelance-invoice-app"
                                       class="text-custom"
                                       target="_blank">
                                        Hello Bonsai
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Everything that helps your freelance business in the back office. Time tracking, invoicing, contracts etc.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.paypal.com/"
                                       class="text-custom"
                                       target="_blank">
                                        Paypal
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Almost. universally accepted, Paypal provides an easy to use system that helps you send invoices and receive payments linked to your bank accounts.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.freshbooks.com"
                                       class="text-custom"
                                       target="_blank">
                                        FreshBooks
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    FreshBooks creates easy, professional looking invoices along with all the tracking features and branding features. It’s like having your own Accounting Department
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.beginner-bookkeeping.com/bookkeeping-software-free.html"
                                       class="text-custom"
                                       target="_blank">
                                        Beginner Bookkeeping
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Free bookkeeping software for small businesses
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://blog.ignitespot.com/virtual-bookkeeping-checklist-the-basics-for-small-businesses"
                                       class="text-custom"
                                       target="_blank">
                                        Ignitespot
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Infographic for basic bookkeeping duties for small business
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://thinkcreativecollective.com/blog/2015/10/26/small-business-101-accounting-for-the-non-accountant"
                                       class="text-custom"
                                       target="_blank">
                                        Think Creative Collective
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Small business accounting 101.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://nerdymillennial.com/self-employment-tax-deductions-for-bloggers/"
                                       class="text-custom"
                                       target="_blank">
                                        Nerdy Millennial
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Tax deductions for self employed.
                                </p>
                            </div>

                            <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.facebook.com/groups/WAHMtaxgroup/"
                                       class="text-custom"
                                       target="_blank">
                                        WAHM Facebook Forum
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Accounting and US Tax Compliance forum
                                </p>
                            </div>


                        </div>
                        <!--/col-md-5 -->

                        <div class="col-md-5">

                        </div>
                        <!--/col-md-5-->
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white">Contracts</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2"><!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.pandadoc.com/freelance-contract-template/"
                                       class="text-custom"
                                       target="_blank">
                                        Pandadoc
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Free and simple contract for freelancers with basic legal terms. Download and fill in.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.hongkiat.com/blog/freelance-contract-clauses/"
                                       class="text-custom"
                                       target="_blank">
                                        Hongkiat Blog
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    This post discusses clauses that freelancers should include in their contracts.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.thebalance.com/five-contract-templates-for-freelancers-1360292"
                                       class="text-custom"
                                       target="_blank">
                                        The Balance
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Provides several templates for freelance contractors.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.sitepoint.com/how-to-create-a-freelance-contract-that-benefits-you-your-clients/"
                                       class="text-custom"
                                       target="_blank">
                                        Site Point
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Offers a guide and sample for creating a contract made for freelancers.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://contractmint.stfi.re/the-freelance-graphic-design-contract/?sf=nglywbj"
                                       class="text-custom"
                                       target="_blank">
                                        Contract Mint
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Contracts for sale for reasonable prices made specifically for freelancers. Take the worry out by purchasing a ready made and customizable contract.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://daviddalloreblog.blogspot.com/2016/07/start-my-own-business.html"
                                       class="text-custom"
                                       target="_blank">
                                        David Dallore Blog
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Infographic on writing contacts and tips on starting a small business.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://neshawoolery.com/blog/4-things-all-designers-should-include-in-their-contracts"
                                       class="text-custom"
                                       target="_blank">
                                        Nesha Woolery Blog
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    4 things that freelance designers should include in their contracts.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://mharriseditor.com/8-must-haves-freelance-editing-contracts/"
                                       class="text-custom"
                                       target="_blank">
                                        Megan Harris Blog
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Freelance contract clauses for editors.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://desiretodone.com/virtual-assistant-contracts/"
                                       class="text-custom"
                                       target="_blank">
                                        Desire To Done
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Contract sample for virtual assistants.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.belong-mag.com/blog/2016/6/13/terms-and-conditions-protecting-your-business-so-you-dont-have-to-stay-up-at-night"
                                       class="text-custom"
                                       target="_blank">
                                        Belong Magazine
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Terms and conditions to include that protect your freelance business.
                                </p>
                            </div>
                        </div>
                        <!--/col-md-5 -->

                        <div class="col-md-5">

                        </div>
                        <!--/col-md-5-->
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white">Portfolio Design</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2"><!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://graphicriver.net/item/black-white-creative-clean-powerpoint-presentation/16990669?ref=ksioks&clickthrough_id=1048514452&redirect_back=true"
                                       class="text-custom"
                                       target="_blank">
                                        Graphic River
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    This powerpoint template gives an example of what a good portfolio should look like and gives instruction on how to make something similar.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://creativemarket.com/tujuhbenua/1366877-Graphic-Design-Portfolio-Template?utm_source=Pinterest&utm_medium=CM%20Social%20Share&utm_campaign=Product%20Social%20Share&utm_content=Graphic%20Design%20Portfolio%20Template"
                                       class="text-custom"
                                       target="_blank">
                                        Creative Market
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Templates you can purchase for a reasonable price, geared towards graphic designers.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://wanderful-world.com/2016/05/03/rock-your-freelancer-portfolio/"
                                       class="text-custom"
                                       target="_blank">
                                        Wanderful World
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    This article walks you through starting your first portfolio.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://ambitionally.com/careers/building-a-freelance-portfolio/"
                                       class="text-custom"
                                       target="_blank">
                                        Ambitionally
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    8 Tips for building a freelance portfolio that’s stunning… even if you’re a beginner
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://hdesignstudios.com/build-a-portfolio/"
                                       class="text-custom"
                                       target="_blank">
                                        Sharese Hendricks
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    How to build a design portfolio without clients.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.writingrevolt.com/freelance-writing-portfolio/"
                                       class="text-custom"
                                       target="_blank">
                                        Writing Revolt
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    How to create a client-winning freelance writing portfolio.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.freelancerfaqs.com/build-a-copywriter-portfolio/"
                                       class="text-custom"
                                       target="_blank">
                                        Freelancer Faqs
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    How to build a copywriter portfolio.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://skillcrush.com/2015/03/12/impressive-tech-portfolio/"
                                       class="text-custom"
                                       target="_blank">
                                        Skill Crush
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Portfolio how to for tech freelancers.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://neshawoolery.com/blog/building-a-portfolio-that-wins-you-the-work-you-want"
                                       class="text-custom"
                                       target="_blank">
                                        Nesha Woolery
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Building a portfolio to get high quality clients.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.lindsayhumes.com/portfolio-best-practices/"
                                       class="text-custom"
                                       target="_blank">
                                        Lindsay Humes
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Portfolio best practices for creative freelancers.
                                </p>
                            </div>
                        </div>
                        <!--/col-md-5 -->

                        <div class="col-md-5">

                        </div>
                        <!--/col-md-5-->
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white">Setting Rates</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2"><!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.therandomp.com/blog/freelancing-rates/"
                                       class="text-custom"
                                       target="_blank">
                                        The Random Passion Project
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Freelancing rates: how much to charge for your freelance projects?
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.forbes.com/sites/laurashin/2014/10/27/freelancers-heres-how-to-set-your-rates/#7622b027c480"
                                       class="text-custom"
                                       target="_blank">
                                        Forbes
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    This article explains how to decide on your rate.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://blog.creativelive.com/how-to-calculate-freelance-hourly-rate-infographic/"
                                       class="text-custom"
                                       target="_blank">
                                        Creative Live
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Infographic on deciding your freelance rate.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://lifehacker.com/5994064/the-complete-guide-to-setting-and-negotiating-freelance-rates"
                                       class="text-custom"
                                       target="_blank">
                                        Life Hacker
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    A guide on setting and negotiation freelance rates.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://careerfoundry.com/en/blog/career-change/pricing-freelancer/"
                                       class="text-custom"
                                       target="_blank">
                                        Career Foundry
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    How to price yourself as a freelancer.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://saganmorrow.com/rhetorically/how-to-price-freelance-services/"
                                       class="text-custom"
                                       target="_blank">
                                        Sagan Morrow
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Pricing yourself as as freelancer.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.thevirtualsavvy.com/set-rates-virtual-assistant/"
                                       class="text-custom"
                                       target="_blank">
                                        The Virtual Savvy
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Setting rates for virtual assistants.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.melissacarterdesign.com/how-to-create-a-pricing-strategy-for-your-freelance-services/?utm_content=buffer10ffa&utm_medium=social&utm_source=pinterest.com&utm_campaign=buffer"
                                       class="text-custom"
                                       target="_blank">
                                        Melissa Carter Design
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Tips on setting rates for freelance designers.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://webloomandgrow.com/en/2017/06/27/ultimate-pricing-guide-for-creative-business-owners/"
                                       class="text-custom"
                                       target="_blank">
                                        We Bloom and Grow
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Tips for setting rates on creative freelancing
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.thebusinessbakery.stfi.re/working-out-your-hourly-rate/?sf=vxxwzzr"
                                       class="text-custom"
                                       target="_blank">
                                        The Business Bakery
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Working out your hourly rate.
                                </p>
                            </div>
                        </div>
                        <!--/col-md-5 -->

                        <div class="col-md-5">

                        </div>
                        <!--/col-md-5-->
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white">Freelance Business Plans</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2"><!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://byregina.com/how-to-write-a-freelance-business-plan/"
                                       class="text-custom"
                                       target="_blank">
                                        By Regina
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    How to write a freelance business plan.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://lp.fiverr.com/lp-busplan-go/"
                                       class="text-custom"
                                       target="_blank">
                                        Fiverr
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Business plans made for freelancers.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="By Grace Bello"
                                       class="text-custom"
                                       target="_blank">
                                        Contently
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    4 Steps to Creating a Freelancing Business Plan You’ll Actually Use
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://millo.co/17-tips-for-effective-freelance-business-planning"
                                       class="text-custom"
                                       target="_blank">
                                        Millo
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    17 Tips for effective freelance business planning
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.godaddy.com/garage/webpro/clients/start-your-freelancing-with-a-simple-business-plan/"
                                       class="text-custom"
                                       target="_blank">
                                        Go Daddy
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    This article explains why freelancers need a good business plan.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.howdesign.com/creative-freelancer-blog/freelancer-need-business-plan/"
                                       class="text-custom"
                                       target="_blank">
                                        How Design
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    This article discusses whether you need a business plan.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.growthink.com/products/bpt/pre-vsl/misc"
                                       class="text-custom"
                                       target="_blank">
                                        Grow Think
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Business plan template available for download.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.inc.com/larry-kim/top-10-business-plan-templates-you-can-download-free.html"
                                       class="text-custom"
                                       target="_blank">
                                        Inc.
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    10 business plan templates to download.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.bplans.com/create-your-business-plan.php?link=on_sample_index"
                                       class="text-custom"
                                       target="_blank">
                                        BPlans
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Over 500 samples of business plans.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.business.com/articles/7-best-free-business-plan-templates/"
                                       class="text-custom"
                                       target="_blank">
                                        Business.com
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Top 7 free business plan templates
                                </p>
                            </div>
                        </div>
                        <!--/col-md-5 -->

                        <div class="col-md-5">

                        </div>
                        <!--/col-md-5-->
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white">Communities</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2"><!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.freelancingwiz.com/podcast/podcast-for-freelancers-client-acquisition-retention/"
                                       class="text-custom"
                                       target="_blank">
                                        Freelancing Wiz
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Podcast For Freelancers.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://mashable.com/2011/05/02/freelancer-communities/#B0pOHo7B_Oqo"
                                       class="text-custom"
                                       target="_blank">
                                        Mashable
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    5 Valuable Online Communities for Freelancers
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.reddit.com/r/freelanceWriters/"
                                       class="text-custom"
                                       target="_blank">
                                        Reddit- Subreddit Freelance Writers
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    A place to share tips about writing, including how to get more gigs or jobs.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://blog.creativelive.com/online-community-for-creatives/"
                                       class="text-custom"
                                       target="_blank">
                                        Creative Live
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Online communities for creative freelancers
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://medium.com/@andco_26266/5-great-online-communities-for-freelance-marketers-63940ee88b5b"
                                       class="text-custom"
                                       target="_blank">
                                        Medium
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    5 great online communities for freelance marketers
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.flareapps.com/blog/freelancing-forums-list/"
                                       class="text-custom"
                                       target="_blank">
                                        Flare Cloud Accounting
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Freelancing Forums List – Community, Help, Advice
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://cmxhub.com/article/how-the-most-successful-freelance-community-professionals-thrive/"
                                       class="text-custom"
                                       target="_blank">
                                        CMX Hub
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Where to find freelance communities.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://teleport.org/blog/2016/02/freelancers-remote-workers-need-communities/"
                                       class="text-custom"
                                       target="_blank">
                                        Teleport
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Why freelancers need communities.
                                </p>
                            </div>
                        </div>
                        <!--/col-md-5 -->

                        <div class="col-md-5">

                        </div>
                        <!--/col-md-5-->
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white">Blogging</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2"><!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://wonderlass.leadpages.co/from-side-hustle-to-success/"
                                       class="text-custom"
                                       target="_blank">
                                        Wonderlass Blogging Courses
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Turn your blog into a profitable business with tips and courses from Wonderlass.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://conversionminded.com/blog-checklist/"
                                       class="text-custom"
                                       target="_blank">
                                        Conversion Mind
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    7 things to do after publishing a blog- checklist
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.emilie-guerin.com/blog/place-to-share-your-blog-post"
                                       class="text-custom"
                                       target="_blank">
                                        Emily Guerin
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Sharing your blog post after you hit publish.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://breakfastatlillys.com/planning-blog-content/"
                                       class="text-custom"
                                       target="_blank">
                                        Breakfast and Lilly’s
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Planning blog content- a helpful guide.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://blog.bufferapp.com/blogging-advice-for-beginners-from-16-experts"
                                       class="text-custom"
                                       target="_blank">
                                        Buffer
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    16 Top Tips from Blogging Experts for Beginners
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://blog.hubspot.com/marketing/beginner-blogger-mistakes"
                                       class="text-custom"
                                       target="_blank">
                                        Hubspot
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Blogging tips and mistakes to avoid.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.bloggingtips.com"
                                       class="text-custom"
                                       target="_blank">
                                        Blogging Tips
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Introduction to niche blogging.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.bloggingbasics101.com/how-do-i-start-a-blog/"
                                       class="text-custom"
                                       target="_blank">
                                        Blogging Basics
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    How to start a blog.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.socialmediaexaminer.com/39-blogging-tips/"
                                       class="text-custom"
                                       target="_blank">
                                        Social Media Examiner
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    39 blogging tips from super pros.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.huffingtonpost.com/topic/blogging-tips"
                                       class="text-custom"
                                       target="_blank">
                                        Huffington Post
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Tips from expert bloggers.
                                </p>
                            </div>
                        </div>
                        <!--/col-md-5 -->

                        <div class="col-md-5">

                        </div>
                        <!--/col-md-5-->
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white">Branding</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2"><!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.bigmarker.com/communities/kuvio/conferences#social_promotion"
                                       class="text-custom"
                                       target="_blank">
                                        Kuvio Creative
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Kuvio Creative LLC hosts free Wednesday Webinars where their Director of Design hosts and answers all of your branding questions.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.byrosanna.co.uk/blog/brand-exercise-whats-your-onliness-statement"
                                       class="text-custom"
                                       target="_blank">
                                        By Rosana
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Branding exercise for beginners.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://mariahalthoff.com/blogposts/create-perfect-color-palette"
                                       class="text-custom"
                                       target="_blank">
                                        Mariah Althoff

                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Branding for beginner: colour palettes
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://elsieroadmagazine.com/the-beginners-guide-to-branding/"
                                       class="text-custom"
                                       target="_blank">
                                        Elsie Road Magazine
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    The beginner's guide to branding.
                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://navidmoazzez.com/personal-branding-tips/"
                                       class="text-custom"
                                       target="_blank">
                                        Navid Moazzez

                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    9 Tips for Creating an Awesome Brand

                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="https://www.forbes.com/pictures/fjji45efli/5-must-read-tips-for-building-a-brand/#6a6dd54f49dd"
                                       class="text-custom"
                                       target="_blank">
                                        Forbes
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    37 Experts Share Their Best Personal Branding Tips For Entrepreneurs

                                </p>
                            </div>
                                                              <!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="http://www.huffingtonpost.com/entry/10-sure-fire-branding-tips-that-work-for-entrepreneurs_us_58cc3fa9e4b07112b6472d64"
                                       class="text-custom"
                                       target="_blank">
                                        Huffington Post

                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    Tips for building a brand.
                                </p>
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