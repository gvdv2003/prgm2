<?php
/** @var mysqli $db */
// Setup connection with database
require_once 'includes/database.php';

//kijken of er een ID is ingevuld zo niet word je terug gestuurd naar de lijst.
if(!isset($_GET['id'])){
    header('Location: lijst.php');
    exit;
}

//sessie word gestart om te checken of er is ingelogd. is er niet ingelogd dan word je doorgestuurd naar de login pagina.
session_start();
if(!isset($_SESSION['login'])){
    $_SESSION['error'] = "Je moet ingelogd zijn";
    $id = $_GET['id'];
    header( "Location: login.php?page=edit&id=$id");
}


// als het formulier word ingeleverd worden de antwoorden toegewezen aan een variabele,
if (isset($_POST['submit'])) {
    $film = mysqli_escape_string($db, $_POST['film']);
    $genre = mysqli_escape_string($db, $_POST['genre']);
    $regiseur = mysqli_escape_string($db, $_POST['regiseur']);
    $kijken_op = mysqli_escape_string($db, $_POST['kijken_op']);
    $index = $_POST['id'];
    $regiseur_name = $_POST['regiseur_name'];

    //als er op een van de vragen niets is ingevuld word er een foutmelding getoont die hier word toegewezen aan de error string.

    $errors = [];
    if ($film == "") {
        $errors['film'] = "Vergeet niet de filmnaam in te vullen";
    }
    if ($genre == "") {
        $errors['genre'] = "vergeet niet het genre in te vullen";
    }
    if ($regiseur == "") {
        $errors['regiseur'] = "Vergeet niet de regiseur in te vullen";
    }
    if ($kijken_op == "" ) {
        $errors['kijken_op'] = "Vergeet niet om in te vullen waar je de film kan kijken";
    }
    // als de error string leeg is, kunnen de gegevens naar de database, geupdate worden.
    //omdat de regiseur naam in een andere tabel staat als de rest van de film gegevens, moet dit in een andere query verwerkt worden

    if (empty($errors)) {

        $queryregiseur = "UPDATE regiseur set name = '$regiseur' WHERE name = '$regiseur_name';";
        $querymovie ="UPDATE movielist SET `film` = '$film', `regiseur_id` = (SELECT regiseur.id FROM `regiseur`WHERE name = '$regiseur'), `genre` = '$genre', `kijken_op` = '$kijken_op' WHERE movielist.id = '$index'";
        $resultregiseur = mysqli_query($db, $queryregiseur);
        $resultmovie = mysqli_query($db, $querymovie);
        if ($resultregiseur and $resultmovie) {
            header('Location: lijst.php');
        } else {
            $errors['db'] = mysqli_error($db);
        }
    }
    // als word doorgestuurd naar de edit pagina is er natuurlijk nog niet op de submit knop geklikt.
    // dus gaat de volgende code in werking als er een id is meegeleverd. ook word het ID er in gezet als index.
}else if (isset($_GET['id']) && $_GET['id'] !== ''){
    $index = $_GET['id'];


// hier word de informatie opgehaald over het id uit de database. de gegevens worden vervolens in een string in dit geval movie gezet.
    // ook zie je een join functie in de query gebruikt. dit word gedaan omdat er een 1-op-veel relatie is gebruikt omdat de informatie over de regiseur in een andere tabel uit de database staan.

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

    // hier worden de stukken uit de movie string verdeeld over variabele zodat deze makkelijker in het bestand gebruikt kunnen worden.
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
    <title>Reading list details</title>
</head>
<body>
<div class="container px-4">

    <section class="columns is-centered">
        <div class="column is-10">
            <h2 class="title mt-4"><?= $film ?></h2>
            <form class="column is-6" action="" method="post">

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="film">filmnaam</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" id="film" type="text" name="film" value="<?= htmlentities($film)?>"/>
                            </div>
                            <p class="help is-danger">
                                <?php
                                if (isset($errors['film'])) {
                                    echo $errors['film'];
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="genre">genre</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" id="genre" type="text" name="genre" value="<?= htmlentities($genre)?>"/>
                            </div>
                            <p class="help is-danger">
                                <?php
                                if (isset($errors['genre'])) {
                                    echo $errors['genre'];
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="regiseur">regiseur</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" id="regiseur" type="text" name="regiseur" value="<?= htmlentities($regiseur)?>"/>
                            </div>
                            <p class="help is-danger">
                                <?php
                                if (isset($errors['regiseur'])) {
                                    echo $errors['regiseur'];
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="kijken_op">te kijken op</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" id="kijken_op" type="text" name="kijken_op" value="<?= htmlentities($kijken_op)?>"/>
                            </div>
                            <p class="help is-danger">
                                <?php
                                if (isset($errors['kijken_op'])) {
                                    echo $errors['kijken_op'];
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>


                <div class="field is-horizontal">
                    <div class="field-label is-normal"></div>
                    <div class="field-body">
                        <input type = "hidden" name = "id" value = "<?= htmlentities($index) ?>" />
                        <input type = "hidden" name = "regiseur_name" value = "<?= htmlentities($regiseur) ?>" />
                        <button class="button is-link is-fullwidth" type="submit" name="submit">Save</button>
                    </div>
                </div>
            </form>

            <a class="button mt-4" href="lijst.php">&laquo; terug naar de lijst</a>
        </div>
    </section>
</div>
</body>
</html>

