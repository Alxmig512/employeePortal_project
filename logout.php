<?php
session_start();
session_unset();
session_destroy();
header("Location: login.html"); // Leitet den Benutzer zurück zur Login-Seite
exit();
?>