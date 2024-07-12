<?php
include_once('mysql.php');

if (isset($_POST['search'])) {
    $title = $_POST['search'];
} else {
    $title = "";
}

$movie_today_data = $mysqlClient->prepare('SELECT ms.*, m.title, r.name FROM movie_schedule ms JOIN movie m ON m.id = ms.id_movie JOIN room r ON r.id = ms.id_room WHERE date_begin LIKE CONCAT("%", DATE(NOW()), "%") AND m.title LIKE CONCAT("%", :title, "%")');
$movie_today_data->execute([
    "title" => $title,
]);
$movie_today = $movie_today_data->fetchAll(PDO::FETCH_ASSOC);



?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">

    
<div class="searchBar">
                    <input type="search" name="search" placeholder="Taper le film ici" id="search">
                    <input type="submit" id="btn-search" value="🔎">
</div>
</form>
<?php foreach ($movie_today as $movies) :?>
    <ul>
        <li>Titre : <?php echo $movies['title'] ?></li>
        <li>Salle : <?php echo $movies['name'] ?></li>
    </ul>
<?php endforeach;?>
</body>
</html>