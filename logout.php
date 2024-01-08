<?php
// Start the session.
session_start();
// destroy the session.
session_unset();
session_destroy();
// Redirect to login page
header('Location: lijst.php');
// Exit the code.
?>


<h1>je bent uitgelogd</h1>
<a href="home.php">ga terug naar home</a>
<a href="lijst.php"> ga terug naar de film lijst</a>