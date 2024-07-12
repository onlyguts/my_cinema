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
    $user_count = $mysqlClient->prepare("SELECT count(u.id) as user_count FROM employee e join user u ON u.id = e.id_user WHERE firstname LIKE CONCAT('%', :nametext, '%') OR lastname LIKE CONCAT('%', :nametext, '%')");
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
            header("Location: employee.php?page=1&ide=$nbr_element_page&title=$gettitleMovie");
        }
    }

    $nbr_de_page = ceil($userc[0]['user_count'] / $nbr_element_page);
    $debut = ($page - 1) * $nbr_element_page;

 //   $user_data = $mysqlClient->prepare("SELECT u.* FROM user u WHERE u.firstname LIKE CONCAT('%', :nametext, '%') OR u.lastname LIKE CONCAT('%', :nametext, '%') ORDER BY u.id ASC LIMIT $debut, $nbr_element_page");
    $user_data = $mysqlClient->prepare("SELECT u.*, j.name as jobs_name FROM employee e join user u ON u.id = e.id_user JOIN job j ON j.id = e.id_job WHERE u.firstname LIKE CONCAT('%', :nametext, '%') OR u.lastname LIKE CONCAT('%', :nametext, '%') ORDER BY j.id DESC LIMIT $debut, $nbr_element_page");
    $user_data->execute([
        'nametext' => $gettitleMovie,
    ]);

    $users = $user_data->fetchAll(PDO::FETCH_ASSOC);


    ?>
    <div class="navBack">
        <?php include_once('nav_admin.php'); ?>

        <section class="search_name">
            <form action="employee.php" method="get">
                <input type="search" name="title" placeholder="Taper le nom,prenom ici" id="title">
                <input type="submit" class="searchBtn" id="btn-search" value="🔎">
            </form>
            <form action="employee.php" method="post">
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
                    <th scope="col">Email</th>
                    <th scope="col">Prénom</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Poste</th>
                    <form action="" method="post">
                        <th scope="col">
                            <select name="element[]" id="element-select">
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </th>
                        <th scope="col">
                            <input type="submit" value="Envoyer" id="sumbit">
                        </th>
                    </form>
                </tr>
                <?php for ($i = 0; $i < count($users); $i++) : ?>
                    <tr>
                        <td class='id_user' scope="row"><?php echo $users[$i]['id'] ?></td>
                        <td><?php echo $users[$i]['email'] ?></td>
                        <td><?php echo $users[$i]['firstname'] ?></td>
                        <td><?php echo $users[$i]['lastname'] ?></td>
                        <td><?php echo $users[$i]['jobs_name'] ?></td>
                        <td class="modifier"><a href="employee_edit.php?id=<?php echo ($users[$i]['id']); ?>">🖋️</a></td>
                        <td class="supprimer"><a href="employee_delete.php?id=<?php echo ($users[$i]['id']); ?>">🗑️</a></td>
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