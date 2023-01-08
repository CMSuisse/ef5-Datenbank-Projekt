<?php

session_start();
include("functions_collection.php");

function create_new_user($new_username, $new_password, $conn){
    // Using placeholders results in a mysql syntax error, so no sql injection proofing D:
    $create_new_user_command = $conn -> prepare(
        "CREATE USER IF NOT EXISTS '$new_username'@'localhost' IDENTIFIED BY '$new_password';"
    );
    $create_new_user_command -> execute();

    // I tried to make it possible to also create a root-like user on demand but when StackOverflow started talk about SQL SECURITY DEFINERs and INVOKERs, VIEWs and such stuff I gave up
    // The new user can add and delete data as well as update it (when this functionality is finally implemented 'cause I'm lazy) but cannot delete or create the database itself or add a new user
    $grant_privileges_command = $conn -> prepare("GRANT SELECT, INSERT, UPDATE, DELETE ON *.* TO '$new_username'@'localhost';");
    $grant_privileges_command -> execute();
    $flush_privileges_command = $conn -> prepare("FLUSH PRIVILEGES;");
    $flush_privileges_command -> execute();
}

try{
    // Check if root is logged in as root is the only user that can grant privileges
    if ($_SESSION["username"] == "root" && isset($_SESSION["password"])){
        $username = "root";
        $password = $_SESSION["password"];
        // Then check if the user has already filled out the registration form (could happen if the user just enters the adress in the browsers url)
        if (isset($_POST["username"]) && isset($_POST["password"])){
            $new_username = $_POST["username"];
            $new_password = $_POST["password"];
        } else{
            throw new Exception("Sie haben das Registrations-Form noch nicht ausgefüllt!<br>");
        }
    } else{
        throw new Exception("Sie sind nicht als root eingeloggt!<br>");
    }
    // Establish connection
    $conn_registration = create_connection("localhost", $username, $password, NULL);
    create_new_user($new_username, $new_password, $conn_registration);
    echo "Der User: $new_username mit Passwort: $new_password wurde erfolgreich hinzugefügt!<br>";
} catch(Exception $e){
    echo "Der neue User konnte nicht hinzugefügt werden: ".$e -> getMessage()."<br>";
}

?>

<html>
    <head>
        <meta charset = "utf-8">
        <title>MySQL Projekt EF Informatik</title>
    </head>

    <body style = "background-color:dimgray">
        <input id = "button" type = "button" name = "back_to_index" value = "Zurück zu index" onclick = "location.href = '../index.php'">
    </body>
</html>