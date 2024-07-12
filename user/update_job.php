<?php 
include_once('../mysql.php');
$jobSQL = $mysqlClient->prepare('SELECT * FROM job WHERE name = :name');
$jobSQL->execute([
    "name" => $_POST['element'][0],
]);
$job = $jobSQL->fetch(PDO::FETCH_ASSOC);
echo $job['name'];
if ($_POST['jobuser'] != "Aucun") {
    $insertRecipeStatement = $mysqlClient->prepare('UPDATE `employee` SET `id_user`=:id_user,`id_job`=:id_job WHERE  id_user=:id_user');
    $insertRecipeStatement->execute([
        'id_user' => $_POST['id'],
        'id_job' => $job['id'],
    ]);
} else {
    $insertRecipeStatement = $mysqlClient->prepare('INSERT INTO `employee`(`id_user`, `id_job`, `date_begin`) VALUES (:id_user, :id_job, NOW())');
    $insertRecipeStatement->execute([
        'id_user' => $_POST['id'],
        'id_job' => $job['id'],
    ]);
}

header('Location: user_read.php?id=' . $_POST['id']);