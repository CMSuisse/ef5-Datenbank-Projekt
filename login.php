<h1>MySQL: Login</h1>

<?php

// The session is used to pass the username and password on to other pages
session_start();
include("functions_collection.php");

try{
    if (isset($_SESSION["username"]) && isset($_SESSION["password"])){
        echo "Sie sind bereits eingeloggt!<br>";
        // The error is echoed out because the exception message won't be displayed
        throw new Exception("User already logged in");
    }
    $username = $_POST["username"];
    $password = $_POST["password"];
    // Create a connection as a test to see if an exception is thrown
    $conn = create_connection("localhost", $username, $password, NULL);
    // The PDO connection itself cannot be written to the SESSION variable, so it is no longer needed
    $conn = null;
    echo "Login erfolgreich! Willkommen $username!<br>";
    // It isn't secure, it isn't smart but I just can't be fucked to learn more about servers and connections for a database that will never leave localhost. May god forgive my sins and may one never obtain the session-ID
    $_SESSION["username"] = $username;
    $_SESSION["password"] = $password;
} catch (Exception $e){
    // REMOVE THE $e->getMessage()!!!! ONLY FOR DEBUGGING!!!
    echo "Das Login war nicht erfolgreich! Überprüfen Sie, ob Sie alle Daten richtig eingegeben haben oder registrieren Sie sich als neuer User!<br>";
}
?>

<html>
    <head>
        <meta charset = "utf-8"/>
        <title>MySQL Projekt EF Informatik</title>
    </head>

    <body style = "background-color:dimgray">
        <input id = "button" type = "submit" name = "goto_index" value = "Zu index" onclick = "location.href = 'index.html'">
    </body>
</html>