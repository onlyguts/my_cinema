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
    <title>Document</title>
    <link rel="stylesheet" href="../style/style_new.css">
    <link rel="stylesheet" href="../style///user_read.css">
    <link rel="stylesheet" href="../style/nav.css">
</head>

<body>
    <?php
    include_once('../mysql.php');

    $getDataId = $_GET['id'];
    $getSub = $_POST;
    $vip = 0;
    $subs = "Aucun";

    $user_data = $mysqlClient->prepare('SELECT * FROM user WHERE id = :id');
    $user_data->execute([
        'id' => $getDataId,
    ]);
    $user = $user_data->fetchAll(PDO::FETCH_ASSOC);


    $membership_data = $mysqlClient->prepare('SELECT * FROM membership WHERE id_user = :id');
    $membership_data->execute([
        'id' => $getDataId,
    ]);
    $membership = $membership_data->fetchAll(PDO::FETCH_ASSOC);

    foreach ($membership as $memberships) {
        if (isset($memberships['id_subscription'])) {
      
            $subscription_data = $mysqlClient->prepare('SELECT * FROM subscription WHERE id = :id');
            $subscription_data->execute([
                'id' => $memberships['id_subscription'],
            ]);
            $subscription = $subscription_data->fetchAll(PDO::FETCH_ASSOC);


            foreach ($subscription as $subscriptions) {
                $subs = $subscriptions['name'];
                $vip =  $subscriptions['name'];
            }
        }
    }

    $history_logs = $mysqlClient->prepare('SELECT u.firstname, u.lastname, ms.date_begin, ms.id_room, m.title, u.id as id_user, mbl.id_session FROM membership mb JOIN membership_log mbl ON mbl.id_membership = mb.id JOIN user u ON u.id = mb.id_user JOIN movie_schedule ms ON ms.id = mbl.id_session JOIN movie m ON m.id = ms.id_movie WHERE mb.id_user = :id ORDER BY ms.date_begin DESC');
    $history_logs->execute([
        'id' => $getDataId,
    ]);
    $history = $history_logs->fetchAll(PDO::FETCH_ASSOC);

    $history_logss = $mysqlClient->prepare('SELECT COUNT(ms.id) as count_history FROM membership mb JOIN membership_log mbl ON mbl.id_membership = mb.id JOIN user u ON u.id = mb.id_user JOIN movie_schedule ms ON ms.id = mbl.id_session JOIN movie m ON m.id = ms.id_movie WHERE mb.id_user = :id ORDER BY ms.date_begin DESC');
    $history_logss->execute([
        'id' => $getDataId,
    ]);
    $historyy = $history_logss->fetchAll(PDO::FETCH_ASSOC);

    $getuserJob = $mysqlClient->prepare('SELECT * FROM employee WHERE id_user = :id');
    $getuserJob->execute([
        'id' => $getDataId,
    ]);
    $getuserJ = $getuserJob->fetch(PDO::FETCH_ASSOC);

    $getJobs = $mysqlClient->prepare('SELECT * FROM job');
    $getJobs->execute([]);
    $getJob = $getJobs->fetchAll(PDO::FETCH_ASSOC);


    
    $getSubs = $mysqlClient->prepare('SELECT * FROM subscription');
    $getSubs->execute([]);
    $getSub = $getSubs->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <div class="navBack">
        <?php include_once('nav_admin.php'); ?>
            <h1>Information User</h1>
    </div>
    <div class="backImg">

        <a href="user.php"> <img src="../img/back.png" alt=""> </a>

    </div>
    <div class="main_userlist">
        <div class="userListBlock">
            <h1 class="h1-center">Information</h1>
            <?php foreach ($user as $users) : ?>
                <form action="update_user.php" method="post" class="user_information">
                    <label for="email">Email : </label>
                    <input type="email" name="email" id="email" value="<?php echo $users['email'] ?>">
                    <label for="firstname">Prénom : </label>
                    <input type="text" name="firstname" id="firstname" value="<?php echo $users['firstname'] ?>">
                    <label for="lastname">Nom : </label>
                    <input type="text" name="lastname" id="lastname" value="<?php echo $users['lastname'] ?>">
                    <label for="birthdate">Date de naissance : </label>
                    <input type='datetime' name="birthdate" id="birthdate" value="<?php echo $users['birthdate'] ?>">
                    <label for="adress">Adresse : </label>
                    <input type='text' name="adress" id="adress" value="<?php echo $users['address'] ?>">
                    <label for="zipcode">Code postal : </label>
                    <input type='number' name="zipcode" id="zipcode" value="<?php echo $users['zipcode'] ?>">
                    <label for="city">City : </label>
                    <input type='text' name="city" id="city" value="<?php echo $users['city'] ?>">
                    <label for="country">Country : </label>
                    <input type='text' name="country" id="country" value="<?php echo $users['country'] ?>">
                    <input type="text" id="id" name="id" value=<?php echo ($getDataId) ?> style="display:none;">
                    <input type="submit" value="Update">
                </form>
            <?php endforeach; ?>
        </div>

        <div class="userListBlock">
            <h1 class="h1-center">Staff</h1>
            <form action="update_job.php" method="post" class="user_information">
                <?php

                $getUserJobsEmploue = $mysqlClient->prepare('SELECT j.name FROM user u JOIN employee e ON e.id_user = u.id JOIN job j ON j.id = e.id_job WHERE u.id = :id');
                $getUserJobsEmploue->execute([
                    'id' => $getDataId,
                ]);
                $getUserJobsEmplo = $getUserJobsEmploue->fetch(PDO::FETCH_ASSOC);

                ?>
                 <?php 
                if (isset($getUserJobsEmplo['name'])) {
                    $jobUser = $getUserJobsEmplo['name'];
                } else {
                    $jobUser = "Aucun";
                }
                ?> <p>Poste : <?php echo $jobUser?></p>
                <input type="text" name="jobuser" id="jobuser" value="<?php echo $jobUser?>" style="display: none;">
                <input type="text" name="id" id="id" value="<?php echo $getDataId?>" style="display: none;">
                <select name="element[]" id="element-select">
                    <?php foreach ($getJob as $getJobs) : ?>
                        <option value="<?php echo $getJobs['name'] ?>"><?php echo $getJobs['name'] ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="submit" value="Update" id="sumbit">
            </form>
            <form action="delete_job.php" method="post" class="user_information">
                <input type="text" name="id" id="id" value=<?php echo ($getDataId) ?> style='display:none;'>
                <input type="submit" value="Delete">
            </form>
        </div>

        <div class="userListBlock">
            <h1 class="h1-center">Sub</h1>
            <form action="update_sub.php" method="post" class="user_information">
                <p><?php echo 'Abonnement : ' . $vip?></p>
                <select name="vip_add[]" id="element-select">
                    <?php foreach ($getSub as $getSubs) : ?>
                        <option value="<?php echo $getSubs['name'] ?>"><?php echo $getSubs['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="vip_base" id="vip_base" value="<?php echo $vip ?>" style='display:none;'>
                <input type="text" name="id" id="id" value=<?php echo ($getDataId) ?> style='display:none;'>
                <input type="submit" value="Update">
            </form>
            <form action="delete_user.php" method="post" class="user_information">
                <input type="text" name="id" id="id" value=<?php echo ($getDataId) ?> style='display:none;'>
                <input type="submit" value="Delete">
            </form>
        </div>

        <div class="userListBlock2">
            <h1 class="h1-center">Historique Film</h1>
            <h5 class="h1-center"><?php echo "Nombre de séance : " . $historyy[0]['count_history']?></h5>
            <a id="btn-add" href="add_history.php?id_user=<?php echo ($getDataId) ?>">+</a>
            <div class="scroll_history">
                <?php if ($history != NULL) : ?>
                    <table>
                        <tr>
                            <th>Titre du film</th>
                            <th>Salle</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                        <?php foreach ($history as $history_user) : ?>
                            <tr>
                                <td><?php echo  $history_user['title'] ?></td>
                                <td><?php echo  $history_user['id_room'] ?></td>
                                <td><?php echo  $history_user['date_begin'] ?></td>
                                <td><a href="delete_history.php?id_user=<?php echo  $history_user['id_user'] ?>&id_session=<?php echo  $history_user['id_session'] ?>">🗑️</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>


                <?php else : ?>
                    <h2>Aucun Historique</h2>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>