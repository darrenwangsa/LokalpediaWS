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
    $teams = strtolower($row['teams']['value'] ?? '');
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
        'teams' => $row['teams']['value'] ?? '',
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
//         echo "<strong>Competition:</strong> " . htmlspecialchars($r['competitionName']);
//         echo " <em>score: " . $r['scoreCompetition'] . "</em>";
//         echo "<br>";
//     }
//     if (!empty($r['linkFoto'])) {
//         echo "<strong>Link:</strong> " . htmlspecialchars($r['linkFoto']);
//         echo "<br>";
//     }

//     echo "<strong>Score:</strong> " . $r['minScore'];
//     echo "</div>";
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokalpedia - Result</title>
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
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: white;
            min-height: 100vh;
            /* color: #ff9d00; */
            /* padding: 20px; */
            position: relative;
            overflow-x: hidden;
        }

        .con {
            max-width: 1200px;
            margin: 5vh auto;
        }

        .search-header {
            text-align: left;
            margin-bottom: 50px;
        }

        .result-title {
            font-size: 32px;
            font-weight: 700;
            margin: 40px 0 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: inline-block;
            position: relative;
        }

        .result-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #f3aa36, transparent);
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

        .other-result {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        
    </style>

</head>

<body>
    <?php include 'header.php'; ?>
    <div class="con">

        <div class="search-header">
            <div class="search-query"><h2>🔍<strong id="search-term"> <?php echo "Hasil Pencarian untuk '$searchInput'" ?> </strong> </h2></div>
        </div>


        <div id="main-resultt">
            <a href="/lokalpediaanan/Competitions/details/detailCompetitions.php#M7" style="width: 100%; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin: 20px; padding: 0; overflow: hidden; font-family: Arial, sans-serif;">
            <div style="width: 100%; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin: 20px; padding: 0; overflow: hidden; font-family: Arial, sans-serif;">
                <div style="width: 100%; height: 25vh;">
                    <img src="./img/M7.png" alt="Thumbnail Image" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <div style="padding: 15px; background-color: #f0f0f0;">
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #333;">M7</h3>
                        <p style="margin: 0; font-size: 14px; color: #666; line-height: 1.5;">Competitions</p>
                </div>
            </div>
            </a>
        </div>

        <h2 class="result-title">Hasil Lainnya</h2>
        <div id="other-result">
    <?php 
        $limit = 26;
        $count = 0;
        $row = 1;
        $limitRow = 5;
        // Tambahkan container flex untuk layout horizontal
        echo '<div style="display: flex; flex-wrap: wrap; justify-content: space-around;">';
        foreach ($results as $r) {
            if($count >= $limit){
                break;
            }
            if($count > 0){
                // Hapus <br> untuk menghindari baris baru paksa
                // if($row == $limitRow){
                //     echo '<br>';
                //     $row = 0;
                // }
                $categories = "All Categories";
                if (!empty($r['teamName'])) {
                    $categories = "Teams";
                }
            
                if (!empty($r['playerNickname'])) {
                    $categories = "Players";
                }
            
                if (!empty($r['competitionName'])) {
                    $categories = "Competitions";
                }

                // Sesuaikan width kartu agar 5 kartu muat horizontal (misalnya 18% untuk margin)
                echo '<a href="/lokalpediaanan/' . $categories . '/details/detail' . $categories . '.php#';
                    if (!empty($r['teamName'])) {
                        echo htmlspecialchars($r['teams']);
                    }
                
                    if (!empty($r['playerNickname'])) {
                        echo htmlspecialchars($r['playerNickname']);
                    }
                
                    if (!empty($r['competitionName'])) {
                        echo htmlspecialchars($r['competitionName']);
                    }
                    echo '" style="text-decoration: none; color: inherit; display: inline-block; flex: 1 1 18%; margin: 10px;">'; // Gunakan flex untuk kontrol lebar
                    
                    // Pindahkan styling kartu ke div dalam, hapus duplikasi
                    echo '<div style="border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 0; overflow: hidden; font-family: Arial, sans-serif;">';

                    echo '<div style="width: 100%; height: 25vh;">
                        <img src="./img/' . htmlspecialchars($r['teams']) . '.png" alt="Thumbnail Image" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>';

                    if (!empty($r['teamName'])) {
                        echo '<div style="padding: 15px; background-color: #f0f0f0;">
                        <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #333;">' . htmlspecialchars($r['teamName']) . '</h3>
                        <p style="margin: 0; font-size: 14px; color: #666; line-height: 1.5;">' . $categories . '</p>
                        </div>'; 
                    }
                
                    if (!empty($r['playerNickname'])) {
                        echo '<div style="padding: 15px; background-color: #f0f0f0;">
                            <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #333;">' . htmlspecialchars($r['playerNickname']) . '</h3>
                            <p style="margin: 0; font-size: 14px; color: #666; line-height: 1.5;">' . $categories . '</p>
                            </div>'; 
                    }
                
                    if (!empty($r['competitionName'])) {
                        echo '<div style="padding: 15px; background-color: #f0f0f0;">
                        <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #333;">' . htmlspecialchars($r['competitionName']) . '</h3>
                        <p style="margin: 0; font-size: 14px; color: #666; line-height: 1.5;">' . $categories . '</p>
                        </div>'; 
                    }

                    echo "</div></a>";
                }
                $count++;
            }
                // Tutup container flex
                echo '</div>';
            ?>
        </div>


    </div>

    <script>
        // Ambil query dari URL
        const params = new URLSearchParams(window.location.search);
        const q = params.get('q')?.toLowerCase() || "";

        // document.getElementById('search-term').textContent = q.toUpperCase() || 'NONE';

        // Data dummy — tinggal diganti dari SPARQL RDF
        const data = {
            teams: {
                "evos": {
                    mainImage: "img/results/evos_main.png",
                    otherImages: [
                        "img/results/evos_1.png",
                        "img/results/evos_2.png",
                        "img/results/evos_3.png"
                    ]
                },

                "rrq": {
                    mainImage: "img/results/rrq_main.png",
                    otherImages: [
                        "img/results/rrq_1.png",
                        "img/results/rrq_2.png",
                        "img/results/rrq_3.png"
                    ]
                }
            }
        };

        // CARI DATA
        let found = data.teams[q];

        if (found) {
            // Hasil utama
            document.getElementById("main-result").innerHTML = `
                <div class="main-result">
                    <img src="${found.mainImage}" alt="Main Image">
                </div>
            `;

            // Hasil lainnya
            let imgList = "";
            found.otherImages.forEach(img => {
                imgList += `
                    <div class="player-card">
                        <img src="${img}" alt="Other Result">
                    </div>
                `;
            });

            document.getElementById("other-result").innerHTML = imgList;

        } else {
            // Jika tidak ditemukan
            document.getElementById("main-result").innerHTML = `
                <div class="not-found">
                    <h2>Tidak ditemukan hasil untuk "${q}"</h2>
                </div>
            `;
            document.getElementById("other-result").innerHTML = "";
        }
    </script>
    <?php include 'footer.php' ?>
</body>

</html>