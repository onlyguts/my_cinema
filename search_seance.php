<?php
session_start();
include_once('mysql.php');
$ifDate = $_POST['date'];

if (!isset($_POST['date'])) {
    $ifDate = '';
}

$movie_today_data = $mysqlClient->prepare('SELECT ms.*, m.title, r.name, m.director, d.name as distributorname, g.name as genrename, m.duration, m.rating, DATE_FORMAT(ms.date_begin, "%H:%i") as time_movie FROM movie_schedule ms JOIN movie m ON m.id = ms.id_movie JOIN room r ON r.id = ms.id_room JOIN movie_genre mg ON mg.id_movie = m.id JOIN genre g ON g.id = mg.id_genre JOIN distributor d ON d.id = m.id_distributor WHERE date_begin LIKE CONCAT("%", DATE(NOW()), "%") AND m.title LIKE CONCAT("%", :title, "%")');
$movie_today_data->execute([
    "title" => '',
]);
$movie_today = $movie_today_data->fetchAll(PDO::FETCH_ASSOC);


$film_data = $mysqlClient->prepare('SELECT ms.*, DATE_FORMAT(ms.date_begin, "%H:%i") as time_movie, m.title, m.director, d.name as distributorname, g.name as genrename, m.duration, m.rating FROM movie_schedule ms JOIN movie m ON m.id = ms.id_movie JOIN movie_genre mg ON mg.id_movie = m.id JOIN genre g ON g.id = mg.id_genre JOIN distributor d ON d.id = m.id_distributor WHERE date_begin LIKE CONCAT("%" ,:dateTxt, "%") LIMIT 8');
$film_data->execute([
    'dateTxt' => $ifDate,
]);
$film = $film_data->fetchAll(PDO::FETCH_ASSOC);

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style_new.css">
    <link rel="stylesheet" href="style/search_seance.css">
    <link rel="stylesheet" href="style/nav.css">
</head>

<body>
    <div class="navBack">
        <?php include_once('nav.php'); ?>
        <div class="search_seance">
            <h1>Chercher une séance disponnible</h1>
            <form action="" method="post">
                <input type="date" name="date" id="date">
                <input type="submit" id="btn-search-date" value="🔎">
            </form>
        </div>
    </div>

    <section class="background-color-black">
        <?php if ($ifDate == '') : ?>
            <h2>Film du jour</h2>
        <?php endif; ?>
        <div class="paginationMain">

            <div class="listfilm">
                <?php if ($ifDate == '') : ?>

                    <?php foreach ($movie_today as $films) : ?>
                        <?php
                        $rating = str_replace("PG", "", $films['rating']);
                        $rating = str_replace("-", "", $rating);
                        $rating = str_replace("G", "", $rating);
                        $rating = str_replace("R", "", $rating);


                        if ($rating == "") {
                            $rating = "<span style='color:greenYELLOW;'>AUCUN</span>";
                        } else {
                            $rating = $rating . " ans";
                        }

                        $minute = $films['duration'];
                        $heure = floor($minute / 60);
                        $minute_restant = $minute % 60;
                        $duree = $heure . "h" . $minute_restant;
                        if ($duree == "0h0") {
                            $duree = "<span style='color:red;'>NON DISPONNIBLE</span>";
                        }
                        $time_movie = str_replace(":", "h", $films['time_movie']);
                        ?>
                        <a href="">
                            <div class="film">
                                <img src="img/film.jpg" alt="<?php echo  $films['title']; ?>" srcset="">
                                <h6><?php echo $films['title']; ?></h6>
                                <div class="infoFilm">
                                    <div class="btn-Distrib"><?php echo  $films['distributorname']; ?></div>
                                    <div class="btn-Genre"><?php echo  $films['genrename']; ?></div>
                                </div>
                                <ul>
                                    <li>Pegi : <?php echo $rating ?></li>
                                    <li>Durée : <?php echo $duree; ?></li>
                                    <li>Director : <?php echo $films['director']; ?></li>
                                    <li>Début de la séance : <?php echo $time_movie; ?></li>
                                </ul>
                            </div>
                        </a>

                    <?php endforeach; ?>
                <?php else : ?>
                    <?php foreach ($film as $films) : ?>
                        <?php
                        $rating = str_replace("PG", "", $films['rating']);
                        $rating = str_replace("-", "", $rating);
                        $rating = str_replace("G", "", $rating);
                        $rating = str_replace("R", "", $rating);


                        if ($rating == "") {
                            $rating = "<span style='color:greenYELLOW;'>AUCUN</span>";
                        } else {
                            $rating = $rating . " ans";
                        }

                        $minute = $films['duration'];
                        $heure = floor($minute / 60);
                        $minute_restant = $minute % 60;
                        $duree = $heure . "h" . $minute_restant;
                        if ($duree == "0h0") {
                            $duree = "<span style='color:red;'>NON DISPONNIBLE</span>";
                        }
                        $time_movie = str_replace(":", "h", $films['time_movie']);
                        ?>
                        <a href="">
                            <div class="film">
                                <img src="img/film.jpg" alt="<?php echo  $films['title']; ?>" srcset="">
                                <h6><?php echo $films['title']; ?></h6>
                                <div class="infoFilm">
                                    <div class="btn-Distrib"><?php echo  $films['distributorname']; ?></div>
                                    <div class="btn-Genre"><?php echo  $films['genrename']; ?></div>
                                </div>
                                <ul>
                                    <li>Pegi : <?php echo $rating ?></li>
                                    <li>Durée : <?php echo $duree; ?></li>
                                    <li>Director : <?php echo $films['director']; ?></li>
                                    <li>Début de la séance : <?php echo $time_movie; ?></li>
                                </ul>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php include_once('footer.php'); ?>
</body>

</html>