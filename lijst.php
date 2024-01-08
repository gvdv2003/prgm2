<?php

/** @var array $db */
require_once "includes/database.php";




$query = "SELECT movielist.id, movielist.film, movielist.genre, regiseur.name, movielist.kijken_op
FROM movielist
JOIN regiseur ON movielist.regiseur_id = regiseur.id";
$result = mysqli_query($db, $query)
or die('Error '.mysqli_error($db).' with query '.$query);


$result = mysqli_query($db, $query)
or die('Error '.mysqli_error($db).' with query '.$query);


$movielist = [];

while($row = mysqli_fetch_assoc($result))
{ $movielist[] = $row;
}




// Close the connection
mysqli_close($db);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lijst</title>
</head>
<body>

<nav>
    <a href="home.php">Home</a>
    <a href="login.php">Login</a>
    <a href="registreer.php">Registreer</a>
    <a href="lijst.php">lijst</a>
    <a href="Create.php">create</a>

</nav>

<main>






    <div class="columns is-centered">
        <div class="column is-narrow">

            <table class="table is-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>film</th>
                    <th>Genre</th>
                    <th>Regiseur</th>
                    <th>te bekijken op</th>
                    <th>details</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="9" class="has-text-centered">&copy; My Collection</td>
                </tr>
                </tfoot>
                <tbody>


                <?php foreach ($movielist as $index => $movie) { ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $movie['film'] ?></td>
                        <td><?= $movie['genre'] ?></td>
                        <td><?= $movie['name'] ?></td>
                        <td><?= $movie['kijken_op'] ?></td>
                        <td><a href="details.php?id=<?= $movie['id'] ?>">zie Details</a></td>
                        <td><a href="edit.php?id=<?= htmlentities($movie['id']) ?>">Edit</a></td>
                        <td><a href="delete.php?id=<?= htmlentities($movie['id']) ?>">Delete</a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>









</body>



