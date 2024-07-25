<?php

// db_connect.php

$host = 'localhost'; // Datenbankserver
$db   = 'database'; // Name der Datenbank
$user = 'root'; // Datenbank-Benutzername
$pass = '';   // Datenbank-Passwort
$charset = 'utf8mb4'; // Zeichensatz

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Fehler als Ausnahme werfen
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch-Mode als Assoziatives Array
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Native Prepared Statements verwenden
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
} catch (PDOException $e) {
    echo 'Verbindung fehlgeschlagen: ' . $e->getMessage();
    exit();
}


?>