<?php
$idSubject = $_GET['id'] ?? null;

// connect JENA
$endpoint = "http://localhost:3030/lokalpedia22/sparql";

$sparqlQueryDetailPlayers = <<<SPARQL
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://www.semanticweb.org/acer/ontologies/2025/10/untitled-ontology-19/>

SELECT DISTINCT 
?idPlayer
?realName
(REPLACE(STR(?idTeam), "_.*$", "") AS ?teamFront)
?teamName
?role
?desc
?nationality
(REPLACE(STR(?age), "^.*/", "") AS ?ages)
WHERE {{
    ?player :playerOf ?team .
    
    }
    ?team :teamName ?teamName
    OPTIONAL{
    ?player :hasRealName ?realName .
    }
  	OPTIONAL{
    ?player :playerInfo ?desc .
    }
  	OPTIONAL {
  	?player :Roles ?role .
  	}
    OPTIONAL {
  	?player :hasName ?role .
  	}
    OPTIONAL {
  	?player :hasNationality ?nationality .
  	}
    OPTIONAL {
  	?player :hasAge ?age .
  	}
  BIND(REPLACE(STR(?team), "^.*/", "") AS ?idTeam)
  BIND(REPLACE(STR(?player), "^.*/", "") AS ?idPlayer)
  FILTER(CONTAINS(?idPlayer,"Moreno"))
}
SPARQL;

$response = file_get_contents($endpoint . "?query=" . urlencode($sparqlQueryDetailPlayers) . "&format=json");
$data = json_decode($response, true);

foreach ($data['results']['bindings'] as $row) {
    // Ambil semua field
    $idPlayer = $row['idPlayer']['value'] ?? '';
    $realName = $row['realName']['value'] ?? '';
    $teamFront = $row['teamFront']['value'] ?? '';
    $teamName = $row['teamName']['value'] ?? '';
    $role = $row['role']['value'] ?? '';
    $desc = $row['desc']['value'] ?? '';
    $nationality = $row['nationality']['value'] ?? '';
    $ages = $row['ages']['value'] ?? '';
}

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Profile</title>
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            /* padding: 60px 40px; */
            min-height: 100vh;
            position: relative;
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

        .con {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 80px;
            padding : 60px 40px;
            align-items: start;
            position: relative;
            z-index: 1;
        }

        .info-section {
            padding: 40px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            animation: fadeInLeft 0.8s ease-out;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .intro-text {
            font-size: 36px;
            line-height: 1.6;
            color: #2c1810;
            margin-bottom: 50px;
            font-weight: 300;
            letter-spacing: -0.5px;
        }

        .intro-text .highlight {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
            position: relative;
        }

        .intro-text .highlight::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #ff6b35, #f7931e);
            opacity: 0.5;
        }

        .details-list {
            font-size: 24px;
            line-height: 2.2;
            color: #333;
        }

        .detail-row {
            display: flex;
            align-items: baseline;
            margin-bottom: 16px;
            padding: 12px 16px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.4);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .detail-row:hover {
            background: rgba(255, 255, 255, 0.7);
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .detail-label {
            font-weight: 500;
            min-width: 220px;
            color: #666;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .detail-value {
            font-weight: 400;
            color: #f3aa36;
            
        }

        .detail-value.highlight {
            background: linear-gradient(135deg, #f3aa36 0%, #f7931e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
        }

        .logo-icon {
            display: inline-flex;
            width: 28px;
            height: 28px;
            margin-left: 8px;
            vertical-align: middle;
            position: relative;
            top: -2px;
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .image-section {
            position: relative;
            animation: fadeInRight 0.8s ease-out;
            filter: drop-shadow(0 12px 28px rgba(0, 0, 0, 0.705));
        }

        .image-header {
            background: rgb(6, 4, 31);
            backdrop-filter: blur(10px);
            padding: 20px 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-bottom: none;
            position: relative;
            overflow: hidden;
        }

        .image-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #ff6b35, #f7931e, #ffd700);
        }

        .logo-box {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;

            transition: transform 0.3s ease;
        }

        .logo-box:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .logo-symbol img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .player-tag {
            font-size: 36px;
            font-weight: 700;
            background: linear-gradient(135deg, #ffffff 0%, #d07f05 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: uppercase;
            background-clip: text;
            letter-spacing: 1px;
        }

        .player-image-con {
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
            width: 100%;
            height: 520px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-top: none;
            position: relative;
        }

        .player-image-con::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, transparent 0%, rgba(255, 107, 53, 0.1) 100%);
            pointer-events: none;
        }

        .player-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .player-image-con:hover .player-image {
            transform: scale(1.05);
        }

        .decorative-line {
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #ff6b35, #f7931e);
            margin-bottom: 30px;
            border-radius: 2px;
            box-shadow: 0 2px 12px rgba(255, 107, 53, 0.5);
        }

        @media (max-width: 1200px) {
            .con {
                grid-template-columns: 1fr;
                gap: 60px;
            }

            .image-section {
                max-width: 450px;
                margin: 0 auto;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 30px 20px;
            }

            .info-section {
                padding: 30px 24px;
            }

            .intro-text {
                font-size: 26px;
                margin-bottom: 40px;
            }

            .details-list {
                font-size: 18px;
            }

            .detail-row {
                flex-direction: column;
                gap: 8px;
                padding: 16px;
            }

            .detail-label {
                min-width: auto;
                font-size: 16px;
            }

            .player-image-con {
                height: 450px;
            }

            .player-tag {
                font-size: 28px;
            }
        }

        @media (max-width: 480px) {
            .intro-text {
                font-size: 22px;
            }

            .details-list {
                font-size: 16px;
            }

            .info-section {
                padding: 24px 20px;
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
    <?php include'header.php' ?>
    <div class="con">
        <?php
        
        ?>
        <div class="info-section">
            <div class="decorative-line"></div>

            <p class="intro-text">
                <?php
                    if(!empty($desc)){
                        echo $desc;
                    } else{
                        echo "The Description of this player is unavailable.";
                    }

                ?>
            </p>

            <div class="details-list">
                <?php
                    if(!empty($realName)){
                        echo '  <div class="detail-row">
                                    <span class="detail-label">Name</span>
                                    <span class="detail-value">' . $realName . '</span>
                                </div>';
                    }
                    if(!empty($nationality)){
                        echo '  <div class="detail-row">
                                    <span class="detail-label">Name</span>
                                    <span class="detail-value">' . $nationality . '</span>
                                </div>';
                    }
                    if(!empty($ages)){
                        echo '  <div class="detail-row">
                                    <span class="detail-label">Name</span>
                                    <span class="detail-value">' . $ages . '</span>
                                </div>';
                    }
                    if(!empty($role)){
                        echo '  <div class="detail-row">
                                    <span class="detail-label">Name</span>
                                    <span class="detail-value">' . $role . '</span>
                                </div>';
                    }
                    if(!empty($teamName)){
                        echo '  <div class="detail-row">
                                    <span class="detail-label">Team</span>
                                    <span class="detail-value highlight">' .
                                        $teamName
                                        . '<span class="logo-icon">
                                            <img src="img/' . $teamFront . '.png" alt="Team Logo" onerror="' . "this.src='img/alternative.png';" . '">
                                        </span>
                                    </span>
                                </div>';
                    }
                ?>
            </div>
        </div>

        <div class="image-section">
            <div class="image-header">
                <div class="logo-box">
                    <span class="logo-symbol">
                        <?php
                            echo '<img src="img/' . $teamFront . '.png" alt="Team Logo" onerror="' . "this.src='img/alternative.png';" . '"> </img>';
                        ?>
                    </span>
                </div>
                <span class="player-tag">
                    <?php
                        echo $idPlayer;
                    ?>
                </span>
            </div>
            <div class="player-image-con">
                <?php
                    echo '<img src="img/' . $idPlayer . '.png" class="player-image" alt="Team Logo" onerror="' . "this.src='img/alternative.png';" . '"> </img>';
                ?>
            </div>
        </div>


    </div>
    <?php include'footer.php' ?>
</body>

</html>