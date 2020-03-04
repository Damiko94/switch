<?php
include '../inc/init.inc.php';
include '../inc/fonction.inc.php';

if (!user_is_admin()) {
    header('location:../connexion.php');
    exit();
}

include '../inc/header.inc.php';
include '../inc/nav.inc.php';
if (isset($_SESSION)) {
    vd($_SESSION);
}
?>
    <div class="row">
        <aside class="col-2">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URL ?>admin/gestion_salle.php">Gestion des salles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URL ?>admin/gestion_produits.php">Gestion des produits</a>
                </li><li class="nav-item">
                    <a class="nav-link" href="<?php echo URL ?>admin/gestion_membres.php">Gestion des membres</a>
                </li><li class="nav-item">
                    <a class="nav-link" href="<?php echo URL ?>admin/gestion_commandes.php">Gestion des commandes</a>
                </li><li class="nav-item">
                    <a class="nav-link" href="<?php echo URL ?>admin/gestion_avis.php">Gestion des avis</a>
                </li>
                </li><li class="nav-item">
                    <a class="nav-link" href="<?php echo URL ?>admin/statistiques.php">Statistiques</a>
                </li>
            </ul>
        </aside>
        <section class="col-10">
        </section>
    </div>
<?php

include '../inc/footer.inc.php';
