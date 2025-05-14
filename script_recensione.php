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
    var_dump($id_utente);
    $id_ristorante = $_POST["nome"];
    $voto = $_POST["voto"];
    $sql = "INSERT INTO recensione (id_utente, codiceristorante, voto) 
            VALUES ($id_utente, $id_ristorante, $voto);";
    var_dump($sql);
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if($row["COUNT(*)"] > 0) {
        return true;
    } else {
        return false;
    }
?>