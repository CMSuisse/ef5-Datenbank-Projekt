<html>
    <head>
        <meta charset = "utf-8"/>
        <title>MySQL Projekt EF Informatik</title>
    </head>

    <body style="background-color:dimgray">
        <h1>MySQL: Projekt EF Informatik</h1>

        <h2>Cool links</h2>
        <a href = "http://localhost/us_opt1/index.php" target = "_blank">phpMyAdmin</a>
        <br><a href = "https://github.com/CMSuisse/ef5-Datenbank-Projekt" target = "_blank">GitHub repo</a>

        <h2>Control panel</h2>
        <input id = "delete_db" type = "button" name = "delete_db" value = "Datenbank löschen" onclick = "location.href = 'scripts/delete.php'">
        <input id = "create_db" type = "button" name = "create_db" value = "Datenbank erstellen" onclick = "location.href = 'scripts/create.php'">
        <br><br>
        <input id = "add_default" type = "button" name = "add_default" value = "Defaultwerte hinzufügen" onclick = "location.href = 'scripts/insert_default.php'">
        <input id = "goto_einsatz_form" type = "button" name = "goto_einsatz_form" value = "Einsatz erfassen" onclick = "location.href = 'forms/einsatz_form.php'">
        <input id = "goto_vk_form" type = "button" name = "goto_vk_form" value = "VK erfassen" onclick = "location.href = 'forms/vk_form.php'">
        <input id = "goto_auftraggeber_form" type = "button" name = "goto_auftraggeber_form" value = "Auftraggeber erfassen" onclick = "location.href = 'forms/auftraggeber_form.php'">
        <input id = "goto_ort_form" type = "button" name = "goto_ort_form" value = "Einsatzort erfassen" onclick = "location.href = 'forms/ort_form.php'">
        <br><br>
        <input id = "goto_login_form" type = "button" name = "goto_login_form" value = "Log in" onclick = "location.href = 'forms/login_form.html'">
        <input id = "goto_logout" type = "button" name = "goto_logout" value = "Log off" onclick = "location.href = 'scripts/logout.php'">
        <br><br>
    </body>
</html>

<?php

session_start();
// Just print out the username of the current user and redirect the page to the login form when no user is logged in
if(isset($_SESSION["username"])){
    $username = $_SESSION["username"];
    echo "Sie sind eingeloggt als: ".$username."!<br>";
} else{
    echo "<script type = 'text/javascript'>window.location.replace('forms/login_form.html')</script>";
}

?>