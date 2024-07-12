<?php

session_start();

if ($_SESSION['LOGIN_USER']['admin'] != 1) {
    header('Location: ../index.php');
    exit;
}


include_once("../mysql.php");

$getDataId = $_GET["id_user"];
$getDate = $_GET["date"];
$checkFilmId = $_GET["film_id"];


if (isset($checkFilmId)) {

    foreach ($checkFilmId as $checkFilmIds) {
        $checkFilmIds = $checkFilmIds;
    }

    $movie_schedules = $mysqlClient->prepare('SELECT ms.* FROM movie m JOIN movie_schedule ms ON ms.id_movie = m.id WHERE ms.id = :id');
    $movie_schedules->execute([
        'id' => $checkFilmIds,
    ]);

    $movie_schedule = $movie_schedules->fetchAll(PDO::FETCH_ASSOC);

    foreach ($movie_schedule as $film) {
        $id_session = $film['id'];
    }

    $membership_data = $mysqlClient->prepare('SELECT * FROM membership WHERE id_user = :id_user');
    $membership_data->execute([
        'id_user' => $getDataId,
    ]);

    $membership = $membership_data->fetchAll(PDO::FETCH_ASSOC);

    foreach ($membership as $memberships) {
        $id_membership = $memberships['id'];
    }

    $membership_data = $mysqlClient->prepare('INSERT INTO `membership_log`(`id_membership`, `id_session`) VALUES (:id_membership, :id_session)');
    $membership_data->execute([
        'id_membership' => $id_membership,
        'id_session' => $id_session,
    ]);
}
if (isset($getDate)) {
    $date_Data = $mysqlClient->prepare('SELECT m.title, ms.id_movie, ms.date_begin, ms.id, r.name, r.floor, r.seats FROM movie_schedule ms JOIN movie m ON m.id = ms.id_movie JOIN room r ON ms.id_room = r.id WHERE date_begin LIKE CONCAT("%", :dateB, "%") ORDER BY ms.date_begin DESC');
    $date_Data->execute([
        'dateB' => $getDate,
    ]);
    $date_film = $date_Data->fetchAll(PDO::FETCH_ASSOC);
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/style_new.css">
    <link rel="stylesheet" href="../style/user_read.css">
    <link rel="stylesheet" href="../style//add_history.css">
    <link rel="stylesheet" href="../style/nav.css">
</head>

<body>
    <div class="navBack">
        <?php include_once('nav_admin.php'); ?>
    </div>
    <div class="backImg">
        <div>
            <a href="user_read.php?id=<?php echo ($getDataId) ?>"> <img src="../img/back.png" alt=""> </a>
        </div>
    </div>

    <div class="searchNav">
        <form action="add_history.php" method="get">
            <input type="date" name="date" id="date" min="2018-01-01" max="2018-12-31">
            <input type="number" name="id_user" id="id_user" value="<?php echo ($getDataId) ?>" style="display: none;">
            <input type="submit" value="Rechercher Séance">
        </form>
    </div>
    <div class="listhistory">
        <form action="add_history.php" method="get">
            <input type="number" name="id_user" id="id_user" value="<?php echo ($_GET["id_user"]) ?>" style="display: none;">
            <div class="searchNav">
                <input type="submit" value="Ajouter la séance dans l'historique">
            </div>
            <table>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>ID MOVIE</th>
                    <th>TITRE FILM</th>
                    <th>NOM SALLE</th>
                    <th>ETAGE DE LA SALLE</th>
                    <th>NOMBRE PLACE SALLE</th>
                    <th>DEBUT DE LA SEANCE</th>
                </tr>
                <? foreach ($date_film as $film_aff) : ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="film_id[]" id="film_id" value="<?php echo $film_aff['id']; ?>">
                        </td>
                        <td>
                            <label for=<?php echo $film_aff['title']; ?>><?php echo $film_aff['id']; ?></label>
                        </td>
                        <td>
                            <label for=<?php echo $film_aff['title']; ?>><?php echo $film_aff['id_movie']; ?></label>
                        </td>
                        <td>
                            <label for=<?php echo $film_aff['title']; ?>><?php echo $film_aff['title'] ?></label>
                        </td>
                        <td>
                            <label for=<?php echo $film_aff['title']; ?>><?php echo $film_aff['name'] ?></label>
                        </td>
                        <td>
                            <label for=<?php echo $film_aff['title']; ?>><?php echo $film_aff['floor'] ?></label>
                        </td>
                        <td>
                            <label for=<?php echo $film_aff['title']; ?>><?php echo $film_aff['seats'] ?></label>
                        </td>
                        <td>
                            <label for=<?php echo $film_aff['title']; ?>><?php echo $film_aff['date_begin'] ?></label>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>


        </form>
    </div>


</body>

</html>