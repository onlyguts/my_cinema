<?php


session_start();

if ($_SESSION['LOGIN_USER']['admin'] != 1) {
    header('Location: ../index.php');
    exit;
}


include_once('../mysql.php');

$seance_data = $mysqlClient->prepare('SELECT * FROM movie_schedule');
$seance_data->execute([]);

$seance = $seance_data->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['search'])) {
    $title_film = $_POST['search'];
} else {
    $title_film = "";
}

$movie_data = $mysqlClient->prepare('SELECT * FROM movie WHERE title LIKE CONCAT("%", :title, "%")');
$movie_data->execute([
    "title" => $title_film,
]);

$movie = $movie_data->fetchAll(PDO::FETCH_ASSOC);


$room_data = $mysqlClient->prepare('SELECT * FROM room');
$room_data->execute([]);

$room = $room_data->fetchAll(PDO::FETCH_ASSOC);

foreach ($_GET['film'] as $filmas) {
    $filmas = $filmas;
}
foreach ($_GET['room'] as $roomas) {
    $roomas = $roomas;
}
$date =  $_GET['date'];


if ($date != "" && $roomas != "" && $filmas != "") {
    $membership_data = $mysqlClient->prepare(' INSERT INTO `movie_schedule`(`id_movie`, `id_room`, `date_begin`) VALUES (:id_movie, :id_room, :date_begin)');
    $membership_data->execute([
        'id_movie' => $filmas,
        'id_room' => $roomas,
        'date_begin' => $date,
    ]);
    $_SESSION['MESSAGE_SUCCESS'] = "Séance ajouté ! pour le " . $date;
} else {
    if (isset($date)) {
        $_SESSION['MESSAGE_ERREUR'] = "Vieullez remplir le formulaire !";
    }
 
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/style_new.css">
    <link rel="stylesheet" href="../style//sc_new.css">
    <link rel="stylesheet" href="../style/nav.css">

</head>

<body>
    <div class="navBack">
        <?php include_once('nav_admin.php'); ?>

        <section class="search_name">
            <form action="seance.php" method="post">
                <input type="search" name="search" placeholder="Taper le du film ici" id="search">
                <input type="submit" class="searchBtn" id="btn-search" value="🔎">
            </form>
        </section>

    </div>

    <?php session_start();
    if (isset($_SESSION['MESSAGE_SUCCESS'])) : ?>
        <div class="warning">
            <div class="warning-bar">
                <img src="../img/succes-icon.png" alt="">
                <p>
                    <?php
                    echo $_SESSION['MESSAGE_SUCCESS'];
                    unset($_SESSION['MESSAGE_SUCCESS']);
                    ?>
                </p>
            </div>
        </div>
        <?php endif; ?>
        <?php session_start();
        if (isset($_SESSION['MESSAGE_ERREUR'])) : ?>
            <div class="warning2">
                <div class="warning-bar">
                    <img src="../img/warning-icon.png" alt="">
                    <p>
                        <?php
                        echo $_SESSION['MESSAGE_ERREUR'];
                        unset($_SESSION['MESSAGE_ERREUR']);
                        ?>
                    </p>
                </div>
            </div>

      
    <?php endif; ?>
    <form action="">
        <h1>Film</h1>
        <div class="movie_seancediv">
            <?php foreach ($movie as $movies) : ?>
                <div>
                    <input type="checkbox" name="film[]" id="film" value="<?php echo $movies['id']; ?>">
                    <label for=<?php echo $movies['title']; ?>><?php echo $movies['title']; ?></label>
                </div>
            <? endforeach; ?>
        </div>
        <h1>Salle</h1>
        <div class="room_seancediv">
            <?php foreach ($room as $rooms) : ?>
                <div>
                    <input type="checkbox" name="room[]" id="room" value="<?php echo $rooms['id']; ?>">
                    <label for=<?php echo $rooms['name']; ?>><?php echo "Nom : " . $rooms['name'] . " | Place : " . $rooms['seats']  . " | Etage : " . $rooms['floor']; ?></label>
                </div>
            <? endforeach; ?>
        </div>
        <h1>Date de projection</h1>
        <input type="datetime-local" name="date" id="date"> <br>
        <input type="submit" value="Ajouter">
    </form>
</body>

</html>