<script async src="https://www.googletagmanager.com/gtag/js?id=<?=env('GOOGLE_ANALYTICS_ID')?>"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    <?php if(Auth::user()){?>
    gtag('set', {'user_id': '<?=Auth::user()->nickname?>'});
    <?php }?>
    gtag('config', '<?=env('GOOGLE_ANALYTICS_ID')?>');
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
        'event_label':'<?=(isset($request->id))?('ID '.ucfirst($request->t).': '.$request->id.'. '):''?>Usuario: <?=ucfirst(Auth::user()->nickname).'. '.((isset($request->ex))?(' '.urldecode($request->ex).'.'):'')?>',
    });
    <?php }?>
    @yield('scriptsanalytics')
</script>