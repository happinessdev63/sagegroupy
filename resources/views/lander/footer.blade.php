
@if (env('LIVE_LAUNCH',true))
<!-- FOOTER -->
<footer class="section bg-gray footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-md-offset-1 col-sm-6">
                <h5>Sagegroupy</h5>
                <ul class="list-unstyled">
                    <li><a href="/">Home</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/resources">Resources</a></li>
                    <li><a href="/contact">Contact Us</a></li>
                </ul>
            </div>

            <div class="col-md-3 col-md-offset-1 col-sm-6">
                <h5>Social</h5>
                <ul class="list-unstyled">
                    <li><a href="https://www.facebook.com/Sagegroupy/">Facebook</a></li>
                    <li><a href="https://twitter.com/sagegroupy">Twitter</a></li>
                    <li><a href="https://www.linkedin.com/company/sage-groupy">LinkedIn</a></li>
                </ul>
            </div>

            <div class="col-md-3 col-md-offset-1 col-sm-6">
                <h5>Support</h5>
                <ul class="list-unstyled">
                    <li><a href="/contact">Help & Support</a></li>
                    <li><a href="/privacy">Privacy Policy</a></li>
                    <li><a href="/terms">Terms & Conditions</a></li>
                    <li><a href="/res/SagegroupyUserAgreement.pdf" target="_blank">User Agreement</a></li>
                </ul>
            </div>
        </div> <!-- end row -->

        <div class="row">
            <div class="col-sm-12">
                <div class="footer-alt text-center">
                    <p class="text-muted m-b-0">{{ date("Y") }} © Sagegroupy</p>
                </div>
            </div>
        </div> <!-- end row -->

    </div> <!-- end container -->
</footer>
@endif
<!-- END FOOTER -->