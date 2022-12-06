<h1>
    MySQL: Create Database
</h1>

<?php

$servername = "localhost";
$username = "root";
$password = "root";

// Establish connection with mysql
try{
    // The optional "dbname = foo" after the mysql:host argument is left out here 
    // because the database might not even exist yet
    $conn_create = new PDO("mysql:host = $servername", $username, $password);
    // Print out mySQL errors on the webpage
    $conn_create -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected! Ready to create database!<br>";

} catch (PDOException $e){
    echo "Connection failed.<br>". $e -> getMessage();
}

function create_database(){
    global $conn_create;

    // Create the new database only if it doesn't already exists
    $create_command = $conn_create -> prepare("CREATE DATABASE IF NOT EXISTS database_cyrill_ef5;");
    $create_command -> execute();

    // Change to the newly created database
    $switch_to_new_database_command = $conn_create -> prepare("USE database_cyrill_ef5;");
    $switch_to_new_database_command -> execute();
}

function create_tables(){
    global $conn_create;

    // Create table Orte
    $create_orte_command = $conn_create -> prepare("
        CREATE TABLE IF NOT EXISTS orte(
            id_ort          INT AUTO_INCREMENT,
            name_ort        VARCHAR(100),
            plz_stadt_ort   VARCHAR(5),
            stadt_ort       VARCHAR(100),
            kanton_ort      VARCHAR(100),

            PRIMARY KEY (id_ort)
        );");

    // Create table Raenge
    $create_raenge_command = $conn_create -> prepare("
        CREATE TABLE IF NOT EXISTS raenge(
            id_rang             INT AUTO_INCREMENT,
            name_rang           VARCHAR(20),
            abkuerzung_rang     VARCHAR(5),
            stundenlohn_rang    FLOAT NOT NULL,

            PRIMARY KEY (id_rang)
        );");

    // Create table Adressen
    // Saving nummer_adresse as a VARCHAR(100) allows for house numbers like 4b
    // And no one will ever need to perform arithmetic operations on house nummers anyway
    $create_adressen_command = $conn_create -> prepare("
        CREATE TABLE IF NOT EXISTS adressen(
            id_adresse      INT AUTO_INCREMENT,
            kanton_adresse  VARCHAR(100),
            plz_adresse     VARCHAR(5),
            stadt_adresse   VARCHAR(100),
            strasse_adresse VARCHAR(100),
            nummer_adresse  VARCHAR(10),

            PRIMARY KEY (id_adresse)
        );");

    // Create table VKs
    $create_vks_command = $conn_create -> prepare("
        CREATE TABLE IF NOT EXISTS vks(
            id_vk           INT AUTO_INCREMENT,
            vorname_vk      VARCHAR(50),
            nachname_vk     VARCHAR(50),
            geburtsdatum_vk DATE NOT NULL,
            email_vk        VARCHAR(100),
            adresse_vk      INT NOT NULL,
            rang_vk         INT NOT NULL,

            PRIMARY KEY (id_vk),
            FOREIGN KEY (adresse_vk) REFERENCES adressen (id_adresse),
            FOREIGN KEY (rang_vk) REFERENCES raenge (id_rang)
        );");

    // Create table Auftraggeber
    $create_auftraggeber_command = $conn_create -> prepare("
        CREATE TABLE IF NOT EXISTS auftraggeber(
            id_auftraggeber                 INT AUTO_INCREMENT,
            name_auftraggeber               VARCHAR(100),
            email_auftraggeber              VARCHAR(100),
            rechnungsadresse_auftraggeber   INT NOT NULL,

            PRIMARY KEY (id_auftraggeber),
            FOREIGN KEY (rechnungsadresse_auftraggeber) REFERENCES adressen (id_adresse)
        );");

    // Create table Einsaetze
    $create_einsaetze_command = $conn_create -> prepare("
        CREATE TABLE IF NOT EXISTS einsaetze(
            id_einsatz              INT AUTO_INCREMENT,
            name_einsatz            VARCHAR(100),
            datum_einsatz           DATE NOT NULL,
            ort_einsatz             INT NOT NULL,
            auftraggeber_einsatz    INT NOT NULL,

            PRIMARY KEY (id_einsatz),
            FOREIGN KEY (ort_einsatz) REFERENCES orte (id_ort),
            FOREIGN KEY (auftraggeber_einsatz) REFERENCES auftraggeber (id_auftraggeber)
        );");

    // Create table Verbindung_VK_Einsatz
    // The combination of id_vk and id_einsatz could also serve as a PRIMARY KEY
    // lohn will be automatically calculated with zeit_geleistet and stundenlohn_rang of the vk referenced
    $create_verbindung_vk_einsatz_command = $conn_create -> prepare("
        CREATE TABLE IF NOT EXISTS verbindung_vk_einsatz(
            id_verbindung   INT AUTO_INCREMENT,
            vk              INT NOT NULL,
            einsatz         INT NOT NULL,
            zeit_geleistet  FLOAT NOT NULL,
            lohn            FLOAT NOT NULL,

            PRIMARY KEY (id_verbindung),
            FOREIGN KEY (vk) REFERENCES vks (id_vk),
            FOREIGN KEY (einsatz) REFERENCES einsaetze (id_einsatz)
        );");

    // Execute all the commands in an order that won't lead to a foreign key being created to a table that doesn't exist yet
    $create_orte_command -> execute();
    $create_raenge_command -> execute();
    $create_adressen_command -> execute();
    $create_vks_command -> execute();
    $create_auftraggeber_command -> execute();
    $create_einsaetze_command -> execute();
    $create_verbindung_vk_einsatz_command -> execute();
}

try{
    // Try to create the database and the tables
    create_database();
    echo "Database created! Ready to create tables!<br>";
    create_tables();
    echo "Tables created! Ready to insert data!<br>";

} catch (PDOException $e){
    // If this fails print out the error message
    echo "Database creation or filling failed with error: ". $e -> getMessage();
}

// Terminate connection with database
$conn_create = null;

?>