<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $err = $_SESSION["errore"];
        switch ($err) {
            case 'u':
                 echo "<h3 style='color: red;'> Lo username inserito è errato.</h3>";
                break;
            case 'p':
                echo "<h3 style='color: red;'> La password inserita è errata.</h3>";
                break;
            default:
                echo "<h3 style='color: red;'> ERRORE SCONOSCIUTO</h3>";
                break;
        }
    ?>
</body>
</html>