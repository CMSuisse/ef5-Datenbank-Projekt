<html>

    <head>
        <title>database_cyrill_ef5_formular</title>
        <meta charset="UTF-8">
    </head>

    <body style="background-color:dimgray">

        <h1>Auftraggeber hinzufügen</h1>

        <form action = "../scripts/insert.php" method = "POST">
            <label for = "name_auftraggeber">Name Auftraggeber</label>
            <br><input type = "text" id = "name_auftraggeber" name = "name_auftraggeber" maxlength = "100" required>

            <br><label for = "email_auftraggeber">e-mail Auftraggeber</label>
            <br><input type = "email" id = "email_auftraggeber" name = "email_auftraggeber" maxlength = "100" required>

            <br><label for = "adresse_plz_auftraggeber">Rechnungsadresse Auftraggeber PLZ</label>
            <br><input type = "number" id = "adresse_plz_auftraggeber" name = "adresse_plz_auftraggeber" min = "1000" max = "9999" required>

            <br><label for = "adresse_ort_auftraggeber">Rechnungsadresse Auftraggeber Name</label>
            <br><input type = "text" id = "adresse_ort_auftraggeber" name = "adresse_ort_auftraggeber" maxlength = "100" required>

            <br><label for = "adresse_strasse_auftraggeber">Rechnungsadresse Auftraggeber Strasse</label>
            <br><input type = "text" id = "adresse_strasse_auftraggeber" name = "adresse_strasse_auftraggeber" maxlength = "100" required>

            <!--The house number isn't a number input field to allow house numbers like 4b-->
            <br><label for = "adresse_hausnummer_auftraggeber">Rechnungsadresse Auftraggeber Hausnummer</label>
            <br><input type = "text" id = "adresse_hausnummer_auftraggeber" name = "adresse_hausnummer_auftraggeber" maxlength = "10" required>

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