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
<link rel="stylesheet" href="/landing3/css/style.css">
<link rel="stylesheet" href="/landing3/css/color/blue.css">
<link rel="stylesheet" href="/landing3/css/datetimepicker.css">
<link rel="stylesheet" href="/landing3/css/filters.css">
<link rel="stylesheet" href="/landing3/css/netlabs-subastas3.css">
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-132702736-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
<?php if(Auth::user()){?>
gtag('set', {'user_id': '<?=Auth::user()->nickname?>'});
<?php }?>
  gtag('config', 'UA-132702736-1');
  gtag('set', {
    'country': 'AR',
    'currency': 'ARS'
  });
  <?php if(isset(Auth::user()->id) && isset($log) and $log==1){echo "gtag('event', 'login', { 'method': 'Local' });";}?>
</script>

<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', 'your-pixel-id-goes-here');
  fbq('track', 'PageView');
</script>
<noscript>
  <img height="1" width="1" style="display:none"
       src="https://www.facebook.com/tr?id=your-pixel-id-goes-here&ev=PageView&noscript=1"/>
</noscript>


<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '2005390536433596',
      cookie     : true,
      xfbml      : true,
      version    : 'v3.2',
      <?php if(Auth::user()){?>
      uid: '<?=Auth::user()->nickname?>'
      <?php }?>
    });

    FB.AppEvents.logPageView();

  };

fbq('init', '<pixel_id>', {uid: '<userID>'});
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>