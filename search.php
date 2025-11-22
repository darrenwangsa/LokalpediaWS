<?php
$error = "";
$searchInput = "";
$category = 0; // default 0

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Ambil dan trim input
    $searchInput = isset($_POST['search']) ? trim($_POST['search']) : "";

    // Validasi search
    if ($searchInput === "") {
        $error = "Search tidak boleh kosong!";
    } else {
        // Pastikan category selalu angka 0-3
        $category = isset($_POST['category']) ? (int)$_POST['category'] : 0;

        // Optional: jika category diluar range
        if ($category < 0 || $category > 3) {
            $category = 0;
        }
    }
}

$keyword = strtolower($searchInput);
// Ambil hasil SPARQL
$endpoint = "http://localhost:3030/lokalpedia/sparql";
$query = "
PREFIX : <http://www.semanticweb.org/acer/ontologies/2025/10/untitled-ontology-19/>
SELECT DISTINCT ?team ?teamName ?hasName WHERE {
  ?team a ?mpl ;
        :teamName ?teamName ;
        :hasName ?hasName .
}
";

$response = file_get_contents($endpoint . "?query=" . urlencode($query) . "&format=json");
$data = json_decode($response, true);

// Hitung skor berurut
$results = [];
foreach ($data['results']['bindings'] as $row) {
    $teamName = strtolower($row['teamName']['value']);

    $pos = 0;  // posisi awal
    $score = 0;
    $lastPos = -1;

    // Hitung skor berdasarkan urutan huruf input
    foreach (str_split($keyword) as $char) {
        $pos = strpos($teamName, $char, $lastPos + 1);
        if ($pos === false) {
            $score += 1000; // huruf tidak ditemukan → penalti besar
        } else {
            $score += $pos; // semakin awal → lebih rendah score
            $lastPos = $pos;
        }
    }

    $results[] = [
        'team' => $row['team']['value'],
        'teamName' => $row['teamName']['value'],
        'hasName' => $row['hasName']['value'],
        'score' => $score
    ];
}

// Urutkan hasil berdasarkan skor
usort($results, function($a, $b) {
    return $a['score'] <=> $b['score'];
});

// Tampilkan hasil
foreach ($results as $r) {
    echo $r['teamName'] . " (" . $r['hasName'] . ") - score: " . $r['score'] . "\n";
}
?>



<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <title>Lokalpedia</title>
        <header>
            
        <!-- Bootstrap -->
		<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>
        
        <!-- Custom stlylesheet -->
        <link type="text/css" rel="stylesheet" href="css/style.css"/>

		<!-- Font Awesome Icon -->
		<link rel="stylesheet" href="css/font-awesome.min.css">

			<!-- MAIN HEADER -->
			<div id="header">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<!-- LOGO -->
						<div class="col-md-3">
							<div class="header-logo">
								<a href="index.html" class="logo">
									<img src="./img/logoLokalpedia.png" alt="">
								</a>
							</div>
						</div>
						<!-- /LOGO -->

						<!-- SEARCH BAR -->
						<div class="col-md-6">
							<div class="header-search">
								<form action="search.php" method="post">
									<select class="input-select" name="category">
										<option value="0">All Categories</option>
										<option value="1">Teams</option>
										<option value="2">Competition</option>
										<option value="3">Players</option>
									</select>
									<input class="input" name="search" placeholder="Search here">
									<button class="search-btn" type="submit">Search</button>
								</form>
							</div>
						</div>
						<!-- /SEARCH BAR -->
					</div>
					<!-- row -->
				</div>
				<!-- container -->
			</div>
			<!-- /MAIN HEADER -->
		</header>
		<!-- /HEADER -->
    </head>

    <body>
        <?php if($error != ""): ?>
            <br>
            <div class="alert alert-danger text-center">
                <?php echo $error;?>
            </div>
            <div class="text-center">
                <button onclick="history.back()" class="back-btn">Kembali</button>
            </div>

        <?php else: ?>
        <?php endif; ?>
    </body>
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
    <!-- /FOOTER -->

    <!-- jQuery Plugins -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/nouislider.min.js"></script>
    <script src="js/jquery.zoom.min.js"></script>
    <script src="js/main.js"></script>

	
</html>
