<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Verifica se il file è stato caricato correttamente
    if(isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        // Percorso in cui salvare il file caricato
        $uploadDirectory = 'uploads/';
        
        // Crea una nuova directory se non esiste già
        if (!file_exists($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }
        
        // Genera un nome univoco per il file
        $fileName = uniqid() . '_' . str_replace(' ', '_', basename($_FILES['picture']['name']));
        
        // Percorso completo del file caricato
        $uploadFilePath = $uploadDirectory . $fileName;
        
        // Sposta il file dalla posizione temporanea alla directory di destinazione
        if(move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFilePath)) {
            // File caricato con successo
        } else {
            // Errore durante il caricamento del file
            echo "Si è verificato un errore durante il caricamento del file.";
        }
    } else {
        // Nessun file è stato caricato o si è verificato un errore
        echo "Nessun file caricato o si è verificato un errore.";
    }

    // Ottieni gli altri dati dal modulo
    $placeId = $_POST['placeId'];
    $product = $_POST['product'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $details = $_POST['details'];

    if(isset($_FILES['pictures'])){
        $picture = $uploadFilePath;
    }
    else{
        if($type == 'food'){
            $picture = 'images/food.svg';
        }
        else{
            $picture = 'images/drink.svg';
        }
    }

    $username = $_SESSION['username'];

    // Connessione al database
    $database = mysqli_connect("localhost", "root", "", "my_prezzi");

    $ris_prezzi = mysqli_query(
        $database,
        "SELECT *
        FROM prezzo
        WHERE IdLuogo = $placeId"
    );

    $prezzoMedio = 0;
    $index = 1;
    
    while($row_prezzi = mysqli_fetch_assoc($ris_prezzi)) {
        $prezzoMedio += $row_prezzi["Costo"];
        $index += 1;
    }

    $prezzoMedio = ($prezzoMedio + $price) / $index;
    $prezzoMedio = round($prezzoMedio, 1);

    mysqli_query(
        $database,
        "UPDATE luogo
        SET PrezzoMedio = $prezzoMedio
        WHERE Id = $placeId"
    );

    // Esegue la query per inserire i dati nel database
    $insertQuery = "INSERT INTO prezzo (Nome, Costo, Tipo, Foto, Dettagli, IdLuogo, Username)
                    VALUES ('$product', '$price', '$type', '$picture', '$details', '$placeId', '$username')";

    if(mysqli_query($database, $insertQuery)) {
        // Inserimento dei dati nel database avvenuto con successo
        header('location: index.php');
    } else {
        // Errore durante l'inserimento dei dati nel database
         echo "Errore MySQL: " . mysqli_error($database);
        echo $insertQuery;
        echo "Si è verificato un errore durante l'inserimento dei dati nel database.";
    }
}