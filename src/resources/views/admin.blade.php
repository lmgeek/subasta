<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/icon">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}" />
    <title>{{ trans("general.app_name") }} :: {{ trans("general.control_panel") }}</title>

    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    @yield('stylesheets')

            <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Scripts -->
    <script src="{{ asset('/js/jquery-2.1.1.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <script src="{{ asset('/js/inspinia.js') }}"></script>
    <script src="{{ asset('/js/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <script>
        @if (Session::has('notification'))
                $(document).ready(function(){
                    App.notificacion['{{  Session::get('notification')['type'] }}'](
                            '{{  Session::get('notification')['title'] }}',
                            '{{  Session::get('notification')['message'] }}');
                });
        @endif
    </script>

    @yield('scripts')

            <!-- Google Analytics -->
    <script>
        $(document).ready(function(){
            var btn = $('.noDblClick').click(function (e) {
                var isValid = $(this).parents('form')[0].checkValidity();
                if(isValid) {
                    $(this).button('loading');
                }
            })
        });

        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', '{{ Config::get('app.google_analytics_key') }}', { 'userId': '{{  Auth::user()->id }}' });
        ga('send', 'pageview');
    </script>
    <!-- End Google Analytics -->
</head>
<body>

<div id="wrapper">

    @include('menu.sidebar')

    <div id="page-wrapper" class="gray-bg">
        @include('navbar.navbar')

        @yield('content')

        @include('footer.footer')
    </div>
</div>

</body>

</html>
