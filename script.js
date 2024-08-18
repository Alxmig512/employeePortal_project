//const {faker} = window['@faker-js/faker'];
//import { faker } from '@faker-js/faker';
import { faker } from "https://esm.sh/@faker-js/faker@v8.4.0/locale/de";


let DB = $('#tableID').DataTable({
    select: true,
    columns: [
        { data: "id", visible: false },
        { data: "vorname" },
        { data: "nachname" },
        { data: "adresse" },
        { data: "abteilung" },
        { data: "telefon" },
        { data: "username" },
    ],
    columnDefs: [
        // Definiere defaultContent für Spalten, die null sein könnten
        { targets: '_all', defaultContent: '-' }
    ]
});

// Initialisiere die Daten aus dem localStorage oder als leeres Array
let data = [];

// Index der aktuell bearbeiteten Zeile
let editIndex = -1;

// Event-Listener für das Hinzufügen-Modal
const myButton = document.getElementById('myBtn');
const myModal = document.querySelector('#exampleModal');
const closeButton = document.getElementById('close');
const modal = new bootstrap.Modal(myModal);

myButton.addEventListener('click', () => {
    modal.show(); // Zeigt das Hinzufügen-Modal
});

closeButton.addEventListener('click', () => {
    modal.hide(); // Schließt das Hinzufügen-Modal
});

// Event-Listener für das Bearbeiten-Modal
const myButton1 = document.getElementById('bear');
const myModal1 = document.querySelector('#exampleModal1');
const closeButton1 = document.getElementById('close1');
const modal1 = new bootstrap.Modal(myModal1);

//Event-Listener für die Passwortbestätigung für den Hinzufügen button
const passwortField = document.getElementById('passwort');
const confirmPasswortField = document.getElementById('confirm-passwort');

confirmPasswortField.addEventListener('input', () => {
    if (confirmPasswortField.value !== passwortField.value) {
        confirmPasswortField.classList.add('is-invalid');
        confirmPasswortField.classList.remove('is-valid');
    } else {
        confirmPasswortField.classList.add('is-valid');
        confirmPasswortField.classList.remove('is-invalid');
    }
});

//Event-Listener für die Passwortbestätigung für den Bearbeiten button

    const editPasswortField = document.getElementById('edit-passwort');
    const editConfirmPasswortField = document.getElementById('edit-confirm-passwort');

    editConfirmPasswortField.addEventListener('input', () => {
        if (editConfirmPasswortField.value !== editPasswortField.value) {
            editConfirmPasswortField.classList.add('is-invalid');
            editConfirmPasswortField.classList.remove('is-valid');
        } else {
            editConfirmPasswortField.classList.add('is-valid');
            editConfirmPasswortField.classList.remove('is-invalid');
        }
    });



myButton1.addEventListener('click', () => {
    if (editIndex >= 0) {
        // Füllt die Bearbeiten-Felder mit den Daten der ausgewählten Zeile
        const { vorname, nachname, adresse, abteilung, telefon, username, passwort } = data[editIndex];
        document.querySelector('#edit-vorname').value = vorname;
        document.querySelector('#edit-nachname').value = nachname;
        document.querySelector('#edit-adresse').value = adresse;
        document.querySelector('#edit-abteilung').value = abteilung;
        document.querySelector('#edit-telefon').value = telefon;
        document.querySelector('#edit-username').value = username;
        document.querySelector('#edit-passwort').value = passwort;
        modal1.show();
    } else {
        alert('Bitte eine Zeile auswählen, um sie zu bearbeiten.');
    }
});

closeButton1.addEventListener('click', () => {
    modal1.hide(); // Schließt das Bearbeiten-Modal
});

// Event-Listener für das Löschen-Modal
const myButton2 = document.getElementById('loe');
const myModal2 = document.querySelector('#exampleModal2');
const closeButton2 = document.getElementById('close2');
const modal2 = new bootstrap.Modal(myModal2);

myButton2.addEventListener('click', () => {
    if (editIndex >= 0) {
        modal2.show(); // Zeigt das Löschen-Modal
    } else {
        alert('Bitte eine Zeile auswählen, um sie zu löschen.');
    }
});

closeButton2.addEventListener('click', () => {
    modal2.hide(); // Schließt das Löschen-Modal
});

// Event-Listener für den Speichern-Button im Hinzufügen-Modal
const saveButton = document.getElementById('save');
saveButton.addEventListener('click', () => {
    // Werte aus den Eingabefeldern auslesen
    const vorname = document.getElementById('vorname').value;
    const nachname = document.getElementById('nachname').value;
    const adresse = document.getElementById('adresse').value;
    const abteilung = document.getElementById('abteilung').value;
    const telefon = document.getElementById('telefon').value;
    const username = document.getElementById('username').value;
    const passwort = document.getElementById('passwort').value;
    const confirmPasswort = document.getElementById('confirm-passwort').value;

    if (vorname && nachname && adresse && abteilung && telefon && username && passwort && confirmPasswort) {
        if (passwort === confirmPasswort) {
            // Daten speichern
            const newData = { vorname, nachname, adresse, abteilung, telefon, username, passwort };

            saveDataToServer(newData); // Speichern der Daten auf dem Server
            modal.hide(); // Modal schließen
            clearAddModal(); // Eingabefelder leeren
        } else {
            alert('Die Passwörter stimmen nicht überein.');
        }
    } else {
        alert('Bitte alle Felder ausfüllen.');
    }
});

// Event-Listener für den Speichern-Button im Bearbeiten-Modal
const saveButton1 = document.getElementById('save1');
saveButton1.addEventListener('click', (e) => {
    e.preventDefault();
    // Werte aus den Eingabefeldern auslesen
    const vorname = document.getElementById('edit-vorname').value;
    const nachname = document.getElementById('edit-nachname').value;
    const adresse = document.getElementById('edit-adresse').value;
    const abteilung = document.getElementById('edit-abteilung').value;
    const telefon = document.getElementById('edit-telefon').value;
    const username = document.getElementById('edit-username').value;
    const editPasswort = document.getElementById('edit-passwort').value;
    const editConfirmPasswort = document.getElementById('edit-confirm-passwort').value;

    if (vorname && nachname && adresse && abteilung && telefon && username && passwort && editConfirmPasswort) {
        if (editPasswort === editConfirmPasswort){
        const editedData = { vorname, nachname, adresse, abteilung, telefon, username, editPasswort };

        updateDataOnServer('edit', editedData); // Aktualisiere Daten auf dem Server
        modal1.hide(); // Modal schließen
        clearEditModal(); // Eingabefelder leeren
        editIndex = -1; // Reset des Index
    } else {
        alert('Die Passwörter stimmen nicht überein.');
    }
    } else {
        alert('Bitte alle Felder ausfüllen.');
    }
});

// Event-Listener für den Löschen-Button im Löschen-Modal
const deleteButton = document.getElementById('delete');
deleteButton.addEventListener('click', () => {
    if (editIndex >= 0) {
        const username = data[editIndex].username; // Benutzername der zu löschenden Zeile

        deleteDataFromServer('delete', { username }); // Löscht Daten auf dem Server
        modal2.hide(); // Modal schließen
        editIndex = -1; // Reset des Index
    } else {
        alert('Bitte eine Zeile zum Löschen auswählen.');
    }
});

// Funktion zum Speichern der neuen Daten in der Datenbank
function saveDataToServer(newData) {
    $.ajax({
        url: 'http://localhost/csw_project/sqltest.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ action: 'add', data: newData }),
        success: function(response) {
            console.log('Server response:', response);
            if (response.success) {
                console.log('Daten erfolgreich auf dem Server gespeichert');
                fetchDataFromServer(); // Aktualisiere die Tabelle mit den neuesten Daten
            } else {
                console.error('Fehler: ', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Fehler beim Speichern der Daten auf dem Server: ', error);
        }
    });
}

// Funktion zum Aktualisieren der Daten auf dem Server
function updateDataOnServer(action, editedData) {
    $.ajax({
        url: 'http://localhost/csw_project/sqltest.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ action, data: editedData }),
        success: function(response) {
            console.log('Server response:', response);
            if (response.success) {
                console.log('Daten erfolgreich auf dem Server aktualisiert');
                fetchDataFromServer(); // Aktualisiere die Tabelle mit den neuesten Daten
            } else {
                console.error('Fehler: ', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Fehler beim Aktualisieren der Daten auf dem Server: ', error);
        }
    });
}

// Funktion zum Löschen der Daten auf dem Server
function deleteDataFromServer(action, deletedData) {
    $.ajax({
        url: 'http://localhost/csw_project/sqltest.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ action, data: deletedData }),
        success: function(response) {
            console.log('Server response:', response);
            if (response.success) {
                console.log('Daten erfolgreich auf dem Server gelöscht');
                fetchDataFromServer(); // Aktualisiere die Tabelle mit den neuesten Daten
            } else {
                console.error('Fehler: ', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Fehler beim Löschen der Daten auf dem Server: ', error);
        }
    });
}

// Funktion zum Abrufen der neuesten Daten vom Server
function fetchDataFromServer() {
    $.ajax({
        url: 'http://localhost/csw_project/sqltest.php', // Endpunkt, der alle Daten zurückgibt
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Fetched data from server:', response);
            if (Array.isArray(response)) {
                data = response; // Aktualisiere das lokale data Array mit den neuesten Daten
                renderTable(); // Aktualisiere die Tabelle
            } else {
                console.error('Fetched data is not an array:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error('Fehler beim Abrufen der Daten vom Server: ', error);
        }
    });
}

// Initiales Abrufen der Daten vom Server und Rendern der Tabelle
fetchDataFromServer();

function renderTable() {
    const tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = '';
    data.forEach((entry, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${entry.vorname}</td>
            <td>${entry.nachname}</td>
            <td>${entry.adresse}</td>
            <td>${entry.abteilung}</td>
            <td>${entry.telefon}</td>
            <td>${entry.username}</td>
        `;
        row.addEventListener('click', () => {
            // Markiert die ausgewählte Zeile
            document.querySelectorAll('#tableBody tr').forEach(r => r.classList.remove('selected'));
            row.classList.add('selected');
            editIndex = index; // Speichert den Index der ausgewählten Zeile
        });
        tableBody.appendChild(row);
    });
}

// Initiales Rendern der Tabelle beim Laden der Seite
renderTable();



function clearEditModal() {
    document.getElementById('edit-vorname').value = '';
    document.getElementById('edit-nachname').value = '';
    document.getElementById('edit-adresse').value = '';
    document.getElementById('edit-abteilung').value = '';
    document.getElementById('edit-telefon').value = '';
    document.getElementById('edit-username').value = '';
    document.getElementById('edit-passwort').value = '';
    document.getElementById('edit-confirm-passwort').value = '';
}

    const generateRandomWorker = () => {
        return {
            vorname: faker.name.firstName(),
            nachname: faker.name.lastName(),
            adresse: faker.address.streetAddress(),
            abteilung: faker.commerce.department(),
            telefon: faker.phone.number(),
            username: faker.internet.userName(),
            passwort: faker.internet.password(),
        };
    };


document.getElementById('generateBtn').addEventListener('click', function() {
    const randomWorker = generateRandomWorker();
    saveDataToServer(randomWorker);
});

document.addEventListener('DOMContentLoaded', function() {
    var passwordInput = document.getElementById('passwort');
    var strengthBar = document.getElementById('password-strength');
    var strengthText = document.getElementById('password-strength-text');

    passwordInput.addEventListener('input', function() {
        var password = this.value;
        var strength = 0;

        // Passwort-Sicherheitsregeln
        if (password.length > 7) strength += 1; // Länge über 7 Zeichen
        if (/[a-z]/.test(password)) strength += 1; // Kleinbuchstaben
        if (/[A-Z]/.test(password)) strength += 1; // Großbuchstaben
        if (/[0-9]/.test(password)) strength += 1; // Zahlen
        if (/[^a-zA-Z0-9]/.test(password)) strength += 1; // Sonderzeichen

        // Stärke Prozentzahl basierend auf den Kriterien
        var strengthPercentage = (strength / 5) * 100; // Stärke auf 100% skalieren

        // Breite der Leiste anpassen
        strengthBar.style.width = strengthPercentage + '%';
        strengthBar.style.height = '5px';
        strengthBar.style.marginTop = '10px';
        strengthBar.style.marginBottom = '10px';


        if (strengthPercentage < 40) {
            strengthBar.style.backgroundColor = 'red';
            strengthText.textContent = 'Schwach';
        } else if (strengthPercentage < 70) {
            strengthBar.style.backgroundColor = 'yellow';
            strengthText.textContent = 'Mittel';
        } else {
            strengthBar.style.backgroundColor = 'green';
            strengthText.textContent = 'Stark';
        }
    });
});
