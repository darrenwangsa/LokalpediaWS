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
// connect JENA
$endpoint = "http://localhost:3030/lokalpedia22/sparql";

// Query for ALL CATEGORIES
$sparqlQuery0 = <<<SPARQL
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://www.semanticweb.org/acer/ontologies/2025/10/untitled-ontology-19/>

SELECT DISTINCT 
(REPLACE(STR(?team), "^.*/", "") AS ?teams)
?teamName 
?teamAddName
?linkFoto
(REPLACE(STR(?player), "^.*/", "") AS ?playerNickname)
?realName
(REPLACE(STR(?competitions), "^.*/", "") AS ?competitionName) WHERE {{
  ?team a ?competition ;
        :teamName ?teamName ;
        :hasName ?teamAddName .
    OPTIONAL {
    ?team :hasFoto ?linkFoto .
    }
  }
  UNION
  {
    {
    ?player :playerOf ?p .
    }UNION
    {
    ?player :staffOf ?p .
  	}
    OPTIONAL{
    ?player :hasRealName ?realName .
    }
  	OPTIONAL {
  	?player :hasFoto ?linkFoto .
    } 
  }
  UNION
  {
    {
      ?headTour rdfs:subClassOf :InternationalTour .
      ?competitions rdfs:subClassOf ?headTour .
    
      
      } UNION
    {
      ?regionTour rdfs:subClassOf :RegionalTour .
      ?headTour rdfs:subClassOf ?regionTour .
      ?competitions rdfs:subClassOf ?headTour .
    } FILTER NOT EXISTS {
        ?child rdfs:subClassOf ?competitions .
        FILTER(?child != ?competitions)
  		}
}
}
SPARQL;

// Query for TEAMS
$sparqlQuery1 = <<<SPARQL
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://www.semanticweb.org/acer/ontologies/2025/10/untitled-ontology-19/>

SELECT DISTINCT 
(REPLACE(STR(?team), "^.*/", "") AS ?teams)
?teamName 
?teamAddName
?linkFoto
WHERE {{
  ?team a ?competition ;
        :teamName ?teamName ;
        :hasName ?teamAddName .
    OPTIONAL {
    ?team :hasFoto ?linkFoto .
    }
  }
}
SPARQL;

// Query for PLAYERS
$sparqlQuery2 = <<<SPARQL
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://www.semanticweb.org/acer/ontologies/2025/10/untitled-ontology-19/>

SELECT DISTINCT 
?linkFoto
(REPLACE(STR(?player), "^.*/", "") AS ?playerNickname)
?realName
WHERE {{
    ?player :playerOf ?p .
    }UNION
    {
    ?player :staffOf ?p .
  	}
    OPTIONAL{
    ?player :hasRealName ?realName .
    }
  	OPTIONAL {
  	?player :hasFoto ?linkFoto .
  	}
  
}
SPARQL;

//  Query utk COMPETITIONS
$sparqlQuery3 = <<<SPARQL
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://www.semanticweb.org/acer/ontologies/2025/10/untitled-ontology-19/>

SELECT DISTINCT 
(REPLACE(STR(?competitions), "^.*/", "") AS ?competitionName) WHERE {
    {
      ?headTour rdfs:subClassOf :InternationalTour .
      ?competitions rdfs:subClassOf ?headTour .
    
     
      } UNION
    {
      ?regionTour rdfs:subClassOf :RegionalTour .
      ?headTour rdfs:subClassOf ?regionTour .
      ?competitions rdfs:subClassOf ?headTour .
    } FILTER NOT EXISTS {
        ?child rdfs:subClassOf ?competitions .
        FILTER(?child != ?competitions)
}
}
SPARQL;


if ($category == 0){
    $response = file_get_contents($endpoint . "?query=" . urlencode($sparqlQuery0) . "&format=json");
} elseif ($category == 1){
    $response = file_get_contents($endpoint . "?query=" . urlencode($sparqlQuery1) . "&format=json");
} elseif ($category == 2){
    $response = file_get_contents($endpoint . "?query=" . urlencode($sparqlQuery2) . "&format=json");
} elseif ($category == 3){
    $response = file_get_contents($endpoint . "?query=" . urlencode($sparqlQuery3) . "&format=json");
} else{
    $response = file_get_contents($endpoint . "?query=" . urlencode($sparqlQuery0) . "&format=json");
}
$data = json_decode($response, true);

// Hitung skor berurut
$results = [];
foreach ($data['results']['bindings'] as $row) {
    // Ambil semua field
    $teamName = strtolower($row['teamName']['value'] ?? '');
    $teamAddName = strtolower($row['teamAddName']['value'] ?? '');
    $playerNickname = strtolower($row['playerNickname']['value'] ?? '');
    $realName = strtolower($row['realName']['value'] ?? '');
    $competitionName = strtolower($row['competitionName']['value'] ?? '');
    $linkFoto = strtolower($row['linkFoto']['value'] ?? '');

    // Fungsi hitung skor berdasarkan posisi huruf (case-insensitive)
    $calculateScore = function($fieldValue) use ($keyword) {
        if ($fieldValue === '') return -1; // tandai field kosong
        $score = 0;
        $lastPos = -1;
        foreach (str_split($keyword) as $char) {
            $pos = stripos($fieldValue, $char, $lastPos + 1);
            if ($pos === false) {
                $score += 1000; // huruf tidak ditemukan → penalti besar
            } else {
                $score += $pos; // huruf ditemukan → skor lebih rendah jika muncul lebih awal
                $lastPos = $pos;
            }
        }
        return $score;
    };

    // Hitung skor tiap field
    $scoreTeamName = $calculateScore($teamName);
    $scoreteamAddName = $calculateScore($teamAddName);
    $scorePlayer = $calculateScore($playerNickname);
    $scoreRealName = $calculateScore($realName);
    $scoreCompetition = $calculateScore($competitionName);

    // Skor maksimum yang akan digunakan untuk urutan
    $scores = [$scoreTeamName, $scoreteamAddName, $scorePlayer, $scoreRealName, $scoreCompetition];
    $minScore = min(array_filter($scores, fn($s) => $s >= 0)); // abaikan field kosong (-1)

    $results[] = [
        'teamName' => $row['teamName']['value'] ?? '',
        'teamAddName' => $row['teamAddName']['value'] ?? '',
        'playerNickname' => $row['playerNickname']['value'] ?? '',
        'realName' => $row['realName']['value'] ?? '',
        'competitionName' => $row['competitionName']['value'] ?? '',
        'linkFoto' => $row['linkFoto']['value'] ?? '',
        'scoreTeamName' => $scoreTeamName,
        'scoreteamAddName' => $scoreteamAddName,
        'scorePlayer' => $scorePlayer,
        'scoreRealName' => $scoreRealName,
        'scoreCompetition' => $scoreCompetition,
        'minScore' => $minScore
    ];
}

// Urutkan ascending → skor paling rendah muncul di atas
usort($results, function($a, $b) {
    return $a['minScore'] <=> $b['minScore'];
});

foreach ($results as $r) {
    

// if($count >= $limit){
//     break;
// }
    echo "<div style='margin-bottom:10px; padding:5px; border:1px solid #000;'>";

    if (!empty($r['teamName'])) {
        echo "<strong>Team:</strong> " . htmlspecialchars($r['teamName']);
        echo " <em>(score: " . $r['scoreTeamName'] . ")</em>";
        if (!empty($r['teamAddName'])) {
            echo " (" . htmlspecialchars($r['teamAddName']) . " <em>score: " . $r['scoreteamAddName'] . "</em>)";
        }
        echo "<br>";
    }

    if (!empty($r['playerNickname'])) {
        echo "<strong>Player:</strong> " . htmlspecialchars($r['playerNickname']);
        if (!empty($r['realName'])) {
            echo " (" . htmlspecialchars($r['realName']) . ")";
        }
        echo " <em>score: " . $r['scorePlayer'] . "</em>";
        echo "<br>";
    }

    if (!empty($r['competitionName'])) {
        echo "<strong>Competition:</strong> " . htmlspecialchars($r['competitionName']);
        echo " <em>score: " . $r['scoreCompetition'] . "</em>";
        echo "<br>";
    }
    if (!empty($r['linkFoto'])) {
        echo "<strong>Link:</strong> " . htmlspecialchars($r['linkFoto']);
        echo "<br>";
    }

    echo "<strong>Score:</strong> " . $r['minScore'];
    echo "</div>";
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
										<option value="2">Players & Staff</option>
										<option value="3">Competition</option>
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
