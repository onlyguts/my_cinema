<?php


session_start();

if ($_SESSION['LOGIN_USER']['admin'] != 1) {
    header('Location: ../index.php');
exit;
}


include_once('../mysql.php');
$getSub = $_POST;
$getDataId = $_POST['id'];



$user_data = $mysqlClient->prepare('SELECT * FROM membership WHERE id_user = :id');
$user_data->execute([
    'id' => $getDataId,
]);
$user = $user_data->fetchAll(PDO::FETCH_ASSOC);

foreach ($user as $users) {
   $id_membership = $users['id'];
}

$insertRecipeStatement = $mysqlClient->prepare('DELETE FROM membership_log WHERE id_membership = :id_membership');
$insertRecipeStatement->execute([
   'id_membership' => $id_membership,
]);

$insertRecipeStatement = $mysqlClient->prepare('DELETE FROM membership WHERE id_user = :id_user');
$insertRecipeStatement->execute([
   'id_user' => $getDataId,
]);


header('Location: user_read.php?id=' . $getDataId);
