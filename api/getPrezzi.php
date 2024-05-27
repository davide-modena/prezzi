<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

$database = mysqli_connect("localhost", "root", "", "my_prezzi");
$prezzi = [];

if (isset($_GET['placeId']) && is_numeric($_GET['placeId'])) {
    $placeId = intval($_GET['placeId']);
    
    $query = "SELECT * FROM prezzo WHERE IdLuogo = ?";
    $stmt = mysqli_prepare($database, $query);
    
    mysqli_stmt_bind_param($stmt, "i", $placeId);
    mysqli_stmt_execute($stmt);
    
    $ris_prezzi = mysqli_stmt_get_result($stmt);
    
    while ($row_prezzi = mysqli_fetch_assoc($ris_prezzi)) {
        array_push($prezzi, array(
            "Id" => intval($row_prezzi["Id"]),
            "Nome" => $row_prezzi["Nome"],
            "Costo" => floatval($row_prezzi["Costo"]),
            "Tipo" => $row_prezzi["Tipo"],
            "Dettagli" => $row_prezzi["Dettagli"],
            "Timestamp" => $row_prezzi["Timestamp"],
            "IdLuogo" => $row_prezzi["IdLuogo"],
            "Username" => $row_prezzi["Username"],
            "Foto" => $row_prezzi["Foto"]
        ));
    }
    
    mysqli_stmt_close($stmt);
}

echo(json_encode($prezzi));