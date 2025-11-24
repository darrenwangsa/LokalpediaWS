<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Evos Esport - Team Profile</title>

    <!-- Styles from teams page -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../../css/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="../../css/slick.css" />
    <link type="text/css" rel="stylesheet" href="../../css/slick-theme.css" />
    <link type="text/css" rel="stylesheet" href="../../css/nouislider.min.css" />
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="../../css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', "Arial", sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 30%, rgba(200, 150, 160, 0.1), transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(200, 150, 160, 0.1), transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* Header Override */
        #header {
            background: #1c1c1c;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

        .container {
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        /* Team Profile Section */
        .team-profile {
            background: white;
            border-radius: 20px;
            padding: 60px;
            margin-bottom: 60px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .team-profile::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background-image: url('playerimg/evos-bg.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center right;
            opacity: 0.05;
            pointer-events: none;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 350px 1fr 300px;
            gap: 60px;
            align-items: start;
        }

        /* Logo Section */
        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .team-logo {
            width: 300px;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .team-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            width: 45px;
            height: 45px;
            border: 2px solid #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-links a:hover {
            border-color: #75c5f0;
            color: #75c5f0;
            transform: translateY(-3px);
        }

        /* Info Section */
        .info-section {
            padding: 20px 0;
        }

        .team-name-header {
            margin-bottom: 40px;
        }

        .team-abbr {
            font-size: 48px;
            font-weight: bold;
            color: #000;
            letter-spacing: 3px;
            margin-bottom: 5px;
        }

        .team-abbr .o-letter {
            color: #75c5f0;
        }

        .team-full-name {
            font-size: 24px;
            color: #333;
            font-weight: 500;
        }

        .description-block h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .description-block p {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
        }

        /* Achievements Section */
        .achievements-section {
            border: 3px solid #333;
            border-radius: 12px;
            padding: 30px;
        }

        .achievements-section h3 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
            letter-spacing: 2px;
            font-weight: bold;
        }

        .achievement-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .achievement-item:last-child {
            border-bottom: none;
        }

        .achievement-name {
            font-weight: bold;
            color: #333;
            font-size: 16px;
        }

        .achievement-result {
            color: #666;
            font-size: 15px;
        }

        /* Roster Section */
        .section-title {
            font-size: 42px;
            font-weight: 300;
            letter-spacing: 6px;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 60px;
            text-transform: uppercase;
        }

        .roster-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Player Card */
        .player-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            cursor: pointer;
        }

        .player-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
        }

        .player-image {
            width: 100%;
            height: 450px;
            background:
                linear-gradient(to bottom,
                    transparent 0%,
                    rgba(18, 39, 60, 0.1) 20%,
                    rgba(117, 197, 240, 0.3) 50%,
                    rgba(255, 255, 255, 0.7) 80%,
                    #12273c 100%),
                repeating-linear-gradient(45deg,
                    #12273c 0,
                    #75c5f0 10px,
                    #ffffff 10px,
                    #ffffff 20px);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .player-image::after {
            content: "";
            position: absolute;
            inset: 0;
            top: 10px;
            background-image: var(--img);
            background-size: cover;
            background-position: center;
            z-index: 1;
            transition: transform 0.5s ease;
        }

        .player-card:hover .player-image::after {
            transform: scale(1.1);
        }

        .player-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 5;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 25px;
            text-align: left;
            border-top: 3px solid #75c5f0;
            transform: translateY(100%);
            transition: transform 0.4s ease;
            max-height: 100%;
            overflow-y: auto;
        }

        .player-card:hover .player-info {
            transform: translateY(0);
        }

        .player-name {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
            letter-spacing: 2px;
        }

        .player-role {
            font-size: 14px;
            color: #75c5f0;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .player-details {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 15px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: 13px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .detail-value {
            font-size: 14px;
            color: #2c3e50;
            font-weight: 500;
        }

        /* Footer Override */
        #footer {
            position: relative;
            z-index: 10;
            margin-top: auto;
        }

        /* Mobile - show info by default */
        @media (max-width: 1024px) {
            .profile-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .team-logo {
                width: 200px;
                height: 200px;
            }

            .achievements-section {
                max-width: 500px;
                margin: 0 auto;
            }

            .player-info {
                transform: translateY(0);
                position: relative;
            }

            .player-image {
                height: 350px;
            }
        }

        @media (max-width: 768px) {
            .team-profile {
                padding: 30px;
            }

            .roster-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 30px;
            }

            .section-title {
                font-size: 32px;
            }

            .team-abbr {
                font-size: 36px;
            }
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <?php include 'header.php' ?>

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
        <div class="container">

            <!-- Team Profile Section -->
            <div class="team-profile">
                <div class="profile-grid">

                    <!-- Logo Section -->
                    <div class="logo-section">
                        <div class="team-logo">
                            <img src="playerimg/evos.png" alt="EVOS Logo">
                        </div>

                        <div class="social-links">
                            <a href="https://www.facebook.com/teamEVOS/?locale=id_ID" aria-label="Facebook"><i
                                    class="fab fa-facebook-f"></i></a>
                            <a href="https://www.instagram.com/evosesports/?hl=id" aria-label="Instagram"><i
                                    class="fab fa-instagram"></i></a>
                            <a href="https://www.youtube.com/@EVOSEsports" aria-label="YouTube"><i
                                    class="fab fa-youtube"></i></a>
                            <a href="https://x.com/evosesports_" aria-label="X"><i
                                    class="fa-brands fa-x-twitter"></i></a>
                            <a href="https://www.tiktok.com/@evosesports?lang=id-ID" aria-label="TikTok"><i
                                    class="fab fa-tiktok"></i></a>
                        </div>
                    </div>

                    <!-- Info Section -->
                    <div class="info-section">
                        <div class="team-name-header">
                            <div class="team-abbr">EVOS</div>
                            <div class="team-full-name">Evos Esport</div>
                        </div>

                        <div class="description-block">
                            <h2>Description</h2>
                            <p>
                                EVOS Esports is one of Indonesia's most legendary Mobile Legends teams, with a rich
                                history
                                of dominance in the MPL ID scene. As the M1 World Champions and multiple-time MPL ID
                                champions, EVOS has consistently been at the forefront of competitive MLBB, known for
                                their
                                aggressive playstyle and strategic innovation.
                            </p>
                        </div>
                    </div>

                    <!-- Achievements Section -->
                    <div class="achievements-section">
                        <h3>ACHIEVEMENTS</h3>
                        <div class="achievement-item">
                            <span class="achievement-name">M1</span>
                            <span class="achievement-result">1st Place</span>
                        </div>
                        <div class="achievement-item">
                            <span class="achievement-name">MPL-ID S4</span>
                            <span class="achievement-result">1st Place</span>
                        </div>
                        <div class="achievement-item">
                            <span class="achievement-name">MPL-ID S7</span>
                            <span class="achievement-result">1st Place</span>
                        </div>
                        <div class="achievement-item">
                            <span class="achievement-name">MPL-ID S11</span>
                            <span class="achievement-result">2nd Place</span>
                        </div>
                        <div class="achievement-item">
                            <span class="achievement-name">MPL-ID S13</span>
                            <span class="achievement-result">2nd Place</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Roster Section -->
            <h2 class="section-title">Roster EVOS Season 16</h2>

            <div class="roster-grid">

                <div class="player-card">
                    <div class="player-image" style="--img: url('playerimg/Vyn.png')"></div>
                    <div class="player-info">
                        <div class="player-name">VYN</div>
                        <div class="player-role">HEAD COACH</div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Real Name</span>
                                <span class="detail-value">Calvin</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Region</span>
                                <span class="detail-value">🇮🇩 Indonesia</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Age</span>
                                <span class="detail-value">25</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Birthday</span>
                                <span class="detail-value">Sep 3, 2000</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Join Date</span>
                                <span class="detail-value">2025</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="player-card">
                    <div class="player-image" style="--img: url('playerimg/Bravo.png')"></div>
                    <div class="player-info">
                        <div class="player-name">BRAVO</div>
                        <div class="player-role">ANALYST</div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Real Name</span>
                                <span class="detail-value">Akbar Tubagus Wira Nirda</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Region</span>
                                <span class="detail-value">🇮🇩 Indonesia</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Join Date</span>
                                <span class="detail-value">2025</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="player-card">
                    <div class="player-image" style="--img: url('playerimg/Kyy.png')"></div>
                    <div class="player-info">
                        <div class="player-name">KYY</div>
                        <div class="player-role">ROAMER</div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Real Name</span>
                                <span class="detail-value">Hengky Gunawan</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Alternate Ids</span>
                                <span class="detail-value">Super Kyy</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Region</span>
                                <span class="detail-value">🇮🇩 Indonesia</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Age</span>
                                <span class="detail-value">21</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Birthday</span>
                                <span class="detail-value">Nov 9, 2003</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Join Date</span>
                                <span class="detail-value">2025</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="player-card">
                    <div class="player-image" style="--img: url('playerimg/Alberttt.png')"></div>
                    <div class="player-info">
                        <div class="player-name">ALBERTTT</div>
                        <div class="player-role">JUNGLER</div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Real Name</span>
                                <span class="detail-value">Albert Neilsen Iskandar</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Region</span>
                                <span class="detail-value">🇮🇩 Indonesia</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Age</span>
                                <span class="detail-value">21</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Birthday</span>
                                <span class="detail-value">Jan 8, 2004</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Join Date</span>
                                <span class="detail-value">2024</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="player-card">
                    <div class="player-image" style="--img: url('playerimg/ERLAN.png')"></div>
                    <div class="player-info">
                        <div class="player-name">ERLAN</div>
                        <div class="player-role">GOLD LANER</div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Real Name</span>
                                <span class="detail-value">Erland Saputra</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Alternate Ids</span>
                                <span class="detail-value">Douma</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Region</span>
                                <span class="detail-value">🇮🇩 Indonesia</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Join Date</span>
                                <span class="detail-value">2025</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="player-card">
                    <div class="player-image" style="--img: url('playerimg/SWAYLOW.png')"></div>
                    <div class="player-info">
                        <div class="player-name">SWAYLOW</div>
                        <div class="player-role">MID LANER</div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Real Name</span>
                                <span class="detail-value">Attanasius David H. Sihaloho</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Region</span>
                                <span class="detail-value">🇮🇩 Indonesia</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Join Date</span>
                                <span class="detail-value">2025</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="player-card">
                    <div class="player-image" style="--img: url('playerimg/Branz.png')"></div>
                    <div class="player-info">
                        <div class="player-name">BRANZ</div>
                        <div class="player-role">GOLD LANER</div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Real Name</span>
                                <span class="detail-value">Jabran Bagus Wiloko</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Region</span>
                                <span class="detail-value">🇮🇩 Indonesia</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Age</span>
                                <span class="detail-value">25</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Join Date</span>
                                <span class="detail-value">2025</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="player-card">
                    <div class="player-image" style="--img: url('playerimg/Roundel.png')"></div>
                    <div class="player-info">
                        <div class="player-name">ROUNDEL</div>
                        <div class="player-role">MID LANER</div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Real Name</span>
                                <span class="detail-value">Faiskal Khadafi</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Region</span>
                                <span class="detail-value">🇮🇩 Indonesia</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Age</span>
                                <span class="detail-value">21</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Join Date</span>
                                <span class="detail-value">2025</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="player-card">
                    <div class="player-image" style="--img: url('playerimg/Xorizo.png')"></div>
                    <div class="player-info">
                        <div class="player-name">XORIZO</div>
                        <div class="player-role">EXP LANER</div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Real Name</span>
                                <span class="detail-value">I Gusti Made Indra Dwipayana</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Region</span>
                                <span class="detail-value">🇮🇩 Indonesia</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Age</span>
                                <span class="detail-value">22</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Join Date</span>
                                <span class="detail-value">2025</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="player-card">
                    <div class="player-image" style="--img: url('playerimg/Rendyy_mid.png')"></div>
                    <div class="player-info">
                        <div class="player-name">RENDYY</div>
                        <div class="player-role">EXP LANER</div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Real Name</span>
                                <span class="detail-value">Rendyy</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Region</span>
                                <span class="detail-value">🇮🇩 Indonesia</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Join Date</span>
                                <span class="detail-value">2025</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<!-- FOOTER -->
<footer id="footer">
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-xs-6">
                    <div class="footer">
                        <h3 class="footer-title">About Us</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt
                            ut.</p>
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
                            <li><a href="teams.html">Teams</a></li>
                            <li><a href="#">Competition</a></li>
                            <li><a href="#">Players</a></li>
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
                            <li><a href="#">Terms & Conditions</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 col-xs-6">
                    <div class="footer">
                        <h3 class="footer-title">Service</h3>
                        <ul class="footer-links">
                            <li><a href="#">My Account</a></li>
                            <li><a href="#">Help</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="bottom-footer" class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <span class="copyright">
                        Copyright &copy;
                        <script>document.write(new Date().getFullYear());</script> All rights reserved
                    </span>
                </div>
            </div>
        </div>
    </div>
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