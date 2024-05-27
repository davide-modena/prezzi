<?php
session_start();

$database = mysqli_connect("localhost", "root", "", "my_prezzi");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prendi i valori dal form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passwordRepeat = $_POST['password-repeat'];

    // Verifica se la password e la conferma della password corrispondono
    if ($password != $passwordRepeat) {
        $_SESSION['error'] = "Le password non corrispondono.";
        header("Location: login.php");
        exit();
    }

    // Verifica se l'username è già in uso utilizzando un prepared statement
    $checkQuery = "SELECT * FROM utente WHERE Username = ?";
    $checkStmt = mysqli_prepare($database, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "s", $username);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        $_SESSION['error'] = "L'username è già in uso.";
        header("Location: login.php");
        exit();
    }

    mysqli_stmt_close($checkStmt);

    // Crittografa la password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Inserisci l'utente nel database utilizzando un prepared statement
    $insertQuery = "INSERT INTO utente (Username, Password) VALUES (?, ?)";
    $insertStmt = mysqli_prepare($database, $insertQuery);
    mysqli_stmt_bind_param($insertStmt, "ss", $username, $hashedPassword);

    if (mysqli_stmt_execute($insertStmt)) {
        // Inserimento dei dati nel database avvenuto con successo
        $_SESSION['error'] = "";
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        // Errore durante l'inserimento dei dati nel database
        $_SESSION['error'] = "Si è verificato un errore durante la registrazione.";
        header("Location: login.php");
        exit();
    }

    mysqli_stmt_close($insertStmt);
}