<?php
    session_start();
?>

<?php include 'head.php'; ?>
<link rel="stylesheet" href="css/add.css">

<body class="centered">
    <h1 class="add">Cosa vuoi aggiungere?</h1>
    <div class="add-container">
        <a href="addpriceguide.php" class="card"><i class="fa-solid fa-euro-sign"></i>Prezzo</a>
        <a href="addplace.php" class="card"><i class="fa-solid fa-location-dot"></i>Luogo</a>
    </div>

    <?php include 'footer.php'; ?> 
</body>

</html>