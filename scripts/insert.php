<h1>
    MySQL: Benutzerdaten hinzufügen
</h1>

<?php 

session_start();
include("functions_collection.php");

function extract_post_values(){
    $post_values = array();
    foreach($_POST as $value){
        array_push($post_values, $value);
    }
    return $post_values;
}

try {
    // Check if the user is logged in already
    if (isset($_SESSION["username"]) && isset($_SESSION["password"])){
        $username = $_SESSION["username"];
        $password = $_SESSION["password"];
    } else{
        throw new Exception("Sie sind noch nicht eingeloggt!<br>");
    }
    // Establish connection with database
    $conn_insert = create_connection("localhost", $username, $password, "database_cyrill_ef5");
    
    $post_values = extract_post_values();
    // Determine what form was just filled out by the user and then call the appropriate function
    switch (key($_POST)){
        case "name_einsatz": 
            validate_einsatz_values($post_values, $conn_insert);
            add_values_einsatz($post_values, $conn_insert); 
            break;
        case "name_auftraggeber": 
            // The validate functions for the auftraggeber only checks if the auftraggeber doesn't already exist in the database
            validate_auftraggeber_values($post_values, $conn_insert);
            add_values_auftraggeber($post_values, $conn_insert); 
            break;
        case "vorname_vk": 
            // Similar to the validate_auftraggeber_values function the validate function only checks if the vk doens't already exist in the database
            validate_vk_values($post_values, $conn_insert);
            add_values_vk($post_values, $conn_insert); 
            break;
        case "name_ort": 
            // Same as validate_vk_values
            validate_ort_values($post_values, $conn_insert);
            add_values_ort($post_values, $conn_insert); 
            break;
    }
    echo "Daten wurden erfolgreich hinzugefügt!<br>";
} catch (Exception $e){
    echo "Daten konnten nicht hinzugefügt werden: ".$e -> getMessage()."<br>";
}

// Terminate connection with database
$conn_insert = null;
?>

<html>
    <head>
        <meta charset = "utf-8"/>
        <title>MySQL Projekt EF Informatik</title>
    </head>

    <body style = "background-color:dimgray">
        <input id = "button" type = "button" name = "back_to_index" value = "Zurück zu index" onclick = "location.href = '../index.php'">
    </body>
</html>