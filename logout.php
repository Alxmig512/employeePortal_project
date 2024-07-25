<?php
session_start();
session_unset();
session_destroy();
setcookie("user", "", time() - 3600, "/");
header("Location: login.html"); // Leitet den Benutzer zurück zur Login-Seite
exit();
?>