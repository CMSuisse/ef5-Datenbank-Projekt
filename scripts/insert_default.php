<h1>
    MySQL: Defaultwerte hinzufügen
</h1>

<?php

session_start();
include("functions_collection.php");

// Here, the default values of the database are defined
// They are in the same format as a the post_values array after a form has been submitted
$vk_default_values = [
    ["Cyrill", "Marti", "2007-03-09", "cmarti@gmx.net", "8750", "Glarus", "Untere Pressistrasse", "9", "Verkehrskadett"],
    ["Vlad", "Verkehr", "2007-05-22", "vlad@verkehr.ch", "8750", "Riedern", "Schulhaushoschet", "5", "Aspirant"],
    ["Erik", "Eins", "1999-12-24", "erik@eins.ch", "8867", "Niederurnen", "Bödeckerstrasse", "20", "Zugführer"],
    ["Daniel", "Dürst", "2003-04-12", "daniel@duerst.ch", "8773", "Haslen", "Höslistrasse", "40", "Gruppenchef"]
];

$auftraggeber_default_values = [
    ["Gemeinde Glarus", "keine@ahnung.ch", "8750", "Glarus", "Gemeindehausplatz", "5"],
];

$einsatzort_default_values = [
    ["Klöntal Parkplatz Güntlenau", "8750", "Glarus"]
];

$einsatz_default_values = [
    ["Klöntal Parkdienst", "2022-08-17", "Gemeinde Glarus", "Klöntal Parkplatz Güntlenau", "Erik", "Eins", "Cyrill", "Marti", 5, "Daniel", "Dürst", 5],
    ["Klöntal Parkdienst", "2022-08-18", "Gemeinde Glarus", "Klöntal Parkplatz Güntlenau", "Erik", "Eins", "Cyrill", "Marti", 5, "Daniel", "Dürst", 5]
];

// This function adds the default values that were defined above
function add_default_values($conn){
    global $vk_default_values, $auftraggeber_default_values, $einsatz_default_values, $einsatzort_default_values;
    // One liners, wooooooooooooo...
    // Also calling the validate functions to avoid adding the default data twice
    foreach ($vk_default_values as $vk) {validate_vk_values($vk, $conn); add_values_vk($vk, $conn);}
    foreach ($auftraggeber_default_values as $auftraggeber) {validate_auftraggeber_values($auftraggeber, $conn); add_values_auftraggeber($auftraggeber, $conn);}
    foreach ($einsatzort_default_values as $einsatzort) {validate_ort_values($einsatzort, $conn); add_values_ort($einsatzort, $conn);}
    foreach ($einsatz_default_values as $einsatz) {validate_einsatz_values($einsatz, $conn); add_values_einsatz($einsatz, $conn);}
    // ...oooooooooooooo. Ok, I'll stop :-)
}

try{
    // Check if the user is logged in already
    if (isset($_SESSION["username"]) && isset($_SESSION["password"])){
        $username = $_SESSION["username"];
        $password = $_SESSION["password"];
    } else{
        throw new Exception("Sie sind noch nicht eingeloggt!<br>");
    }
    // Establish a connection with the database
    $conn_default = create_connection("localhost", $username, $password, "database_cyrill_ef5");
    add_default_values($conn_default);
    echo "Defaultwerte wurden hinzugefügt!<br>";
} catch (Exception $e){
    echo "Defaultwerte konnten nicht hinzugefügt werden: ".$e -> getMessage()."<br>";
}

$conn_default = null;
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