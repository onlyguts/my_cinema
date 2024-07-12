<?php 
include_once('../mysql.php');

$user_data = $mysqlClient->prepare("DELETE FROM avis WHERE id =:id");
$user_data->execute([
    'id' => $_GET['id'],
]);

header('Location: list_avis.php');