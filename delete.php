<h1>
MySQL: Delete Database
</h1>

<?php

$servername = "localhost";
$username = "root";
$password = "root";

//Establish connection with database
try{
    $conn = new PDO("mysql:host=$servername;dbname=test_ef5", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected!<br>";
} catch (PDOException $e){
    echo "Connection failed.<br>". $e->getMessage();
}

try{
    $conn->exec("DROP DATABASE test_ef5;");
    $conn->exec("CREATE DATABASE test_ef5");
    echo "Database flushed.<br>";
} catch(PDOException $e){
    echo "Action failed: ".$e->getMessage()."<br>";
}

$conn = null;

?>