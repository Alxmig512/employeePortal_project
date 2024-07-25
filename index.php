<?php

if (!isset($_SESSION['id'])) {
    header("Location: login.php"); // Redirect to dashboard if already logged in
    exit();
}

?>

<!doctype html>
<html lang="en">
    <head>
        <title>CSW Data</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
        <script src="https://cdn.jsdelivr.net/npm/@faker-js/faker@7.6.0/dist/faker.umd.min.js"></script>
        <script src="script.js" type="module" defer></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-sm navbar-light bg-danger">
                <div class="container">
                    <a class="navbar-brand" href="#">CSW Mitarbeiter </a>
                    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="collapsibleNavId">
                        <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <button class="btn my-2 my-sm-1" id="myBtn" style="background-color:rgb(34, 148, 241); border-color:black;">Hinzufügen</button>                      
                            </li>
                            <li class="nav-item">
                                <button class="btn my-2 my-sm-1" id="bear" style="background-color:rgb(34, 148, 241); border-color:black;">Bearbeiten</button>
                            </li>
                            <li class="nav-item">
                                <button class="btn my-2 my-sm-1" id="loe" style="background-color:rgb(34, 148, 241); border-color:black;">Löschen</button>
                            </li>
                            <li class="nav-item">
                                <button id="generateBtn" class="btn my-2 my-sm-1" style="background-color:rgb(34, 148, 241); border-color:black;">Zufällige Daten generieren</button>
                            </li>
                            
                        </ul>
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <form action="logout.php" method="post">
                                    <button class="btn my-2 my-sm-1" type="submit" id="logout" style="background-color:rgb(120, 123, 124); border-color:black;">Logout</button>
                                </form>
                               
                            </li>
                            <li>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            <div class="container mt-3">
                <table class="table table-striped" id="tableID">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Vorname</th>
                            <th scope="col">Nachname</th>
                            <th scope="col">Adresse</th>
                            <th scope="col">Abteilung</th>
                            <th scope="col">Telefon</th>
                            <th scope="col">Username</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
        </main>
        <footer>
          
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQ+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/v/dt/dt-2.0.8/datatables.min.js"></script>
        <link href="https://cdn.datatables.net/v/dt/dt-2.0.8/datatables.min.css" rel="stylesheet">
        
        <!-- Modal für Hinzufügen -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hinzufügen</h5>
                        
                    </div>
                    <div class="modal-body">
                        <form>
                            <!-- Vorherige Felder bleiben unverändert -->
                            <div class="form-group">
                                <label for="vorname" class="col-form-label">Vorname</label>
                                <input type="text" class="form-control" id="vorname">
                            </div>
                            <div class="form-group">
                                <label for="nachname" class="col-form-label">Nachname</label>
                                <input type="text" class="form-control" id="nachname">
                            </div>
                            <div class="form-group">
                                <label for="adresse" class="col-form-label">Adresse</label>
                                <input type="text" class="form-control" id="adresse">
                            </div>
                            <div class="form-group">
                                <label for="abteilung" class="col-form-label">Abteilung</label>
                                <input type="text" class="form-control" id="abteilung">
                            </div>
                            <div class="form-group">
                                <label for="telefon" class="col-form-label">Telefon</label>
                                <input type="text" class="form-control" id="telefon">
                            </div>
                            <div class="form-group">
                                <label for="username" class="col-form-label">Username</label>
                                <input type="text" class="form-control" id="username">
                            </div>
                            <div class="form-group">
                                <label for="passwort" class="col-form-label">Passwort</label>
                                <input type="password" class="form-control" id="passwort">
                            </div>
                            <div class="form-group">
                                <label for="confirm-passwort" class="col-form-label">Passwort bestätigen</label>
                                <input type="password" class="form-control" id="confirm-passwort">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="close" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                        <button type="button" class="btn btn-primary" id="save">Speichern</button>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal für Bearbeiten -->
        <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Bearbeiten</h5>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="edit-vorname" class="col-form-label">Vorname</label>
                                <input type="text" class="form-control" id="edit-vorname">
                            </div>
                            <div class="form-group">
                                <label for="edit-nachname" class="col-form-label">Nachname</label>
                                <input type="text" class="form-control" id="edit-nachname">
                            </div>
                            <div class="form-group">
                                <label for="edit-adresse" class="col-form-label">Adresse</label>
                                <input type="text" class="form-control" id="edit-adresse">
                            </div>
                            <div class="form-group">
                                <label for="edit-abteilung" class="col-form-label">Abteilung</label>
                                <input type="text" class="form-control" id="edit-abteilung">
                            </div>
                            <div class="form-group">
                                <label for="edit-telefon" class="col-form-label">Telefon</label>
                                <input type="text" class="form-control" id="edit-telefon">
                            </div>
                            <div class="form-group">
                                <label for="edit-username" class="col-form-label">Username</label>
                                <input type="text" class="form-control" id="edit-username">
                            </div>
                            <div class="form-group">
                                <label for="edit-passwort" class="col-form-label">Passwort</label>
                                <input type="password" class="form-control" id="edit-passwort">
                            </div>
                            <div class="form-group">
                                <label for="edit-confirm-passwort" class="col-form-label">Passwort bestätigen</label>
                                <input type="password" class="form-control" id="edit-confirm-passwort">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="close1" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                        <button type="button" class="btn btn-primary" id="save1">Speichern</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal für Löschen -->
        <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Löschen</h5>
                    </div>
                    <div class="modal-body"><!--Ab hier werden die Tabellenspalten statisch erstellt für die Aktionen-->
                        <p>Möchten Sie diesen Eintrag wirklich löschen?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="close2" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                        <button type="button" class="btn btn-danger" id="delete">Löschen</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
