<html>

    <head>
        <title>database_cyrill_ef5_formular</title>
        <meta charset="UTF-8">
    </head>

    <body style="background-color:dimgray">

        <h1>Einsatz hinzufügen</h1>

        <form action = "../scripts/insert.php" method = "POST">

            <label for = "name_einsatz">Name Einsatz</label>
            <br><input type = "text" id = "name_einsatz" name = "name_einsatz" maxlength = "100" required>

            <br><label for = "date_einsatz">Datum Einsatz</label>
            <br><input type = "date" id = "date_einsatz" name = "date_einsatz" required>

            <br><label for = "auftraggeber_name">Auftraggeber Name</label>
            <br><input type = "text" id = "auftraggeber_name" name = "auftraggeber_name" maxlength = "100" required>

            <br><label for = "ort_einsatz_name">Einsatzort Name</label>
            <br><input type = "text" id = "ort_einsatz_name" name = "ort_einsatz_name" maxlength = "100" required>

            <br><label for = "vorname_el">Vorname EL</label>
            <br><input type = "text" id = "vorname_el" name = "vorname_el" maxlength = "50" required>

            <br><label for = "nachname_el">Nachname EL</label>
            <br><input type = "text" id = "nachname_el" name = "nachname_el" maxlength = "50" required>

            <table border = "1" id = "form_table">
            <tr>
                <th colspan = "7">VKs hinzufügen</th>
            </tr>

            <td>1</td>

            <td><label for = "vk_vorname_einsatz1">Vorname VK</label></td>
            <td><input type = "text" id = "vk_vorname_einsatz1" name = "vk_vorname_einsatz1" maxlength = "50" required></td>

            <td><label for = "vk_nachname_einsatz1">Nachname VK</label></td>
            <td><input type = "text" id = "vk_nachname_einsatz1" name = "vk_nachname_einsatz1" maxlength = "50" required></td>

            <td><label for = "vk_einsatzstunden_einsatz1">Einsatzstuden VK</label></td>
            <td><input type = "number" id = "vk_einsatzstunden_einsatz1" name = "vk_einsatzstunden_einsatz1" min = "1" max = "12" required></td>

            </table>

            <br><input type = "button" value = "Zusätzlicher VK hinzufügen" onclick = "addRowToTable();">
            <input type = "button" value = "Letzte Reihe löschen" onclick = "removeRowFromTable();">

            <br><input type = "submit" value = "Absenden">
        </form>
    </body>
</html>

<script type = "text/javascript" src = "handle_einsatz_vk_table.js"></script>

<?php

// This little piece of php code is to redirect the user to the login page if no database user is logged in
session_start();
include("../scripts/functions_collection.php");
if (!is_user_logged_in()){
    redirect_user_to_login();
}

?>