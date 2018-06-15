<!DOCTYPE html>
@section('html')
    <html lang="en">
@show
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="p:domain_verify" content="b75690e8538e41bb9f3f14e2fb9ec65c"/>

        @section ('meta')
            <!-- META -->
            <meta itemprop="name" content="Sage Groupy">
            <meta itemprop="url" content="http://sagegroupy.com"/>
            <meta name="description" itemprop="description" content="Sage Groupy">
            <meta name="author" content="SageGroupy">

            <meta property="og:url" content="http://sagegroupy.com"/>
            <meta property="og:type" content="website"/>
            <meta property="og:title" content="Sage Groupy Freelancers"/>
            <meta property="og:description" content="Sage Groupy Freelancers"/>
            <meta property="twitter:url" content="http://sagegroupy.com"/>
            <meta property="twitter:type" content="website"/>
            <meta property="twitter:title" content="Sage Groupy Freelancers"/>
            <meta property="twitter:description" content="Sage Groupy Freelancers"/>

            <meta property="og:image" content="{{ env("APP_URL")}}/img/icon_xxs.png"/>
            <meta property="twitter:image" content="{{ env("APP_URL")}}/img/icon_xxs.png"/>
            <meta name="p:domain_verify" content="b75690e8538e41bb9f3f14e2fb9ec65c"/>
        @show

        @section ('title')
            <title>SageGroupy</title>
        @show

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet" type="text/css">
        <link href="/css/sage.css" rel="stylesheet" type="text/css">
        <link href="/js/plugins/jquery.fancybox.min.css" rel="stylesheet" type="text/css">


        <!-- Styles -->
        @section('header-styles')
        @show

        <!-- Scripts -->
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>
			(adsbygoogle = window.adsbygoogle || []).push({
				google_ad_client: "ca-pub-6500659003306107",
				enable_page_level_ads: true
			});
        </script>
        <script>
            window.Laravel = <?php echo json_encode( [
                'csrfToken' => csrf_token(),
                '_token' => csrf_token(),
                'apiToken' => $currentUser->api_token ?? null,
                'user' => \Auth::check() ? Auth::user() : [],
            ] ); ?>;
        </script>
        <!-- Facebook Pixel Code -->
        <script>
			!function (f, b, e, v, n, t, s) {
				if (f.fbq)return;
				n = f.fbq = function () {
					n.callMethod ?
						n.callMethod.apply(n, arguments) : n.queue.push(arguments)
				};
				if (!f._fbq) f._fbq = n;
				n.push = n;
				n.loaded = !0;
				n.version = '2.0';
				n.queue = [];
				t = b.createElement(e);
				t.async = !0;
				t.src = v;
				s = b.getElementsByTagName(e)[0];
				s.parentNode.insertBefore(t, s)
			}(window, document, 'script',
				'https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '542388909257295');
			fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1"
                 src="https://www.facebook.com/tr?id=542388909257295&ev=PageView
&noscript=1"/>
        </noscript>
        <!-- End Facebook Pixel Code -->
        @section('header-scripts')
        @show

    </head>
    @section('body')
    <body>
    @show

        <div id="app">
            @section('nav')
                @include('lander.nav')
            @show

            @section('content')
            @show
        </div>
    </body>


    <script src="/js/lander.js"></script>
    <script src="/js/plugins/jquery.fancybox.min.js"></script>
    <script src="/js/plugins/jquery.ajaxchimp.js"></script>
    <script src="/js/plugins/jquery.easing.1.3.min.js"></script>
    <script src="/js/plugins/jquery.magnific-popup.min.js"></script>
    <script src="/js/plugins/jquery.sticky.js"></script>
    <script src="/js/plugins/parsley.min.js"></script>
    <script src="/js/plugins/jquery.app.js"></script>
      <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-84063026-1', 'auto');
      ga('send', 'pageview');
    </script>
    @section('scripts')

    @show
</html>
