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
    include "delete.php";
    include "create.php";
} catch (PDOException $e){
    echo "Action failed: ". $e->getMessage()."<br>";
}

$faecher = [
    'Informatik',
    'Mathematik',
    'Deutsch',
    'Geographie',
    'Sport',
    'Biologie'
];

function insert_faecher_to_database($faecher){
    global $conn;

    foreach ($faecher as $value){
        try{
            $insert_schulfach_to_database = $conn->prepare("
            INSERT INTO schulfaecher (name_schulfach) VALUES ('$value')
            ;");
        
            $insert_schulfach_to_database->execute();
        } catch (PDOException $e){
            echo "Insert failed with error message: ".$e->getMessage()."<br>";
        }
    }
}

try{
    insert_faecher_to_database($faecher);
    echo "Data inserted<br>";
} catch (PDOException $e){
    echo "Action failed: ". $e->getMessage(). "<br>";
}

$conn = null;

?>