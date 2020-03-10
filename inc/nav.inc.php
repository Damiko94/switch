<?php


?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo URL ?>index.php">Switch</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="presentation.php">Qui sommes nous?</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contact.php">Contact</a>
            </li>
        </ul>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <?php if (!user_is_connect()) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URL ?>index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URL ?>inscription.php">Inscription</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URL ?>connexion.php">Connexion</a>
                    </li>

                <?php } else {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URL ?>index.php">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URL ?>profil.php">Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URL ?>connexion.php?action=deconnexion">Deconnexion</a>
                        </li>
                        <?php if (user_is_admin()) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URL ?>admin/gestion_admin.php">Administration</a>
                        </li>
                        <?php
                    } } ?>
            </ul>
        </div>
    </div>
</nav>
<main>