<?php
include_once('../mysql.php');
session_start();
if (!isset($_SESSION['LOGIN_USER']['id'])) {
    header('Location: ../index.php');
    exit;
}
$getUserID = $mysqlClient->prepare('SELECT * FROM user WHERE id = :id');
$getUserID->execute([
    'id' => $_SESSION['LOGIN_USER']['id'],
]);
$getUser = $getUserID->fetch(PDO::FETCH_ASSOC);

$history_logs = $mysqlClient->prepare('SELECT u.firstname, u.lastname, ms.date_begin, ms.id_room, m.title FROM membership mb JOIN membership_log mbl ON mbl.id_membership = mb.id JOIN user u ON u.id = mb.id_user JOIN movie_schedule ms ON ms.id = mbl.id_session JOIN movie m ON m.id = ms.id_movie WHERE mb.id_user = :id ORDER BY ms.date_begin DESC');
$history_logs->execute([
    'id' => $_SESSION['LOGIN_USER']['id'],
]);
$history = $history_logs->fetchAll(PDO::FETCH_ASSOC);

$history_logss = $mysqlClient->prepare('SELECT COUNT(ms.id) as count_history FROM membership mb JOIN membership_log mbl ON mbl.id_membership = mb.id JOIN user u ON u.id = mb.id_user JOIN movie_schedule ms ON ms.id = mbl.id_session JOIN movie m ON m.id = ms.id_movie WHERE mb.id_user = :id ORDER BY ms.date_begin DESC');
$history_logss->execute([
    'id' => $_SESSION['LOGIN_USER']['id'],
]);
$historyy = $history_logss->fetchAll(PDO::FETCH_ASSOC);

$vip = "Aucun";

$membership_data = $mysqlClient->prepare('SELECT * FROM membership WHERE id_user = :id');
$membership_data->execute([
    'id' => $_SESSION['LOGIN_USER']['id'],
]);
$membership = $membership_data->fetchAll(PDO::FETCH_ASSOC);

foreach ($membership as $memberships) {
    if (isset($memberships['id_subscription'])) {
  
        $subscription_data = $mysqlClient->prepare('SELECT * FROM subscription WHERE id = :id');
        $subscription_data->execute([
            'id' =>  $memberships['id_subscription'],
        ]);
        $subscription = $subscription_data->fetchAll(PDO::FETCH_ASSOC);


        foreach ($subscription as $subscriptions) {
            $vip = $subscriptions['name'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/profil.css">
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="../style/style_new.css">
</head>

<body>
    <div class="navBack">
        <section class="navMain">
            <nav>
                <div>
                    <a href="../index.php"><img src="../img/logo.png" alt="" style="width: 50%;"></a>
                </div>
                <ul>

                    <li><a href="search_seance.php">Chercher une séance</a></li>
                    <?php if (isset($_SESSION['LOGIN_USER']['id'])) : ?>
                        <li class="deroulant" id="nav"><a href="#">Salut, <?php echo $_SESSION['LOGIN_USER']['firstname'] ?></a>
                            <ul class="sous">
                                <li><a href="profil.php">Profil</a></li>
                                <li><a href="../login/logout.php">Deconnexion</a></li>
                            </ul>
                        </li>
                    <?php else : ?>
                        <li><a href="login/login_register.php">Connexion</a></li>
                    <?php endif; ?>
                    <?php if ($_SESSION['LOGIN_USER']['admin'] == 1) : ?>
                        <li><a href="../user/admin.php">Panel Admin</a></li>
                    <?php endif; ?>

                </ul>
            </nav>

        </section>
    </div>
    <div class="card-profil">
        <div class="update">
            <form action="profil/update_information.php" method="post">
                <h3>Abonnement</h3>
                <p>Type : <?php echo $vip?></p>
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" value="<?php echo $getUser['email'] ?>">

                <label for="firstname">prénom : </label>
                <input type="text" name="firstname" id="firstname" value="<?php echo $getUser['firstname'] ?>">

                <label for="lastname">NOM : </label>
                <input type="text" name="lastname" id="lastname" value="<?php echo $getUser['lastname'] ?>">

                <label for="date">DATE DE NAISSANCE : </label>
                <input type="datetime-local" name="date" id="date" value="<?php echo $getUser['birthdate'] ?>">

                <label for="address">ADRESSE : </label>
                <input type="text" name="address" id="address" value="<?php echo $getUser['address'] ?>">

                <label for="zipcode">CODE POSTAL : </label>
                <input type="number" name="zipcode" id="zipcode" value="<?php echo $getUser['zipcode'] ?>">

                <label for="city">VILLE : </label>
                <input type="text" name="city" id="city" value="<?php echo $getUser['city'] ?>">

                <label for="country">PAYS : </label>
                <input type="text" name="country" id="country" value="<?php echo $getUser['country'] ?>">

                <input type="text" name="id" id="id" value="<?php echo $getUser['id'] ?>" style="display: none">

                <input type="submit" id="submit" value="Changer vos information">
            </form>
        </div>
        <div class="password">
            <form action="profil/update_password.php" method="post">
                <label for="passwordnew">Nouveau mot de passe :</label>
                <input type="text" name="passwordnew" id="passwordnew" required>

                <label for="passwordold">Ancien mot de passe : </label>
                <input type="password" name="passwordold" id="passwordold" required>

                <input type="text" name="id" id="id" value="<?php echo $getUser['id'] ?>" style="display: none">

                <input type="submit" id="submit" value="Changer de mot de passe">

            </form>
        </div>
    </div>
    <div class="card-profil">
        <div class="history">
            <h1>Historique séance</h1>
            <h5 class="h1-center"><?php echo "Nombre de séance : " . $historyy[0]['count_history'] ?></h5>
            <?php if ($history != NULL) : ?>
                <table>
                    <tr>
                        <th>Titre du film</th>
                        <th>Salle</th>
                        <th>Date</th>
                    </tr>
                    <?php foreach ($history as $history_user) : ?>
                        <tr>
                            <td><?php echo  $history_user['title'] ?></td>
                            <td><?php echo  $history_user['id_room'] ?></td>
                            <td><?php echo  $history_user['date_begin'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>


            <?php else : ?>
                <h2>Aucun Historique</h2>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>