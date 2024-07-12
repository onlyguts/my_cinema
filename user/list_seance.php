<!-- <?php /*
include_once('../mysql.php');



$user_count = $mysqlClient->prepare('SELECT count(m.id) as user_count FROM movie_schedule ms JOIN movie m ON m.id = ms.id_movie JOIN room r ON r.id = ms.id_room ORDER BY date_begin DESC');
$user_count->execute([]);
$userc = $user_count->fetchAll(PDO::FETCH_ASSOC);

@$page = $_GET['page'];

foreach ($_POST['element'] as $getelementpage) {
    $getelementpage = $getelementpage;
}

if (isset($getelementpage)) {
    $nbr_element_page = $getelementpage;
} else {
    if (isset($_GET['ide'])) {
        $nbr_element_page = $_GET['ide'];
    } else {
        $nbr_element_page = 10;
        header("Location: list_seance.php?page=1&ide=$nbr_element_page");
    }
}
$nbr_de_page = ceil($userc[0]['user_count'] / $nbr_element_page);
$debut = ($page - 1) * $nbr_element_page;

$user_data = $mysqlClient->prepare("SELECT ms.id, m.title, ms.date_begin, r.name, r.floor, r.seats FROM movie_schedule ms JOIN movie m ON m.id = ms.id_movie JOIN room r ON r.id = ms.id_room ORDER BY date_begin DESC LIMIT $debut, $nbr_element_page");
$user_data->setFetchMode(PDO::FETCH_ASSOC);
$user_data->execute();
$user = $user_data->fetchAll(); */

        ?>
-->
<?php

session_start();

if ($_SESSION['LOGIN_USER']['admin'] != 1) {
    header('Location: ../index.php');
    exit;
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <link rel="stylesheet" href="../style/style_new.css">
    <link rel="stylesheet" href="../style/user.css">
    <link rel="stylesheet" href="../style/nav.css">
</head>

<body>
    <?php
    include_once('../mysql.php');

    $getData = $_POST;

    $gettitleMovie = $_GET['title'];

    if (!isset($gettitleMovie)) {
        $gettitleMovie = "";
    } else {
        $gettitleMovie = $_GET['title'];
    }
    $user_count = $mysqlClient->prepare("SELECT count(m.id) as user_count FROM movie_schedule ms JOIN movie m ON m.id = ms.id_movie JOIN room r ON r.id = ms.id_room WHERE m.title LIKE CONCAT('%', :nametext, '%') OR ms.date_begin LIKE CONCAT('%', :nametext, '%')  OR r.name LIKE CONCAT('%', :nametext, '%')  ORDER BY ms.date_begin DESC");
    $user_count->execute([
        'nametext' => $gettitleMovie,
    ]);
    $userc = $user_count->fetchAll(PDO::FETCH_ASSOC);

    @$page = $_GET['page'];

    foreach ($_POST['element'] as $getelementpage) {
        $getelementpage = $getelementpage;
    }

    if (isset($getelementpage)) {
        $nbr_element_page = $getelementpage;
    } else {
        if (isset($_GET['ide'])) {
            $nbr_element_page = $_GET['ide'];
        } else {
            $nbr_element_page = 10;
            header("Location: list_seance.php?page=1&ide=$nbr_element_page&title=$gettitleMovie");
        }
    }

    $nbr_de_page = ceil($userc[0]['user_count'] / $nbr_element_page);
    $debut = ($page - 1) * $nbr_element_page;

    $user_data = $mysqlClient->prepare("SELECT ms.id, m.title, ms.date_begin, r.name, r.floor, r.seats FROM movie_schedule ms JOIN movie m ON m.id = ms.id_movie JOIN room r ON r.id = ms.id_room WHERE m.title LIKE CONCAT('%', :nametext, '%') OR ms.date_begin LIKE CONCAT('%', :nametext, '%') OR r.name LIKE CONCAT('%', :nametext, '%') ORDER BY ms.date_begin DESC LIMIT $debut, $nbr_element_page");
    $user_data->execute([
        'nametext' => $gettitleMovie,
    ]);

    $users = $user_data->fetchAll(PDO::FETCH_ASSOC);


    ?>
    <div class="navBack">
        <?php include_once('nav_admin.php'); ?>

        <section class="search_name">
            <form action="list_seance.php" method="get">
                <input type="search" name="title" placeholder="Taper le titre du film ou date, salle" id="title">
                <input type="submit" class="searchBtn" id="btn-search" value="🔎">
            </form>
            <form action="list_seance.php" method="post">
                <input type="submit" class="refreshBtn" id="btn-search" value="♻️">
            </form>


        </section>

        <div class="searchCount">
            <h1><?php echo "Résultat : <span style='color:aqua;'>" . $userc[0]["user_count"] . ""; ?></h1>
        </div>
    </div>



    <section class="wrapper">
        <div class="nombreElement">

        </div>
        <table>
            <tbody>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">TITRE DU FILM</th>
                    <th scope="col">NOM SALLE</th>
                    <th scope="col">ETAGE SALLE</th>
                    <th scope="col">SIEGE SALLE</th>
                    <th scope="col">DEBUT DE LA SEANCE</th>
                    <th scope="col">
                        <form action="" method="post">
                            <select name="element[]" id="element-select">
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <input type="submit" value="Envoyer" id="sumbit">
                        </form>
                    </th>

                </tr>
                <?php for ($i = 0; $i < count($users); $i++) : ?>
                    <tr>
                        <td class='id_user' scope="row"><?php echo $users[$i]['id'] ?></td>
                        <td><?php echo $users[$i]['title'] ?></td>
                        <td><?php echo $users[$i]['name'] ?></td>
                        <td><?php echo $users[$i]['floor'] ?></td>
                        <td><?php echo $users[$i]['seats'] ?></td>
                        <td><?php echo $users[$i]['date_begin'] ?></td>
                       
                        <td class="supprimer"><a href="seance_delete.php?id=<?php echo ($users[$i]['id']); ?>">🗑️</a></td>
                    </tr>
                <?php endfor; ?>


            </tbody>
        </table>
        <div class="pageMain">
            <div class="nombredePage">
                <?php for ($i = 1; $i <= $nbr_de_page; $i++) {
                    echo "<a href='?page=$i&ide=$nbr_element_page&title=$gettitleMovie'><button class='btnPag'>$i</button></a>&nbsp";
                } ?>
            </div>
        </div>
    </section>
    <?php include_once('../footer.php') ?>
</body>

</html>