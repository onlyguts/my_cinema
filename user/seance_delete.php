<?php 
include_once('../mysql.php');

$user_data = $mysqlClient->prepare("DELETE FROM movie_schedule WHERE id =:id");
$user_data->execute([
    'id' => $_GET['id'],
]);

header('Location: list_seance.php');