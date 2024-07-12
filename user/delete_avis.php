<?php
include_once('../mysql.php');

echo $_GET['id_avis'];

$sql = $mysqlClient->prepare("DELETE FROM avis WHERE id = :id");
$sql->execute([
    "id" => $_GET['id_avis'],
]);

header("Location: avis_admin.php");