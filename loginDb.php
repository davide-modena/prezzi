<?php
session_start();

$database = mysqli_connect("localhost", "root", "", "my_prezzi");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prendi i valori dal form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query per controllare le credenziali nel database utilizzando un prepared statement
    $query = "SELECT Password FROM utente WHERE Username = ?";
    $stmt = mysqli_prepare($database, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 1) {
        // Utente trovato, controlla la password
        mysqli_stmt_bind_result($stmt, $storedPassword);
        mysqli_stmt_fetch($stmt);

        if (password_verify($password, $storedPassword)) {
            // Password corretta, avvia la sessione e memorizza l'username
            $_SESSION['username'] = $username;
            $_SESSION['error'] = "";
            header("Location: index.php"); // Redirect alla dashboard dopo il login
            exit();
        } else {
            // Password non corretta
            $_SESSION['error'] = "Password errata";
            header("Location: login.php");
            exit();
        }
    } else {
        // Utente non trovato
        $_SESSION['error'] = "Utente non trovato";
        header("Location: login.php");
        exit();
    }

    mysqli_stmt_close($stmt);
}