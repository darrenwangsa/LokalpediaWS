<?php
$idSubject = $_GET['id'] ?? null;
$competitionName = "";
$typeCompetition = "";

// connect JENA
$endpoint = "http://localhost:3030/lokalpedia22/sparql";

$sparqlQueryDetailCompetitions = <<<SPARQL
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://www.semanticweb.org/acer/ontologies/2025/10/untitled-ontology-19/>

SELECT DISTINCT 
  ?idCompetition
  ?competitionName
  (REPLACE(STR(?idCompetition), "[0-9]+$", "") AS ?competitionPng)
  (REPLACE(STR(?typeCompetitions), "^.*/", "") AS ?typeCompetition)
WHERE {
    {
        BIND(:InternationalTour AS ?typeCompetitions)
        ?headTour rdfs:subClassOf :InternationalTour .
        ?competitions rdfs:subClassOf ?headTour .
    }
    UNION
    {
        BIND(:RegionalTour AS ?typeCompetitions)
        ?regionTour rdfs:subClassOf :RegionalTour .
        ?headTour rdfs:subClassOf ?regionTour .
        ?competitions rdfs:subClassOf ?headTour .
    }
    BIND(REPLACE(STR(?competitions), "^.*/", "") AS ?idCompetition)
	BIND(REPLACE(REPLACE(STR(?competitions), "^.*[/#]", ""), "_", " ") AS ?competitionName)
    FILTER(CONTAINS(?idCompetition, "$idSubject"))

    FILTER NOT EXISTS {
        ?child rdfs:subClassOf ?competitions .
        FILTER(?child != ?competitions)
    }
}

SPARQL;

$response = file_get_contents($endpoint . "?query=" . urlencode($sparqlQueryDetailCompetitions) . "&format=json");
$data = json_decode($response, true);

foreach ($data['results']['bindings'] as $row) {
    // Ambil semua field
    $idCompetition = $row['idCompetition']['value'] ?? '';
    $competitionName = $row['competitionName']['value'] ?? '';
    $competitionPng = $row['competitionPng']['value'] ?? '';
    $typeCompetition = $row['typeCompetition']['value'] ?? '';
}
// echo $idCompetition . $competitionName . $competitionPng . $typeCompetition;

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
	(REPLACE(STR(?rankRegional), "\"", "") AS ?rankMPL)
	(REPLACE(STR(?rankInter), "\"", "") AS ?rankInternational)
    (REPLACE(REPLACE(STR(?idTeam), "^.*_", ""),  "[0-9]+", "")AS ?region)
    WHERE {{
    ?team a ?competition ;
            :teamName ?teamName ;
            :hasName ?teamAddName .
    OPTIONAL{
    	?team :RankMPL ?rankRegional
    }
    OPTIONAL{
    	?team :RankInter ?rankInter
    }
        BIND(REPLACE(STR(?team), "^.*/", "") AS ?idTeam)
        BIND(REPLACE(REPLACE(STR(?competition), "^.*[/#]", ""), "_", " ") AS ?competitionName)
        FILTER(CONTAINS(?competitionName, "$competitionName"))
    }
    }
SPARQL;

$responses = file_get_contents($endpoint . "?query=" . urlencode($sparqlQueryTeamsPerCompetitions) . "&format=json");
$datas = json_decode($responses, true);
$results = [];

foreach ($datas['results']['bindings'] as $row) {
    // Ambil semua field
    $results[] = [
        'idTeam' => $row['idTeam']['value'] ?? '',
        'teamName' => $row['teamName']['value'] ?? '',
        'teamFront' => $row['teamFront']['value'] ?? '',
        'rankMPL' => $row['rankMPL']['value'] ?? '',
        'rankInternational' => $row['rankInternational']['value'] ?? '',
        'teamAddName' => $row['teamAddName']['value'] ?? '',
        'region' => $row['region']['value'] ?? '',
    ];

}

usort($results, function($a, $b) {
    return $a['teamName'] <=> $b['teamName'];
});
?>


    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detail Competitions</title>
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
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
                min-height: 100vh;
                /* padding: 20px; */
            }

            .con {
                max-width: 1400px;
                margin: 5vh auto;
            }

            .headerr {
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                padding: 30px;
                margin-bottom: 30px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 20px;
            }

            .headerr-left h1 {
                color: #2c3e50;
                font-size: 2.5em;
                margin-bottom: 10px;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            }

            .location {
                display: flex;
                align-items: center;
                gap: 10px;
                color: #555;
                font-size: 1.1em;
            }

            .flag {
                width: 30px;
                height: 20px;
            }

            .logo-section {
                text-align: center;
            }

            .logo-section img {
                width: 150px;
                height: 150px;
                object-fit: contain;
            }

            .tagline {
                color: #2c3e50;
                font-weight: bold;
                font-size: 1.2em;
                margin-top: 10px;
            }

            .main-content {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
                gap: 30px;
                margin-bottom: 30px;
            }

            .card {
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                padding: 30px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            }

            .card h2 {
                color: #2c3e50;
                font-size: 1.8em;
                margin-bottom: 20px;
                border-bottom: 3px solid #3498db;
                padding-bottom: 10px;
            }

            .venue-info, .format-info {
                line-height: 2;
            }

            .venue-info p, .format-info p {
                color: #555;
                font-size: 1.1em;
                margin-bottom: 10px;
            }

            .venue-info strong, .format-info strong {
                color: #2c3e50;
                display: inline-block;
                width: 150px;
            }

            .league-info {
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                padding: 30px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                margin-bottom: 30px;
            }

            .league-info h2 {
                color: #2c3e50;
                font-size: 1.8em;
                margin-bottom: 20px;
                text-align: center;
            }

            .info-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 15px;
            }

            .info-item {
                background: rgba(52, 152, 219, 0.1);
                padding: 15px;
                border-radius: 10px;
                border-left: 4px solid #3498db;
            }

            .info-item strong {
                color: #2c3e50;
                display: block;
                margin-bottom: 5px;
            }

            .info-item span {
                color: #555;
            }

            .participants {
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                padding: 30px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                margin-bottom: 30px;
            }

            .participants h2 {
                color: #2c3e50;
                font-size: 1.8em;
                margin-bottom: 25px;
                text-align: center;
            }

            .teams-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 20px;
            }

            .team-card {
                background: rgba(255, 255, 255, 0.8);
                border-radius: 15px;
                padding: 20px;
                text-align: center;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }

            .team-card:hover {
                transform: scale(1.05);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            }

            .team-card img {
                width: 100px;
                height: 100px;
                object-fit: contain;
                margin-bottom: 10px;
            }

            .team-name {
                color: #2c3e50;
                font-weight: bold;
                font-size: 1.1em;
            }

            .standings {
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                padding: 30px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                overflow-x: auto;
            }

            .standings h2 {
                color: #2c3e50;
                font-size: 1.8em;
                margin-bottom: 25px;
                text-align: center;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            thead {
                background: linear-gradient(135deg, #F3AA36 0%, #ff9d00 100%);
                color: white;
            }

            th {
                padding: 15px;
                text-align: left;
                font-weight: 600;
            }

            tbody tr {
                background: rgba(124, 255, 101, 0.426);
                transition: all 0.3s ease;
            }

            tbody tr:nth-child(even) {
                background: rgba(124, 255, 101, 0.426);
            }

            tbody tr:hover {
                background: rgba(52, 152, 219, 0.2);
                transform: scale(1.01);
            }

            tbody tr.playoff-zone {
                border-left: 5px solid #008bfd;
            }

            tbody tr.elimination-zone {
                border-left: 5px solid #e74c3c;
                background: rgba(255, 25, 0, 0.1);
            }

            td {
                padding: 15px;
                color: #2c3e50;
            }

            .rank {
                font-weight: bold;
                font-size: 1.2em;
                color: #000000;
            }

            .team-logo {
                width: 30px;
                height: 30px;
                object-fit: contain;
                vertical-align: middle;
                margin-right: 10px;
            }

            .positive {
                color: #27ae60;
                font-weight: bold;
            }

            .negative {
                color: #e74c3c;
                font-weight: bold;
            }

            @media (max-width: 768px) {
                .headerr {
                    flex-direction: column;
                    text-align: center;
                }

                .headerr-left h1 {
                    font-size: 1.8em;
                }

                .main-content {
                    grid-template-columns: 1fr;
                }

                .teams-grid {
                    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                }
            }
        </style>
    </head>

    <script>
    const hash = window.location.hash.substring(1); // ambil setelah "#"

    if (hash) {
        // redirect ulang dengan parameter GET
        window.location.href = "?id=" + encodeURIComponent(hash);
    }
    </script>

    <body>
        <?php include 'header.php'?>
        <div class="con">
            <!-- headerr -->
            <div class="headerr">
                <div class="headerr-left">
                    <?php
                        echo '<h1>' . $competitionName . '</h1>';
                    ?>
                </div>
                
                <div class="logo-section" style="
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 40px;
                ">
                
                    <?php
                    $champs = 0;
                    $champsLimit = 1;
                    $runnerUp = 1;
                    $runnerUpLimit = 2;
                        if($typeCompetition == "InternationalTour"){
                            usort($results, function($a, $b) {
                                return $a['rankInternational'] <=> $b['rankInternational'];
                            });
                            foreach($results as $r){
                                echo '  <div style="display: flex; flex-direction: column; gap: 15px; font-size: 1em; color:#2c3e50; text-align:left;">';
                                if ($r['rankInternational'] == "1" || $r['rankInternational'] == "2"){
                                    if($r['rankInternational'] == "1"){
                                    echo '    <div style="display: flex; align-items: center; gap: 10px;">
                                                <strong>Champions:</strong>
                                                <img src="img/' . htmlspecialchars($r['teamFront']) . '.png" alt="Champions" style="width:60px; height:60px; object-fit:contain;">
                                            </div>';
                                    } elseif($r['rankInternational'] == "2"){
                                    echo    '<div style="display: flex; align-items: center; gap: 10px;">
                                                <strong>Runner-up:</strong>
                                                <img src="img/' . htmlspecialchars($r['teamFront']) . '.png" alt="Runner-up" style="width:60px; height:60px; object-fit:contain;">
                                            </div>';
                                        }
                                    }
                                echo    '</div>';
                            }
                        } elseif($typeCompetition == "RegionalTour"){
                            usort($results, function($a, $b) {
                                return $a['rankMPL'] <=> $b['rankMPL'];
                            });
                            echo '  <div style="display: flex; flex-direction: column; gap: 15px; font-size: 1em; color:#2c3e50; text-align:left;">';
                            foreach($results as $r){
                                if ($r['rankMPL'] == "1" || $r['rankMPL'] == "2"){
                                    if($r['rankMPL'] == "1"){
                                    echo '    <div style="display: flex; align-items: center; gap: 10px;">
                                                <strong>Champions:</strong>
                                                <img src="img/' . htmlspecialchars($r['teamFront']) . '.png" alt="Champions" style="width:60px; height:60px; object-fit:contain;">
                                            </div>';
                                    } elseif($r['rankMPL'] == "2"){
                                    echo    '<div style="display: flex; align-items: center; gap: 10px;">
                                                <strong>Runner-up:</strong>
                                                <img src="img/' . htmlspecialchars($r['teamFront']) . '.png" alt="Runner-up" style="width:60px; height:60px; object-fit:contain;">
                                            </div>';
                                        }
                                    }
                                }
                            echo    '</div>';
                        }

                        echo '  <div style="text-align: center;">
                                    <img src="img/' . $competitionPng . '.png" alt ="' . $competitionName . ' Logo" style="width:150px; height:150px; object-fit:contain;">
                                </div>';

                        
                    ?>

                    <!-- Champions & Runner-up (KIRI) -->
                    <!-- <div style="display: flex; flex-direction: column; gap: 15px; font-size: 1em; color:#2c3e50; text-align:left;">
                
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <strong>Champions:</strong>
                            <img src="img/logo-onic.png" alt="Champions" style="width:60px; height:60px; object-fit:contain;">
                        </div>
                
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <strong>Runner-up:</strong>
                            <img src="img/logo-ae.png" alt="Runner-up" style="width:60px; height:60px; object-fit:contain;">
                        </div>
                
                    </div> -->
                
                    <!-- Logo MPL (KANAN) -->
                
                </div>

            </div>

            
            <div class="participants">
                <h2>Participants</h2>
                <div class="teams-grid">

                    <?php
                    usort($results, function($a, $b) {
                        return $a['teamName'] <=> $b['teamName'];
                    });

                    foreach($results as $r){
                        
                    echo '  <div class="team-card">
                                <img src="img/' . htmlspecialchars($r['teamFront']) . '.png" alt="' . htmlspecialchars($r['teamName']) . ' Logo" onerror="' . "this.src='img/alternative.png';" . '"></img>
                                <div class="team-name">' . htmlspecialchars($r['teamName']) . '</div>
                            </div>';
                    }
                    ?>
                    <!-- <div class="team-card">
                        <img src="img/logo-ae.png" alt="Alter Ego">
                        <div class="team-name">Alter Ego</div>
                    </div>
                    <div class="team-card">
                        <img src="img/logo-btr.png" alt="Bigetron by Vitality">
                        <div class="team-name">Bigetron by Vitality</div>
                    </div>
                    <div class="team-card">
                        <img src="img/logo-dewa.png" alt="Dewa United Esports">
                        <div class="team-name">Dewa United Esports</div>
                    </div>
                    <div class="team-card">
                        <img src="img/logo-evos.png" alt="EVOS">
                        <div class="team-name">EVOS</div>
                    </div>
                    <div class="team-card">
                        <img src="img/logo-geek.png" alt="Geek Fam ID">
                        <div class="team-name">Geek Fam ID</div>
                    </div>
                    <div class="team-card">
                        <img src="img/logo-navi.png" alt="Natus Vincere">
                        <div class="team-name">Natus Vincere</div>
                    </div>
                    <div class="team-card">
                        <img src="img/logo-onic.png" alt="ONIC">
                        <div class="team-name">ONIC</div>
                    </div>
                    <div class="team-card">
                        <img src="img/logo-rrq.png" alt="RRQ Hoshi">
                        <div class="team-name">RRQ Hoshi</div>
                    </div>
                    <div class="team-card">
                        <img src="img/logo-tlph.png" alt="Team Liquid ID">
                        <div class="team-name">Team Liquid ID</div>
                    </div> -->


                </div>
            </div>

            <div class="standings">
                <h2>Regular Season Standings</h2>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Team</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if($typeCompetition == "InternationalTour"){
                            usort($results, function($a, $b) {

                                // Ambil angka pertama dari string
                                preg_match('/\d+/', $a['rankInternational'], $matchA);
                                preg_match('/\d+/', $b['rankInter'], $matchB);
                            
                                $numA = isset($matchA[0]) ? intval($matchA[0]) : PHP_INT_MAX;
                                $numB = isset($matchB[0]) ? intval($matchB[0]) : PHP_INT_MAX;
                            
                                return $numA <=> $numB;
                            });
                        } elseif ($typeCompetition == "RegionalTour") {
                            usort($results, function($a, $b) {

                                // Ambil angka pertama dari string
                                preg_match('/\d+/', $a['rankMPL'], $matchA);
                                preg_match('/\d+/', $b['rankMPL'], $matchB);
                            
                                $numA = isset($matchA[0]) ? intval($matchA[0]) : PHP_INT_MAX;
                                $numB = isset($matchB[0]) ? intval($matchB[0]) : PHP_INT_MAX;
                            
                                return $numA <=> $numB;
                            });                            
                            foreach($results as $r){
                                if(!empty($r['rankMPL'])){
                                    if ($r['rankMPL'] < "7") {
                                        echo '  <tr class="playoff-zone">
                                                    <td class="rank">' . htmlspecialchars($r['rankMPL']) . '</td>
                                                    <td><img src="img/' . htmlspecialchars($r['teamFront']) . '.png" alt="' . htmlspecialchars($r['teamName']) . ' Logo" class="team-logo">' . htmlspecialchars($r['teamFront']) . '</td>
                                                </tr>';
                                    }
                                    elseif ($r['rankMPL'] > "6") {
                                        echo '  <tr class="elimination-zone">
                                                    <td class="rank">' . htmlspecialchars($r['rankMPL']) . '</td>
                                                    <td><img src="img/' . htmlspecialchars($r['teamFront']) . '.png" alt="' . htmlspecialchars($r['teamName']) . ' Logo" class="team-logo">' . htmlspecialchars($r['teamFront']) . '</td>
                                                </tr>';
                                    }
                                }
                            }
                        }

                        
                        ?>

                        <!-- <tr class="playoff-zone">
                            <td class="rank">1</td>
                            <td><img src="img/logo-onic.png" alt="ONIC" class="team-logo">ONIC</td>
                        </tr>
                        <tr class="playoff-zone">
                            <td class="rank">2</td>
                            <td><img src="img/logo-btr.png" alt="BIGETRON" class="team-logo">BIGETRON BY VIT</td>
                        </tr>
                        <tr class="playoff-zone">
                            <td class="rank">3</td>
                            <td><img src="img/logo-ae.png" alt="ALTER EGO" class="team-logo">ALTER EGO ESPORTS</td>
                        </tr>
                        <tr class="playoff-zone">
                            <td class="rank">4</td>
                            <td><img src="img/logo-evos.png" alt="EVOS" class="team-logo">EVOS</td>
                        </tr>
                        <tr class="playoff-zone">
                            <td class="rank">5</td>
                            <td><img src="img/logo-dewa.png" alt="DEWA" class="team-logo">DEWA UNITED</td>
                        </tr>
                        <tr class="playoff-zone">
                            <td class="rank">6</td>
                            <td><img src="img/logo-navi.png" alt="NAVI" class="team-logo">NAVI</td>
                        </tr>
                        <tr class="elimination-zone">
                            <td class="rank">7</td>
                            <td><img src="img/logo-rrq.png" alt="RRQ" class="team-logo">RRQ HOSHI</td>
                        </tr>
                        <tr class="elimination-zone">
                            <td class="rank">8</td>
                            <td><img src="img/logo-geek.png" alt="GEEK FAM" class="team-logo">GEEK FAM</td>
                        </tr>
                        <tr class="elimination-zone">
                            <td class="rank">9</td>
                            <td><img src="img/logo-liquid.png" alt="TL" class="team-logo">TEAM LIQUID ID</td>
                        </tr> -->


                    </tbody>
                </table>
            </div>
            
        </div>
        <?php include 'footer.php' ?>
    </body>
    </html>