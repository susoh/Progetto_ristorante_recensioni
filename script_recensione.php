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
<?php
    $id_utente = $_SESSION["id_utente"];
    $id_ristorante = $_POST["nome"];
    $voto = $_POST["voto"];
    $sql = "SELECT COUNT(*) as count FROM recensione WHERE id_utente = $id_utente AND codiceristorante = '$id_ristorante';";
$result = $conn->query($sql);

if ($result === false) {
    die("Errore nella query: " . $conn->error);
}

$row = $result->fetch_assoc();

if($row["count"] > 0) {
    $_SESSION["errore"] = "rec";
    header("Location: benvenuto.php");
    exit;
} else {
    $sql = "INSERT INTO recensione (id_utente, codiceristorante, voto) VALUES ($id_utente, '$id_ristorante', $voto);";
    if ($conn->query($sql) === false) {
        die("Errore nell'inserimento della recensione: " . $conn->error);
    }
    header("Location: benvenuto.php");
    exit;
}
?>