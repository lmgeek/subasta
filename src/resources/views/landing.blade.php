<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Subastas del mar">
    <meta name="author" content="Netblas">
    <!-- Add Your favicon here -->
    <!--<link rel="icon" href="img/favicon.ico">-->

    <title>Subastas del mar</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/landing/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Animation CSS -->
    <link href="{{ asset('/landing/css/animate.min.css') }}" rel="stylesheet">

    <link href="{{ asset('/landing/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">



    <!-- Custom styles for this template -->
    <link href="{{ asset('/landing/css/style.css') }}" rel="stylesheet">
</head>
<body id="page-top">
<div class="navbar-wrapper">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header page-scroll">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Subastas del mar</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a class="page-scroll" href="#page-top">Home</a></li>
                        <li><a class="page-scroll" href="#testimonials">Testimonios</a></li>
                        <li><a class="page-scroll" href="#features">Registrarse</a></li>
                        <li><a class="page-scroll" href="#contact">Contacto</a></li>
                        <li><a class="page-scroll" href="{{ url('auth/login') }}">Ingresar</a></li>
                    </ul>
                </div>
            </div>
        </nav>
</div>
<div id="inSlider" class="carousel slide carousel-fade" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#inSlider" data-slide-to="0" class="active"></li>
        <li data-target="#inSlider" data-slide-to="1"></li>
    </ol>
    <div class="carousel-inner" role="listbox">
        <div class="item active">
            <div class="container">
                <div class="carousel-caption">
                    <h1>We craft<br/>
                        brands, web apps,<br/>
                        and user interfaces<br/>
                        we are IN+ studio</h1>
                    <p>Lorem Ipsum is simply dummy text of the printing.</p>
                    <p>
                        <a class="btn btn-primary page-scroll" href="#features">Registrate</a>
                    </p>
                </div>
                <div class="carousel-image wow zoomIn">
                    <img src="{{ asset('/landing/img/laptop.png') }}" alt="laptop"/>
                </div>
            </div>
            <!-- Set background for slide in css -->
            <div class="header-back one"></div>

        </div>
        <div class="item">
            <div class="container">
                <div class="carousel-caption blank">
                    <h1>We create meaningful <br/> interfaces that inspire.</h1>
                    <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
                    <p>
                        <a class="btn btn-primary page-scroll" href="#features">Registrate</a>
                    </p>
                </div>
            </div>
            <!-- Set background for slide in css -->
            <div class="header-back two"></div>
        </div>
    </div>
    <a class="left carousel-control" href="#inSlider" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#inSlider" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>



<section  class="container features">
    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="navy-line"></div>
            <h1>Subastas<br/> <span class="navy"> </span> </h1>
            <p>Toda la informacion que necesitas</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 text-center wow fadeInLeft">
            <div>
                <i class="fa fa-clock-o features-icon"></i>
                <h2>Info en tiempo real</h2>
                <p>Actualizacion en tiepmo real de toda la informacion que necesita para realizar las compras en cada una de las subastas.</p>
            </div>
            <div class="m-t-lg">
                <i class="fa fa-cubes features-icon"></i>
                <h2>Disponibilidad</h2>
                <p>Sepa en todo momento la disponibilidad del cada uno de los productos que desea comprar.</p>
            </div>
        </div>
        <div class="col-md-6 text-center  wow zoomIn">
            <img src="{{ asset('/landing/img/perspective.png') }}" alt="dashboard" class="img-responsive">
        </div>
        <div class="col-md-3 text-center wow fadeInRight">
            <div>
                <i class="fa fa-usd features-icon"></i>
                <h2>Precio</h2>
                <p>Visualizacion en tiempo real del precio actual del producto y el tiempo restante para que se modifique.</p>
            </div>
            <div class="m-t-lg">
                <i class="fa fa-user features-icon"></i>
                <h2>Vendedor</h2>
                <p>Conozca al vendedor y su confiabilidad gracias a nuestro sistema de calificaciones.</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="navy-line"></div>
            <h1>Facil</h1>
            <p>Facil de utilizar y muy intuitivo.</p>
        </div>
    </div>
    <div class="row features-block">
        <div class="col-lg-6 features-text wow fadeInLeft">
            <h2>Diseño</h2>
            <p>Nuestra aplicacion fue diseñada para que sea facil e intuitivo de usar.</p>
            <p>Esto le permitira realizar todas las acciones que quiera de una manera rapida y eficiente.</p>
            <a class="btn btn-primary page-scroll" href="#features">Registrate</a>
        </div>
        <div class="col-lg-6 text-right wow fadeInRight">
            <img src="{{ asset('/landing/img/dashboard.png') }}" alt="dashboard" class="img-responsive pull-right">
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Maxima Ganancia</h1>
                <p>Le proporcionamos toda la informacion que necesita para optimizar sus ganancias</p>
            </div>
        </div>
        <div class="row features-block">
            <div class="col-lg-3 features-text wow fadeInLeft">
                <h2>Control</h2>
                <p>Le ofrecemos herramientas para el total control sobre los arribo de sus barcos y gestion del cargamento.</p>
                <br>
                <h2>Seguimiento</h2>
                <p>Visualizacion del estado de todas sus subastas y todas las operaciones que se realizaron en tiempo real.</p>
                <a class="btn btn-primary page-scroll" href="#features">Registrate</a>
            </div>
            <div class="col-lg-6 text-right m-t-n-lg wow zoomIn">
                <img src="{{ asset('/landing/img/iphone.jpg') }}" class="img-responsive" alt="dashboard">
            </div>
            <div class="col-lg-3 features-text text-right wow fadeInRight">
                <h2>Elija la forma de venta</h2>
                <p>Podra elegir el metodo de venta que mas se ajuste a sus nececidades.</p>
                <p>Subasta holandesa publica que permitira que todos los usuarios puedan comprarle sus productos.</p>
                <p>Subasta holandesa privada donde solo tendran acceso los compradores que usted haya invitado.</p>
                <p>Venta privada para realizar operaciones directas.</p>
                <a class="btn btn-primary page-scroll" href="#features">Registrate</a>
            </div>
        </div>
    </div>

</section>

<section id="testimonials" class="navy-section testimonials">

    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center wow zoomIn">
                <i class="fa fa-comment big-icon"></i>
                <h1>
                    Testimonios de usuarios
                </h1>
                <div class="testimonials-text">
                    <i>"Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like)."</i>
                </div>
                <small>
                    <strong>12.02.2014 - Andy Smith</strong>
                </small>
            </div>
        </div>
    </div>

</section>
<section class="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Tome la delantera del mercado</h1>
                <p>Toda la informacion que necesita a su alcance</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-lg-offset-1 features-text">
                <h2>Facil y rapido</h2>
                <i class="fa fa-bolt big-icon pull-right"></i>
                <p>Facil y rapido acceso a la compra de mercaderia que agilizara el proceso de compra</p>
            </div>
            <div class="col-lg-5 features-text">
                <h2>Confianza</h2>
                <i class="fa fa-thumbs-up big-icon pull-right"></i>
                <p>Un sistema de calificacion de compradores y vendedores que le dara la confianza de que sus operaciones se realizaran de forma exitosa.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-lg-offset-1 features-text">
                <h2>Seguimiento</h2>
                <i class="fa fa-clock-o big-icon pull-right"></i>
                <p>Podra tener control total sobre las operaciones en cualquier lugar y en cualquier momento.</p>
            </div>
            <div class="col-lg-5 features-text">
                <h2>Directo y eficiente</h2>
                <i class="fa fa-users big-icon pull-right"></i>
                <p>Gracias a nuestra plataforma usted podra centrarse en las cosas mas importantes. Dejandonos a nosotros las tareas mas comunes.</p>
            </div>
        </div>
    </div>

</section>

<section id="features" class="container services">
    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="navy-line"></div>
            <h1>Registrate<br/> <span class="navy"></span> </h1>
            <p></p>
        </div>
    </div>
    <div class="row" st>
        <div class="col-sm-5 col-sm-offset-1">
            <center>
                <p><i class="fa fa-shopping-cart features-icon"></i></p>
                <h2>Comprador</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta dolores eligendi exercitationem impedit ipsum iusto quae quis. Delectus, excepturi id libero molestiae molestias perferendis praesentium quae sit velit, voluptate voluptatum!</p>
                {{--<p><a class="navy-link" href="/registro/comprador" role="button">Registrarse &raquo;</a></p>--}}
                <p><a href="/registro/comprador" class="btn btn-primary">Registrarse</a></p>
            </center>
        </div>
        <div class="col-sm-5">
            <center>
                <p><i class="fa fa-money features-icon"></i></p>
                <h2>Vendedor</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias beatae consequatur consequuntur dolorem doloribus, eius est facilis fugit in incidunt labore laboriosam maxime mollitia odio quo reprehenderit velit veniam voluptatibus.</p>
                {{--<p><a class="navy-link" href="/registro/vendedor" role="button">Registrarse &raquo;</a></p>--}}
                <p><a href="/registro/vendedor" class="btn btn-primary">Registrarse</a></p>
            </center>
        </div>

    </div>
</section>

<section id="contact" class="gray-section contact">
    <div class="container">
        <div class="row m-b-lg">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Contacto</h1>
                <p>Cualquier consulta o duda puede contactarse con nosotros</p>
            </div>
        </div>
        <div class="row m-b-lg">
            <div class="col-lg-3 col-lg-offset-3">
                <address>
                    <strong><span class="navy">Company name, Inc.</span></strong><br/>
                    795 Folsom Ave, Suite 600<br/>
                    San Francisco, CA 94107<br/>
                    <abbr title="Phone">P:</abbr> (123) 456-7890
                </address>
            </div>
            <div class="col-lg-4">
                <p class="text-color">
                    Consectetur adipisicing elit. Aut eaque, totam corporis laboriosam veritatis quis ad perspiciatis, totam corporis laboriosam veritatis, consectetur adipisicing elit quos non quis ad perspiciatis, totam corporis ea,
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <a href="mailto:test@email.com" class="btn btn-primary">Send us mail</a>
                <p class="m-t-sm">
                    Or follow us on social platform
                </p>
                <ul class="list-inline social-icon">
                    <li><a href="#"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li><a href="#"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li><a href="#"><i class="fa fa-linkedin"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center m-t-lg m-b-lg">
                <p><strong>&copy; 2015 Company Name</strong><br/> consectetur adipisicing elit. Aut eaque, laboriosam veritatis, quos non quis ad perspiciatis, totam corporis ea, alias ut unde.</p>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('/landing/js/jquery-2.1.1.js') }}"></script>
<script src="{{ asset('/landing/js/pace.min.js') }}"></script>
<script src="{{ asset('/landing/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/landing/js/classie.js') }}"></script>
<script src="{{ asset('/landing/js/cbpAnimatedHeader.js') }}"></script>
<script src="{{ asset('/landing/js/wow.min.js') }}"></script>
<script src="{{ asset('/landing/js/inspinia.js') }}"></script>
</body>
</html>
