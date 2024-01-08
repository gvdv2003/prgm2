<?php
/** @var mysqli $db */
session_start();
if(!isset($_GET['id'])){
    header('Location: lijst.php');
    exit;
}
$index = $_GET['id'];
// Setup connection with database
require_once 'includes/database.php';

// Select all the albums from the database
$query = "SELECT movielist.id, movielist.film, movielist.genre, regiseur.name, movielist.kijken_op
FROM movielist
JOIN regiseur ON movielist.regiseur_id = regiseur.id WHERE movielist.id = '$index'";
$result = mysqli_query($db, $query)
or die('Error '.mysqli_error($db).' with query '.$query);


if(mysqli_num_rows($result)== 1){
    $movie = mysqli_fetch_assoc($result);
}else{
    header('Location: index.php');
    exit;
}

mysqli_close($db);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Details <?= $movie['film'] ?> | movie list </title>
</head>
<body>
<div class="container px-4">
    <div class="columns is-centered">
        <div class="column is-narrow">
            <h2 class="title mt-4"><?= htmlentities($movie['film'])?></h2>
            <section class="content">
                <ul>
                    <li>genre <?= htmlentities($movie['genre'])?></li>
                    <li>regiseur <?= htmlentities($movie['name'])?></li>
                    <li>kijk op <?= htmlentities($movie['kijken_op'])?></li>

                </ul>
            </section>
            <div>
                <a class="button" href="lijst.php">ga terug naar de lijst</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>