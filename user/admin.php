<?php

session_start();

if ($_SESSION['LOGIN_USER']['admin'] != 1) {
    header('Location: ../index.php');
    exit;
}


include_once('../mysql.php');
$sql = $mysqlClient->prepare("SELECT COUNT(id) as count FROM user");
$sql->execute([]);

$users = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $count) {
    $countUser = $count['count'];
}
$sql = $mysqlClient->prepare("SELECT COUNT(u.id) as count FROM user u JOIN employee e ON e.id_user = u.id");
$sql->execute([]);

$employee = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($employee as $count) {
    $countEmployee = $count['count'];
}
$sql = $mysqlClient->prepare("SELECT COUNT(id) as count FROM movie");
$sql->execute([]);

$movie = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($movie as $count) {
    $countMovie = $count['count'];
}
$sql = $mysqlClient->prepare("SELECT COUNT(id) as count FROM genre");
$sql->execute([]);

$genre = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($genre as $count) {
    $countGenre = $count['count'];
}
$sql = $mysqlClient->prepare("SELECT COUNT(id) as count FROM distributor");
$sql->execute([]);

$distributor = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($distributor as $count) {
    $countDistributor = $count['count'];
}
$sql = $mysqlClient->prepare("SELECT COUNT(id) as count FROM job");
$sql->execute([]);

$job = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($job as $count) {
    $countJob = $count['count'];
}
$sql = $mysqlClient->prepare("SELECT COUNT(id) as count FROM movie_schedule");
$sql->execute([]);

$seance = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($seance as $count) {
    $countSeance = $count['count'];
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/style_new.css">
    <link rel="stylesheet" href="../style/user.css">
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="../style/admin.css">
</head>


<body>
    <div class="navBack">
        <section class="navMain">
            <nav>
                <div>
                    <a href="../index.php"><img src="../img/logo.png" alt=""></a>
                </div>
                <ul>
                    <li><a href="../index.php">HOME CINEMA</a></li>
                    <li class="deroulant" id="nav"><a href="#">Utilisateur</a>
                        <ul class="sous">
                            <li><a href="user.php">Liste Utilisateur</a></li>
                            <li><a href="employee.php">Liste Employés</a></li>
                        </ul>
                    </li>
                    <li class="deroulant" id="nav"><a href="#">Séance</a>
                        <ul class="sous">
                            <li><a href="seance.php">Créer une séance</a></li>
                            <li><a href="list_seance.php">Liste des séances</a></li>
                        </ul>
                    </li>
                    <li class="deroulant" id="nav"><a href="#">Avis</a>
                        <ul class="sous">
                        <li><a href="avis_admin.php">Accepter les avis en attente</a></li>
                            <li><a href="list_avis.php">Liste des avis</a></li>
                        </ul>
                    </li>
             
                </ul>
            </nav>
        </section>
        <div class="title_admin">
            <h1>Panel Admin</h1>
            <h2>Bienvenue <?php echo $_SESSION['LOGIN_USER']['firstname'] ?> !</h2>
        </div>
    </div>
    <section class="stats">

        <table>
            <tr>
                <th>Nombre Utilisateur</th>
                <th>Nombre Employé</th>
                <th>Nombre Film</th>
                <th>Nombre Genre</th>
                <th>Nombre Production</th>
                <th>Nombre Job</th>
                <th>Nombre Séance</th>
            </tr>
            <tr>
                <td><?php echo $countUser ?></td>
                <td><?php echo $countEmployee ?></td>
                <td><?php echo $countMovie ?></td>
                <td><?php echo $countGenre ?></td>
                <td><?php echo $countDistributor ?></td>
                <td><?php echo $countJob ?></td>
                <td><?php echo $countSeance ?></td>
            </tr>
        </table>



    </section>
 
</body>

</html>