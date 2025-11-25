<?php 
// connect JENA
$endpoint = "http://localhost:3030/lokalpedia22/sparql";

// Query for TEAMS
$sparqlQueryTeams = <<<SPARQL
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://www.semanticweb.org/acer/ontologies/2025/10/untitled-ontology-19/>

SELECT
  ?idTeam
  (REPLACE(STR(?idTeam), "_.*$", "") AS ?teamFront)
  ?teamName
  ?teamAddName
  ?idCompetitions
  ?competitionNames
WHERE {
  {
    SELECT
      ?idTeam
      (SAMPLE(?teamNameIn)    AS ?teamName)
      (SAMPLE(?teamAddNameIn) AS ?teamAddName)
      (GROUP_CONCAT(DISTINCT ?idCompetition; separator=", ") AS ?idCompetitions)
      (GROUP_CONCAT(DISTINCT ?competitionName; separator=", ") AS ?competitionNames)
    WHERE {
      ?team a ?competition ;
            :teamName ?teamNameIn ;
            :hasName ?teamAddNameIn .

      { 
        ?headTour rdfs:subClassOf :InternationalTour .
        ?competition rdfs:subClassOf ?headTour .
      } UNION {
        ?regionTour rdfs:subClassOf :RegionalTour .
        ?headTour rdfs:subClassOf ?regionTour .
        ?competition rdfs:subClassOf ?headTour .
      }

      BIND(REPLACE(STR(?team), "^.*/", "") AS ?idTeam)
      BIND(REPLACE(STR(?competition), "^.*/", "") AS ?idCompetition)
      BIND(REPLACE(REPLACE(STR(?competition), "^.*[/#]", ""), "_", " ") AS ?competitionName)

      FILTER NOT EXISTS {
        ?child rdfs:subClassOf ?competition .
        FILTER(?child != ?competition)
      }
    }
    GROUP BY ?idTeam
  }
}
ORDER BY ?idTeam
SPARQL;

$response = file_get_contents($endpoint . "?query=" . urlencode($sparqlQueryTeams) . "&format=json");
$data = json_decode($response, true);
$results = [];

foreach ($data['results']['bindings'] as $row) {
    // Ambil semua field
    $results[] = [
        'idTeam' => $row['idTeam']['value'] ?? '',
        'teamFront' => $row['teamFront']['value'] ?? '',
        'teamName' => $row['teamName']['value'] ?? '',
        'teamAddName' => $row['teamAddName']['value'] ?? '', 
        'idCompetitions' => $row['idCompetitions']['value'] ?? '',
        'competitionNames' => $row['competitionNames']['value'] ?? '',
    ];
}

usort($results, function($a, $b) {
    return $a['teamName'] <=> $b['teamName'];
});

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokalpedia - Teams</title>
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
    <style>

        .flag {
            font-size: 1.2em;
            margin-right: 8px;
        }


        /* * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        } */

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

        .con {
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .main-content {
            flex: 1;
            width: 100%;
            padding: 40px 20px;
            position: relative;
            z-index: 1;
        }

        .detail-value {
            font-size: 12px;
            color: #2c3e50;
            font-weight: 500;
            text-align: right;
            max-width: 60%;
        }

        .section-title {
            font-size: 42px;
            font-weight: 300;
            letter-spacing: 6px;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 60px;
            text-transform: uppercase;
        }
        .section-title {
                font-size: 32px;
            }

        .player-card {
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            height: 100%;
        }

        .player-name {
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
            letter-spacing: 1px;
            line-height: 1.2;
        }
        
        .more-detail-label {
            font-size: 11px;
            color: #257af1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            display: block;
            margin-top: 20px;   /* ini bikin turun */
        }

        .player-role {
            font-size: 13px;
            color: #75c5f0;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .player-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 8px;
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: 11px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .more-detail-label {
            font-size: 11px;
            color: #257af1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            display: block;
            margin-top: 20px;   /* ini bikin turun */
        }

        .player-info {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            gap: 15px;
            background: white;
        }

        .player-image {
            flex: 0 0 220px;
            background:
                linear-gradient(to bottom,
                    transparent 0%,
                    rgba(18, 39, 60, 0.1) 20%,
                    rgba(3, 37, 90, 0) 50%,
                    rgba(255, 255, 255, 0.7) 80%,
                    #f3aa36 100%),
                repeating-linear-gradient(45deg,
                    #f3aa36 0,
                    #ffffff 10px,
                    #ffffff 10px,
                    #ffffff 20px);
            position: relative;
            overflow: hidden;
        }

        .player-image::after {
            content: "";
            position: absolute;
            inset: 0;
            top: 10px;
            background-image: var(--img);
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            transition: transform 0.5s ease;
        }

        .player-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
        }

        .roster-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            max-width: 1600px;
            margin: 0 auto;
        }
        .player-card:hover img {
            transform: scale(1.1);
        }
        img {
            transition: 0.3s;
        }

        @media (max-width: 1200px) {
            .roster-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .roster-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .roster-grid {
                grid-template-columns: 1fr;
            }

            .player-card {
                flex-direction: column;
            }

            .player-image {
                flex: 0 0 250px;
            }

            .player-info {
                padding: 20px;
            }
        }
        @media (max-width: 480px) {
            .roster-grid {
                gap: 20px;
            }

            .player-info {
                padding: 15px;
            }

            .player-name {
                font-size: 20px;
            }
        }

        h1 {
            color: #f3aa36;
            font-size: 2.5em;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgb(255, 255, 255);
        }

        .competition-card {
            background: rgba(255, 255, 255, 0.6);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .competition-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #f3aa36, #f4e5b8, #c9a961);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card-footer {
    padding: 15px 20px;
}
.main-resultt {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 50px;
            text-align: center;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transition: 0.3s ease;
        }

.main-resultt img {
            max-width: 450px;
            width: 100%;
            border-radius: 20px;
        }
.more-info {
    color: #3498db;
    font-weight: bold;
    text-decoration: none;
    cursor: pointer;
}

.more-info:hover {
    text-decoration: underline;
}


        .tier-badge {
            background: linear-gradient(135deg, #f3aa36 0%, #f4e5b8 100%);
            color: #333;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 1.2em;
            box-shadow: 0 4px 15px rgba(201, 169, 97, 0.4);
        }

        .logoImg-section {
            text-align: center;
            flex-shrink: 0;
        }

        .logoImg {
            width: 150px;
            height: 150px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            box-shadow: 0 8px 20px rgba(201, 169, 97, 0.5);
            overflow: hidden;
            padding: 10px;
        }

        .logoImg img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .tagline {
            font-style: italic;
            color: #666;
            font-size: 0.95em;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .info-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .info-section h3 {
            color: #f3aa36;
            margin-bottom: 15px;
            font-size: 1.3em;
            border-bottom: 2px solid #c9a961;
            padding-bottom: 8px;
        }

        .info-item {
            display: flex;
            margin-bottom: 12px;
            line-height: 1.6;
            align-items: center;
        }

        .info-label {
            font-weight: 600;
            color: #333;
            min-width: 140px;
        }

        .info-value {
            color: #555;
            flex: 1;
        }

        .venue-item {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 4px solid #f3aa36;
        }

        .prize-highlight {
            font-size: 1.8em;
            font-weight: bold;
            color: #f3aa36;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .date-highlight {
            font-weight: 600;
            color: #f3aa36;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.8em;
            }

            .competition-card {
                padding: 25px;
            }

            .logoImg {
                width: 120px;
                height: 120px;
                font-size: 2.5em;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php include 'header.php';?>
    <div class="main-content">
        <div class="con">
            <h2 class="section-title">Teams</h2>
            <div class="roster-grid">

                <?php
                foreach($results as $r){
                    $competitionNames = "";
                    $competitions = htmlspecialchars($r['competitionNames']);
                    $competitionNames = str_replace(", ", "<br>", $competitions);
                    $parts = explode(",", $competitions);
                    $regionalCompetition = end($parts);

                    echo '  <div class="player-card">
                                <img src="img/' . htmlspecialchars($r['teamFront']) . '.png" class="player-image" style="object-fit: contain;  " onerror="' . "this.src='img/alternative.png';" . '"></img>';
                    echo '      <div class="player-info">
                                    <div>
                                        <div class="player-name">' . htmlspecialchars($r['teamName']) . '</div>
                                        <div class="player-role">' . $regionalCompetition . '</div>
                                    </div>
                                    <div class ="player-details">
                                        <div class="detail-row">
                                            <span class="detail-label">Playing At</span>
                                            <span class="detail-value">' . $competitionNames . '</span>
                                        </div>
                                        <a href="/lokalpediaanan/Teams/details/detailTeams.php#' . htmlspecialchars($r['idTeam']) . '">
                                            <div class ="detail-row">
                                                <span class="more-detail-label">More Details</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>' ;

                }
                ?>
                <!-- <div class="player-card">
                    <img src="img/Onic.png" class="player-image" style="object-fit: contain;  " onerror="this.src='img/alternative.png';"></img>
                    <div class="player-info">
                        <div>
                            <div class="player-name">Onic</div>
                            <div class="player-role">MPL ID16</div>
                        </div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Playing At</span>
                                <span class="detail-value">MPL <br> M7</span> 
                            </div>
                            <a href="/lokalpediaanan/Teams/details/detailTeams.php#Onic_ID16">
                                <div class ="detail-row">
                                    <span class="more-detail-label">More Details</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="player-card">
                    <img src="playerimg/Onic.png" class="player-image" style="object-fit: contain;  " onerror="this.src='img/alternative.png';"></img>
                    <div class="player-info">
                        <div>
                            <div class="player-name">Onic</div>
                            <div class="player-role">MPL ID16</div>
                        </div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Playing At</span>
                                <span class="detail-value">MPL</span>
                                <span class="detail-value">MPL</span>
                            </div>
                            <a href="/lokalpediaanan/Teams/details/detailTeams.php#Onic_ID16">
                                <div class ="detail-row">
                                    <span class="more-detail-label">More Details</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="player-card">
                    <img src="playerimg/Onic.png" class="player-image" style="object-fit: contain;  " onerror="this.src='img/alternative.png';"></img>
                    <div class="player-info">
                        <div>
                            <div class="player-name">Onic</div>
                            <div class="player-role">MPL ID16</div>
                        </div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Playing At</span>
                                <span class="detail-value">MPL</span>
                                <span class="detail-value">MPL</span>
                            </div>
                            <a href="/lokalpediaanan/Teams/details/detailTeams.php#Onic_ID16">
                                <div class ="detail-row">
                                    <span class="more-detail-label">More Details</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="player-card">
                    <img src="playerimg/Onic.png" class="player-image" style="object-fit: contain;  " onerror="this.src='img/alternative.png';"></img>
                    <div class="player-info">
                        <div>
                            <div class="player-name">Onic</div>
                            <div class="player-role">MPL ID16</div>
                        </div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Playing At</span>
                                <span class="detail-value">MPL</span>
                                <span class="detail-value">MPL</span>
                            </div>
                            <a href="/lokalpediaanan/Teams/details/detailTeams.php#Onic_ID16">
                                <div class ="detail-row">
                                    <span class="more-detail-label">More Details</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="player-card">
                    <img src="playerimg/Onic.png" class="player-image" style="object-fit: contain;  " onerror="this.src='img/alternative.png';"></img>
                    <div class="player-info">
                        <div>
                            <div class="player-name">Onic</div>
                            <div class="player-role">MPL ID16</div>
                        </div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="detail-label">Playing At</span>
                                <span class="detail-value">MPL</span>
                                <span class="detail-value">MPL</span>
                            </div>
                            <a href="/lokalpediaanan/Teams/details/detailTeams.php#Onic_ID16">
                                <div class ="detail-row">
                                    <span class="more-detail-label">More Details</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <?php include 'footer.php';?>
</body>

</html>
        <!-- International Competitions -->
        <!-- <div id="main-resultt">
            <a href="/lokalpediaanan/Competitions/details/detailCompetitions.php#M7" style="width: 100%; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin: 20px; padding: 0; overflow: hidden; font-family: Arial, sans-serif;">
            <div style="width: 100%; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin: 20px; padding: 0; overflow: hidden; font-family: Arial, sans-serif;">
                <div style="width: 100%; height: 25vh;">
                    <img src="./img/M7.png" alt="Thumbnail Image" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <div style="padding: 15px; background-color: #f0f0f0;">
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: black;">M7</h3>
                        <p style="margin: 0; font-size: 14px; color: #666; line-height: 1.5;">Competitions</p>
                </div>
            </div>
            </a>
        </div>

        <div id="main-resultt">
            <a href="/lokalpediaanan/Competitions/details/detailCompetitions.php#M7" style="width: 100%; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin: 20px; padding: 0; overflow: hidden; font-family: Arial, sans-serif;">
            <div style="width: 100%; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin: 20px; padding: 0; overflow: hidden; font-family: Arial, sans-serif;">
                <div style="width: 100%; height: 25vh;">
                    <img src="./img/M7.png" alt="Thumbnail Image" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <div style="padding: 15px; background-color: #f0f0f0;">
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: black;">M7</h3>
                        <p style="margin: 0; font-size: 14px; color: #666; line-height: 1.5;">Competitions</p>
                </div>
            </div>
            </a> -->
        <!-- </div> -->
        
        <!-- </div>
    </div>
    