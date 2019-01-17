<!-- Basic Page Needs
================================================== -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="author" content="humans.txt">
<!-- description -->
<meta name="description" content="">
<!-- keywords -->
<meta name="keywords" content="">
<meta property="og:title" content="" />
<meta property="og:description" content="" />
<meta property="og:image" content="/landing3/images/facebook.png" />
<!-- favicon -->
<link rel="shortcut icon" href="/landing3/images/favicon.png">
<link rel="apple-touch-icon" href="/landing3/images/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="72x72" href="/landing3/images/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="/landing3/images/apple-touch-icon-114x114.png">

<!-- CSS
================================================== -->
<link rel="stylesheet" href="/landing3/css/style.css">
<link rel="stylesheet" href="/landing3/css/color/blue.css">
<link rel="stylesheet" href="/landing3/css/datetimepicker.css">
<link rel="stylesheet" href="/landing3/css/filters.css">
<link rel="stylesheet" href="/landing3/css/netlabs-subastas3.css">
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-132702736-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
<?php if(Auth::user()){?>
gtag('set', {'user_id': '<?=Auth::user()->nickname?>'});
<?php }?>
  gtag('config', 'UA-132702736-1');
</script>
