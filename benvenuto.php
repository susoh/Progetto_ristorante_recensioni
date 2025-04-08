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
    <title>Benvuenuto</title>
</head>
<body>
    <div class="input rounded card" style="border: 2px solid black; padding: 3%">
    <?php 
        echo "<h2>Benvenuto: </h2>";
        $sql = "SELECT * FROM utente u WHERE u.username = \"" . $username . "\";";
        $result = $conn->query($sql); 
        $row = $result->fetch_assoc();
        echo "<ul><li><b>Codice Utente: </b>" . $row["id_utente"] . "</li>";
        echo "<li><b>Nome Utente: </b>" . $row["username"] . "</li>";
        echo "<li><b>Nome: </b>" . $row["nome"] . "</li>";
        echo "<li><b>Cognome: </b>" . $row["cognome"] . "</li>";
        echo "<li><b>Email: </b>" . $row["email"] . "</li>";
        echo "<li><b>Data di Registrazione: </b>" . $row["dataRegistrazione"] . "</li></ul>";
    ?>
    <a href="script_logout.php" style="width 100%"><button class="btn btn-outline-danger">LOG-OUT</button></a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>