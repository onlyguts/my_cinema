<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style_new.css">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&family=Roboto+Condensed:wght@100;500&display=swap" rel="stylesheet">
    <title>My Cinema</title>
</head>

<body>
    <?php
    session_start();
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
        $requete_moviec = "SELECT count(m.id) AS 'cm' FROM movie m JOIN distributor d ON d.id = m.id_distributor  JOIN movie_genre mg ON m.id = mg.id_movie JOIN genre g ON mg.id_genre = g.id WHERE d.name LIKE CONCAT('%', :distributor, '%') AND g.name = :genre AND m.title LIKE CONCAT('%', :title, '%')";
        $execute_moviec = [
            'distributor' => $distributors_name,
            'genre' => $genre_names,
            'title' => $title_film,
        ];
    } else if (isset($distributors_name)) {
        $requete_moviec = "SELECT count(m.id) AS 'cm' FROM movie m JOIN distributor d ON d.id = m.id_distributor WHERE d.name LIKE CONCAT('%', :distributor, '%') AND m.title LIKE CONCAT('%', :title, '%')";
        $execute_moviec = [
            'distributor' => $distributors_name,
            'title' => $title_film,
        ];
    } else if (isset($genre_names)) {
        $requete_moviec = "SELECT count(m.id) AS 'cm' FROM movie m JOIN movie_genre mg ON m.id = mg.id_movie JOIN genre g ON mg.id_genre = g.id WHERE g.name = :genre AND m.title LIKE CONCAT('%', :title, '%')";
        $execute_moviec = [
            'genre' => $genre_names,
            'title' => $title_film,
        ];
    } else {
        $requete_moviec = "SELECT count(id) AS 'cm' FROM movie WHERE title LIKE CONCAT('%', :title, '%')";
        $execute_moviec = [
            'title' => $title_film,
        ];
    };

    $movie_countd = $mysqlClient->prepare($requete_moviec);
    $movie_countd->execute($execute_moviec);
    $movie_count = $movie_countd->fetchAll(PDO::FETCH_ASSOC);

    @$page = $_GET['page'];

    foreach ($_POST['element'] as $getelementpage) {
        $getelementpage = $getelementpage;
    }




    if (isset($getelementpage)) {
        if ($getelementpage == "all") {
            $nbr_element_page = ($movie_count[0]['cm']);
        } else {
            $nbr_element_page = $getelementpage;
        }
    } else {
        if (isset($_GET['ide'])) {
            $nbr_element_page = $_GET['ide'];
        } else {
            $nbr_element_page = 15;
            header("Location: index.php?page=1&ide=$nbr_element_page");
        }
    }

    $nbr_de_page = ceil($movie_count[0]["cm"] / $nbr_element_page);
    $debut = ($page - 1) * $nbr_element_page;



    if (isset($distributors_name) && isset($genre_names)) {
        $requete_movie = "SELECT m.*, d.*, g.name as genrename FROM movie m JOIN distributor d ON d.id = m.id_distributor  JOIN movie_genre mg ON m.id = mg.id_movie JOIN genre g ON mg.id_genre = g.id WHERE d.name LIKE CONCAT('%', :distributor, '%') AND g.name = :genre AND m.title LIKE CONCAT('%', :title, '%') LIMIT $debut, $nbr_element_page";
        $execute_movie = [
            'distributor' => $distributors_name,
            'genre' => $genre_names,
            'title' => $title_film,
        ];
    } else if (isset($distributors_name)) {
        $requete_movie = "SELECT m.*, d.*, g.name as genrename FROM movie m JOIN distributor d ON d.id = m.id_distributor JOIN movie_genre mg ON m.id = mg.id_movie JOIN genre g on mg.id_genre = g.id WHERE d.name LIKE CONCAT('%', :distributor, '%') AND m.title LIKE CONCAT('%', :title, '%') LIMIT $debut, $nbr_element_page";
        $execute_movie = [
            'distributor' => $distributors_name,
            'title' => $title_film,
        ];
        $genre_names = "Aucun";
    } else if (isset($genre_names)) {
        $requete_movie = "SELECT m.*, g.name as genrename, d.* FROM movie m JOIN movie_genre mg ON m.id = mg.id_movie JOIN genre g ON mg.id_genre = g.id JOIN distributor d ON d.id = m.id_distributor WHERE g.name = :genre AND m.title LIKE CONCAT('%', :title, '%') LIMIT $debut, $nbr_element_page";
        $execute_movie = [
            'genre' => $genre_names,
            'title' => $title_film,
        ];
        $distributors_name = "Aucun";
    } else {
        $requete_movie = "SELECT m.*, d.*, g.name as genrename FROM movie m JOIN movie_genre mg ON mg.id_movie = m.id JOIN genre g ON g.id = mg.id_genre JOIN distributor d ON d.id = m.id_distributor WHERE m.title LIKE CONCAT('%', :title, '%')  LIMIT $debut, $nbr_element_page";
        $execute_movie = [
            'title' => $title_film,
        ];
        $distributors_name = "Aucun";
        $genre_names = "Aucun";
    }

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



    <form action=<?php echo "index.php?page=1&ide=$nbr_element_page" ?> method="post">
        <div class="navBack">
            <?php include_once('nav.php'); ?>
            <div class="searchBar">
                <div class="searchBarr">
                    <input type="search" name="search" placeholder="Taper le film ici" id="search">
                    <input type="submit" id="btn-search" value="🔎">
                </div>
                <div class="searchCount">
                    <h1><?php echo "Résultat : <span style='color:aqua;'>" . $movie_count[0]["cm"] . ""; ?></h1>

                </div>
            </div>

        </div>

        <div class="grid-filtre-titre">
            <h2>Genres</h2>
            <h2>Producteur</h2>
        </div>
        <div class="grid-filtre">

            <div class="genreMain">
                <?php foreach ($genre as $value) : ?>
                    <div class="genrediv">
                        <input type="checkbox" name="genre[]" id="genre" value="<?php echo $value['name']; ?>">
                        <label for=<?php echo $value['name']; ?>>
                            <p><?php echo $value['name']; ?></p>
                        </label>
                    </div>
                <?php endforeach ?>
            </div>

            <div class="distributorMain">

                <?php foreach ($distributor as $values) : ?>
                    <div class="distributordiv">
                        <input type="checkbox" name="distributor[]" id="distributor" value='<?php echo $values['name']; ?>' />
                        <label for=<?php echo $values['name']; ?>>
                            <p><?php echo $values['name']; ?></p>
                        </label>
                    </div>
                <?php endforeach ?>
            </div>

        </div>

    </form>

    <section class="background-color-black">
        <div class="paginationMain">
            <div class="listfilm">
                <?php for ($i = 0; $i < count($data_bdd); $i++) : ?>
                    <?php
                    $rating = str_replace("PG", "", $data_bdd[$i]['rating']);
                    $rating = str_replace("-", "", $rating);
                    $rating = str_replace("G", "", $rating);
                    $rating = str_replace("R", "", $rating);


                    if ($rating == "") {
                        $rating = "<span style='color:greenYELLOW;'>AUCUN</span>";
                    } else {
                        $rating = $rating . " ans";
                    }

                    $minute = $data_bdd[$i]['duration'];
                    $heure = floor($minute / 60);
                    $minute_restant = $minute % 60;
                    $duree = $heure . "h" . $minute_restant;
                    if ($duree == "0h0") {
                        $duree = "<span style='color:red;'>NON DISPONNIBLE</span>";
                    }
                    ?>
                    <a href="">
                        <div class="film">
                            <img src="img/film.jpg" alt="<?php echo  $data_bdd[$i]['title']; ?>" srcset="">
                            <h6><?php echo  $data_bdd[$i]['title']; ?></h6>
                            <div class="infoFilm">
                                <div class="btn-Distrib"><?php echo  $data_bdd[$i]['name']; ?></div>
                                <div class="btn-Genre"><?php echo  $data_bdd[$i]['genrename']; ?></div>
                            </div>
                            <ul>
                                <li>Pegi : <?php echo $rating ?></li>
                                <li>Durée : <?php echo $duree; ?></li>
                                <li>Director : <?php echo $data_bdd[$i]['director']; ?></li>
                            </ul>
                        </div>
                    </a>

                <?php endfor; ?>
            </div>

            <form action="" method="post">
                <label for="element-select">Nombre element sur la page </label>
                <select name="element[]" id="element-select">
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="25">35</option>
                    <option value="25">45</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="all">Tout</option>
                </select>
                <input type="submit" value="Envoyer">
            </form>

            <div class="nombredePage">
                <?php for ($i = 1; $i <= $nbr_de_page; $i++) {
                    echo "<a href='?page=$i&ide=$nbr_element_page'><button class='btnPag'>$i</button></a>&nbsp";
                } ?>
            </div>
        </div>
        <?php include_once('footer.php') ?>

    </section>

    <script src="script.js"></script>
</body>

</html>