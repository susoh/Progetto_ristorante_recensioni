<?php
include("connessione.php");
session_start();

if (!isset($_SESSION["id_utente"])) {
    header("Location: paginalogin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_utente = $_SESSION["id_utente"];
    $old_password = $_POST["old_password"] ?? "";
    $new_password = $_POST["new_password"] ?? "";

    $old_hashed = hash('sha256', $old_password);
    $new_hashed = hash('sha256', $new_password);

    $sql = "SELECT password FROM utente WHERE id_utente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_utente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if ($row["password"] === $old_hashed) {
            $sql_update = "UPDATE utente SET password = ? WHERE id_utente = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("si", $new_hashed, $id_utente);
            if ($stmt_update->execute()) {
                $_SESSION["esito_modifica_password"] = "successo";
            } else {
                $_SESSION["esito_modifica_password"] = "errore_aggiornamento";
            }
        } else {
            $_SESSION["esito_modifica_password"] = "vecchia_password_errata";
        }
    } else {
        $_SESSION["esito_modifica_password"] = "utente_non_trovato";
    }

    header("Location: benvenuto.php");
    exit;
}
?>
