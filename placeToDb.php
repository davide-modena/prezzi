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
            // File caricato con successo, ora puoi fare altro con i dati del modulo e il percorso del file
            // echo "File caricato con successo.";
            // Esegui altre operazioni qui, come salvare i dati nel database
        } else {
            // Errore durante il caricamento del file
            // echo "Si è verificato un errore durante il caricamento del file.";
        }
    } else {
        // Nessun file è stato caricato o si è verificato un errore
        // echo "Nessun file caricato o si è verificato un errore.";
    }

    $name = $_POST['name'];
    $picture = $uploadFilePath;
    $lat = $_POST['latitude'];
    $lon = $_POST['longitude'];

    $database = mysqli_connect("localhost", "root", "", "my_prezzi");

    mysqli_query(
        $database,
        "INSERT INTO luogo (Nome, Foto, Latitudine, Longitudine)
        VALUES ('$name', '$picture', '$lat', '$lon')"
    );

    header('location: index.php');
}