<h1>
MySQL: Do everything
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

$insert_informatik_to_schulfaecher = $conn->prepare("INSERT INTO schulfaecher (name_schulfach) VALUES ('Informatik');");
$insert_temperli_to_lehrpersonen = $conn->prepare("INSERT INTO lehrpersonen (first_name, last_name, schulfach) VALUES ('Beat', 'Temperli', 1);");
$insert_timon_to_lehrpresonen = $conn->prepare("INSERT INTO lehrpersonen (first_name, last_name, schulfach) VALUES ('Timon', 'Ruther', 1);");

try{
    include "delete.php";
    include "create.php";
    $insert_informatik_to_schulfaecher->execute();
    $insert_temperli_to_lehrpersonen->execute();
    $insert_timon_to_lehrpresonen->execute();
    echo "Data inserted<br>";
} catch (PDOException $e){
    echo "Action failed: ". $e->getMessage(). "<br>";
}

$conn = null;

?>