<?php
session_start();
include_once('../mysql.php');

$getPost = $_POST;

if (isset($getPost['email'])) {
    $userSQL = $mysqlClient->prepare('SELECT * FROM user WHERE email = :email');
    $userSQL->execute([
        "email" => $getPost['email'],
    ]);
    $user = $userSQL->fetch(PDO::FETCH_ASSOC);
    if (isset($user['email'])) {
        $_SESSION['ERROR_LOGIN'] = "Email déjà utilisé";
        header('Location: ../login/login_register.php');
    } else {
        if ($getPost['password'] != $getPost['passwordverif']) {
            $_SESSION['ERROR_LOGIN'] = "Mot de passe différent";
            header('Location: ../login/login_register.php');
        } else {
            $insertData = $mysqlClient->prepare('INSERT INTO `user`(`email`, `firstname`, `lastname`, `birthdate`, `address`, `zipcode`, `city`, `country`) VALUES (:email, :firstname, :lastname, :birthdate, :address, :zipcode, :city, :country)');
            $insertData->execute([
                'email' => $getPost['email'],
                'firstname' => $getPost['firstname'],
                'lastname' => $getPost['lastname'],
                'birthdate' => $getPost['date'],
                'address' => $getPost['address'],
                'zipcode' => $getPost['zipcode'],
                'city' => $getPost['city'],
                'country' => $getPost['country'],
            ]);
            $getUserID = $mysqlClient->prepare('SELECT id FROM user WHERE email = :email');
            $getUserID->execute([
                'email' => $getPost['email'],
            ]);
            $getUser = $getUserID->fetch(PDO::FETCH_ASSOC);

            $insertData = $mysqlClient->prepare('INSERT INTO `user_password`(`id_user`, `password`) VALUES (:id_user, :password)');
            $insertData->execute([
                'id_user' => $getUser['id'],
                'password' => password_hash($getPost['password'], PASSWORD_DEFAULT),
            ]);
           
            header('Location: login_register.php');
        }
    }
} 
?>
