<h1>
    MySQL: Delete database
</h1>

<?php

$servername = "localhost";
$username = "root";
$password = "root";

// Establish connection with database
try{
    $conn_delete = new PDO("mysql:host=$servername", $username, $password);
    // Print out mySQL errors on the webpage
    $conn_delete -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected! Ready to delete database!<br>";

} catch (PDOException $e){
    echo "Connection failed.<br>". $e->getMessage();
}

function delete_database(){
    global $conn_delete;
    // Prepare the command to delete the database
    $flush_database_command = $conn_delete -> prepare("
        DROP DATABASE database_cyrill_ef5;"
    );
    // Execute the prepared command
    $flush_database_command -> execute();
}


try{
    // Try to delete the database
    delete_database();
    echo "Database deleted!<br>";

} catch (PDOException $e){
    // Print out the error if one occured
    echo "Deletion failed with error: ". $e -> getMessage();
}

// Terminate connection with database
$conn_delete = null;

?>