<h1>
    MySQL: Projekt EF Informatik
</h1>

<?php

$servername = "localhost";
$username = "root";
$password = "root";

// Establish connection with databse
try{
    // Try to create the database before connecting
    include "create.php";
    $conn_index = new PDO("mysql:host=$servername; dbname=database_cyrill_ef5", $username, $password);
    // Print out mySQL errors on the webpage
    $conn_index -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected! Ready to insert data!<br>";

} catch (PDOException $e){
    // If this ever gets printed out I'll have no idea what happened
    echo "Connection failed.<br>". $e->getMessage();
}

// Terminate connection with database
$conn_index = null;

?>