<?php
    include("connessione.php");
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['recensioni'])) {
        $recensioni_id = $_POST['recensioni'];
        $i = 0;
        foreach ($recensioni_id as $id_recensione) {
            i++;
            $sql = "DELETE FROM recensione WHERE id_recensione = $id_recensione";
            $conn->query($sql);
        }

        $_SESSION["eliminate"] = $i;

        header("Location: benvenuto.php");
    } else {
        $_SESSION["eliminate"] = 0;
        header("Location: benvenuto.php");
    }
?>
