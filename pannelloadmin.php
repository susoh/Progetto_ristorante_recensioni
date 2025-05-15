<?php
    include("connessione.php");
    session_start();

    if (!$_SESSION["login"]) {
        header("Location:paginalogin.php");
        exit;
    }

    $username = $_SESSION["username"];

    $sql = "SELECT admin FROM utente WHERE username = '$username';";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if (!$row["admin"]) {
        header("Location:paginalogin.php");
        exit;
    }
    $sql = "SELECT r.*, 
                (SELECT COUNT(*) FROM recensione rec 
                WHERE rec.codiceristorante = r.id_ristorante) 
            AS numero_recensioni FROM ristorante r;";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Pannello Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="input rounded benvenuto" style="border: 2px solid black; padding: 3%">
        <h2>Benvenuto Admin: <b style="text-decoration: underline;"><?php echo $username; ?></b></h2><br>
        
        <?php
            if ($result->num_rows > 0) {
                echo '<table class="table table-bordered tabella">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Indirizzo</th>
                                <th>Città</th>
                                <th>Numero Recensioni</th>
                            </tr>
                        </thead>
                        <tbody>';
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["nome"] . "</td>
                            <td>" . $row["indirizzo"] . "</td>
                            <td>" . $row["citta"] . "</td>
                            <td>" . $row["numero_recensioni"] . "</td>
                          </tr>";
                }
                echo '</tbody></table>';
            } else {
                echo "<h4>Nessun ristorante presente.</h4><br>";
            }
        ?>

        <button type="button" class="btn btn-info mt-4" data-bs-toggle="modal" data-bs-target="#ModalRistorante">
            Inserisci un nuovo ristorante
        </button>

        <div class="modal fade" id="ModalRistorante" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Inserisci un nuovo ristorante</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="inserisciristorante.php">
            <div class="form-group mb-2">
                <label for="nome"><b>Nome ristorante:</b></label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group mb-2">
                <label for="indirizzo"><b>Indirizzo ristorante:</b></label>
                <input type="text" class="form-control" id="indirizzo" name="indirizzo" required>
            </div>
            <div class="form-group mb-3">
                <label for="id"><b>Id ristorante:</b></label>
                <input type="text" class="form-control" id="id" name="id" required>
            </div>
            <div class="form-group mb-2">
                <label for="citta"><b>Città:</b></label>
                <input type="text" class="form-control" id="citta" name="citta" required>
            </div>
            <input type="submit" class="btn btn-success" value="Inserisci">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

        <button type="button" class="btn btn-outline-danger mt-4" data-bs-toggle="modal" data-bs-target="#logoutModal">Log-Out</button>

        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="logoutLabel">Log-Out</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
              </div>
              <div class="modal-body">
                <p>Sei sicuro di voler effettuare il Log-Out?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Annulla</button>
                <a href="script_logout.php"><button type="button" class="btn btn-danger">Log-Out</button></a>
              </div>
            </div>
          </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
