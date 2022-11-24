<h1>
MySQL: Delete Database
</h1>

<?php

$servername = "localhost";
$username = "root";
$password = "root";

//Establish connection with database
try{
    $conn_delete = new PDO("mysql:host=$servername;dbname=test_ef5", $username, $password);
    $conn_delete->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected!<br>";
} catch (PDOException $e){
    echo "Connection failed.<br>". $e->getMessage();
}

try{
    $conn_delete->exec("DROP DATABASE test_ef5;");
    $conn_delete->exec("CREATE DATABASE test_ef5");
    echo "Database flushed.<br>";
} catch(PDOException $e){
    echo "Action failed: ".$e->getMessage()."<br>";
}

$conn_delete = null;

?>