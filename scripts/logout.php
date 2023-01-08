<h1>MySQL: Logout</h1>

<?php

session_start();
session_unset();
session_destroy();
echo "Sie wurden ausgeloggt!<br>";

?>

<html>
    <head>
        <meta charset = "utf-8">
        <title>MySQL Projekt EF Informatik</title>
    </head>

    <body style = "background-color:dimgray">
</html>