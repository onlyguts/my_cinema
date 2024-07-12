<?php
session_start();
include_once('../mysql.php');

$getPost = $_POST;


if (isset($getPost['email'])) {
    $userSQL = $mysqlClient->prepare('SELECT * FROM user WHERE email = :email');
    $userSQL->execute([
        "email" => $getPost['email'],
    ]);
    $users = $userSQL->fetch(PDO::FETCH_ASSOC);

    $userAdminSQL = $mysqlClient->prepare('SELECT * FROM user_admin WHERE id_user = :id_user');
    $userAdminSQL->execute([
        "id_user" => $users['id'],
    ]);
    $userAdmin = $userAdminSQL->fetch(PDO::FETCH_ASSOC);

    if (isset($userAdmin['admin'])) {
        $admin = $userAdmin['admin'];
    } else {
        $admin = 0;
    }

    if (isset($users['email'])) {
        $userSQL = $mysqlClient->prepare('SELECT * FROM user_password WHERE id_user = :id');
        $userSQL->execute([
            "id" => $users['id'],
        ]);
        $user = $userSQL->fetch(PDO::FETCH_ASSOC);
        if (isset($user['password'])) {
            if (password_verify($getPost['password'], $user['password'])) {
                $_SESSION['LOGIN_USER'] = [
                    'id' => $users['id'],
                    'admin' => $admin,
                    'email' => $users['email'],
                    'firstname' => $users['firstname'],
                    'lastname' => $users['lastname'],
                    'date' => $users['birthdate'],
                    'address' => $users['address'],
                    'zipcode' => $users['zipcode'],
                    'city' => $users['city'],
                    'country' => $users['country'],
                    'address' => $users['address'],
                ];
                header('Location: ../index.php');
            } else {
                $_SESSION['ERROR_LOGIN'] = "Mot de passe différent";
                header('Location: ../login/login_register.php');
            }
        } else {
            $bytes = random_bytes(5);
            $newpassword = bin2hex($bytes);
            $insertData = $mysqlClient->prepare('INSERT INTO `user_password`(`id_user`, `password`) VALUES (:id_user, :password)');
            $insertData->execute([
                'id_user' => $users['id'],
                'password' => password_hash($newpassword, PASSWORD_DEFAULT),
            ]);

            $_SESSION['ERROR_LOGIN'] = "Nous navons pas trouver de password sur votre compte!<br>Donc on vous à generer un nouveau mots de passe le voici !<br><br> Mot de passe : " . $newpassword;
            header('Location: ../login/login_register.php');
        }
    } else {
        $_SESSION['ERROR_LOGIN'] = "Email introuvable";
        header('Location: ../login/login_register.php');
    }
}
