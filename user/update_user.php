<?php


session_start();

if ($_SESSION['LOGIN_USER']['admin'] != 1) {
    header('Location: ../index.php');
exit;
}

include_once('../mysql.php');
$getSub = $_POST;
$getDataId = $_POST['id'];
$vip = $_POST['vip'];


if (isset($getSub)) {
    if (isset($getSub['firstname'])) {
        echo "Update good !";
        $insertRecipeStatement = $mysqlClient->prepare('UPDATE `user` SET `email`=:email,`firstname`=:firstname,`lastname`=:lastname,`birthdate`=:birthdate,`address`=:addresss,`zipcode`=:zipcode,`city`=:city,`country`=:country WHERE id = :id');
        $insertRecipeStatement->execute([
            'id' => $getDataId,
            'email' => $getSub['email'],
            'firstname' => $getSub['firstname'],
            'lastname' => $getSub['lastname'],
            'birthdate' => $getSub['birthdate'],
            'addresss' => $getSub['adress'],
            'zipcode' => $getSub['zipcode'],
            'city' => $getSub['city'],
            'country' => $getSub['country'],
        ]);
    }
}

header('Location: user_read.php?id=' . $getDataId);
