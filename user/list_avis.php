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
        header("Location: list_avis.php?page=1&ide=$nbr_element_page");
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
    $user_count = $mysqlClient->prepare("SELECT count(a.id) as user_count FROM avis a JOIN user u ON u.id = a.id_user WHERE u.firstname LIKE CONCAT('%', :nametext, '%') or u.lastname LIKE CONCAT('%', :nametext, '%') or a.description LIKE CONCAT('%', :nametext, '%') or a.date_create LIKE CONCAT('%', :nametext, '%')");
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
            header("Location: list_avis.php?page=1&ide=$nbr_element_page&title=$gettitleMovie");
        }
    }

    $nbr_de_page = ceil($userc[0]['user_count'] / $nbr_element_page);
    $debut = ($page - 1) * $nbr_element_page;

    $user_data = $mysqlClient->prepare("SELECT a.rating, a.description, a.date_create, a.id, a.enable, u.firstname, u.lastname, u.id as user_id FROM avis a JOIN user u ON u.id = a.id_user WHERE u.firstname LIKE CONCAT('%', :nametext, '%') or u.lastname LIKE CONCAT('%', :nametext, '%') or a.description LIKE CONCAT('%', :nametext, '%') or a.date_create LIKE CONCAT('%', :nametext, '%') LIMIT $debut, $nbr_element_page");
    $user_data->execute([
        'nametext' => $gettitleMovie,
    ]);

    $users = $user_data->fetchAll(PDO::FETCH_ASSOC);


    ?>
    <div class="navBack">
        <?php include_once('nav_admin.php'); ?>

        <section class="search_name">
            <form action="list_avis.php" method="get">
                <input type="search" name="title" placeholder="Taper le Nom ou Prenom,Date,Commentaires " id="title">
                <input type="submit" class="searchBtn" id="btn-search" value="🔎">
            </form>
            <form action="list_avis.php" method="post">
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
                    <th scope="col">COMMENTAIRE</th>
                    <th scope="col">NOTE</th>
                    <th scope="col">NOM : PRENOM</th>
                    <th scope="col">ET ACTIVER</th>
                    <th scope="col">DATE DU POST</th>
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

                        <td><?php echo $users[$i]['description'] ?></td>
                        <td><?php echo $users[$i]['rating'] ?></td>
                        <td><?php echo $users[$i]['firstname'] . " " . $users[$i]['lastname'] ?></td>
                        <td><?php echo $users[$i]['enable'] ?></td>
                        <td><?php echo $users[$i]['date_create'] ?></td>
                       
                        <td class="supprimer"><a href="avis_delete.php?id=<?php echo ($users[$i]['id']); ?>">🗑️</a></td>
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