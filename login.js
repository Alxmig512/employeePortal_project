import { faker } from "https://esm.sh/@faker-js/faker@v8.4.0/locale/de";

const registerButton = document.getElementById("registrationlink");
const regmodal = new bootstrap.Modal('#registerModal');

registerButton.addEventListener('click', () => {
    regmodal.show(); // Zeigt das Hinzufügen-Modal
});

const resetButton = document.getElementById("forgotPasswordLink");
const resmodal = new bootstrap.Modal('#resetPasswordModal');

resetButton.addEventListener('click', () => {
    resmodal.show(); // Zeigt das Hinzufügen-Modal
});


$('#loginForm').submit(function(e) {
    e.preventDefault();
    const username = $('#loginUsername').val();
    const password = $('#loginPassword').val();

    loginUser({ username, password });
});

// Event Listener für Registrierung
$('#regBtn').on('click',function(e) {
    e.preventDefault();
    const vorname = $('#registerVorname').val();
    const nachname = $('#registerNachname').val();
    const adresse = $('#registerAdresse').val();
    const abteilung = $('#registerAbteilung').val();
    const telefon = $('#registerTelefon').val();
    const username = $('#registerUsername').val();
    const password = $('#registerPassword').val();
    const confirmPassword = $('#registerConfirmPassword').val();

    if (password !== confirmPassword) {
        alert('Passwörter stimmen nicht überein');
        return;
    }

    registerUser( vorname, nachname, adresse, abteilung, telefon, username, password, confirmPassword );
});

// Event Listener für Passwort zurücksetzen
$('#resetPasswordForm').submit(function(e) {
    e.preventDefault();
        const username = $('#resetUsername').val();
        const newPassword = $('#resetNewPassword').val();
        const confirmPassword = $('#confirmResetNewPassword').val();
        
        if (newPassword !== confirmPassword) {
            alert('Passwörter stimmen nicht überein');
        } else {
            resetPassword({ username: username, newPassword: newPassword });
        }
    
});

function loginUser(username, password) {
    $.ajax({
        url: 'http://localhost/csw_project/login.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ action: 'login', username: username, password: password }),
        success: function(response) {
            //const result = JSON.parse(response);
            if (response.success) {
                window.location.href = 'index.php';
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Fehler beim Einloggen: ', error);
        }
    });
}

// Funktion zur Registrierung
function registerUser( vorname, nachname, adresse, abteilung, telefon, username, password, confirmPassword ) {
    $.ajax({
        url: 'http://localhost/csw_project/login.php',
        type: 'POST',
        contentType: 'application/json',
        dataType:'json',
        data: JSON.stringify({ action: 'register', username: username, password: password, vorname: vorname, nachname: nachname, adresse: adresse, abteilung: abteilung, telefon: telefon, confirmPassword: confirmPassword  }),
        success: function(response) {
            console.log('Serverantwort:', response);

            if (typeof response === 'string') {
                try {
                    response = JSON.parse(response);
                } catch (e) {
                    console.error('Fehler beim Parsen der JSON-Antwort:', e);
                    alert('Ungültige JSON-Antwort');
                    return;
                }
            }

            console.log('Geparste Antwort:', response);

            if (response.success) {
                window.location.href = 'index.php';
                regmodal.hide(); 
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Fehler bei der Registrierung:', error);
        }

    });
}

// Funktion zum Passwort zurücksetzen
function resetPassword(username) {
    $.ajax({
        url: 'http://localhost/csw_project/login.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ action: 'forgot', username: username }),
        success: function(response) {
        
            if (response.success) {
                alert('Passwort zurückgesetzt, neues Passwort ist: ' + response.newPassword);
                resmodal.hide();
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Fehler beim Zurücksetzen des Passworts: ', error);
        }
    });
}

const generateRandomWorker = () => {
    return {
        vorname: faker.name.firstName(),
        nachname: faker.name.lastName(),
        adresse: faker.address.streetAddress(),
        abteilung: faker.commerce.department(),
        telefon: faker.phone.number(),
        username: faker.internet.userName(),
        password: faker.internet.password(),
    };
};

document.getElementById('generateRdmBtn').addEventListener('click', function() {
    const randomWorker = generateRandomWorker();
    
    // Hier wird die Funktion `registerUser` verwendet, um den zufälligen Benutzer zu registrieren.
    registerUser(
        randomWorker.vorname,
        randomWorker.nachname,
        randomWorker.adresse,
        randomWorker.abteilung,
        randomWorker.telefon,
        randomWorker.username,
        randomWorker.password,
        randomWorker.confirmPassword // confirmPassword, da es sich um eine zufällige Erstellung handelt
    );
});