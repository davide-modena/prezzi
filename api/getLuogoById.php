<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

$database = mysqli_connect("localhost", "root", "", "my_prezzi");
$luoghi = [];

// Assicurati che il parametro sia stato fornito e sia un intero
if (isset($_GET['placeId']) && is_numeric($_GET['placeId'])) {
    $placeId = intval($_GET['placeId']);
    
    // Utilizziamo una query preparata per evitare SQL injection
    $query = "SELECT * FROM luogo WHERE Id = ?";
    $stmt = mysqli_prepare($database, $query);
    
    // Lega il parametro
    mysqli_stmt_bind_param($stmt, "i", $placeId);
    
    // Eseguiamo la query preparata
    mysqli_stmt_execute($stmt);
    
    // Ottieni il risultato
    $ris_luoghi = mysqli_stmt_get_result($stmt);
    
    // Cicliamo sui risultati
    while ($row_luoghi = mysqli_fetch_assoc($ris_luoghi)) {
        array_push($luoghi, array(
            "Id" => intval($row_luoghi["Id"]),
            "Nome" => $row_luoghi["Nome"],
            "Foto" => $row_luoghi["Foto"],
            "PrezzoMedio" => floatval($row_luoghi["PrezzoMedio"]),
            "Latitudine" => doubleval($row_luoghi["Latitudine"]),
            "Longitudine" => doubleval($row_luoghi["Longitudine"])
        ));
    }
    
    // Chiudi l'istruzione preparata
    mysqli_stmt_close($stmt);
}

echo(json_encode($luoghi));