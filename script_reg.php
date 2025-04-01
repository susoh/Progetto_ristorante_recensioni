<?php
include("connessione.php");
    session_start();
?>  

<?php
    $username = $_POST["username"];
    $password = $_POST["password"];
    $nome = $_POST["nome"];
    $cognome = $_POST["cognome"];
    $email = $_POST["email"];
    $hpswd = hash('sha256', $password);
    $sql = "INSERT INTO utente (username, password, nome, cognome, email) VALUES ('$username','$hpswd','$nome','$cognome', '$email');";
    $result = $conn->query($sql);
    var_dump($result);
    if($result) {
        echo "true";
    } else {
        echo "false";
    }
?>