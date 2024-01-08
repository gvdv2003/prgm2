<?php
/** @var mysqli $db */
require_once "includes/database.php";

// Get form data
$user= [];
$firstName = "";
$lastName = "";
$email = "";
$password = "";

if (isset($_POST['submit'])) {
    $firstName = mysqli_escape_string($db, $_POST['firstName']);
    $lastName = mysqli_escape_string($db, $_POST['lastName']);
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = $_POST['password'];




    $errors = [];
    if ($firstName == "") {
        $errors['firstname'] = "vergeet niet je voornaam in te vullen";
    }
    if ($lastName == "") {
        $errors['lastname'] = "Vergeet niet je achternaam in te vullen";
    }
    if ($email == "") {
        $errors['e-mail'] = "vergeet niet je email in te vullen";
    }
    if ($password == "") {
        $errors['password'] = "vergeet niet je wachtwoord in te vullen";
    }

    // If data valid
    if (empty($errors)) {
        // create a secure password, with the PHP function password_hash()
        $password = password_hash($password, PASSWORD_DEFAULT);
        // store the new user in the database.
        $query = "INSERT INTO users (email, password, first_name, last_name)
                    VALUES('$email', '$password', '$firstName', '$lastName')";
        $result = mysqli_query($db, $query);
        // If query succeeded
        if ($result) {
            // Redirect to login page
            header('Location: login.php');
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
    <title>registreer</title>
</head>
<body>

<nav>
    <a href="home.php">Home</a>
    <a href="login.php">Login</a>
    <a href="registreer.php">Registreer</a>
    <a href="lijst.php">lijst</a>

</nav>

<main>




    <h2>Registreren</h2>
    <form action="registreer.php" method="post">
        <label for="firstName">
            Voornaam:
            <input type="text" id="firstName" name="firstName" value="<?= htmlentities($firstName)?>"/>
            <p class="help is-danger">
                <?= $errors['firstName'] ?? '' ?>
            </p>
        </label>

        <label for="lastName">
            Achternaam:
            <input type="text" id="lastName" name="lastName" value="<?= htmlentities($lastName)?>"/>
            <p class="help is-danger">
                <?= $errors['lastName'] ?? '' ?>
            </p>
        </label>

        <label for="email">
            E-mail:
            <input type="text" id="email" name="email" value="<?= htmlentities($email)?>"/>
            <p class="help is-danger">
                <?= $errors['email'] ?? '' ?>
            </p>
        </label>

        <label for="password">
            Wachtwoord:
            <input type="text" id="password" name="password" value="<?= $password ?? ''?>"/>
            <p class="help is-danger">
                <?= $errors['password'] ?? '' ?>
            </p>
        </label>

        <input type="submit" name="submit">


    </form





</main>

</body>
</html>




