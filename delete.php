<?php
/** @var mysqli $db */
session_start();

//May I visit this page? Check the SESSION
if(!isset($_SESSION['login'])){
    $_SESSION['error'] = "Je moet ingelogd zijn";
    $index = $_GET['id'];
    header("Location: login.php?page=delete&id=$index");

}
// Setup connection with database
require_once 'includes/database.php';

if(!isset($_GET['id'])){
    header('Location: lijst.php');
    exit;
}
// check of alles is ingevuld
if (isset($_POST['submit'])) {
    $index = mysqli_escape_string($db, $_POST['id']);
    //update database
    $query = "DELETE FROM movielist WHERE id = '$index'";
    $result = mysqli_query($db, $query);

    if ($result) {
        header('Location: lijst.php');
    } else {
        $errors['db'] = mysqli_error($db);
    }
}else if (isset($_GET['id']) && $_GET['id'] !== '') {
    $index = $_GET['id'];
// select de album emt de juiste id van de database
    $query = "SELECT movielist.id, movielist.film, movielist.genre, regiseur.name, movielist.kijken_op
FROM movielist
JOIN regiseur ON movielist.regiseur_id = regiseur.id WHERE movielist.id = '$index'";
    $result = mysqli_query($db, $query)
    or die('Error '.mysqli_error($db).' with query '.$query);

    if (mysqli_num_rows($result) == 1) {
        $movie = mysqli_fetch_assoc($result);
    } else {
        header('Location: lijst.php');
        exit;
    }
    $film = $movie['film'];
    $genre = $movie['genre'];
    $regiseur = $movie['name'];
    $kijken_op = $movie['kijken_op'];
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
    <title>Details book</title>
</head>
<body>
<div class="container px-4">
    <div class="columns is-centered">
        <div class="column is-narrow">
            <h2 class="title mt-4"><?= htmlentities($movie['film'])?></h2>
            <section class="content">
                <ul>
                    <li>film: <?= htmlentities($movie['film'])?></li>
                    <li>Genre <?= htmlentities($movie['genre'])?></li>
                    <li>Regiseur: <?= htmlentities($movie['name'])?></li>
                    <li>kijken op: <?= htmlentities($movie['kijken_op']).'/10'?></li>
                </ul>
            </section>
            <form class="column is-6" action="" method="post">
                <div class="field is-horizontal">
                    <div class="field-label is-normal"></div>
                    <div class="field-body">
                        <input type = "hidden" name = "id" value = "<?= htmlentities($index)?>" />
                        <button class="button is-link is-fullwidth" type="submit" name="submit">verwijder film</button>
                    </div>
                </div>
            </form>
            <div>
                <a class="button" href="lijst.php">Ga terug naar de lijst</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
