<section class="navMain">
    <nav>
        <div>
            <a href="index.php"><img src="img/logo.png" alt=""></a>
        </div>
        <ul>

            <li><a href="search_seance.php">Chercher une séance</a></li>
            <li><a href="avis.php">Avis</a></li>
            <?php if (isset($_SESSION['LOGIN_USER']['id'])) : ?>
                <li class="deroulant" id="nav"><a href="#">Salut, <?php echo $_SESSION['LOGIN_USER']['firstname'] ?></a>
                    <ul class="sous">
                        <li><a href="user/profil.php">Profil</a></li>
                        <li><a href="login/logout.php">Deconnexion</a></li>
                    </ul>
                </li>
            <?php else : ?>
                <li><a href="login/login_register.php">Connexion</a></li>
            <?php endif; ?>
            <?php if ($_SESSION['LOGIN_USER']['admin'] == 1) : ?>
                <li><a href="user/admin.php">Panel Admin</a></li>
            <?php endif; ?>

        </ul>
    </nav>

</section>