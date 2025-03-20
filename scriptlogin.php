<?php
    include("connessione.php");
    session_start();
?>

<?php
    $_SESSION["username"] = $_POST["username"];
    $_SESSION["password"] = $_POST["password"];
    $sql = 'SELECT * FROM utente u WHERE username = "' .$_SESSION["username"] . '" ';
    $result = $conn->query($sql);
    if($result->num_rows > 0) {
       echo "utente trovato";
       
    }
?>