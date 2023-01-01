<html>

    <head>
        <title>database_cyrill_ef5_formular</title>
        <meta charset="UTF-8">
    </head>

    <body style="background-color:dimgray">

    <h1>Neuer Datenbankbenutzer hinzufügen</h1>

        <form action = "../scripts/registration.php" method = "POST">
            <label for = "username">Benutzername</label>
            <br><input type = "text" id = "username" name = "username" maxlength = "100" required>

            <br><label for = "password">Passwort</label>
            <br><input type = "password" id = "password" name = "password" maxlength = "100" required>

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