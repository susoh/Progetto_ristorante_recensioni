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
    
    include("connessione.php");
    session_start();
    if (!$_SESSION["login"]) {
        header("Location:paginalogin.php");
        exit;
    }

    $username = $_SESSION["username"];

    $nome = $_POST["nome"];
    $indirizzo = $_POST["indirizzo"];
    $citta = $_POST["citta"];
    $id_ristorante = $_POST["id"];
    $latitudine = empty($_POST["latitudine"]) ? 43.7800127 : $_POST["latitudine"];
    $longitudine = empty($_POST["longitudine"]) ? 11.1997685 : $_POST["longitudine"];

    $sql = "INSERT INTO ristorante (id_ristorante, nome, indirizzo, citta, latitudine, longitudine) 
            VALUES ('$id_ristorante', '$nome', '$indirizzo', '$citta', $latitudine, $longitudine);";
    $result = $conn->query($sql);

    if ($result) {
        header("Location: pannelloadmin.php");
    } else {
        echo "Errore nell'inserimento: " . $conn->error;
    }
?>

