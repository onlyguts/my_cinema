<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style_new.css">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style//avis.css">
</head>
<?php
include_once('mysql.php');
session_start();
$enabletoComment = false;
if (isset($_SESSION['LOGIN_USER']['id'])) {
    $enabletoComment = true;
}

$sql = $mysqlClient->prepare("SELECT ROUND(AVG(rating), 2) as rating_avg FROM avis WHERE enable = 1");
$sql->execute([]);
$avg = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($avg as $avgs){
$avg_rating = $avgs['rating_avg'];
}
$sql = $mysqlClient->prepare("SELECT a.id, u.firstname, a.description, a.rating, a.id_user, a.date_create FROM avis a JOIN user u ON u.id = a.id_user WHERE enable = 1");
$sql->execute([]);
$avis_users = $sql->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['description']) && isset($_POST['rating'])) {
    $sql = $mysqlClient->prepare("INSERT INTO `avis`(`id_user`, `description`, `rating`, `date_create`, `enable`) VALUES (:id_user, :descriptiontext, :rating, NOW(), 0)");
    $sql->execute([
        "id_user" => $_SESSION['LOGIN_USER']['id'],
        "descriptiontext" => $_POST['description'],
        "rating" => $_POST['rating'],
    ]);
    $_SESSION['MESSAGE_SUCCES'] = "Votre commentaires va être examiner par un administrateur";
}


?>

<body>
    <div class="navBack">
        <section class="navMain">
            <nav>
                <div>
                    <a href="index.php"><img src="img/logo.png" alt="" style="width: 50%" ;></a>
                </div>
                <ul>
                    <li><a href="search_seance.php">Chercher une séance</a></li>
                    <li><a href="avis.php">Avis</a></li>
                    <?php if (isset($_SESSION['LOGIN_USER']['id'])) : ?>
                        <li class="deroulant" id="nav"><a href="#">Salut, <?php echo $_SESSION['LOGIN_USER']['firstname'] ?></a>
                            <ul class="sous">
                                <li><a href="user/profil.php">Profil</a></li>
                                <li><a href="login/logout.php">Deconnexion</a></li>
                            </ul>
                        </li>
                    <?php else : ?>
                        <li><a href="login/login_register.php">Connexion</a></li>
                    <?php endif; ?>
                    <?php if ($_SESSION['LOGIN_USER']['admin'] == 1) : ?>
                        <li><a href="user/admin.php">Panel Admin</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

        </section>
        <h1>Moyenne avis : <span style="color:greenyellow"><?php echo $avg_rating?></span></h1>
    </div>
    <?php session_start();
    if (isset($_SESSION['MESSAGE_SUCCES'])) : ?>
        <div class="warning">
            <div class="warning-bar">
                <img src="img/succes-icon.png" alt="">
                <p>
                    <?php
                    echo $_SESSION['MESSAGE_SUCCES'];
                    unset($_SESSION['MESSAGE_SUCCES']);
                    ?>
                </p>
            </div>
        </div>

    <?php endif; ?>
    <div class="avisMain">
        <?php foreach ($avis_users as $avis) : ?>
            <div class="avis">
                <ul>
                    <li><span style="font-weight: bold;"> Numéro :</span> <?php echo $avis['id']; ?></li>
                    <li><span style="font-weight: bold;">Prénom :</span> <?php echo $avis['firstname']; ?></li>
                    <li><span style="font-weight: bold;">Note :</span> <?php echo $avis['rating']; ?></li>
                    <li><span style="font-weight: bold;">Commentaire :</span> <br> <?php echo $avis['description']; ?></li>
                    <li><p>Date du commentaire : <?php echo $avis['date_create']; ?></p></li>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($enabletoComment == true) : ?>
        <form action="" method="post">
            <label for="description">Poster un commentaire : </label>
            <textarea id="description" name="description" rows="5" cols="33"></textarea>
            <label for="rating">Note ( sur 5 )</label>
            <input type="number" name="rating" id="rating" max="5">
            <input type="submit" value="Envoyer">


        </form>
    <?php endif; ?>
</body>

</html>