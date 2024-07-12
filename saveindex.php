<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&family=Roboto+Condensed:wght@100;500&display=swap" rel="stylesheet">
    <title>My Cinema</title>
</head>

<body>
    <?php
    include_once('mysql.php');

    $getData = $_POST;
    $genre_name = $_POST['genre'];
    $distributor_name = $_POST['distributor'];

    if (!isset($getData['search'])) {
        $title_film = "";
    } else {
        $title_film = $getData['search'];
    }

    foreach ($genre_name as $genre_names) {
        $genre_names = $genre_names;
    }

    foreach ($distributor_name as $distributors_name) {
        $distributors_name = $distributors_name;
    }


    if (isset($distributors_name) && isset($genre_names)) {
        $requete_movie = 'SELECT m.*, d.* FROM movie m JOIN distributor d ON d.id = m.id_distributor  JOIN movie_genre mg ON m.id = mg.id_movie JOIN genre g ON mg.id_genre = g.id WHERE d.name LIKE CONCAT("%", :distributor, "%") AND g.name = :genre AND m.title LIKE CONCAT("%", :title, "%") ';
        $execute_movie = [
            'distributor' => $distributors_name,
            'genre' => $genre_names,
            'title' => $title_film,
        ];
    } else if (isset($distributors_name)) {
        $requete_movie = 'SELECT m.*, d.* FROM movie m JOIN distributor d ON d.id = m.id_distributor WHERE d.name LIKE CONCAT("%", :distributor, "%") AND m.title LIKE CONCAT("%", :title, "%") ';
        $execute_movie = [
            'distributor' => $distributors_name,
            'title' => $title_film,
        ];
        $genre_names = "Aucun";
    } else if (isset($genre_names)) {
        $requete_movie = 'SELECT m.* FROM movie m JOIN movie_genre mg ON m.id = mg.id_movie JOIN genre g ON mg.id_genre = g.id WHERE g.name = :genre AND m.title LIKE CONCAT("%", :title, "%") ';
        $execute_movie = [
            'genre' => $genre_names,
            'title' => $title_film,
        ];
        $distributors_name = "Aucun";
    } else {
        $requete_movie = 'SELECT * FROM movie WHERE title LIKE CONCAT("%", :title, "%") ';
        $execute_movie = [
            'title' => $title_film,
        ];
        $distributors_name = "Aucun";
        $genre_names = "Aucun";
    };

    $datad = $mysqlClient->prepare($requete_movie);
    $datad->execute($execute_movie);
    $data_bdd = $datad->fetchAll(PDO::FETCH_ASSOC);
    $genre_data = $mysqlClient->prepare('SELECT * FROM genre');
    $genre_data->execute([]);
    $genre = $genre_data->fetchAll(PDO::FETCH_ASSOC);
    $distributor_data = $mysqlClient->prepare('SELECT * FROM distributor ORDER BY distributor.name ASC');
    $distributor_data->execute([]);
    $distributor = $distributor_data->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php include_once('nav.php'); ?>

    <div class="mid">
        <section class="search">

            <form action="index.php" method="post">
                <div class="searchBar">
                    <input type="search" name="search" placeholder="Taper le film ici" id="search">
                    <input type="submit" id="btn-search" value="🔎">
                </div>
                <fieldset class="genre">

                    <?php foreach ($genre as $value) : ?>
                        <div class="genrediv">
                            <input type="checkbox" name="genre[]" id="genre" value="<?php echo $value['name']; ?>">
                            <label for=<?php echo $value['name']; ?>>
                                <p><?php echo $value['name']; ?></p>
                            </label>
                        </div>
                    <?php endforeach ?>
                </fieldset>

                <fieldset class="distributor">

                    <?php foreach ($distributor as $value) : ?>
                        <div class="distributordiv">
                            <input type="checkbox" name="distributor[]" id="distributor" value='<?php echo $value['name']; ?>' />
                            <label for=<?php echo $value['name']; ?>>
                                <p><?php echo $value['name']; ?></p>
                            </label>
                        </div>
                    <?php endforeach ?>
                </fieldset>
            </form>
    </div>
    </section>
    </div>
    <section>
        <div class="mid">
          
            <?php echo "<h2>Nombre de film : " . count($data_bdd) . " | Genre : " . $genre_names . " | Production : " . $distributors_name . "</h2>" ?>
            <div class="listfilm">

                <br>
                <?php foreach ($data_bdd as $value) : ?>
                    <?php
                    $rating = str_replace("PG", "", $value['rating']);
                    $rating = str_replace("-", "", $rating);
                    $rating = str_replace("G", "", $rating);
                    $rating = str_replace("R", "", $rating);

                    if ($rating == "") {
                        $rating = "<span style='color:greenYELLOW;'>AUCUN</span>";
                    } else {
                        $rating = $rating . " ans";
                    }

                    $minute = $value['duration'];
                    $heure = floor($minute / 60);
                    $minute_restant = $minute % 60;
                    $duree = $heure . "h" . $minute_restant;
                    if ($duree == "0h0") {
                        $duree = "<span style='color:red;'>NON DISPONNIBLE</span>";
                    }
                    ?>
                    <a href="">
                        <div class="film">
                            <img src="img/film.jpg" alt="<?php echo  $value['title']; ?>" srcset="">
                            <h6><?php echo  $value['title']; ?></h6>
                            <ul>
                                <li>Pegi : <?php echo $rating ?></li>
                                <li>Durée : <?php echo $duree; ?></li>
                            </ul>
                        </div>
                    </a>
                <?php endforeach ?>
            </div>
        </div>
    </section>

    <script src="script.js"></script>
</body>

</html>