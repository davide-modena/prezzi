<?php
    session_start();
?>

<?php include 'head.php'; ?>
<link rel="stylesheet" href="css/user-page.css">

<body class="centered">
    <div class="userpage-container">
        <h1>Ciao, <?php echo $_SESSION['username']?></h1>
        <p>Log out</p>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        document.querySelector('p').addEventListener('click',() => {
            fetch('logout.php')
                .then(response => {
                    window.location.href = 'login.php';
                })
                .catch(error => console.error('Errore durante il logout:', error));
        });
    </script>
</body>

</html>