<!DOCTYPE html>
<e lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>TIM - Esports Teams</title>

	<!-- Styles dari file pertama -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" href="css/slick.css" />
	<link type="text/css" rel="stylesheet" href="css/slick-theme.css" />
	<link type="text/css" rel="stylesheet" href="css/nouislider.min.css" />
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link type="text/css" rel="stylesheet" href="css/style.css" />

	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: 'Montserrat', 'Arial Black', Arial, sans-serif;
			background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
			min-height: 100vh;
			display: flex;
			flex-direction: column;
			position: relative;
			overflow-x: hidden;
		}

		body::before {
			content: '';
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-image:
				radial-gradient(circle at 20% 30%, rgba(255, 200, 200, 0.06) 0%, transparent 50%),
				radial-gradient(circle at 80% 70%, rgba(200, 180, 200, 0.04) 0%, transparent 50%);
			pointer-events: none;
			z-index: 0;
		}

		/* Header Override */
		#header {
			background: #1c1c1c;
			box-shadow: 0 2px 5px rgba(0,0,0,0.1);
			position: relative;
			z-index: 10;
		}

		#navigation {
			background: #1e1f29;
			position: relative;
			z-index: 10;
		}

		/* Main Content Area */
		.main-content {
			flex: 1;
			width: 100%;
			padding: 40px 20px;
			position: relative;
			z-index: 1;
		}

		.all-leagues {
			width: 100%;
			max-width: 1400px;
			margin: 0 auto;
		}

		.league-section {
			margin-bottom: 40px;
			padding: 20px;
			background: rgba(255, 255, 255, 0.6);
			border-radius: 12px;
			box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
		}

		.league-header {
			display: flex;
			align-items: center;
			gap: 20px;
			margin-bottom: 18px;
		}

		.league-title {
			font-size: 36px;
			font-weight: 900;
			color: #2c1810;
			letter-spacing: -2px;
		}

		.dark-logo img {
			filter: drop-shadow(0px 0px 6px rgba(0, 0, 0, 0.75));
		}

		.league-logo {
			width: 110px;
			height: auto;
			position: relative;
			top: -6px;
		}

		.teams-container {
			display: flex;
			gap: 20px;
			flex-wrap: wrap;
			justify-content: center;
		}

		.team-card {
			width: 110px;
			background: white;
			border-radius: 8px 8px 0 0;
			box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
			transition: transform 0.3s ease, box-shadow 0.3s ease;
			overflow: visible;
			position: relative;
		}

		.team-card:hover {
			transform: translateY(-8px);
			box-shadow: 0 12px 28px rgba(0, 0, 0, 0.20);
		}

		.team-header {
			background: #F3AA36;
			color: white;
			padding: 12px;
			text-align: center;
			font-size: 16px;
			font-weight: 900;
			letter-spacing: 2px;
			position: relative;
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.team-flag {
			position: absolute;
			top: -10px;
			right: -10px;
			width: 18px;
			height: 18px;
			border-radius: 12px;
			background-size: cover;
			background-position: center;
			border: 2px solid white;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
			z-index: 10;
		}

		.team-logo {
			height: 180px;
			padding: 20px;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 30px;
			clip-path: polygon(0 0, 100% 0, 100% 85%, 50% 100%, 0 85%);
		}

		.team-logo img {
			max-width: 100%;
			max-height: 100%;
			object-fit: contain;
		}

		/* Footer Override */
		#footer {
			position: relative;
			z-index: 10;
			margin-top: auto;
		}

		@media (max-width: 1024px) {
			.league-title {
				font-size: 30px;
			}

			.league-logo {
				width: 90px;
			}
		}

		@media (max-width: 768px) {
			.league-header {
				flex-direction: column;
				align-items: flex-start;
				gap: 8px;
			}

			.league-title {
				font-size: 26px;
			}

			.league-logo {
				width: 70px;
				top: 0;
			}

			.team-card {
				width: 120px;
			}

			.team-logo {
				height: 200px;
				padding: 20px;
			}
		}
	</style>
</head>

<body>
	<!-- HEADER -->
	<?php include 'header.php'; ?>

	<!-- NAVIGATION -->
	<nav id="navigation">
		<div class="container">
			<div id="responsive-nav">
				<!-- Navigation content -->
			</div>
		</div>
	</nav>

	<!-- MAIN CONTENT -->
	<div class="main-content">
		<div class="all-leagues">
		
			<!-- M7 Section -->
			<section class="league-section" id="M7">
				<div class="league-header">
					<img src="./img/m7.png" alt="M7" class="league-logo">
					<div class="league-title">M World Series 7</div>
				</div>
		
				<div class="teams-container">
					<div class="team-card">
						<div class="team-header">AE
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/id.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-ae.png" alt="AE"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">ONIC
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/id.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-onic.png" alt="ONIC"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">RORA
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/ph.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-rora.png" alt="AURORA"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">TLPH
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/ph.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-tlph.png" alt="TEAM LIQUID PH"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">CG
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/my.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-cg.png" alt="CG Esport"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">SRG
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/my.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-srg.png" alt="Selangor Red Giants OG"></div>
					</div>
				</div>
			</section>
		
			<!-- MPL ID S16 Section -->
			<section class="league-section" id="mpl-id-s16">
				<div class="league-header">
					<img src="./img/mpl_id.png" alt="MPL ID" class="league-logo">
					<div class="league-title">MPL ID S16</div>
				</div>
		
				<div class="teams-container">
					<div class="team-card">
						<div class="team-header">AE
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/id.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-ae.png" alt="AE"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">BTR
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/id.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-btr.png" alt="BTR"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">DEWA
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/id.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-dewa.png" alt="DEWA"></div>
					</div>
		
                    <a href="detail_team/evos/index.php" class="team-link">
                        <div class="team-card">
                            <div class="team-header">EVOS
                                <div class="team-flag" style="background-image: url('https://flagcdn.com/w40/id.png');"></div>
                            </div>
                            <div class="team-logo"><img src="./img/logo-evos.png" alt="EVOS"></div>
                        </div>
                    </a>
		
					<div class="team-card">
						<div class="team-header">GEEK
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/id.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-geek.png" alt="GEEK"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">NAVI
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/id.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-navi.png" alt="NAVI"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">ONIC
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/id.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-onic.png" alt="ONIC"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">RRQ
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/id.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-rrq.png" alt="RRQ"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">TLID
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/id.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-liquid.png" alt="TLID"></div>
					</div>
				</div>
			</section>
		
			<!-- MPL PH S16 Section -->
			<section class="league-section" id="mpl-ph-s16">
				<div class="league-header">
					<img src="./img/mpl-ph.png" alt="MPL PH" class="league-logo">
					<div class="league-title">MPL PH S16</div>
				</div>
		
				<div class="teams-container">
					<div class="team-card">
						<div class="team-header">APBR
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/ph.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-apbren.png" alt="APBren"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">RORA
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/ph.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-rora.png" alt="AURORA"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">ONIC
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/ph.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-onicph.png" alt="ONICPH"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">OMG
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/ph.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-omega.png" alt="OMEGA"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">FLCN
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/ph.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-falcon.png" alt="Falcons"></div>
					</div>

					<div class="team-card">
						<div class="team-header">TLPH
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/ph.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-tlph.png" alt="TEAM LIQUID PH"></div>
					</div>

					<div class="team-card">
						<div class="team-header">TNC
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/ph.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-tnc.png" alt="TNC Pro"></div>
					</div>

					<div class="team-card">
						<div class="team-header">TWIS
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/ph.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-twisted.png" alt="Twisted"></div>
					</div>
				</div>
			</section>
		
			<!-- MPL MY S15 Section -->
			<section class="league-section" id="mpl-my-s15">
				<div class="league-header">
					<img src="./img/mpl-my.png" alt="MPL MY" class="league-logo">
					<div class="league-title">MPL MY S15</div>
				</div>
		
				<div class="teams-container">
					<div class="team-card">
						<div class="team-header">AERO
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/my.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-aero.png" alt="Aero Esport"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">CG
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/my.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-cg.png" alt="CG Esport"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">GMXK
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/my.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-gamesmy.png" alt="GamesmyKelantan"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">HB
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/my.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-homebois.png" alt="Homebois"></div>
					</div>
		
					<div class="team-card">
						<div class="team-header">MV
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/my.png');"></div>
						</div>
						<div class="team-logo dark-logo">
							<img src="./img/logo-mv.png" alt="Movicius">
						</div>
					</div>

					<div class="team-card">
						<div class="team-header">TR
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/my.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-teamrey.png" alt="TEAM Rey"></div>
					</div>

					<div class="team-card">
						<div class="team-header">SRG
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/my.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-srg.png" alt="Selangor Red Giants OG"></div>
					</div>

					<div class="team-card">
						<div class="team-header">TDK
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/my.png');"></div>
						</div>
						<div class="team-logo dark-logo">
							<img src="./img/logo-todak.png" alt="TODAK">
						</div>
					</div>

					<div class="team-card">
						<div class="team-header">VMS
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/my.png');"></div>
						</div>
						<div class="team-logo"><img src="./img/logo-vamos.png" alt="Team vamos"></div>
					</div>

					<div class="team-card">
						<div class="team-header">UNT
							<div class="team-flag" style="background-image: url('https://flagcdn.com/w40/my.png');"></div>
						</div>
						<div class="team-logo dark-logo">
							<img src="./img/logo-untitled.png" alt="Untitled">
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>

	<!-- FOOTER -->
	<footer id="footer">
		<!-- top footer -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-3 col-xs-6">
						<div class="footer">
							<h3 class="footer-title">About Us</h3>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut.</p>
							<ul class="footer-links">
								<li><a href="#"><i class="fa fa-map-marker"></i>1734 Stonecoal Road</a></li>
								<li><a href="#"><i class="fa fa-phone"></i>+021-95-51-84</a></li>
								<li><a href="#"><i class="fa fa-envelope-o"></i>email@email.com</a></li>
							</ul>
						</div>
					</div>

					<div class="col-md-3 col-xs-6">
						<div class="footer">
							<h3 class="footer-title">Categories</h3>
							<ul class="footer-links">
								<li><a href="#">Hot deals</a></li>
								<li><a href="#">Laptops</a></li>
								<li><a href="#">Smartphones</a></li>
								<li><a href="#">Cameras</a></li>
								<li><a href="#">Accessories</a></li>
							</ul>
						</div>
					</div>

					<div class="clearfix visible-xs"></div>

					<div class="col-md-3 col-xs-6">
						<div class="footer">
							<h3 class="footer-title">Information</h3>
							<ul class="footer-links">
								<li><a href="#">About Us</a></li>
								<li><a href="#">Contact Us</a></li>
								<li><a href="#">Privacy Policy</a></li>
								<li><a href="#">Orders and Returns</a></li>
								<li><a href="#">Terms & Conditions</a></li>
							</ul>
						</div>
					</div>

					<div class="col-md-3 col-xs-6">
						<div class="footer">
							<h3 class="footer-title">Service</h3>
							<ul class="footer-links">
								<li><a href="#">My Account</a></li>
								<li><a href="#">View Cart</a></li>
								<li><a href="#">Wishlist</a></li>
								<li><a href="#">Track My Order</a></li>
								<li><a href="#">Help</a></li>
							</ul>
						</div>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /top footer -->

		<!-- bottom footer -->
		<div id="bottom-footer" class="section">
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12 text-center">
						<ul class="footer-payments">
							<li><img style="width: 20%;" src="./img/logoLokalpedia.png"></img></li>
						</ul>
						<span class="copyright">
							<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
							Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved </a>
						<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
						</span>
					</div>
				</div>
					<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /bottom footer -->
	</footer>

	<!-- jQuery Plugins -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/slick.min.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/jquery.zoom.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>