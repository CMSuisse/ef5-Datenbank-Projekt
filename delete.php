<h1>
    MySQL: Datenbank löschen
</h1>

<?php

include("functions_collection.php");

$servername = "localhost";
$username = "root";
$password = "root";

function delete_database(){
    global $conn_delete;
    // Prepare the command to delete the database
    $delete_database_command = $conn_delete -> prepare("
        DROP DATABASE IF EXISTS database_cyrill_ef5;"
    );
    // Execute the prepared command
    $delete_database_command -> execute();
}

try{
    // Establish connection with database
    $conn_delete = create_connection($servername, $username, $password, "database_cyrill_ef5");

    // Try to delete the database
    delete_database();
    echo "Datenbank gelöscht!<br>";

} catch (Exception $e){
    // Print out the error if one occured
    echo "Datenbank konnte nicht gelöscht werden: ". $e -> getMessage();
}

// Terminate connection with database
$conn_delete = null;
?>

<html>
    <head>
        <meta charset = "utf-8"/>
        <title>MySQL Projekt EF Informatik</title>
    </head>

    <body style = "background-color:dimgray">
        <input id = "button" type = "submit" name = "back_to_index" value = "Zurück zu index.html" onclick = "location.href = 'index.html'"/>
    </body>
</html>