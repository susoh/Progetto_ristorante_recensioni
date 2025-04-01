<?php
    include("connessione.php");
    session_start();
?>
 
<?php
    $_SESSION["username"] = $_POST["username"];
    $_SESSION["password"] = $_POST["password"];
    $p = $_POST["password"];
    $hp = hash('sha256', $p);
    var_dump($hp);
    $sql = 'SELECT * FROM utente u WHERE u.username = "' .$_SESSION["username"] . '" ';
    $result = $conn->query($sql);
    if($result->num_rows > 0) {
        $sql = 'SELECT * FROM utente u WHERE u.username = "' .$_SESSION["username"] . '" AND u.password="' . $hp . '";';
        $result = $conn->query($sql);
        if($result->num_rows > 0) {
            $_SESSION["login"] = true;
            header("Location: benvenuto.php");
        } else {
            $_SESSION["errore"] = "p";
            header("Location:errore_loginreg.php");
        }
    } else {
        $_SESSION["errore"] = "u";
        header("Location:errore_loginreg.php");
    }

?>