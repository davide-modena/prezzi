<?php
    session_start();
?>

<?php include 'head.php'; ?>
<style>
    h1{
        text-align: center;
    }

    button{
        width: 40px;
        height: 40px;
        background-color: var(--green);
        border: none;
        color: var(--white);
        font-family: var(--font-title);
        font-weight: 700;
        font-size: 16px;
        box-shadow: 0 4px 7px 1px rgba(10, 172, 17, .25);
        border-radius: 5px;
    }
</style>

<body class="centered">

    <h1>Per aggiungere un prezzo vai sulla mappa, clicca un luogo e poi clicca sul</h1><button onClick="window.location.href='index.php'"><i class="fa-solid fa-plus"></i></button>

    <?php include 'footer.php'; ?> 
</body>

</html>