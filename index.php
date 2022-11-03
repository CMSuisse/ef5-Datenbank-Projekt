<h1>
Hello there
</h1>

<?php

$servername = "localhost";
$username = "root";
$password = "Hannes";

try{
    $conn = new PDO("mysql:host=$servername;dbname=test_ef5", $username, $password);
    echo "Connected!";
} catch (PDOException $e){
    echo "Connection failed. " . $e->getMessage();
}

?>