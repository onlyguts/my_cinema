<?php 
include_once('../mysql.php');

$userSQL = $mysqlClient->prepare('SELECT * FROM employee WHERE id_user = :id');
$userSQL->execute([
    "id" => $_POST['id'],
]);
$user = $userSQL->fetch(PDO::FETCH_ASSOC);
if (isset($user['id_user'])){
    $userSQL = $mysqlClient->prepare('DELETE FROM `employee` WHERE id_user = :id');
    $userSQL->execute([
        "id" => $_POST['id'],
    ]);
}

header('Location: user_read.php?id=' . $_POST['id']);