<?php
    include("connessione.php");
    session_start();
    if($_SESSION["login"]) {
    } else {
        header("Location:paginalogin.php");
    }
    $username = $_SESSION["username"];
?>
<?php
    $nome = $_POST["nome"];
    $indirizzo = $_POST["indirizzo"];
    $citta = $_POST["citta"];
    $id_ristorante = $_POST["id"];
    $sql = "INSERT INTO ristorante (id_ristorante, nome, indirizzo, citta) VALUES ('$id_ristorante','$nome','$indirizzo', '$citta');";
    $result = $conn->query($sql);
    header("Location: pannelloadmin.php");
?>