<?php
    include("connessione.php");
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['recensioni'])) {
        $recensioni_id = $_POST['recensioni'];

        foreach ($recensioni_id as $id_recensione) {
            $sql = "DELETE FROM recensione WHERE id_recensione = $id_recensione";
            $conn->query($sql);
        }

        $_SESSION["eliminate"] = count($recensioni_id);

        header("Location: benvenuto.php");
    } else {
        $_SESSION["eliminate"] = 0;
        header("Location: benvenuto.php");
    }
?>
