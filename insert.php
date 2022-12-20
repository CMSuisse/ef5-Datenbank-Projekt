<h1>
    MySQL: Benutzerdaten hinzufügen
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
    $conn_insert = new PDO("mysql:host=$servername;dbname=database_cyrill_ef5;charset=utf8", $username, $password);
    // Print out mySQL errors on the webpage
    $conn_insert -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected! Ready to insert data!<br>";

} catch (PDOException $e){
    echo "Connection failed.<br>". $e -> getMessage();
}

function extract_post_values(){
    $post_values = array();
    foreach($_POST as $value){
        array_push($post_values, $value);
    }
    return $post_values;
}

// Here, the default values of the database are defined
// They are in the same format as a the post_values array after a form has been submitted
$vk_default_values = [
    ["Cyrill", "Marti", "2007-03-09", "cmarti@gmx.net", "8750", "Glarus", "Untere Pressistrasse", "9", "Verkehrskadett"],
    ["Vlad", "Verkehr", "2007-05-22", "vlad@verkehr.ch", "8750", "Riedern", "Schulhaushoschet", "5", "Aspirant"],
    ["Erik", "Eins", "1999-12-24", "erik@eins.ch", "8867", "Niederurnen", "Bödeckerstrasse", "20", "Zugführer"],
    ["Daniel", "Dürst", "2003-04-12", "daniel@duerst.ch", "8773", "Haslen", "Höslistrasse", "40", "Gruppenchef"]
];

$auftraggeber_default_values = [
    ["Gemeinde Glarus", "keine@ahnung.ch", "8750", "Glarus", "Gemeindehausplatz", "5"],
];

$einsatzort_default_values = [
    ["Klöntal Parkplatz Güntlenau", "8750", "Glarus"]
];

$einsatz_default_values = [
    ["Klöntal Parkdienst", "2022-08-17", "Gemeinde Glarus", "Klöntal Parkplatz Güntlenau", "Erik", "Eins", "Cyrill", "Marti", 5, "Daniel", "Dürst", 5],
    ["Klöntal Parkdienst", "2022-08-18", "Gemeinde Glarus", "Klöntal Parkplatz Güntlenau", "Erik", "Eins", "Cyrill", "Marti", 5, "Daniel", "Dürst", 5]
];

function validate_einsatz_values($post_values){
    global $conn_insert;
    // First, check if an einsatz with this name and date hasn't been added already
    $check_if_einsatz_exists_command = $conn_insert -> prepare(
        "SELECT EXISTS (SELECT id_einsatz FROM einsaetze WHERE name_einsatz = '$post_values[0]' AND datum_einsatz = '$post_values[1]');"
    );
    $check_if_einsatz_exists_command -> execute();
    $einsatz_exists = $check_if_einsatz_exists_command -> fetchColumn();

    // If the einsatz is already in the database throw an exception
    // If it isn't already in the database continue on with the rest of the function
    if ($einsatz_exists == 1){
        throw new Exception("In der Datenbank ist bereits ein Einsatz mit diesem Namen und Datum enthalten.");
    }
    // Check if the values that will be processed in the create_verbindung_vk_einsatz function won't cause an error
    // This is because if these values throw an error but the values for the einsatz itself won't, the einsatz will be created but the verbindungen table won't be updated
    // No need to check for if the values for the einsatz are valid as when they throw an error, neither the einsaetze nor the verbindungen table will be updated
    // For this, the einsatzleiter and all the vks participating have to be in the database
    // So, Einsatzleiter values stored at index 4 and 5 as well as additional vk names stored from index 6 on have to exist
    // First, check if the Einsatzleiter exists in the database
    // SELECT EXISTS returns a 1 if the entry has been found in the database and a 0 if the entry doesn't exist
    $check_for_el_command = $conn_insert -> prepare(
        "SELECT EXISTS (SELECT id_vk FROM vks WHERE vorname_vk = '$post_values[4]' AND nachname_vk = '$post_values[5]');"
    );
    $check_for_el_command -> execute();
    $el_exists = $check_for_el_command -> fetchColumn();

    // If the Einsatzleiter wasn't found in the database print out this message and throw an exception
    if ($el_exists == 0){
        throw new Exception("Der angegebene Einsatzleiter konnte nicht in der Datenbank gefunden werden. Überprüfen Sie, 
                            ob Sie den Namen richtig eingegeben haben oder fügen Sie ihn als neuer VK hinzu.");
    }

    // Then, iterate through the array beginning with index 6 until the end of the array to check the other vks
    for ($i = 6; $i < count($post_values); $i += 3){
        // MySQL didn't like it when I entered $i + 1 directly into $post_values[] so now we're all left with this beauty
        $index_nachname = $i + 1;
        $check_for_vk_command = $conn_insert -> prepare(
            "SELECT EXISTS (SELECT id_vk FROM vks WHERE vorname_vk = '$post_values[$i]' AND nachname_vk = '$post_values[$index_nachname]');"
        );
        $check_for_vk_command -> execute();
        $vk_exists = $check_for_vk_command -> fetchColumn();

        // If one of the vks isn't already in the database print out which one and then throw an exception
        if ($vk_exists == 0){
        throw new Exception("Der VK $post_values[$i] $post_values[$index_nachname] wurde nicht in der Datenbank gefunden.
                            Überprüfen Sie, ob Sie den Namen richtig eingegeben haben oder fügen Sie ihn als neuer VK hinzu.");
        }
    }
}

function validate_auftraggeber_values($post_values){
    global $conn_insert;
    // Just check if the name of the auftraggeber doesn't already exist in the database
    $check_if_auftraggeber_exists_command = $conn_insert -> prepare(
        "SELECT EXISTS (SELECT id_auftraggeber FROM auftraggeber WHERE name_auftraggeber = '$post_values[0]');"
    );
    $check_if_auftraggeber_exists_command -> execute();
    $auftraggeber_exists = $check_if_auftraggeber_exists_command -> fetchColumn();
    
    // If the auftraggeber does already exist throw an exception
    if ($auftraggeber_exists == 1){
        throw new Exception("Dieser Auftraggeber ist bereits in der Datenbank vorhanden.");
    }
}

function validate_vk_values($post_values){
    global $conn_insert;
    // Just check if the name of the vk doesn't already exist in the database
    // Sure, two vks with the same name can exist but then one of them is to be entered into the database with a slightly different name (e.g. vk foo bar and vk foo1 bar)
    $check_if_vk_exists_command = $conn_insert -> prepare(
        "SELECT EXISTS (SELECT id_vk FROM vks WHERE vorname_vk = '$post_values[0]' AND nachname_vk = '$post_values[1]');"
    );
    $check_if_vk_exists_command -> execute();
    $vk_exists = $check_if_vk_exists_command -> fetchColumn();

    // If a vk with name is already in the database throw an exception
    if ($vk_exists == 1){
        throw new Exception("Der Verkehrskadett $post_values[0] $post_values[1] ist bereits in der Datenbank vorhanden.");
    }
}

function validate_ort_values($post_values){
    global $conn_insert;
    // Just check if the name of the ort doesn't already exist in the database
    $check_if_ort_exists_command = $conn_insert -> prepare(
        "SELECT EXISTS (SELECT id_ort FROM orte WHERE name_ort = '$post_values[0]');"
    );
    $check_if_ort_exists_command -> execute();
    $ort_exits = $check_if_ort_exists_command -> fetchColumn();

    // If the ort is already in the database throw an exception
    if ($ort_exits == 1){
        throw new Exception("Dieser Einsatzort ist bereits in der Datenbank vorhanden.");
    }
}

// If the form posted was an einsatz_form then this function gets executed
function add_values_einsatz($post_values){
    global $conn_insert;
    //print_r($post_values);
    // Before inserting the data, get the ids for the Einsatzort, the Einatzleiter and the Auftraggeber
    // First, select the id of the Einsatzort which in the post_values array is stored at index 3
    $select_id_einsatzort = $conn_insert -> prepare(
        "SELECT id_ort FROM orte WHERE name_ort = '$post_values[3]';"
    );
    $select_id_einsatzort -> execute();
    $id_einsatzort = $select_id_einsatzort -> fetchColumn();

    // Then, do the same thing with the Einsatzleiter
    // Einsatzleiter Vorname is stored at index 4, Nachname at index 5
    $select_id_einsatzleiter = $conn_insert -> prepare(
        "SELECT id_vk FROM vks WHERE vorname_vk = '$post_values[4]' AND nachname_vk = '$post_values[5]';"
    );
    $select_id_einsatzleiter -> execute();
    $id_einsatzleiter = $select_id_einsatzleiter -> fetchColumn();

    // Finally, do the same with the Auftraggeber
    // Auftraggeber name is stored at index 2
    $select_id_auftraggeber = $conn_insert -> prepare(
        "SELECT id_auftraggeber FROM auftraggeber WHERE name_auftraggeber = '$post_values[2]';"
    );
    $select_id_auftraggeber -> execute();
    $id_auftraggeber = $select_id_auftraggeber -> fetchColumn();

    // After all the necessary ids have been collected, prepare and execute the command to insert a new einsatz into the database
    $insert_einsatz_command = $conn_insert -> prepare(
        "INSERT INTO einsaetze (name_einsatz, datum_einsatz, ort_einsatz, auftraggeber_einsatz, einsatzleiter_einsatz)
        VALUES ('$post_values[0]', '$post_values[1]', $id_einsatzort, $id_auftraggeber, $id_einsatzleiter);"
    );
    $insert_einsatz_command -> execute();

    // After the new einsatz has been created, the connection between the participating vks and it have to be made
    create_verbindung_vk_einsatz($post_values, $id_einsatzleiter);
}

// This function gets called by add_values_einsatz
function create_verbindung_vk_einsatz($post_values, $id_einsatzleiter){
    global $conn_insert;
    // For this, first select the id of the newly created einsatz
    $select_id_einsatz = $conn_insert -> prepare(
        "SELECT id_einsatz FROM einsaetze WHERE name_einsatz = '$post_values[0]' AND datum_einsatz = '$post_values[1]' AND einsatzleiter_einsatz = $id_einsatzleiter;"
    );
    $select_id_einsatz -> execute();
    $id_einsatz = $select_id_einsatz -> fetchColumn();

    $verbindung_vk_einsatz_values = array();
    $len_post_values = count($post_values);

    // Data for the vks for whom a verbindung has to be created is stored from index 6 on in post_values
    for ($i = 0; $i < ($len_post_values - 6)/3; $i++){
        $verbindung_vk_einsatz_value = array();
        for ($j = 0; $j <= 2; $j++){
            array_push($verbindung_vk_einsatz_value, $post_values[6]);
            // After having written the value to the new array delete it in post_values
            unset($post_values[6]);
            // unset doesn't change the array keys, so array_values creates new, enumerated keys
            $post_values = array_values($post_values);
        }
        // After the data for one verbindung has been created add it to the verbindung_vk_einsatz_values array
        array_push($verbindung_vk_einsatz_values, $verbindung_vk_einsatz_value);
    }

    // Now, go through verbindung_vk_einsatz_values and replace the vor- and nachname with the id of the vk as well as adding the id of
    // the einsatz the verbindung belongs to and 0 as a placeholder for lohn
    foreach ($verbindung_vk_einsatz_values as $verbindung){
        $index_verbindung = array_search($verbindung, $verbindung_vk_einsatz_values);
        $select_id_vk = $conn_insert -> prepare(
            "SELECT id_vk FROM vks WHERE vorname_vk = '$verbindung[0]' AND nachname_vk = '$verbindung[1]';"
        );
        $select_id_vk -> execute();
        $id_vk = $select_id_vk -> fetchColumn();
        // First, delete vorname_vk because array_replace can't replace two values with the same value
        unset($verbindung[0]);
        // Enumerate the keys again
        $verbindung = array_values($verbindung);
        // Then, replace index 0 (nachname_vk) with id_vk
        $verbindung = array_replace($verbindung, [0 => $id_vk]);
        // Also add the id of the einsatz and 0 as a placeholder for lohn
        array_push($verbindung, $id_einsatz, 0);
        // Now, replace the old with the new verbindung in the parent array
        $verbindung_vk_einsatz_values = array_replace($verbindung_vk_einsatz_values, [$index_verbindung => $verbindung]);
    }

    // Quickly add the verbindung for the Einsatzleiter
    // lohn is set to 20 instead of 0 which is because the einsatzleiter gets CHF20 on top of everything else for his efforts
    // There can be an additional verbindung for the hours the EL actually worked
    array_push($verbindung_vk_einsatz_values, [$id_einsatzleiter, 0, $id_einsatz, 20]);
    
    // Calculate lohn and create the verbindung
    foreach ($verbindung_vk_einsatz_values as $verbindung){
        $index_verbindung = array_search($verbindung, $verbindung_vk_einsatz_values);
        // First, fetch the id of the rang of the vk in the verbindung
        $fetch_rang_command = $conn_insert -> prepare(
            "SELECT rang_vk FROM database_cyrill_ef5.vks WHERE id_vk = $verbindung[0];"
        );
        $fetch_rang_command -> execute();
        $rang_vk_verbindung = $fetch_rang_command -> fetchColumn();

        // Then, fetch the stundenlohn parameter for this rang
        $fetch_stundenlohn_command = $conn_insert -> prepare(
            "SELECT stundenlohn_rang FROM database_cyrill_ef5.raenge WHERE id_rang = $rang_vk_verbindung;"
        );
        $fetch_stundenlohn_command -> execute();
        $stundenlohn_vk_verbindung = $fetch_stundenlohn_command -> fetchColumn();

        // Then, multiply stundenlohn and zeit_geleistet together and replace the placeholder with the final value
        // The + verbindung[3] is to not overwrite the einsatzleiter's lump sum
        $replacement = [3 => $stundenlohn_vk_verbindung * $verbindung[1] + $verbindung[3]];
        $verbindung = array_replace($verbindung, $replacement);

        // The array verbindung_vk_einsatz_values technically will never be needed again, but...
        // ANYWAYS!!!
        $replacement_parent_array = [$index_verbindung => $verbindung];
        $verbindung_vk_einsatz_values = array_replace($verbindung_vk_einsatz_values, $replacement_parent_array);
        
        // Insert values into verbindung_vk_einsatz_table
        $insert_command = $conn_insert -> prepare(
            "INSERT INTO verbindung_vk_einsatz (vk, zeit_geleistet, einsatz, lohn)
            VALUES ($verbindung[0], $verbindung[1], $verbindung[2], $verbindung[3]);"
        );
        $insert_command -> execute();

        // Also update the lohn_total value in the vks table
        $update_lohn_total_command = $conn_insert -> prepare(
            "UPDATE vks SET lohn_total = lohn_total + $verbindung[3] WHERE id_vk = $verbindung[0];"
        );
        $update_lohn_total_command -> execute();
    }
}

// If the form posted was a vk_form then this function gets executed
function add_values_vk($post_values){
    global $conn_insert;
    // In the vk_form the user entered values for two tables
    // The adressen table and the vk table
    // First the adressen table is filled because the vk table needs a foreign key to the adressen table
    // However, before filling the adressen table first one must ensure that the values the user entered don't already exist
    // Unfortunately because id_adresse is auto incrementing one can't just do an INSERT IGNORE or ON DUPLICATE KEY UPDATE
    $check_if_adresse_exists_command = $conn_insert -> prepare(
        "SELECT EXISTS (SELECT id_adresse FROM adressen WHERE plz_adresse = '$post_values[4]'
                                                        AND stadt_adresse = '$post_values[5]'
                                                        AND strasse_adresse = '$post_values[6]'
                                                        AND nummer_adresse = '$post_values[7]');"
    );
    $check_if_adresse_exists_command -> execute();
    $adresse_exists = $check_if_adresse_exists_command -> fetchColumn();
    // If the adresse doesn't already exist add it to the database
    // If it is already in the database, select its id
    if ($adresse_exists == 0){
        $insert_new_adresse_command = $conn_insert -> prepare(
            "INSERT INTO adressen (plz_adresse, stadt_adresse, strasse_adresse, nummer_adresse)
            VALUES ($post_values[4], '$post_values[5]', '$post_values[6]', '$post_values[7]');"
        );
        $insert_new_adresse_command -> execute();
    }

    // Then select the id of either the newly created adresse or the adresse that is already in the database
    $select_id_adresse_command = $conn_insert -> prepare(
        "SELECT id_adresse FROM adressen WHERE plz_adresse = $post_values[4]
                                        AND stadt_adresse = '$post_values[5]'
                                        AND strasse_adresse = '$post_values[6]'
                                        AND nummer_adresse = '$post_values[7]';"
    );
    $select_id_adresse_command -> execute();
    $id_adresse = $select_id_adresse_command -> fetchColumn();
    
    $select_id_rang_command = $conn_insert -> prepare(
        "SELECT id_rang FROM raenge WHERE name_rang = '$post_values[8]';"
    );
    $select_id_rang_command -> execute();
    $id_rang = $select_id_rang_command -> fetchColumn();

    // After adding the adresse to the adressen table and the id of the vks rang has been fetched, add the vk to the vks table
    $insert_vk_values_command = $conn_insert -> prepare(
        "INSERT INTO vks (vorname_vk, nachname_vk, geburtsdatum_vk, email_vk, adresse_vk, rang_vk)
        VALUES ('$post_values[0]', '$post_values[1]', '$post_values[2]', '$post_values[3]', $id_adresse, $id_rang);"
    );
    $insert_vk_values_command -> execute();
}

// If the form posted was a auftraggeber_form then this function gets executed
function add_values_auftraggeber($post_values){
    global $conn_insert;
    // This function writes to two tables:
    // The adressen table and the auftraggeber table
    // First, add the new adresse (if it doesn't exist already) and fetch its id analogous to add_values_vk
    $check_if_adresse_exists_command = $conn_insert -> prepare(
        "SELECT EXISTS (SELECT id_adresse FROM adressen WHERE plz_adresse = $post_values[2]
                                                        AND stadt_adresse = '$post_values[3]'
                                                        AND strasse_adresse = '$post_values[4]'
                                                        AND nummer_adresse = '$post_values[5]');"
    );
    $check_if_adresse_exists_command -> execute();
    $adresse_exists = $check_if_adresse_exists_command -> fetchColumn();
    // If the adresse doesn't already exist add it to the database
    // If it is already in the database, select its id
    if ($adresse_exists == 0){
        $insert_new_adresse_command = $conn_insert -> prepare(
            "INSERT INTO adressen (plz_adresse, stadt_adresse, strasse_adresse, nummer_adresse)
            VALUES ($post_values[2], '$post_values[3]', '$post_values[4]', '$post_values[5]');"
        );
        $insert_new_adresse_command -> execute();
    }

    // Then select the id of either the newly created adresse or the adresse that is already in the database
    $select_id_adresse_command = $conn_insert -> prepare(
        "SELECT id_adresse FROM adressen WHERE plz_adresse = $post_values[2]
                                        AND stadt_adresse = '$post_values[3]'
                                        AND strasse_adresse = '$post_values[4]'
                                        AND nummer_adresse = '$post_values[5]';"
    );
    $select_id_adresse_command -> execute();
    $id_adresse = $select_id_adresse_command -> fetchColumn();

    // After the new adresse has been created and its id fetched add the auftraggeber to the auftraggeber table
    $insert_auftraggeber_values_command = $conn_insert -> prepare(
        "INSERT INTO auftraggeber (name_auftraggeber, email_auftraggeber, rechnungsadresse_auftraggeber)
        VALUES ('$post_values[0]', '$post_values[1]', $id_adresse);"
    );
    $insert_auftraggeber_values_command -> execute();
}

function add_values_ort($post_values){
    global $conn_insert;
    // This is a really simple function its just a mysql query
    $insert_ort_values_command = $conn_insert -> prepare(
        "INSERT INTO orte (name_ort, plz_stadt_ort, stadt_ort)
        VALUES ('$post_values[0]', $post_values[1], '$post_values[2]');"
    );
    $insert_ort_values_command -> execute();
}

function add_default_values($vk_default_values, $einsatz_default_values, $auftraggeber_default_values, $einsatzort_default_values){
    // One liners, wooooooooooooo...
    // Also calling the validate functions to avoid adding the default data twice
    foreach ($vk_default_values as $vk) {validate_vk_values($vk); add_values_vk($vk);}
    foreach ($auftraggeber_default_values as $auftraggeber) {validate_auftraggeber_values($auftraggeber); add_values_auftraggeber($auftraggeber);}
    foreach ($einsatzort_default_values as $einsatzort) {validate_ort_values($einsatzort); add_values_ort($einsatzort);}
    foreach ($einsatz_default_values as $einsatz) {validate_einsatz_values($einsatz); add_values_einsatz($einsatz);}
    // ...oooooooooooooo. Ok, I'll stop :-)
}

try {
    // Function is called outside the main try catch clause as an exception thrown during the execution of this function is (hopefully) always "man-made"
    add_default_values($vk_default_values, $einsatz_default_values, $auftraggeber_default_values, $einsatzort_default_values);
    echo "Default data inserted successfully!<br>";
} catch (Exception $e){
    echo "Data insertion of default values failed with error: ".$e -> getMessage()."<br>";
}

try {
    $post_values = extract_post_values();
    // Determine what form was just filled out by the user and then call the appropriate function
    switch (key($_POST)){
        case "name_einsatz": 
            validate_einsatz_values($post_values);
            add_values_einsatz($post_values); 
            break;
        case "name_auftraggeber": 
            // The validate functions for the auftraggeber only checks if the auftraggeber doesn't already exist in the database
            validate_auftraggeber_values($post_values);
            add_values_auftraggeber($post_values); 
            break;
        case "vorname_vk": 
            // Similar to the validate_auftraggeber_values function the validate function only checks if the vk doens't already exist in the database
            validate_vk_values($post_values);
            add_values_vk($post_values); 
            break;
        case "name_ort": 
            // Same as validate_vk_values
            validate_ort_values($post_values);
            add_values_ort($post_values); 
            break;
    }
    echo "Data inserted successfully!<br>";
} catch (Exception $e){
    echo "Data insertion failed with error: ".$e -> getMessage()."<br>";
}

// Terminate connection with database
$conn_insert = null;
?>