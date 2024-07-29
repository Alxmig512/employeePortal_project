<?php
// index.php
session_start();


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Einbinden der Verbindungsdatei
require 'db_connection.php';

class Workmanagement {
    // Mitarbeiter hinzufügen
    public static function addworker($pdo, $data) {
        try {
            $stmt = $pdo->prepare('INSERT INTO mitarbeiter (vorname, nachname, adresse, abteilung, telefon, username, passwort) 
                VALUES (:vorname, :nachname, :adresse, :abteilung, :telefon, :username, :passwort)');
            $stmt->execute([
                'vorname'    => $data['vorname'],
                'nachname'   => $data['nachname'],
                'adresse'    => $data['adresse'],
                'abteilung'  => $data['abteilung'],
                'telefon'    => $data['telefon'],
                'username'   => $data['username'],
                'passwort'   => password_hash($data['passwort'], PASSWORD_BCRYPT),
            ]);

            echo json_encode(['success' => true, 'message' => 'Mitarbeiter erfolgreich hinzugefügt']);
        } catch (\PDOException $e) {
            error_log('Fehler: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Fehler beim Hinzufügen des Mitarbeiters']);
        }
    }

    // Mitarbeiter bearbeiten
    public static function editworker($pdo, $data) {
        try {
            $stmt = $pdo->prepare('UPDATE mitarbeiter SET vorname = :vorname, nachname = :nachname, adresse = :adresse, abteilung = :abteilung, telefon = :telefon, passwort = :passwort WHERE username = :username');
            $stmt->execute([
                'vorname'    => $data['vorname'],
                'nachname'   => $data['nachname'],
                'adresse'    => $data['adresse'],
                'abteilung'  => $data['abteilung'],
                'telefon'    => $data['telefon'],
                'username'   => $data['username'],
                'passwort'   => password_hash($data['editPasswort'], PASSWORD_BCRYPT),
            ]);

            echo json_encode(['success' => true, 'message' => 'Mitarbeiter erfolgreich bearbeitet']);
        } catch (\PDOException $e) {
            error_log('Fehler: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Fehler beim Bearbeiten des Mitarbeiters']);
        }
    }

    // Mitarbeiter löschen
    public static function deleteworker($pdo, $username) {
        try {
            $stmt = $pdo->prepare('DELETE FROM mitarbeiter WHERE username = :username');
            $stmt->execute(['username' => $username]);

            echo json_encode(['success' => true, 'message' => 'Mitarbeiter erfolgreich gelöscht']);
        } catch (\PDOException $e) {
            error_log('Fehler: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Fehler beim Löschen des Mitarbeiters']);
        }
    }

    // Alle Mitarbeiter abrufen
    public static function getallworkers($pdo) {
        try {
            $stmt = $pdo->query('SELECT vorname, nachname, adresse, abteilung, telefon, username FROM mitarbeiter');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($results);
        } catch (\PDOException $e) {
            error_log('Fehler: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Fehler beim Abrufen der Mitarbeiterdaten']);
        }
    }
}

$input = json_decode(file_get_contents('php://input'), true);
$action = isset($input['action']) ? $input['action'] : '';

switch ($action) {
    case 'add':
        Workmanagement::addworker($pdo, $input['data']);
        break;
    case 'edit':
        Workmanagement::editworker($pdo, $input['data']);
        break;
    case 'delete':
        Workmanagement::deleteworker($pdo, $input['data']['username']);
        break;
    default:
        Workmanagement::getallworkers($pdo);
        break;
}

?>

