<?php

session_start();

//May I visit this page? Check the SESSION
if(!isset($_SESSION['login'])){
    $_SESSION['error'] = "Je moet ingelogd zijn";
    header('Location: login.php?page=create');

}

/** @var $db */
require_once "includes/database.php";


$film = '';
$genre = '';
$regiseur = '';
$kijken_op = '';


if (isset($_POST['submit'])) {
    $film = mysqli_escape_string($db, $_POST['film']);
    $genre = mysqli_escape_string($db, $_POST['genre']);
    $regiseur = mysqli_escape_string($db, $_POST['regiseur']);
    $kijken_op = mysqli_escape_string($db, $_POST['kijken_op']);


    $errors = [];
    if($film == '') {
        $errors['film'] = 'vergeet niet de filmnaam in te vullen.';
    }
    if($genre == '') {
        $errors['genre'] = 'vergeet niet het genre in te vullen.';
    }
    if($regiseur == '') {
        $errors['regiseur'] = 'vergeet niet de regiseur in te vullen.';
    }
    if($kijken_op == '') {
        $errors['kijken_op'] = 'vergeet niet om in te vullen waar je deze film kan kijken';
    }

    if (empty($errors)) {
        //INSERT in DB


            $queryregiseur = "INSERT IGNORE INTO regiseur (name) VALUES ('$regiseur')";
            $querymovie = "INSERT INTO movielist (film, genre, regiseur_id, kijken_op)
                SELECT '$film', '$genre', regiseur.id , '$kijken_op'
                FROM regiseur
                WHERE name = '$regiseur'";
            $resultregiseur = mysqli_query($db, $queryregiseur);
            $resultmovie = mysqli_query($db, $querymovie);


            if ($resultregiseur && $resultmovie) {
                header('Location: lijst.php');
            } else {
                $errors['db'] = mysqli_error($db);
            }


        }




    
}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create</title>
</head>
<body>

<nav>
    <a href="home.php">Home</a>
    <a href="login.php">Login</a>
    <a href="registreer.php">Registreer</a>
    <a href="lijst.php">lijst</a>

</nav>

<main>




    <h2>Create</h2>
    <form action="" method="post">
        <label for="film">
            Filmnaam:
            <input type="text" id="film" name="film" value="<?= htmlentities($film) ?>"/>
            <p class="help is-danger">
                <?= $errors['film'] ?? '' ?>
            </p>
        </label>

        <label for="genre">
            Genre:
            <input type="text" id="genre" name="genre" value="<?= htmlentities($genre) ?>"/>
            <p class="help is-danger">
                <?= $errors['genre'] ?? '' ?>
            </p>
        </label>

        <label for="regiseur">
            Regiseur
            <input type="text" id="regiseur" name="regiseur" value="<?= htmlentities($regiseur) ?>"/>
            <p class="help is-danger">
                <?= $errors['regiseur'] ?? '' ?>
            </p>
        </label>

        <label for="password">
            te bekijken op:
            <input type="text" id="kijken_op" name="kijken_op" value="<?= htmlentities($kijken_op) ?>"/>
            <p class="help is-danger">
                <?= $errors['kijken_op'] ?? '' ?>
            </p>
        </label>

        <button type="submit" name="submit">submit</button>

    </form

    <p><?= $worked ?? '' ?></p>





</main>

</body>
</html>




<?php

/** @var mysqli $db */
require_once "includes/database.php";




if(isset($_POST['submit'])) {


    $worked = '';

    $film = $_POST['film'];
    $genre = $_POST['genre'];
    $regiseur = $_POST['regiseur'];
    $kijken_op = $_POST['kijken_op'];

    if(!empty($_POST['film']) && !empty($_POST['genre']) && !empty($_POST['regiseur']) && !empty($_POST['kijken_op'])){

        $query="INSERT INTO movielist (film, genre, regiseur, kijken_op)
        VALUES ('$film', '$genre', '$regiseur', '$kijken_op')";

        $movielist = mysqli_query($db, $query);

        $worked = 'De film is toegevoegd';


    };





    $errors = [];
    if($film == '') {
        $errors['film'] = 'vergeet niet de filmnaam in te vullen.';
    }
    if($genre == '') {
        $errors['genre'] = 'vergeet niet het genre in te vullen.';
    }
    if($regiseur == '') {
        $errors['regiseur'] = 'vergeet niet de regiseur in te vullen.';
    }
    if($kijken_op == '') {
        $errors['kijken_op'] = 'vergeet niet om in te vullen waar je deze film kan kijken';
    }



}

mysqli_close($db);




?>





