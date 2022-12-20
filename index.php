<h1>
    MySQL: Projekt EF Informatik
</h1>

<html>
    <body style="background-color:dimgray"></body>
</html>

<?php

$servername = "localhost";
$username = "root";
$password = "root";

// Establish connection with databse
try{
    // Try to delete the database to avoid adding data twice
    include "delete.php";
    // Try to create the database before connecting
    include "create.php";
    $conn_index = new PDO("mysql:host=$servername;dbname=database_cyrill_ef5;charset=utf8", $username, $password);
    // Print out mySQL errors on the webpage
    $conn_index -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected! Ready to insert data!<br>";

} catch (PDOException $e){
    echo "Connection failed.<br>". $e -> getMessage();
}

// The raenge values are the only values that are inserted in the index file as the values there are the only constants in the project
$raenge_values = [
    ["Aspirant", "ASP", 10.0],
    ["Verkehrskadett", "VK", 10.0],
    ["Gruppenchef", "GC", 10.5],
    ["Zugführer", "ZGF", 11.0],
    ["Einsatzleiter 2", "EL2", 11.5],
    ["Einsatzleiter 1", "EL1", 12.0]
];

function insert_raenge_values($raenge_values) {
    global $conn_index;
    // First, check if there aren't already values in the raenge table
    // Insert values into raenge table
    foreach ($raenge_values as $rang){
        $insert_command = $conn_index -> prepare(
            "INSERT INTO raenge (name_rang, abkuerzung_rang, stundenlohn_rang)
            VALUES ('$rang[0]', '$rang[1]', '$rang[2]');"
        );
        $insert_command -> execute();
    }
}                                  

// Try to execute the insert_values_into_tables function
try{
    insert_raenge_values($raenge_values);
    // Print out confirmation on the webpage
    echo "Data inserted into database!<br>";

} catch(PDOException $e){
    // Print out the mysql error on the webpage
    echo "Data insertion failed with error: ". $e -> getMessage();
}

// Terminate connection with database
$conn_index = null;

?>

<html>
<h1>Cool links</h1>
<a href = "http://localhost/us_opt1/index.php" target = "_blank">phpMyAdmin</a>
<br><a href = "https://github.com/CMSuisse/ef5-Datenbank-Projekt" target = "_blank">GitHub repo</a>
</html>