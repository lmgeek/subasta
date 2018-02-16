<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">

                @include('menu.partials.profile')

                <div class="logo-element">
                    <img src="{{ asset('/images/logo.png') }}" alt="" style="max-width: 49px"/>
                </div>
            </li>
            @include('menu.partials.menu.'.Auth::user()->type)
        </ul>
		<a class="navbar-minimalize hidden-xs  sidebar-minify-btn" href="#"><i class="ccc fa fa-angle-double-left"></i></a>
    </div>
</nav>
<script>
	$( ".sidebar-minify-btn" ).click(function(){
		$( this ).find('i').toggleClass('fa-angle-double-right');
	});
	
</script>
<style>
.sidebar-minify-btn {
    margin: 10px 0;
    float: right;
    padding: 5px 20px 5px 10px!important;
    background: #293846;
    color: #fff;
	font-size:20px;
    -webkit-border-radius: 20px 0 0 20px;
    -moz-border-radius: 20px 0 0 20px;
    border-radius: 20px 0 0 20px;
}
a.sidebar-minify-btn:hover , a.sidebar-minify-btn:focus {color: #fff;}
</style>