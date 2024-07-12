<?php
include_once('../mysql.php');

echo $_GET['id_avis'];

$sql = $mysqlClient->prepare("UPDATE avis SET enable = 1 WHERE id = :id");
$sql->execute([
    "id" => $_GET['id_avis'],
]);

header("Location: avis_admin.php");