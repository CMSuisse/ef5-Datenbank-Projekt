<html>
    
    <head>
        <title>database_cyrill_ef5_formular</title>
        <meta charset="UTF-8">
    </head>

    <body style="background-color:dimgray">

        <h1>VK hinzufügen</h1>

        <form action = "../scripts/insert.php" method = "POST">
            <label for = "vorname_vk">Vorname VK</label>
            <br><input type = "text" id = "vorname_vk" name = "vorname_vk" maxlength = "50" required>

            <br><label for = "nachname_vk">Nachname VK</label>
            <br><input type = "text" id = "nachname_vk" name = "nachname_vk" maxlength = "50" required>

            <br><label for = "birthdate_vk">Geburtstag VK</label>
            <br><input type = "date" id = "birthdate_vk" name = "birthdate_vk" required>

            <br><label for = "email_vk">e-mail VK</label>
            <br><input type = "email" id = "email_vk" name = "email_vk" maxlength = "100" required>

            <br><label for = "adresse_plz_vk">Heimatort VK PLZ</label>
            <br><input type = "number" id = "adresse_plz_vk" name = "adresse_plz_vk" min = "1000" max = "9999" required>

            <br><label for = "adresse_ort_vk">Heimatort VK Name</label>
            <br><input type = "text" id = "adresse_ort_vk" name = "adresse_ort_vk" maxlength = "100" required>

            <br><label for = "adresse_strasse_vk">Heimatort VK Strasse</label>
            <br><input type = "text" id = "adresse_strasse_vk" name = "adresse_strasse_vk" maxlength = "100" required>

            <!--The house number isn't a number input field to allow house numbers like 4b-->
            <br><label for = "adresse_hausnummer_vk">Heimatort VK Hausnummer</label>
            <br><input type = "text" id = "adresse_hausnummer_vk" name = "adresse_hausnummer_vk" maxlength = "10" required>

            <br><label for = "rang_vk">Rang VK</label>
            <br><select id = "rang_vk" name = "rang_vk" required>
                <option value = "Aspirant">Aspirant</option>
                <option value = "Verkehrskadett">Verkehrskadett</option>
                <option value = "Gruppenchef">Gruppenchef</option>
                <option value = "Zugführer">Zugführer</option>
                <option value = "Einsatzleiter 2">Einsatzleiter 2</option>
                <option value = "Einsatzleiter 1">Einsatzleiter 1</option>
            </select>

            <br><input type = "submit" value = "Absenden">
        </form>
    </body>
</html>

<?php

// This little piece of php code is to redirect the user to the login page if no database user is logged in
session_start();
include("../scripts/functions_collection.php");
if (!is_user_logged_in()){
    redirect_user_to_login();
}

?>