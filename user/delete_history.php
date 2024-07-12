<?php
include_once('../mysql.php');

echo $_GET['id_user'];
echo $_GET['id_session'];

$getJobs = $mysqlClient->prepare('DELETE mbl FROM membership_log mbl JOIN membership m ON m.id = mbl.id_membership WHERE m.id_user = :id_user AND mbl.id_session = :id_session');
$getJobs->execute([
    "id_user" => $_GET['id_user'],
    "id_session" => $_GET['id_session'],
]);


header('Location: user_read.php?id=' . $_GET['id_user']);