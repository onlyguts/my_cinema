<?php
include_once('../../mysql.php');
$getData = $_POST;


$GetPassData = $mysqlClient->prepare('SELECT up.password FROM user u JOIN user_password up ON up.id_user = u.id WHERE u.id = :id');
$GetPassData->execute([
    "id" => $getData['id'],
]);
$GetPass = $GetPassData->fetch(PDO::FETCH_ASSOC);

if (password_verify($getData['passwordold'], $GetPass['password'])) {
    $insertData = $mysqlClient->prepare('UPDATE user_password SET password=:password WHERE id_user = :id_user');
    $insertData->execute([
        'id_user' => $getData['id'],
        'password' => password_hash($getData['passwordnew'], PASSWORD_DEFAULT),
    ]);
    echo "c'est bon";
} else {
    echo "pas le meme";
}
