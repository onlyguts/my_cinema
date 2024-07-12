<?php

include_once('mysql.php');

$user_count = $mysqlClient->prepare('SELECT count(id) as user_count FROM user');
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
        header("Location: pagination.php?page=1&ide=$nbr_element_page");
    }
}
$nbr_de_page = ceil($userc[0]['user_count'] / $nbr_element_page);
$debut = ($page - 1) * $nbr_element_page;

$user_data = $mysqlClient->prepare("SELECT * FROM user ORDER BY id ASC LIMIT $debut, $nbr_element_page");
$user_data->setFetchMode(PDO::FETCH_ASSOC);
$user_data->execute();
$user = $user_data->fetchAll();

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1><?php echo $userc[0]["user_count"] . "<br>"; ?></h1>
    <form action="" method="post">
        <label for="element-select">Nombre element sur la page :</label>
        <select name="element[]" id="element-select">
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        <input type="submit" value="Envoyer">
    </form>

    <?php for ($i = 1; $i <= $nbr_de_page; $i++) {
        echo "<a href='?page=$i&ide=$nbr_element_page'><button>$i</button></a>&nbsp";
    } ?>


    <table>
        <tbody>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Email</th>
                <th scope="col">Prénom</th>
                <th scope="col">Nom</th>
            </tr>
            <?php for ($i = 0; $i < count($user); $i++) : ?>
                <tr>
                    <td class='id_user' scope="row"><?php echo $user[$i]['id'] ?></td>
                    <td scope="row"><?php echo $user[$i]['email'] ?></td>
                    <td><?php echo $user[$i]['firstname'] ?></td>
                    <td><?php echo $user[$i]['lastname'] ?></td>
                    <td class="modifier"><a href="user_read.php?id=<?php echo ($user[$i]['id']); ?>">🖋️</a></td>
                    <td class="supprimer">🗑️</td>
                </tr>
            <?php endfor; ?>


        </tbody>
    </table>


</body>

</html>