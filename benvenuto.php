<?php
    include("connessione.php");
    session_start();
    if($_SESSION["login"]) {
    } else {
        header("Location:paginalogin.php");
    }
    $username = $_SESSION["username"];
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
    <div class="input rounded card" style="border: 2px solid black; padding: 3%">
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
            var_dump($result);
            echo '<table class="table table-bordered">
                <thead>
                 <tr>
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Handle</th>
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
    <a href="script_logout.php" style="width 100%"><button class="btn btn-outline-danger">LOG-OUT</button></a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html> 