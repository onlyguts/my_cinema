<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style///login.css">
</head>

<body>
    <div class="backImg">
        <a href="../index.php"> <img src="../img/back.png" alt=""> </a>
    </div>
    <?php session_start();
    if (isset($_SESSION['ERROR_LOGIN'])) : ?>
        <div class="warning">
            <div class="warning-bar">
                <img src="../img/warning-icon.png" alt="">
                <p>
                    <?php
                    echo $_SESSION['ERROR_LOGIN'];
                    unset($_SESSION['ERROR_LOGIN']);
                    ?>
                </p>
            </div>
        </div>

    <?php endif; ?>
    <div class="wrapper">

        <div class="grid-lr">
            <div class="login">
                <form action="login.php" method="post">
                    <h2>Connexion</h2>
                    <label for="email">Email :</label>
                    <input type="email" name="email" id="email" required>

                    <label for="password">MDP :</label>
                    <input type="password" name="password" id="password" required>

                    <input type="submit" id="sbt-login" value="Ce connecter">
                </form>
            </div>
            <div class="register">
                <form action="register.php" method="post">
                    <h2>Inscription</h2>
                    <label for="email">Email :</label>
                    <input type="email" name="email" id="email" required>

                    <label for="password">MDP :</label>
                    <input type="password" name="password" id="password" required>

                    <label for="passwordverif">MDP VERIF :</label>
                    <input type="password" name="passwordverif" id="passwordverif" required>

                    <label for="firstname">Firstname : </label>
                    <input type="text" name="firstname" id="firstname" required>

                    <label for="lastname">Lastname : </label>
                    <input type="text" name="lastname" id="lastname" required>

                    <label for="date">Birthdate : </label>
                    <input type="datetime-local" name="date" id="date" required>

                    <label for="address">Address : </label>
                    <input type="text" name="address" id="address" required>

                    <label for="zipcode">zipcode : </label>
                    <input type="number" name="zipcode" id="zipcode" required>

                    <label for="city">city : </label>
                    <input type="text" name="city" id="city" required>

                    <label for="country">country : </label>
                    <input type="text" name="country" id="country" required>

                    <input type="submit" id="sbt-register" value="Créer un compte">
                </form>
            </div>
        </div>
    </div>
</body>

</html>