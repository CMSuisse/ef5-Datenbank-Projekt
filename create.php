<h1>
    MySQL: Datenbank erstellen
</h1>

<?php

session_start();
include("functions_collection.php");

// These values will be used by the add_values_raenge function
$raenge_values = [
    ["Aspirant", "ASP", 10.0],
    ["Verkehrskadett", "VK", 10.0],
    ["Gruppenchef", "GC", 10.5],
    ["Zugführer", "ZGF", 11.0],
    ["Einsatzleiter 2", "EL2", 11.5],
    ["Einsatzleiter 1", "EL1", 12.0]
];

function create_database($conn){
    // Create the new database only if it doesn't already exists
    $create_command = $conn -> prepare("CREATE DATABASE IF NOT EXISTS database_cyrill_ef5;");
    $create_command -> execute();

    // Change to the newly created database
    $switch_to_new_database_command = $conn -> prepare("USE database_cyrill_ef5;");
    $switch_to_new_database_command -> execute();
}

function create_tables($conn){
    // Create table Orte
    $create_orte_command = $conn -> prepare("
        CREATE TABLE IF NOT EXISTS orte(
            id_ort          INT AUTO_INCREMENT,
            name_ort        VARCHAR(100) NOT NULL,
            plz_stadt_ort   VARCHAR(5) NOT NULL,
            stadt_ort       VARCHAR(100) NOT NULL,

            PRIMARY KEY (id_ort)
        );");

    // Create table Raenge
    $create_raenge_command = $conn -> prepare("
        CREATE TABLE IF NOT EXISTS raenge(
            id_rang             INT AUTO_INCREMENT,
            name_rang           VARCHAR(20) NOT NULL,
            abkuerzung_rang     VARCHAR(5) NOT NULL,
            stundenlohn_rang    FLOAT NOT NULL,

            PRIMARY KEY (id_rang)
        );");

    // Create table Adressen
    // Saving nummer_adresse as a VARCHAR(100) allows for house numbers like 4b
    // And no one will ever need to perform arithmetic operations on house nummers anyway
    $create_adressen_command = $conn -> prepare("
        CREATE TABLE IF NOT EXISTS adressen(
            id_adresse      INT AUTO_INCREMENT,
            plz_adresse     VARCHAR(5) NOT NULL,
            stadt_adresse   VARCHAR(100) NOT NULL,
            strasse_adresse VARCHAR(100) NOT NULL,
            nummer_adresse  VARCHAR(10) NOT NULL,

            PRIMARY KEY (id_adresse)
        );");

    // Create table VKs
    // lohn_total has default value 0.0 because it is never set
    // to a value but rather always updated
    $create_vks_command = $conn -> prepare("
        CREATE TABLE IF NOT EXISTS vks(
            id_vk           INT AUTO_INCREMENT,
            vorname_vk      VARCHAR(50) NOT NULL,
            nachname_vk     VARCHAR(50) NOT NULL,
            geburtsdatum_vk DATE NOT NULL,
            email_vk        VARCHAR(100) NOT NULL,
            adresse_vk      INT NOT NULL,
            rang_vk         INT NOT NULL,
            lohn_total      FLOAT DEFAULT 0.0,

            PRIMARY KEY (id_vk),
            FOREIGN KEY (adresse_vk) REFERENCES adressen (id_adresse),
            FOREIGN KEY (rang_vk) REFERENCES raenge (id_rang)
        );");

    // Create table Auftraggeber
    $create_auftraggeber_command = $conn -> prepare("
        CREATE TABLE IF NOT EXISTS auftraggeber(
            id_auftraggeber                 INT AUTO_INCREMENT,
            name_auftraggeber               VARCHAR(100) NOT NULL,
            email_auftraggeber              VARCHAR(100) NOT NULL,
            rechnungsadresse_auftraggeber   INT NOT NULL,

            PRIMARY KEY (id_auftraggeber),
            FOREIGN KEY (rechnungsadresse_auftraggeber) REFERENCES adressen (id_adresse)
        );");

    // Create table Einsaetze
    $create_einsaetze_command = $conn -> prepare("
        CREATE TABLE IF NOT EXISTS einsaetze(
            id_einsatz              INT AUTO_INCREMENT,
            name_einsatz            VARCHAR(100) NOT NULL,
            datum_einsatz           DATE NOT NULL,
            ort_einsatz             INT NOT NULL,
            auftraggeber_einsatz    INT NOT NULL,
            einsatzleiter_einsatz   INT NOT NULL,

            PRIMARY KEY (id_einsatz),
            FOREIGN KEY (ort_einsatz) REFERENCES orte (id_ort),
            FOREIGN KEY (auftraggeber_einsatz) REFERENCES auftraggeber (id_auftraggeber),
            FOREIGN KEY (einsatzleiter_einsatz) REFERENCES vks (id_vk)
        );");

    // Create table Verbindung_VK_Einsatz
    // The combination of id_vk and id_einsatz could also serve as a PRIMARY KEY
    // lohn will be automatically calculated with zeit_geleistet and stundenlohn_rang of the vk referenced
    $create_verbindung_vk_einsatz_command = $conn -> prepare("
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

// This function adds the raenge values that were defined on line 7 to the database
// raenge are not a default value as they are constant
function add_values_raenge($conn, $raenge_values) {
    // First, check if there aren't already values in the raenge table
    $check_if_raenge_is_filled = $conn -> prepare(
        "SELECT EXISTS (SELECT * FROM raenge);"
    );
    $check_if_raenge_is_filled -> execute();
    $raenge_is_filled = $check_if_raenge_is_filled -> fetchColumn();

    // Insert values into raenge table if there aren't any values in the raenge table
    if ($raenge_is_filled == 0){
        foreach ($raenge_values as $rang){
            $insert_command = $conn -> prepare(
                "INSERT INTO raenge (name_rang, abkuerzung_rang, stundenlohn_rang)
                VALUES ('$rang[0]', '$rang[1]', '$rang[2]');"
            );
            $insert_command -> execute();
        }
    }   
}

try{
    // Check if the user is logged in already
    if (isset($_SESSION["username"]) && isset($_SESSION["password"])){
        $username = $_SESSION["username"];
        $password = $_SESSION["password"];
    } else{
        throw new Exception("Sie sind noch nicht eingeloggt!<br>");
    }
    // Establish connection with the database
    $conn_create = create_connection("localhost", $username, $password, NULL);

    // Try to create the database and the tables
    create_database($conn_create);
    echo "Datenbank erstellt!<br>";
    create_tables($conn_create);
    echo "Tabellen erstellt!<br>";
    add_values_raenge($conn_create, $raenge_values);
    echo "Werte der Ränge-Tabelle hinzugefügt!<br>";

} catch (Exception $e){
    // If this fails print out the error message
    echo "Datenbank konnte nicht erstellt werden: ". $e -> getMessage()."<br>";
}

// Terminate connection with database
$conn_create = null;
?>

<html>
    <head>
        <meta charset = "utf-8"/>
        <title>MySQL Projekt EF Informatik</title>
    </head>

    <body style = "background-color:dimgray">
        <input id = "button" type = "submit" name = "back_to_index" value = "Zurück zu index" onclick = "location.href = 'index.html'">
    </body>
</html>