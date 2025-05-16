<?php
session_start(); // Start the session

// Load JSON data
$jsonData = file_get_contents('products.json');
$products = json_decode($jsonData, true);
?>

<!doctype html>
<html class="no-js" lang="en">

    <head>
        <!-- meta data -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <!--font-family-->
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
        
        <!-- title of site -->
        <title>OH Store</title>

        <!-- For favicon png -->
		<link rel="shortcut icon" type="image/icon" href="assets/logo/logo.ico"/>
       
        <!--font-awesome.min.css-->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">

        <!--linear icon css-->
		<link rel="stylesheet" href="assets/css/linearicons.css">

		<!--animate.css-->
        <link rel="stylesheet" href="assets/css/animate.css">

        <!--owl.carousel.css-->
        <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
		<link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
		
        <!--bootstrap.min.css-->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- bootsnav -->
		<link rel="stylesheet" href="assets/css/bootsnav.css" >	
        
        <!--style.css-->
        <link rel="stylesheet" href="assets/css/style.css">
        
        <!--responsive.css-->
        <link rel="stylesheet" href="assets/css/responsive.css">
        
    </head>
        
        <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-N5GD1VJ7K7"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-N5GD1VJ7K7');
</script>
	
	<body>
				
		<!--welcome-hero start -->
		<header id="home" class="welcome-hero">

			<div id="header-carousel" class="carousel slide carousel-fade" data-ride="carousel">
				<!--/.carousel-indicator -->
				 <ol class="carousel-indicators">
					<li data-target="#header-carousel" data-slide-to="0" class="active"><span class="small-circle"></span></li>
					<li data-target="#header-carousel" data-slide-to="1"><span class="small-circle"></span></li>
					<li data-target="#header-carousel" data-slide-to="2"><span class="small-circle"></span></li>
				</ol><!-- /ol-->
				<!--/.carousel-indicator -->

				<!--/.carousel-inner -->
				<div class="carousel-inner" role="listbox">
					<!-- .item -->
					<div class="item active">
						<div class="single-slide-item slide1">
							<div class="container">
								<div class="welcome-hero-content">
									<div class="row">
										<div class="col-sm-7">
											<div class="single-welcome-hero">
												<div class="welcome-hero-txt">
													<h2>Bienvenido a OH Store!</h2>
													<p>
													Tu tienda online para todo lo coreano: álbumes K-Pop, cosméticos K-Beauty, snacks y mucho más. ¡Explora nuestra selección y sumérgete en la cultura que te encanta! 
													</p>
													<button class="btn-cart welcome-add-cart" onclick="window.location.href='catalogo.php'">
														Nuestro Catalogo
													</button>
												</div><!--/.welcome-hero-txt-->
											</div><!--/.single-welcome-hero-->
										</div><!--/.col-->
										<div class="col-sm-5">
											<div class="single-welcome-hero">
												<div class="welcome-hero-img">
													<img src="assets/images/slider/lightstick.png" alt="slider image">
												</div><!--/.welcome-hero-txt-->
											</div><!--/.single-welcome-hero-->
										</div><!--/.col-->
									</div><!--/.row-->
								</div><!--/.welcome-hero-content-->
							</div><!-- /.container-->
						</div><!-- /.single-slide-item-->

					</div><!-- /.item .active-->

					<div class="item">
						<div class="single-slide-item slide2">
							<div class="container">
								<div class="welcome-hero-content">
									<div class="row">
										<div class="col-sm-7">
											<div class="single-welcome-hero">
												<div class="welcome-hero-txt">
													<h4>Noticia</h4>
													<h2>¡𝗚𝗜𝗩𝗘𝗔𝗪𝗔𝗬: J-HOPE ON THE STAGE-Live in Japan</h2>
													<p>
														¡Llévate 2 ENTRADAS para vivir el concierto de J-Hope en la pantalla grande en Cinépolis Las Misiones! 🎬💜 
													</p>
													<button class="btn-cart welcome-add-cart" onclick="window.location.href='https://www.instagram.com/p/DJc6bJptor6/'">
														Participa
													</button>
													
												</div><!--/.welcome-hero-txt-->
											</div><!--/.single-welcome-hero-->
										</div><!--/.col-->
										<div class="col-sm-5">
											<div class="single-welcome-hero">
												<div class="welcome-hero-img">
													<img src="assets/images/slider/anuncio1.JPG" alt="slider image">
												</div><!--/.welcome-hero-txt-->
											</div><!--/.single-welcome-hero-->
										</div><!--/.col-->
									</div><!--/.row-->
								</div><!--/.welcome-hero-content-->
							</div><!-- /.container-->
						</div><!-- /.single-slide-item-->

					</div><!-- /.item .active-->

					<div class="item">
						<div class="single-slide-item slide3">
							<div class="container">
								<div class="welcome-hero-content">
									<div class="row">
										<div class="col-sm-7">
											<div class="single-welcome-hero">
												<div class="welcome-hero-txt">
													<h4>El encanto de Beomgyu en tus labios</h4>
													<h2>Descubre el nuevo labial de #CoralHaze</h2>
													<p>
														Un color suave pero impactante, con la fórmula hidratante que tus labios merecen.
														</p>
                                                       <p>
														💄 ¿Lo mejor? Elegido por el estilo único y carismático de Beomgyu. Ideal para un look diario o ese toque especial para tus selfies.
														</p>
														<p>
															🌟 Disponible en edición limitada... ¡No te lo pierdas!
													</p>
													<button class="btn-cart welcome-add-cart" onclick="window.location.href='catalogo.php'">
														Realizar pedido
													</button>
												</div><!--/.welcome-hero-txt-->
											</div><!--/.single-welcome-hero-->
										</div><!--/.col-->
										<div class="col-sm-5">
											<div class="single-welcome-hero">
												<div class="welcome-hero-img">
													<img src="assets/images/slider/anuncio2.jpg" alt="slider image">
												</div><!--/.welcome-hero-txt-->
											</div><!--/.single-welcome-hero-->
										</div><!--/.col-->
									</div><!--/.row-->
								</div><!--/.welcome-hero-content-->
							</div><!-- /.container-->
						</div><!-- /.single-slide-item-->
						
					</div><!-- /.item .active-->
				</div><!-- /.carousel-inner-->

			</div><!--/#header-carousel-->

			<!-- top-area Start -->
			<div class="top-area">
				<div class="header-area">
					<nav class="navbar navbar-default bootsnav navbar-fixed navbar-sticky navbar-scrollspy navbar-white" data-minus-value-desktop="70" data-minus-value-mobile="55" data-speed="1000">

    <div class="container">            
        <!-- Start Atribute Navigation -->
        <div class="attr-nav">
            <ul>
                <li class="nav-setting">
                    <a href="admin_login.php"><span class="lnr lnr-user"></span></a>
                </li><!--/.search-->
                <li class="dropdown">
                    <a href="catalogo.php" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="lnr lnr-cart"></span>
                        <span class="badge badge-bg-1"></span>
                    </a>
                </li><!--/.dropdown-->
            </ul>
        </div><!--/.attr-nav-->
        <!-- End Atribute Navigation -->

        <!-- Start Header Navigation -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="index.php">OH STORE</a>
        </div><!--/.navbar-header-->
        <!-- End Header Navigation -->

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
            <ul class="nav navbar-nav navbar-center" data-in="fadeInDown" data-out="fadeOutUp">
                <li class="scroll active"><a href="#home">Inicio</a></li>
                <li class="scroll"><a href="#new-arrivals">Productos</a></li>
                <li class="scoll"><a href="catalogo.php">Catalogo</a></li>
                <li class="scroll"><a href="#blog">Noticias</a></li>
                <li class="scroll"><a href="#newsletter">Contacto</a></li>
            </ul><!--/.nav -->
        </div><!-- /.navbar-collapse -->
    </div><!--/.container-->
</nav><!--/nav-->

				    <!-- End Navigation -->
				</div><!--/.header-area-->
			    <div class="clearfix"></div>

			</div><!-- /.top-area-->
			<!-- top-area End -->

		</header><!--/.welcome-hero-->
		<!--welcome-hero end -->

		<!--populer-products start -->
		<section id="populer-products" class="populer-products">
			<div class="container">
				<div class="populer-products-content">
					<div class="row">
						<div class="col-md-3">
							<div class="single-populer-products">
								<div class="single-populer-product-img mt40">
									<img src="assets/images/populer-products/caja.png" alt="populer-products images">
								</div>
								<h2>Un Bocado de Corea</h2>
								<div class="single-populer-products-para">
									<p>¡Prepárate para una explosión de sabor con nuestra increíble selección de snacks coreanos en Ohstore!</p>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="single-populer-products">
								<div class="single-inner-populer-products">
									<div class="row">
										<div class="col-md-4 col-sm-12">
											<div class="single-inner-populer-product-img">
												<img src="assets/images/populer-products/superj.png" alt="populer-products images">
											</div>
										</div>
										<div class="col-md-8 col-sm-12">
											<div class="single-inner-populer-product-txt">
												<h2>
														¿Buscas lo último de Corea?
												</h2>
												<p>
													Somos tu destino principal para encontrar una cuidada selección de artículos coreanos que te enamorarán. Explora nuestra colección y déjate sorprender.
												</p>
											
												<button class="btn-cart welcome-add-cart populer-products-btn" onclick="window.location.href='catalogo.php'">
													Catalogo
												</button>  
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="single-populer-products">
								<div class="single-populer-products">
									<div class="single-populer-product-img">
										<img src="assets/images/populer-products/album.png" alt="populer-products images">
									</div>
									<h2>El Universo K-pop en tus Manos</h2>
									<div class="single-populer-products-para">
										<p>Aquí encontrarás los últimos lanzamientos de tus artistas favoritos, ediciones especiales y todo lo que necesitas para conectar aún más con la música y el arte que amas.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!--/.container-->

		</section><!--/.populer-products-->
		<!--populer-products end-->
<br><br>
		<!--sofa-collection start -->
		<section id="sofa-collection">
			<div class="owl-carousel owl-theme" id="collection-carousel">
				<div class="sofa-collection collectionbg1">
					<div class="container">
						<div class="sofa-collection-txt">
							<h2>unlimited sofa collection</h2>
							<p>
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
							</p>
						</div>
					</div>	
				</div><!--/.sofa-collection-->
				<div class="sofa-collection collectionbg2">
					<div class="container">
						<div class="sofa-collection-txt">
							<h2>unlimited dainning table collection</h2>
							<p>
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
							</p>
						</div>
					</div>
				</div><!--/.sofa-collection-->
			</div><!--/.collection-carousel-->

		</section><!--/.sofa-collection-->
		<!--sofa-collection end -->

		<!--new-arrivals start -->
		<section id="new-arrivals" class="new-arrivals">
			<div class="container">
				<div class="section-header">
					<h2>Productos mas Recientes</h2>
				</div><!--/.section-header-->
				<div class="new-arrivals-content">
					<div class="row">
						<div class="col-md-3 col-sm-4">
							<div class="single-new-arrival">
								<div class="single-new-arrival-bg">
									<img src="assets/images/collection/1.png" alt="new-arrivals images">
									<div class="single-new-arrival-bg-overlay"></div>
								</div>
								<div class="single-feature-txt text-center">
									<h4>TAMAGOTCHI × SKZOO</h4>
									</div>
								<p class="arrival-product-price">$1,350MXN</p>
							</div>
						</div>
						<div class="col-md-3 col-sm-4">
							<div class="single-new-arrival">
								<div class="single-new-arrival-bg">
									<img src="assets/images/collection/2.png" alt="new-arrivals images">
									<div class="single-new-arrival-bg-overlay"></div>
								</div>
								<div class="single-feature-txt text-center">
									<h4>Stray Kids ATE: Photocards</h4>
									</div>
								<p class="arrival-product-price">$150MXNc/u</p>
							</div>
						</div>
						<div class="col-md-3 col-sm-4">
							<div class="single-new-arrival">
								<div class="single-new-arrival-bg">
									<img src="assets/images/collection/3.png" alt="new-arrivals images">
									<div class="single-new-arrival-bg-overlay"></div>
								</div>
								<div class="single-feature-txt text-center">
									<h4>TXT Photocards</h4>
									</div>
								<p class="arrival-product-price">$150MXNc/u</p>
							</div>
						</div>
						<div class="col-md-3 col-sm-4">
							<div class="single-new-arrival">
								<div class="single-new-arrival-bg">
									<img src="assets/images/collection/4.png" alt="new-arrivals images">
									<div class="single-new-arrival-bg-overlay"></div>
								</div>
								<div class="single-feature-txt text-center">
								<h4>MINI SKZOO MAGIC SCHOOL</h4>
								</div>
								<p class="arrival-product-price">$750MXN</p>
							</div>
						</div>
						<div class="col-md-3 col-sm-4">
							<div class="single-new-arrival">
								<div class="single-new-arrival-bg">
									<img src="assets/images/collection/5.png" alt="new-arrivals images">
									<div class="single-new-arrival-bg-overlay"></div>
								</div>
								<div class="single-feature-txt text-center">
									<h4>TOMORROW X TOGETHER < Lighstick ver. 2 ></h4>
									</div>
								<p class="arrival-product-price">$1480MXN</p>
							</div>
						</div>
						<div class="col-md-3 col-sm-4">
							<div class="single-new-arrival">
								<div class="single-new-arrival-bg">
									<img src="assets/images/collection/6.png" alt="new-arrivals images">
									<div class="single-new-arrival-bg-overlay"></div>
								</div>
								<div class="single-feature-txt text-center">
									<h4>NACIFIC × ATEEZ</h4>
									</div>
								<p class="arrival-product-price">$550MXN c/u</p>
							</div>
						</div>
						<div class="col-md-3 col-sm-4">
							<div class="single-new-arrival">
								<div class="single-new-arrival-bg">
									<img src="assets/images/collection/7.png" alt="new-arrivals images">
									<div class="single-new-arrival-bg-overlay"></div>
								</div>
								<div class="single-feature-txt text-center">
									<h4>(G)I-DLE — Season's Greetings</h4>
									</div>
								<p class="arrival-product-price">$1230MXN</p>
							</div>
						</div>
						<div class="col-md-3 col-sm-4">
							<div class="single-new-arrival">
								<div class="single-new-arrival-bg">
									<img src="assets/images/collection/8.png" alt="new-arrivals images">
									<div class="single-new-arrival-bg-overlay"></div>
								</div>
								<div class="single-feature-txt text-center">
									<h4>樂-STAR — STRAY KIDS</h4>
									</div>
								<p class="arrival-product-price">$750MXN</p>
							</div>
						</div>
					</div>
				</div>
			</div><!--/.container-->
		
		</section><!--/.new-arrivals-->
		<!--new-arrivals end -->


		<!--blog start -->
		<section id="blog" class="blog">
			<div class="container">
				<div class="section-header">
					<h2>Noticias</h2>
				</div><!--/.section-header-->
				<div class="blog-content">
					<div class="row">
						<div class="col-sm-4">
							<div class="single-blog">
								<div class="single-blog-img">
									<img src="assets/images/blog/1.png" alt="blog image">
									<div class="single-blog-img-overlay"></div>
								</div>
								<div class="single-blog-txt">
									<h2><a href="https://www.instagram.com/p/DIwS7AVO9Jy/">¡Makeup Coreano!</a></h2>
									
									<h3>22/04/2025</h3>
									<p>
										Por fin tenemos re-stock de skin care y maquillaje directo de Corea del Sur.

Sombras hermosas con pigmento increíble, tintas de tonos fascinantes, ¡y más!
									</p>
								</div>
							</div>
							
						</div>
						<div class="col-sm-4">
							<div class="single-blog">
								<div class="single-blog-img">
									<img src="assets/images/blog/2.png" alt="blog image">
									<div class="single-blog-img-overlay"></div>
								</div>
								<div class="single-blog-txt">
									<h2><a href="https://www.instagram.com/p/DG1j7KNtF65/">¡Únete a nuestro equipo!</a></h2>
									<h3>05/03/25</h3>
									<p>
										Estamos buscando un empleadx general apasionado por el arte y el K-pop para unirse a nuestro equipo en Oh! store y nuestra tienda hermana nueshopjrz
									</p>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="single-blog">
								<div class="single-blog-img">
									<img src="assets/images/blog/3.png" alt="blog image">
									<div class="single-blog-img-overlay"></div>
								</div>
								<div class="single-blog-txt">
									<h2><a href="https://www.instagram.com/p/DJm4-BKSMCp/?img_index=1">Donde Luis Miguel y Chayanne se vuelven Amor</a></h2>
									<h3> 13/05/2025</h3>
									<p>
										Del 15 al 18 de mayo, llega una experiencia románticamente nostálgica:
“Tarde de Mamá” en el café, con música de fondo que derrite el corazón 
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!--/.container-->
			
		</section><!--/.blog-->
		<!--blog end -->

		<!--newsletter strat -->
		<section id="newsletter"  class="newsletter">
			<div class="container">
				<div class="hm-footer-details">
					<div class="row">
						<div class=" col-md-3 col-sm-6 col-xs-12">
							<div class="hm-footer-widget">
								<div class="hm-foot-title">
									<h4>Informacion</h4>
								</div><!--/.hm-foot-title-->
								<div class="hm-foot-menu">
									<ul>
										<li>Ubicado en: Plaza De las Américas </li>
										<li>Dirección: Plaza De las Américas, BENJAMIN FRANKLIN 3220-20E Margaritas 32300, Zona Pronaf Condominio La Plata, 32310 Juárez, Chih.</li><!--/li-->
									</ul><!--/ul-->
								</div><!--/.hm-foot-menu-->
							</div><!--/.hm-footer-widget-->
						</div><!--/.col-->
						<div class=" col-md-3 col-sm-6 col-xs-12">
							<div class="hm-footer-widget">
								<div class="hm-foot-title">
									<h4>Horarios</h4>
								</div><!--/.hm-foot-title-->
								<div class="hm-foot-menu">
									<ul>
										<p>Lunes-Domingo: 11:00am-7:00pm</p>
									</ul><!--/ul-->
								</div><!--/.hm-foot-menu-->
							</div><!--/.hm-footer-widget-->
						</div><!--/.col-->
						<div class=" col-md-3 col-sm-6  col-xs-12">
							<div class="hm-footer-widget">
								<div class="hm-foot-title">
									<h4>newsletter</h4>
								</div><!--/.hm-foot-title-->
								<div class="hm-foot-para">
									<p>
										Suscribete para recibir noticias de proximos eventos y productos.
									</p>
										</div><!--/.hm-foot-para-->
										<div class="hm-foot-email">
											<form id="subscribe-form" method="POST" action="suscripcion.php">
												<div class="foot-email-box">
													<input type="text" class="form-control" name="email" placeholder="Enter Email Here...." required>
												</div><!--/.foot-email-box-->
												<div class="foot-email-subscribe">
													<button type="submit"><i class="fa fa-location-arrow"></i></button>
												</div><!--/.foot-email-icon-->
											</form>
										</div><!--/.hm-foot-email-->
										<div id="response-message"></div> <!-- To display response messages -->
							</div><!--/.hm-footer-widget-->
						</div><!--/.col-->
							<div class=" col-md-3 col-sm-6 col-xs-12">
							<div class="hm-footer-widget">
								<div class="hm-foot-title">
									<h4>QR</h4>
								</div><!--/.hm-foot-title-->
								<div class="hm-foot-menu">
									<ul>
										<img src="assets/images/collection/qr.jpg" alt="">
									</ul><!--/ul-->
								</div><!--/.hm-foot-menu-->
							</div><!--/.hm-footer-widget-->
						</div><!--/.col-->
					</div><!--/.row-->
				</div><!--/.hm-footer-details-->

			</div><!--/.container-->

		</section><!--/newsletter-->	
		<!--newsletter end -->

		<!--footer start-->
		<footer id="footer"  class="footer">
			<div class="container">
				<div class="hm-footer-copyright text-center">
					<div class="footer-social">
						<a href="https://www.facebook.com/ohstorejrz/"><i class="fa fa-facebook"></i></a>	
						<a href="https://www.instagram.com/ohstxre/"><i class="fa fa-instagram"></i></a>
	
					</div>
					<p>
						&copy;copyright. designed and developed by <a href="">Nayeli R.R</a>
					</p><!--/p-->
				</div><!--/.text-center-->
			</div><!--/.container-->

			<div id="scroll-Top">
				<div class="return-to-top">
					<i class="fa fa-angle-up " id="scroll-top" data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to Top" aria-hidden="true"></i>
				</div>
				
			</div><!--/.scroll-Top-->
			
        </footer><!--/.footer-->
		<!--footer end-->
		
		<!-- Include all js compiled plugins (below), or include individual files as needed -->

		<script src="assets/js/jquery.js"></script>
        
        <!--modernizr.min.js-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
		
		<!--bootstrap.min.js-->
        <script src="assets/js/bootstrap.min.js"></script>
		
		<!-- bootsnav js -->
		<script src="assets/js/bootsnav.js"></script>

		<!--owl.carousel.js-->
        <script src="assets/js/owl.carousel.min.js"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
		
        <!--Custom JS-->
        <script src="assets/js/custom.js"></script>

		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    </body>
	
</html>