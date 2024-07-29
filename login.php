<?php

ob_start();

session_start();


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require 'db_connection.php';


class AuthManagement {
    public static function loginUser($pdo, $username, $password) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM mitarbeiter WHERE username = :username');
            $stmt->execute(['username' => $username['username']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($username['password'], $user['passwort'])) {
                //Cookie mit Ablauf wird hier generiert, um die Session dem User zuzuweisen
                setcookie("user", $user['username'], time() + (86400 * 30), "/");
                echo json_encode(['success' => true, 'message' => 'Login erfolgreich']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ungültiger Benutzername oder Passwort']);
            }
        } catch (\PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Datenbankfehler: ' . $e->getMessage()]);
        }
    }

    public static function registerUser($pdo, $data) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM mitarbeiter WHERE username = :username');
            $stmt->execute(['username' => $data['username']]);
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Der Benutzername ' . $data['username'] . ' bereits vergeben']);
                return;
            }

            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO mitarbeiter (vorname, nachname, adresse, abteilung, telefon, username, passwort) 
                VALUES (:vorname, :nachname, :adresse, :abteilung, :telefon, :username, :passwort)');
            $stmt->execute([
                'vorname'    => $data['vorname'],
                'nachname'   => $data['nachname'],
                'adresse'    => $data['adresse'],
                'abteilung'  => $data['abteilung'],
                'telefon'    => $data['telefon'],
                'username'   => $data['username'],
                'passwort'   => password_hash($data['password'], PASSWORD_BCRYPT),
            ]);
            //Session wird generiert mit Ablauf
            setcookie("user", $data['username'], time() + (86400 * 30), "/");
            echo json_encode(['success' => true, 'message' => 'Registrierung erfolgreich']);
        } catch (\PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Datenbankfehler: ' . $e->getMessage()]);
        }
    }
// Passwort zurücksetzen
    /**
     * Resets the password for a user in the database.
     *
     * @param PDO $pdo The PDO object for the database connection.
     * @param array $username An array containing the username of the user.
     *                        The array should have a key 'username' with the value of the username.
     * @throws PDOException If there is an error executing the SQL statements.
     * @return void
     */
    public static function resetPassword($pdo, $username) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM mitarbeiter WHERE username = :username');
            $stmt->execute(['username' => $username['username']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                //$newPassword = bin2hex(random_bytes(4)); // Generiert ein neues Passwort
                $hashedPassword = password_hash($username['newPassword'], PASSWORD_DEFAULT);

                $stmt = $pdo->prepare('UPDATE mitarbeiter SET passwort = :passwort WHERE username = :username');
                $stmt->execute(['passwort' => $hashedPassword, 'username' => $username['username']]);

                //$stmt->rowCount();

                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Benutzername nicht gefunden']);
            }
        } catch (\PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Datenbankfehler: ' . $e->getMessage()]);
        }
    }
}

$input = json_decode(file_get_contents('php://input'), true);
$action = isset($input['action']) ? $input['action'] : '';

$username = $input['username'] ?? '';
$password = $input['password'] ?? '';

switch ($action) {
    case 'login':
        AuthManagement::loginUser($pdo, $username, $password);
        break;
    case 'register':
        AuthManagement::registerUser($pdo, $input);
        break;
    case 'forgot':
        AuthManagement::resetPassword($pdo, $username);
        break;
    default:
        header("Location: login.html");
        exit();
        break;
}

ob_end_flush();
exit;
?>