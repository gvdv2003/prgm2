<?php
/** @var mysqli $db */
require_once "includes/database.php";
// required when working with sessions
session_start();
// Is user logged in?
$email = '';
if (isset($_POST['submit'])) {
    // Get form data
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = $_POST['password'];
    // Server-side validation
    $errors = [];
    if($email == ""){
        $errors['email'] = 'Vergeet niet je email in te vullen';
    }
    if($password == ""){
        $errors['password'] = 'vergeet niet je wachtwoord in te vullen';
    }

    // If data valid
    if(empty($errors)){

        // SELECT the user from the database, based on the email address.
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($db, $query)
        or die('Error '.mysqli_error($db).' with query '.$query);
        // check if the user exists
        if(mysqli_num_rows($result) == 1){
            // Get user data from result
            $user = mysqli_fetch_assoc($result);
            // Check if the provided password matches the stored password in the database
            if(password_verify($password, $user['password'])){
                // Store the user in the session
                $_SESSION['email'] = $email;
                $_SESSION['firstname'] = $user['first_name'];
                // Redirect to secure page
                $_SESSION['login'] = true;
                if($_GET['page'] == 'create'){
                    header('Location: create.php');
                }else if($_GET['page'] == 'edit'){
                    $index = $_GET['id'];
                    header("Location: edit.php?id=$index");
                    echo 'edit';
                }else if($_GET['page'] == 'delete') {
                    $index = $_GET['id'];
                    header("Location: delete.php?id=$index");
                    echo 'delete';
                }else {
                    header('Location: lijst.php');
                    echo 'lijst';
                }
                // Credentials not valid
            }else{
                //error incorrect log in
                $errors['loginFailed'] = 'credentials not valid';
            }
            // User doesn't exist
        }else{
            //error incorrect log in
            $errors['loginFailed'] = 'credentials not valid';
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
    <title>Login</title>
</head>
<body>

<nav>
    <a href="home.php">Home</a>
    <a href="login.php">Login</a>
    <a href="registreer.php">Registreer</a>
    <a href="lijst.php">lijst</a>

</nav>

<main>


    <?php if (isset($_SESSION['login'])) { ?>
        <p>Je bent ingelogd!</p>
        <p><a href="logout.php">Uitloggen</a> / <a href="lijst.php">Naar de home pagina</a></p>

    <?php } else { ?>
        <p class="help is-danger ">
            <?php if(isset($_SESSION['error'])){
                echo $_SESSION['error'];
            }?>
        </p>
        <section class="columns">
            <form class="column is-6" action="" method="post">

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="email">Email</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control has-icons-left">
                                <input class="input" id="email" type="text" name="email" value="<?= htmlentities($email) ?? '' ?>" />
                                <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                            </div>
                            <p class="help is-danger">
                                <?= $errors['email'] ?? '' ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="password">Password</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control has-icons-left">
                                <input class="input" id="password" type="password" name="password"/>
                                <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>

                                <?php if(isset($errors['loginFailed'])) { ?>
                                    <div class="notification is-danger">
                                        <button class="delete"></button>
                                        <?=$errors['loginFailed']?>
                                    </div>
                                <?php } ?>

                            </div>
                            <p class="help is-danger">
                                <?= $errors['password'] ?? '' ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal"></div>
                    <div class="field-body">
                        <button class="button is-link is-fullwidth" type="submit" name="submit">Log in With Email</button>
                    </div>
                </div>

            </form>
        </section>

    <?php } ?>






</main>

</body>
</html>



