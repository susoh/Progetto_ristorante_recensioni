<?php
    include("connessione.php");
    session_start();
    if($_SESSION["login"]) {
    } else {
        header("Location:paginalogin.php");
    }
    $username = $_SESSION["username"];

    function query_check() {
        $sql = "SELECT COUNT(*) FROM  utente u 
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
        echo "<p>Benvenuto: <b> $username </b> </p><br>";
        echo "<p>Numero recensioni effettuate: <b>" . $row["COUNT(*)"] . "</b></p><br>";
        if ($row["COUNT(*)"] > 0) {
            $sql = "SELECT r.nome, r.indirizzo, rec.voto, rec.data FROM recensione rec JOIN ristorante r ON r.id_ristorante = rec.codiceristorante WHERE rec.id_utente = $id_utente;";
            $result = $conn->query($sql); 
            echo '<table class="table table-bordered tabella">
                <thead>
                 <tr>
      <th scope="col">Nome Ristorante</th>
      <th scope="col">Indirizzo ristorante</th>
      <th scope="col">Voto</th>
      <th scope="col">Data recensione</th>
    </tr>
  </thead>
  <tbody>';
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["nome"] ." </td><td>" . $row["indirizzo"] ." </td><td> ". $row["voto"] ." </td><td> ".$row["data"] . "</td></tr>";
            }
            echo "</table>";
            
        } else {
            echo "<h4>Nessuna recensione effettuata.</h4><br>";
        }
    ?>
    <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalRecensione">
  Inserisci nuova recensione
</button>

<!-- Modal -->
<div class="modal fade" id="ModalRecensione" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Inserisci nuova recensione</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Inserisci recensione</button>
      </div>
    </div>
  </div>
</div>
    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">Log-Out</button>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Log-Out</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Sei sicuro di voler effettuare il Log-Out</p>
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
</body>
</html> 