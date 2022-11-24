<h1>
MySQL: Create Database
</h1>

<?php

$servername = "localhost";
$username = "root";
$password = "root";

//Establish connection with database
try{
    $conn_create = new PDO("mysql:host=$servername;dbname=test_ef5", $username, $password);
    $conn_create->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected!<br>";
} catch (PDOException $e){
    echo "Connection failed.<br>". $e->getMessage();
}

$createTableLehrperson = "
    CREATE TABLE lehrpersonen(
        id_lehrperson   INT AUTO_INCREMENT,
        first_name      VARCHAR(100) NOT NULL,
        last_name       VARCHAR(100) NOT NULL,
        schulfach       INT NOT NULL,
        PRIMARY KEY (id_lehrperson),
        FOREIGN KEY (schulfach) REFERENCES schulfaecher (id_schulfach)
    );";

$createTableBewertung = "
    CREATE TABLE bewertungen(
        id_bewertung        INT AUTO_INCREMENT,
        value_humor         TINYINT NOT NULL,
        value_unterricht    TINYINT NOT NULL,
        value_pruefungen    TINYINT NOT NULL,
        value_fachwissen    TINYINT NOT NULL,
        querbezug           TINYINT NOT NULL,
        lehrperson          INT NOT NULL,
        PRIMARY KEY (id_bewertung),
        FOREIGN KEY (lehrperson) REFERENCES lehrpersonen (id_lehrperson)
    );";

$createTableSchulfach = "
    CREATE TABLE schulfaecher(
        id_schulfach        INT AUTO_INCREMENT,
        name_schulfach      VARCHAR(100) NOT NULL,
        PRIMARY KEY(id_schulfach)
    );";

$createTableVLS = "
    CREATE TABLE vls(
        id_vls_instance        INT AUTO_INCREMENT,
        id_lehrer           INT NOT NULL,
        id_schulfach        INT NOT NULL,
        PRIMARY KEY (id_vls_instance),
        FOREIGN KEY (id_lehrer) REFERENCES lehrpersonen (id_lehrperson),
        FOREIGN KEY (id_schulfach) REFERENCES schulfaecher (id_schulfach)
    );";

// Execute mySQL commands
try{
    $conn_create->exec($createTableSchulfach);
    $conn_create->exec($createTableLehrperson);
    $conn_create->exec($createTableBewertung);
    $conn_create->exec($createTableVLS);
    echo "Tables created!<br>";
} catch (PDOException $e){
    echo "The requested action could not be executed.<br>".$e -> getMessage();
}

$conn_create = null;


//Beliebtheit von Lehrpersonen
//Entities:
// - Lehrpersonen
// -- id_lehrperson
// -- Name: String
// -- Vornamen: String
// -- Schulfach: Fremdschlüssel Schulfach
//
// - Bewertung
// -- id_bewertung
// -- Humor: 1-6
// -- Unterricht: 1-6
// -- Pruefungen: 1-6
// -- Fachwissen: 1-6
// -- Querbezug: Boolean
// -- Lehrperson: Fremdschlüssel Lehrperson
//
// - Schulfach
// -- id_schulfach
// -- Name: String
?>