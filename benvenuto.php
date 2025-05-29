<?php
    include("connessione.php");
    session_start();
    if($_SESSION["login"]) {
    } else {
        header("Location:paginalogin.php");
    }
    $username = $_SESSION["username"];

    function query_check() {
        $sql = "SELECT COUNT(*) FROM utente u
                JOIN recensione r
                ON u.id_utente = r.id_utente
                WHERE u.username = '$username' AND r.codiceristorante = ristorante.id_ristorante;";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if($row["COUNT(*)"] > 0) {
            return true;
        } else {
            return false;
        }
    }
?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles2.css">
    <title>Benvenuto</title>
</head>
<body>
    <div class="input rounded benvenuto" style="border: 2px solid black; padding: 3%">
    <?php 
        $sql = "SELECT id_utente FROM  utente u WHERE u.username = '$username';";
        $result = $conn->query($sql); 
        $row = $result->fetch_assoc();
        $id_utente = $row["id_utente"];
        $sql = "SELECT COUNT(*) FROM recensione r JOIN utente u ON u.id_utente = r.id_utente WHERE r.id_utente = $id_utente;";
        $result = $conn->query($sql); 
        $row = $result->fetch_assoc();
        echo "<h3>Benvenuto: <b style='text-decoration: underline;'> $username </b> </h3><br>";

        if (isset($_SESSION["error"]) && $_SESSION["error"] == "rec") {
            echo "<p style='color: red;'><b>Recensione già effettuata per questo ristorante!</b></p>";
            $_SESSION["error"] = "brobiz";
        }
        echo "<p>Numero recensioni effettuate: <b>" . $row["COUNT(*)"] . "</b></p><br>";

        if ($row["COUNT(*)"] > 0) {
            $sql = "SELECT rec.id_recensione, r.nome, r.indirizzo, rec.voto, rec.data FROM recensione rec JOIN ristorante r ON r.id_ristorante = rec.codiceristorante WHERE rec.id_utente = $id_utente;";
            $result = $conn->query($sql); 
            echo '<form action="elimina_recensioni.php" method="post">
                <table class="table table-bordered tabella">
                    <thead>
                     <tr>
                        <th class="tabella-sfondo" scope="col">Nome Ristorante</th>
                        <th class="tabella-sfondo" scope="col">Indirizzo ristorante</th>
                        <th class="tabella-sfondo" scope="col">Voto</th>
                        <th class="tabella-sfondo" scope="col">Data recensione</th>
                        <th class="tabella-sfondo" scope="col">Seleziona</th>
                    </tr>
                  </thead>
                  <tbody>';
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td><input type='checkbox' name='recensioni[]' value='" . $row["id_recensione"] . "' class='delete-checkbox'></td>
                        <td>" . $row["nome"] . "</td>
                        <td>" . $row["indirizzo"] . "</td>
                        <td>" . $row["voto"] . "</td>
                        <td>" . $row["data"] . "</td>
                    </tr>";
            }
            echo '</tbody>
                </table>';
                ?>
                <button type="submit" class="btn btn-danger d-none" id="eliminaButton">Elimina</button>
            </form>
            <?php
        } else {
            echo "<h4>Nessuna recensione effettuata.</h4><br>";
        }
    ?>

    <script>
        const checkboxes = document.querySelectorAll('.delete-checkbox');
        const deleteButton = document.getElementById('eliminaButton');

        function updateDeleteButton() {
            const checkedCount = document.querySelectorAll('.delete-checkbox:checked').length;
            if (checkedCount > 0) {
                deleteButton.classList.remove("d-none");
            } else {
                deleteButton.classList.add("d-none");
            }
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateDeleteButton);
        });

        // Ensure correct state on page load
        updateDeleteButton();
    </script>

 <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalRecensione">
  Inserisci nuova recensione
</button>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalRistorante">
  Vai al ristorante
</button>

<div class="modal fade" id="ModalRistorante" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Info ristorante</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <form action="info_ristorante.php" method="post">
        <div class="form-group">
            <label for="nome"><b>Nome ristorante:</b></label>
            <select name="nome" id="nome">
                <?php
                    $sql = "SELECT id_ristorante, nome FROM ristorante;";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["id_ristorante"] . "'>" . $row["nome"] . "</option>";
                    }
                ?>
            </select>
            <hr>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary form-control" value="Vai al ristorante selezionato" required>
        </div>
    </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="ModalRecensione" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Inserisci nuova recensione</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="script_recensione.php">
            <div class="form-group">
                <label for="nome"><b>Nome ristorante:</b></label>
                <select name="nome" id="nome">
                    <?php
                        $sql = "SELECT id_ristorante, nome FROM ristorante;";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["id_ristorante"] . "'>" . $row["nome"] . "</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="voto"><b>Voto:</b></label>
                <input type="number" class="form-control" id="voto" name="voto" min="1" max="5" required>
            </div>
            <div class="form-group">
                <label for="invia"><b>Invia: </b></label>
                <input type="submit" class="btn btn-primary form-control" id="invia" value="Invia recensione" required >
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">Log-Out</button>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Log-Out</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Sei sicuro di voler effettuare il Log-Out?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
        <a href="script_logout.php" style="width 100%"><button type="button" class="btn btn-danger">Log-Out</button></a>
      </div>
    </div>
  </div>
</div>
<button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change Password</button>

<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="changePasswordModalLabel">Cambia Password</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="change_password.php">
    <div class="form-group mb-3">
        <label for="old_password"><b>Vecchia Password:</b></label>
        <input type="password" class="form-control" id="old_password" name="old_password" required>
    </div>
    <div class="form-group mb-3">
        <label for="new_password"><b>Nuova Password:</b></label>
        <input type="password" class="form-control" id="new_password" name="new_password" required>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary form-control" value="Cambia Password">
    </div>
</form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
      </div>
    </div>
  </div>
</div>
<?php

if (isset($_SESSION["esito_modifica_password"])) {
    $esito = $_SESSION["esito_modifica_password"];
    unset($_SESSION["esito_modifica_password"]);

    switch ($esito) {
        case "successo":
            echo "<div class='alert alert-success'>Password modificata con successo.</div>";
            break;
        case "vecchia_password_errata":
            echo "<div class='alert alert-danger'>La vecchia password non è corretta.</div>";
            break;
        case "errore_aggiornamento":
            echo "<div class='alert alert-danger'>Errore durante l'aggiornamento della password. Riprova.</div>";
            break;
        case "utente_non_trovato":
            echo "<div class='alert alert-danger'>Utente non trovato. Effettua nuovamente il login.</div>";
            break;
        default:
            echo "<div class='alert alert-warning'>Si è verificato un errore sconosciuto.</div>";
            break;
    }
}
?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
