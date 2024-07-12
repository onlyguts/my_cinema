<?php
session_start();

if ($_SESSION['LOGIN_USER']['admin'] != 1) {
    header('Location: ../index.php');
exit;
}

include_once('../mysql.php');
$getDataId = $_GET['id'];


$user_data = $mysqlClient->prepare('SELECT m.* FROM membership m WHERE m.id_user = :id');
$user_data->execute([
    'id' => $getDataId,
]);
$user = $user_data->fetch(PDO::FETCH_ASSOC);

if (isset($user['id_user'])) {
    $id_membership = $users['id'];

    $insertRecipeStatement = $mysqlClient->prepare('DELETE FROM membership_log WHERE id_membership = :id_membership');
    $insertRecipeStatement->execute([
       'id_membership' => $id_membership,
    ]);
    
    $insertRecipeStatement = $mysqlClient->prepare('DELETE FROM membership WHERE id_user = :id_user');
    $insertRecipeStatement->execute([
       'id_user' => $getDataId,
    ]);
    
    $insertRecipeStatement = $mysqlClient->prepare('DELETE FROM user WHERE id = :id');
    $insertRecipeStatement->execute([
       'id' => $getDataId,
    ]);
    
} else {
    $insertRecipeStatement = $mysqlClient->prepare('DELETE FROM user WHERE id = :id');
    $insertRecipeStatement->execute([
       'id' => $getDataId,
    ]);
}
header('Location: user.php');
/*


header('Location: user.php'); */

