<?php
// connect JENA
$endpoint = "http://localhost:3030/lokalpedia22/sparql";
$competitionName = "";

// Query for searching all Competitions
$sparqlQueryCompetitions = <<<SPARQL
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://www.semanticweb.org/acer/ontologies/2025/10/untitled-ontology-19/>

SELECT DISTINCT 
(REPLACE(STR(?competitions), "^.*[/#]", "") AS ?idCompetition)
(REPLACE(REPLACE(STR(?competitions), "^.*[/#]", ""), "_", " ") AS ?competitionName) WHERE {
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

$response = file_get_contents($endpoint . "?query=" . urlencode($sparqlQueryCompetitions) . "&format=json");
                $data = json_decode($response, true);
                $competitions = [];
                foreach ($data['results']['bindings'] as $row) {
                    // Ambil semua field
                    $competitions[] = [
                        'idCompetition' => $row['idCompetition']['value'] ?? '',
                        'competitionName' => $row['competitionName']['value'] ?? ''
                    ];
                
                }

				usort($competitions, function($a, $b) {
                    return $a['competitionName'] <=> $b['competitionName'];
                });


// foreach ($results as $r) {
    

// // if($count >= $limit){
// //     break;
// // }
//     echo "<div style='margin-bottom:10px; padding:5px; border:1px solid #000;'>";

//     if (!empty($r['teamName'])) {
//         echo "<strong>Team:</strong> " . htmlspecialchars($r['teamName']);
//         echo " <em>(score: " . $r['scoreTeamName'] . ")</em>";
//         if (!empty($r['teamAddName'])) {
//             echo " (" . htmlspecialchars($r['teamAddName']) . " <em>score: " . $r['scoreteamAddName'] . "</em>)";
//         }
//         echo "<br>";
//     }

//     if (!empty($r['playerNickname'])) {
//         echo "<strong>Player:</strong> " . htmlspecialchars($r['playerNickname']);
//         if (!empty($r['realName'])) {
//             echo " (" . htmlspecialchars($r['realName']) . ")";
//         }
//         echo " <em>score: " . $r['scorePlayer'] . "</em>";
//         echo "<br>";
//     }

//     if (!empty($r['competitionName'])) {
//         echo "<strong>Competition:</strong> " ;
// 		$competitionName = htmlspecialchars($r['competitionName']);
// 		$competition = htmlspecialchars($r['idCompetition']);
// 		echo $competition . "<br>" . $competitionName;
//         echo "<br>";
//     }
//     if (!empty($r['linkFoto'])) {
//         echo "<strong>Link:</strong> " . htmlspecialchars($r['linkFoto']);
//         echo "<br>";
//     }

//     // echo "<strong>Score:</strong> " . $r['minScore'];
//     echo "</div>";
// }

?>

<!DOCTYPE html>
<e lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Lokalpedia - Competitions</title>

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
			height: 38vh;
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
			height: 30%;
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
	
	
	<div class="main-content">
		<div class="all-leagues">
			<!-- MAIN CONTENT -->
			<?php
				foreach($competitions as $c){
					$idCompetition = htmlspecialchars($c['idCompetition']);
					$competitionName = htmlspecialchars($c['competitionName']);
					// echo $competitionName;
					// echo htmlspecialchars($c['competitionName']) . htmlspecialchars($c['idCompetition']);
					// echo '<br>';

					$sparqlQueryTeamsPerCompetitions = <<<SPARQL
					PREFIX owl: <http://www.w3.org/2002/07/owl#>
					PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
					PREFIX : <http://www.semanticweb.org/acer/ontologies/2025/10/untitled-ontology-19/>

					SELECT DISTINCT 
					?idTeam
					?teamName 
					(REPLACE(STR(?idTeam), "_.*$", "")AS ?teamFront)
					?teamAddName
					(REPLACE(REPLACE(STR(?idTeam), "^.*_", ""),  "[0-9]+", "")AS ?region)
					WHERE {{
					?team a ?competition ;
							:teamName ?teamName ;
							:hasName ?teamAddName .
						BIND(REPLACE(STR(?team), "^.*/", "") AS ?idTeam)
						BIND(REPLACE(REPLACE(STR(?competition), "^.*[/#]", ""), "_", " ") AS ?competitionName)
						FILTER(CONTAINS(?competitionName, "$competitionName"))
					}
					}
					SPARQL;

					$response = file_get_contents($endpoint . "?query=" . urlencode($sparqlQueryTeamsPerCompetitions) . "&format=json");
					$data = json_decode($response, true);
					$teamArr = [];

					echo '	<section class="league-section" id="' . $idCompetition . '">
								<div class="league-header">
									<a href="/localpediaanan/detailCompetitions.php#' . $idCompetition . '" style="text-decoration:none; color:inherit;">' . 
										'<img src="./img/' . $idCompetition . '.png" class="league-logo" onerror="this.src=' . "'img/alternative.png'" . ';">
									</a>';
					echo '			<a href="/localpediaanan/detailCompetitions.php#' . $idCompetition . '" style="text-decoration:none; color:inherit;">' . '
										<div class="league-title">' . $competitionName . '</div>
									</a>
								</div>

								<div class="teams-container">';

					foreach ($data['results']['bindings'] as $row) {
						// Ambil semua field
						$teamArr[] = [
							'idTeam' => $row['idTeam']['value'] ?? '',
							'teamName' => $row['teamName']['value'] ?? '',
							'teamFront' => $row['teamFront']['value'] ?? '',
							'teamAddName' => $row['teamAddName']['value'] ?? '',
							'region' => strtolower($row['region']['value'] ?? ''),
						];
					
					}
					foreach($teamArr as $t){
						$idTeam = htmlspecialchars($t['idTeam']);
						$teamName = htmlspecialchars($t['teamName']);
						$teamFront = htmlspecialchars($t['teamFront']);
						$teamAddName = htmlspecialchars($t['teamAddName']);
						$region = htmlspecialchars($t['region']);

						echo '	<a href="/localpediaanan/detailTeams.php#' . $idTeam . '" style="text-decoration:none; color:inherit;">' . 
								'	<div class="team-card">
										<div class="team-header">' . $teamName . 
										'	<img src="https://flagcdn.com/w40/' . $region . '.png" class="team-flag" onerror="this.src=' . "'img/alternative.png'" . ';">  
										</div>
										<div class="team-logo"><img src="./img/' . $teamFront . '.png" onerror="this.src=' . "'img/alternative.png'" . ';"></div>
									</div>
								</a>';
						}

					echo '</section>';

				}
			?>
		</div>
	</div>

	<!-- FOOTER -->
	<?php include 'footer.php'?>

	<!-- jQuery Plugins -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/slick.min.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/jquery.zoom.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>