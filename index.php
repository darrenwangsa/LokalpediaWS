<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<title>Lokalpedia</title>

		<!-- Google font -->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

		<!-- Bootstrap -->
		<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>

		<!-- Slick -->
		<link type="text/css" rel="stylesheet" href="css/slick.css"/>
		<link type="text/css" rel="stylesheet" href="css/slick-theme.css"/>

		<!-- nouislider -->
		<link type="text/css" rel="stylesheet" href="css/nouislider.min.css"/>

		<!-- Font Awesome Icon -->
		<link rel="stylesheet" href="css/font-awesome.min.css">

		<!-- Custom stlylesheet -->
		<link type="text/css" rel="stylesheet" href="css/style.css"/>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

    </head>
	<body>
		<!-- HEADER -->
		<?php include 'header.php'; ?>
		<!-- /HEADER -->

		<!-- SECTION -->
		<div class="section">
			<div class="container py-5">
				<div class="row justify-content-center text-center">
				  
				  <!-- LOGO -->
				  <div class="col-12">
					<img class="logo mb-4" src="./img/logoLokalpedia.png" width="450">
				  </div>
				  
				  <!-- HERO TEXT -->
				  <div class="col-12 hero-text">
					<h1 class="fw-bold text-body-emphasis" style="color: #F3AA36;">Lokalpedia</h1>
					<!-- <div class="col-12 mx-auto text-center" style="width: 50%;"> -->
					  <p class="lead">Semantic-based Website yang menampilkan tim dan kompetisi MLBB yang ada di tahun 2025.<br>Made by Kelompok 4</p>
					<!-- </div> -->
				  </div>
				  
				</div>
			</div>
			  
			  
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<!-- shop -->
					<div class="col-md-4 col-xs-6">
						<div class="shop">
							<!-- Gambar jadi clickable -->
							<a href="teams.php">
								<div class="shop-img">
									<img src="./img/logomlbb.png" alt="">
								</div>
							</a>
					
							<div class="shop-body">
								<h3>MLBB<br>Teams</h3>
					
								<!-- Tombol juga menuju halaman -->
								<a href="teams.php" class="cta-btn">Teams page
									<i class="fa fa-arrow-circle-right"></i>
								</a>
							</div>
						</div>
					</div>

					<!-- /shop -->

					<!-- shop -->
					<div class="col-md-4 col-xs-6">
						<div class="shop">
							<div class="shop-img">
								<img src="./img/competition.png" alt="">
							</div>
							<div class="shop-body">
								<h3>MLBB<br>Competition</h3>
								<a href="competitions.php" class="cta-btn">Competition page <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
					<!-- /shop -->

					<!-- shop -->
					<div class="col-md-4 col-xs-6">
						<div class="shop">
							<div class="shop-img">
								<img src="./img/players.jpeg" alt="">
							</div>
							<div class="shop-body">
								<h3>MLBB<br>Players</h3>
								<a href="players.php" class="cta-btn">Players page <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
					<!-- /shop -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->


		<!-- FOOTER -->
		<?php include 'footer.php'; ?>
		<!-- /FOOTER -->

		<!-- jQuery Plugins -->
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/slick.min.js"></script>
		<script src="js/nouislider.min.js"></script>
		<script src="js/jquery.zoom.min.js"></script>
		<script src="js/main.js"></script>

	</body>
</html>
