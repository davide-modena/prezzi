<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
?>

<?php include 'head.php' ?>
<?php
    // Controlla se il parametro placeId è presente nell'URL
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recupera i dati inviati tramite POST
        $placeId = $_POST['placeId'];
        // Se il parametro è presente, invia le intestazioni JSON
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        // header("Content-Type: application/json");
        
        // Recupera i dati dal database
        $database = mysqli_connect("localhost", "root", "", "my_prezzi");
        $ris_luogo = mysqli_query($database, "SELECT * FROM luogo WHERE Id = $placeId");
        
        // Prepara i dati JSON
        $luogo = array();
        while($row_luoghi = mysqli_fetch_assoc($ris_luogo)) {
            $luogo = array(
                "Id" => intval($row_luoghi["Id"]),
                "Nome" => $row_luoghi["Nome"],
                "Foto" => $row_luoghi["Foto"],
                "PrezzoMedio" => floatval($row_luoghi["PrezzoMedio"]),
                "Latitudine" => doubleval($row_luoghi["Latitudine"]),
                "Longitudine" => doubleval($row_luoghi["Longitudine"])
            );
        }
    }
?>

<link rel="stylesheet" href="css/add.css">

<body>
    <div class="add-smth-container">
        <h1>Aggiungi prezzo</h1>
        <form action="priceToDb.php" method="post" enctype="multipart/form-data">
            <div class="form-element">
                <label for="place">Luogo</label>
                <input type="text" name="place" id="place" disabled value="<?php echo $luogo["Nome"]?>" required>
                <input type="number" name="placeId" value="<?php echo $placeId ?>" hidden>
                <i class="fa-solid fa-lock"></i>
            </div>
            <div class="form-element">
                <label for="product">Nome Prodotto</label>
                <input type="text" name="product" id="product" placeholder="Inserisci il prodotto..." required>
            </div>
            <div class="form-element">
                <label for="price">Prezzo</label>
                <input type="number" step="0.01" name="price" id="price" placeholder="Inserisci il prezzo..." required>
                <i class="fa-solid fa-euro-sign"></i>
            </div>
            <div class="form-element">
                <label for="type">Tipo</label>
                <select name="type" id="type" required>
                    <option value="food">Cibo 🍔</option>
                    <option value="drink">Bevanda 🥤</option>
                </select>
            </div>
            <div class="form-element">
                <label for="details">Dettagli</label>
                <textarea type="text" name="details" id="details" placeholder="Inserisci i dettagli (ingredienti, descrizione, ...)"></textarea>
            </div>
            <div class="form-element">
                <label for="picture">Foto</label>
                <input type="file" name="picture" id="picture">
            </div>
            <button>Aggiungi Prezzo</button>
        </form>
    </div>


    <?php include 'footer.php'; ?>
    <?php include 'utils.php'; ?>
</body>

</html>