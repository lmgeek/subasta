<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/img/favicon.ico" type="image/icon">
    <link rel="shortcut icon" type="image/png" href="/img/favicon.ico" />
    <title>{{ trans("general.app_name") }} :: {{ trans("general.control_panel") }}</title>

    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
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
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <script src="/js/inspinia.js"></script>
    <script src="/js/plugins/pace/pace.min.js"></script>
    <script src="/js/plugins/toastr/toastr.min.js"></script>
    <script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

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
    @include('analytics')
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
