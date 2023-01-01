<html>

    <head>
        <title>database_cyrill_ef5_formular</title>
        <meta charset="UTF-8">
    </head>

    <body style="background-color:dimgray">

        <h1>Einsatzort hinzufügen</h1>

        <form action = "../scripts/insert.php" method = "POST">
            <label for = "name_ort">Name Einsatzort</label>
            <br><input type = "text" id = "name_ort" name = "name_ort" maxlength = "100" required>

            <br><label for = "plz_ort">PLZ Einsatzort</label>
            <br><input type = "number" id = "plz_ort" name = "plz_ort" min = "1000" max = "9999" required>

            <br><label for = "stadt_ort">Stadt Einsatzort</label>
            <br><input type = "text" id = "stadt_ort" name = "stadt_ort" maxlength = "100" required>

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