<h1>
    MySQL: Projekt EF Informatik
</h1>

<html>
    <body style="background-color:dimgray"></body>
</html>

<?php

$servername = "localhost";
$username = "root";
$password = "root";

// Establish connection with databse
try{
    // Try to delete the database to avoid adding data twice
    include "delete.php";
    // Try to create the database before connecting
    include "create.php";
    $conn_index = new PDO("mysql:host=$servername;dbname=database_cyrill_ef5;charset=utf8", $username, $password);
    // Print out mySQL errors on the webpage
    $conn_index -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected! Ready to insert data!<br>";

} catch (PDOException $e){
    echo "Connection failed.<br>". $e -> getMessage();
}

// Store the values to insert into the tables in lists
$orte_values = [
    ["Klöntal Parkplatz Güntlenau", "8750", "Glarus"]
];

$raenge_values = [
    ["Aspirant", "ASP", 10.0],
    ["Verkehrskadett", "VK", 10.0],
    ["Gruppenchef", "GC", 10.5],
    ["Zugführer", "ZGF", 11.0],
    ["Einsatzleiter 2", "EL2", 11.5],
    ["Einsatzleiter 1", "EL1", 12.0]
];

$adressen_values = [
    ["8750", "Glarus", "Untere Pressistrasse", "9"],
    ["8867", "Niederurnen", "Speerstrasse", "35"],
    ["8750", "Glarus", "Gemeindehausplatz", "5"],
    ["8750", "Glarus", "Musterstrasse", "69"],
    ["8750", "Glarus", "Testgässlein", "42b"]
];

// The last two integers are foreign keys referencing the adressen and raenge table respectively
$vks_values = [
    ["Ben", "Bödecker", "2006-05-09", "ben.boedecker@stud.schulegl.ch", 2, 3],
    ["Cyrill", "Marti", "2007-03-09", "cyrill.marti@stud.schulegl.ch", 1, 2],
    ["Vlad", "Verkehr", "2005-06-13", "vladverkehr@lol.ch", 4, 2],
    ["Erik", "Eins", "1999-09-25", "erikeins@lol.ch", 5, 4]
];

// The last integer is a foreign key referencing the adressen table
$auftraggeber_values = [
    ["Gemeinde Glarus", "keine Ahnung", 3]
];

// The last three integers are foreign keys referencing the orte, auftraggeber and vks table respectively
$einsaetze_values = [
    ["Parkdienst Klöntal", "2022-06-18", 1, 1, 4],
    ["Parkdienst Klöntal", "2022-06-19", 1, 1, 4]
];

// Use 0 as a placeholder for lohn
// It is replaced in the insert_values_into_tables function
// because it requires other tables to already have values inserted into them
// The order here is (from index 0 to 3): vk, einsatz, zeit_geleistet, lohn
$verbindung_vk_einsatz_values = [
    [1, 1, 7.5, 0],
    [2, 1, 7.5, 0],
    [4, 1, 4, 0],
    [1, 2, 7, 0],
    [3, 2, 7, 0],
    [4, 2, 3, 0]
];

function insert_values_into_tables(
                                $orte_values, 
                                $raenge_values, 
                                $adressen_values, 
                                $vks_values, 
                                $auftraggeber_values,
                                $einsaetze_values,
                                $verbindung_vk_einsatz_values)
{
    global $conn_index;

    // Start with inserting into the orte table
    // Loop through the orte_values list defined above and insert the values into the table
    foreach ($orte_values as $ort){
        // Sure, php prepare commands are not sql injetion proof, but for this database this isn't really of concern
        $insert_command = $conn_index -> prepare(
            "INSERT INTO orte (name_ort, plz_stadt_ort, stadt_ort)
            VALUES ('$ort[0]', '$ort[1]', '$ort[2]');"
        );
        $insert_command -> execute();
    }

    // Then do the same thing for every other table
    // Insert values into raenge table
    foreach ($raenge_values as $rang){
        $insert_command = $conn_index -> prepare(
            "INSERT INTO raenge (name_rang, abkuerzung_rang, stundenlohn_rang)
            VALUES ('$rang[0]', '$rang[1]', '$rang[2]');"
        );
        $insert_command -> execute();
    }

    // Insert values into vks table
    foreach ($adressen_values as $adresse){
        $insert_command = $conn_index -> prepare(
            "INSERT INTO adressen (plz_adresse, stadt_adresse, strasse_adresse, nummer_adresse)
            VALUES ('$adresse[0]', '$adresse[1]', '$adresse[2]', '$adresse[3]');"
        );
        $insert_command -> execute();
    }

    // Insert values into vks table
    foreach ($vks_values as $vk){
        $insert_command = $conn_index -> prepare(
            "INSERT INTO vks (vorname_vk, nachname_vk, geburtsdatum_vk, email_vk, adresse_vk, rang_vk)
            VALUES ('$vk[0]', '$vk[1]', '$vk[2]', '$vk[3]', $vk[4], $vk[5]);"
        );
        $insert_command -> execute();
    }

    // Insert values into auftraggeber table
    foreach ($auftraggeber_values as $auftraggeber){
        $insert_command = $conn_index -> prepare(
            "INSERT INTO auftraggeber (name_auftraggeber, email_auftraggeber, rechnungsadresse_auftraggeber)
            VALUES ('$auftraggeber[0]', '$auftraggeber[1]', $auftraggeber[2]);"
        );
        $insert_command -> execute();
    }

    // Insert values into einsaetze table
    foreach ($einsaetze_values as $einsatz){
        $insert_command = $conn_index -> prepare(
            "INSERT INTO einsaetze(name_einsatz, datum_einsatz, ort_einsatz, auftraggeber_einsatz, einsatzleiter_einsatz)
            VALUES ('$einsatz[0]', '$einsatz[1]', $einsatz[2], $einsatz[3], '$einsatz[4]');"
        );
        $insert_command -> execute();
        // Einsatzleiter gets 20CHF for his efforts on top of everything else he might have earned
        // einsatz[4] is referencing the id of the vk who was Einsatzleiter
        $add_extra_lohn_einsatzleiter_command = $conn_index -> prepare("
            UPDATE vks SET lohn_total = lohn_total + 20 WHERE id_vk = '$einsatz[4]';"
        );
        $add_extra_lohn_einsatzleiter_command -> execute();

        $select_id_einsatz = $conn_index -> prepare(
            "SELECT id_einsatz FROM einsaetze WHERE name_einsatz = '$einsatz[0]' AND datum_einsatz = '$einsatz[1]' AND einsatzleiter_einsatz = $einsatz[4];"
        );
        $select_id_einsatz -> execute();
        $id_einsatz = $select_id_einsatz -> fetchColumn();
        // Quickly add the verbindung for the Einsatzleiter
        // lohn is set to 20 instead of 0 which is because the einsatzleiter gets CHF20 on top of everything else for his efforts
        // There can be an additional verbindung for the hours the EL actually worked
        array_push($verbindung_vk_einsatz_values, [$einsatz[4], $id_einsatz, 0, 20]);
    }
    
    // Calculate lohn and create the verbindung
    foreach ($verbindung_vk_einsatz_values as $verbindung){
        $index_verbindung = array_search($verbindung, $verbindung_vk_einsatz_values);
        // First, fetch the id of the rang of the vk in the verbindung
        $fetch_rang_command = $conn_index -> prepare(
            "SELECT rang_vk FROM vks WHERE id_vk = $verbindung[0];"
        );
        $fetch_rang_command -> execute();
        $rang_vk_verbindung = $fetch_rang_command -> fetchColumn();

        // Then, fetch the stundenlohn parameter for this rang
        $fetch_stundenlohn_command = $conn_index -> prepare(
            "SELECT stundenlohn_rang FROM raenge WHERE id_rang = $rang_vk_verbindung;"
        );
        $fetch_stundenlohn_command -> execute();
        $stundenlohn_vk_verbindung = $fetch_stundenlohn_command -> fetchColumn();

        // Then, multiply stundenlohn and zeit_geleistet together and replace the placeholder with the final value
        // The + verbindung[3] is to not overwrite the einsatzleiter's lump sum
        $replacement = [3 => $stundenlohn_vk_verbindung * $verbindung[2] + $verbindung[3]];
        $verbindung = array_replace($verbindung, $replacement);

        // The array verbindung_vk_einsatz_values technically will never be needed again, but...
        // ANYWAYS!!!
        $replacement_parent_array = [$index_verbindung => $verbindung];
        $verbindung_vk_einsatz_values = array_replace($verbindung_vk_einsatz_values, $replacement_parent_array);
        
        // Insert values into verbindung_vk_einsatz_table
        $insert_command = $conn_index -> prepare(
            "INSERT INTO verbindung_vk_einsatz (vk, einsatz, zeit_geleistet, lohn)
            VALUES ($verbindung[0], $verbindung[1], $verbindung[2], $verbindung[3]);"
        );
        $insert_command -> execute();

        // Also update the lohn_total value in the vks table
        $update_lohn_total_command = $conn_index -> prepare(
            "UPDATE vks SET lohn_total = lohn_total + $verbindung[3] WHERE id_vk = $verbindung[0];"
        );
        $update_lohn_total_command -> execute();
    }
}                                  

// Try to execute the insert_values_into_tables function
try{
    insert_values_into_tables(
                            $orte_values, 
                            $raenge_values,
                            $adressen_values,
                            $vks_values,
                            $auftraggeber_values,
                            $einsaetze_values,
                            $verbindung_vk_einsatz_values
    );
    // Print out confirmation on the webpage
    echo "Data inserted into database!<br>";

} catch(PDOException $e){
    // Print out the mysql error on the webpage
    echo "Data insertion failed with error: ". $e -> getMessage();
}

// Terminate connection with database
$conn_index = null;

?>

<html>
<h1>Cool links</h1>
<a href = "http://localhost/us_opt1/index.php" target = "_blank">phpMyAdmin</a>
<br><a href = "https://github.com/CMSuisse/ef5-Datenbank-Projekt" target = "_blank">GitHub repo</a>
</html>