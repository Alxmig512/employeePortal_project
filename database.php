<?php
// Stelle sicher, dass Fehler angezeigt werden
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Datenbankverbindung aufbauen 
$dsn = 'mysql:host=localhost;dbname=database';
$username = 'alex.mig';
$password = 'a54321';

try {
    // PDO-Verbindung aufbauen
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Eingehende JSON-Daten empfangen
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Handle POST request for saving data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($data['vorname'], $data['nachname'], $data['adresse'], $data['abteilung'], $data['telefon'], $data['username'], $data['passwort'])) {
            //Einfügen neuer Daten in die DB
            $sql = "INSERT INTO deine_tabelle (vorname, nachname, adresse, abteilung, telefon, username, passwort) 
                    VALUES (:vorname, :nachname, :adresse, :abteilung, :telefon, :username, :passwort)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'vorname' => $data['vorname'],
                'nachname' => $data['nachname'],
                'adresse' => $data['adresse'],
                'abteilung' => $data['abteilung'],
                'telefon' => $data['telefon'],
                'username' => $data['username'],
                'passwort' => $data['passwort'],
            ]);

            
            $response = ['success' => true];
            echo json_encode($response);
        } elseif (isset($data['index'], $data['data'])) {
            // Aktualisieren von Daten
            $index = $data['index'];
            $editedData = $data['data'];
            $sql = "UPDATE deine_tabelle SET 
                    vorname = :vorname, 
                    nachname = :nachname, 
                    adresse = :adresse, 
                    abteilung = :abteilung, 
                    telefon = :telefon, 
                    username = :username, 
                    passwort = :passwort 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'vorname' => $editedData['vorname'],
                'nachname' => $editedData['nachname'],
                'adresse' => $editedData['adresse'],
                'abteilung' => $editedData['abteilung'],
                'telefon' => $editedData['telefon'],
                'username' => $editedData['username'],
                'passwort' => $editedData['passwort'],
                'id' => $index + 1, 
            ]);

            // Antwort aus der Datenbank
            $response = ['success' => true];
            echo json_encode($response);
        } elseif (isset($data['index'], $data['data'])) {
            // Antwort für das Löschen von Daten
            $index = $data['index'];
            $sql = "DELETE FROM deine_tabelle WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id' => $index + 1, 
            ]);

            //für die Antwort
            $response = ['success' => true];
            echo json_encode($response);
        } else {
            //für eine fehlerhafte Anfrage
            $response = ['success' => false, 'message' => 'Ungültige Anfrage'];
            echo json_encode($response);
        }
    }
} catch (PDOException $e) {
    // Fehlerbehandlung für die Datenbankverbindung
    $response = ['success' => false, 'message' => 'Datenbankfehler: ' . $e->getMessage()];
    echo json_encode($response);
}
?>