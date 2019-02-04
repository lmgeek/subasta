<script async src="https://www.googletagmanager.com/gtag/js?id=UA-132702736-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    <?php if(Auth::user()){?>
    gtag('set', {'user_id': '<?=Auth::user()->nickname?>'});
    <?php }
    $urlactual = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $urldev='subastas.local.dev.netlabs.com.ar';
    $urlprod='subastasdelmar.com';
    $urlqa='subastas.qa.netlabs.com.ar';
    if(substr($urlactual,0,strlen($urlqa))==$urlqa){
        $analytics='UA-132702736-1';
    }elseif(substr($urlactual,0,strlen($urldev))==$urldev){
        $analytics='UA-132702736-2';
    }elseif(substr($urlactual,0,strlen($urlprod))==$urlprod){
        $analytics='UA-132702736-3';
    }
    ?>
    gtag('config', '<?=$analytics?>');
    gtag('set', {
        'country': 'AR',
        'currency': 'ARS'
    });
    <?php if(isset($log)){?>
    gtag('event', 'login', { 'method': 'Local' });
    <?php }
    if(isset($request->e)){?>
    gtag('event', '<?=ucfirst($request->e)?>', {
        'event_category':'<?=ucfirst($request->t)?>',
        'event_label':'ID <?=ucfirst($request->t)?>: <?=$request->id?> Usuario: <?=ucfirst(Auth::user()->nickname).'.'.((isset($request->ex))?(' '.urldecode($request->ex).'.'):'')?>',
    });
    <?php }?>
    @yield('scriptsanalytics')
</script>