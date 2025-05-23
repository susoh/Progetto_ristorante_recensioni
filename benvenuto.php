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
    <link rel="stylesheet" href="./styles.css">
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
                        <th scope="col">Seleziona</th>
                        <th scope="col">Nome Ristorante</th>
                        <th scope="col">Indirizzo ristorante</th>
                        <th scope="col">Voto</th>
                        <th scope="col">Data recensione</th>
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
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                // Controlla se almeno una checkbox è selezionata
                deleteButton.classList.toggle("d-none");
            });
        });
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
