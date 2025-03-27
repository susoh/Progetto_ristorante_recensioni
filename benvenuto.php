<?php
    session_start();
    if($_SESSION["login"]) {
    } else {
        header("Location:errore_loginreg.php");
    }
?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php echo "<h3>Benvenuto: " . $_SESSION['username'] . ".</h3>"; ?>
    <a href="script_logout.php"><button>LOG-OUT</button></a>
</body>
</html>