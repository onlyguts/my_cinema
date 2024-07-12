<?php


session_start();

if ($_SESSION['LOGIN_USER']['admin'] != 1) {
    header('Location: ../index.php');
exit;
}

include_once('../mysql.php');
$getSub = $_POST;
$getDataId = $_POST['id'];
$vip_add = $_POST['vip_add'][0];
$vip_base = $_POST['vip_base'];

$getSubs = $mysqlClient->prepare('SELECT * FROM subscription WHERE name = :name');
$getSubs->execute([
    "name" => $vip_add,
]);
$getSub = $getSubs->fetch(PDO::FETCH_ASSOC);

$vip_add = $getSub['id'];

if (isset($getSub)) {
    if (isset($vip_add)) {
        if ($vip_base == 0) {
            echo "Insert";
            $mysqltime = date('Y-m-d H:i:s', $phptime);
            $insertRecipeStatement = $mysqlClient->prepare('INSERT INTO membership (`id_user`, `id_subscription`, `date_begin`) VALUES (:id_user, :id_sub, :datetim)');
            $insertRecipeStatement->execute([
                'id_user' => $getDataId,
                'id_sub' => $vip_add,
                'datetim' => $mysqltime,
            ]);
        } else {
            echo "Update";
            $insertRecipeStatement = $mysqlClient->prepare('UPDATE `membership` SET `id_subscription`=:id_subscription WHERE id_user = :id_user');
            $insertRecipeStatement->execute([
                'id_user' => $getDataId,
                'id_subscription' => $vip_add,
            ]);
        }
    }
}

header('Location: user_read.php?id=' . $getDataId);
