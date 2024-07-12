<?php
include_once('../mysql.php');
session_start();


$sql = $mysqlClient->prepare("SELECT a.id as id_avis, u.*, a.rating, a.description, a.date_create FROM avis a JOIN user u ON u.id = a.id_user WHERE enable = 0");
$sql->execute([]);
$avis_users = $sql->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/style_new.css">
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="../style/avis_admin.css">
</head>

<body>
    <div class="navBack">
        <section class="navMain">
            <nav>
                <div>
                    <a href="../index.php"><img src="../img/logo.png" alt="" style="width: 50%" ;></a>
                </div>
                <ul>
                    <li><a href="admin.php">Page Admin</a></li>
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
        <h1>Liste des avis à accepter</h1>
    </div>


        <table>
            <tr>
                <th>ID</th>
                <th>PRENOM NOM</th>
                <th>NOTE</th>
                <th>COMMENTAIRE</th>
                <th>DATE</th>
                <th></th>
            </tr>
            <?php foreach ($avis_users as $avis_users) : ?>
            <tr>
          
                <td><?php echo $avis_users['id_avis'] ?></td>
                <td><?php echo $avis_users['firstname'] . " " . $avis_users['lastname'] ?></td>
                <td><?php echo $avis_users['rating'] ?></td>
                <td><?php echo $avis_users['description'] ?></td>
                <td><?php echo $avis_users['date_create'] ?></td>
                <td><a href="accepte_avis.php?id_avis=<?php echo ($avis_users['id_avis']) ?>">✅</a> <a href="delete_avis.php?id_avis=<?php echo ($avis_users['id_avis']) ?>">❌</a></td>
          
            </tr>
            <?php endforeach; ?>
        </table>
 
</body>

</html>