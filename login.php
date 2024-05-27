<?php
    session_start();

    if (isset($_SESSION['username'])) {
        header("Location: userPage.php");
        exit();
    }
?>

<?php include 'head.php' ?>
<link rel="stylesheet" href="css/login.css">


<body class="centered">
    <div class="login-form centered">
        <h1>
            <span>Login</span>
            /
            <span class="disabled">Registrati</span>
        </h1>
        <form action="loginDb.php" method="POST" id="loginForm">
            <div class="form-element">
                <h2>Username</h2>
                <input type="text" name="username" placeholder="Inserisci username...">
            </div>
            <div class="form-element">
                <h2>Password</h2>
                <input type="password" name="password" placeholder="Inserisci password...">
            </div>
            <button>Accedi</button>
        </form>
        <form action="signupDb.php" method="POST" id="signupForm" class="hidden">
            <div class="form-element">
                <h2>Username</h2>
                <input type="text" name="username" placeholder="Crea username...">
            </div>
            <div class="form-element">
                <h2>Password</h2>
                <input type="password" name="password" placeholder="Crea password...">
                <input type="password" name="password-repeat" placeholder="Ripeti password...">
            </div>
            <button>Registrati</button>
        </form>
    </div>
    <div id="errorContainer"></div>

    <script>
        var error = "<?php echo isset($_SESSION['error']) ? $_SESSION['error'] : ''; ?>";

        if (error.trim() !== '') {
            var errorMessageDiv = document.createElement('div');
            errorMessageDiv.textContent = error;
            
            document.getElementById('errorContainer').appendChild(errorMessageDiv);
        }
    </script>

    <script>
        const triggers = document.querySelector('.login-form h1');

        triggers.querySelectorAll('span').forEach(span => {
            span.addEventListener('click', (e) => {
                triggers.querySelectorAll('span').forEach(trigger => {
                    trigger.classList.toggle('disabled');
                    if(trigger.innerHTML == 'Login'){
                        document.getElementById('loginForm').classList.toggle('hidden');
                    }
                    else{
                        document.getElementById('signupForm').classList.toggle('hidden');
                    }
                });
            });
        });
    </script>

    <?php include 'footer.php'; ?> 
</body>

</html>