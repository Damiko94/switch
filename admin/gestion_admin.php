<?php
include '../inc/init.inc.php';
include '../inc/fonction.inc.php';

if (!user_is_admin()) {
    header('location:../connexion.php');
    exit();
}
// differentes requetes pour affichage infos administrateur

// requete pour le nombres de commandes
$nb_commande = $pdo->query("SELECT id_commande FROM commande");
$nb_c = $nb_commande->rowCount();

// requete pour le nombre de clients
$nb_clients = $pdo->query("SELECT id_membre FROM membre");
$client = $nb_clients->rowCount();

// requete pour les produits
$produits = $pdo->query("SELECT id_produit FROM produit");
$nb_produits = $produits->rowCount();
$lb_produits = $pdo->query("SELECT id_produit FROM produit WHERE etat = 'libre'");
$nb_lb_prod = $lb_produits->rowCount();

// requete pour les salles
$nb_salles = $pdo->query("SELECT titre FROM salle");
$salle = $nb_salles->rowCount();

include '../inc/header.inc.php';
include '../inc/nav.inc.php';
if (isset($_SESSION)) {
    // vd($_SESSION);
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
            <div class="row">
                <div class="col-3">
                    <h3>Commandes :</h3>
                    <p>Vous avez actuellement <b><?= $nb_c ?></b> commande(s) en cours.</p>
                </div>
                <div class="col-3">
                    <h3>Clients</h3>
                    <p>Il y a actuellement <b><?= $client ?></b> compte(s) client(s).</p>
                </div>
                <div class="col-3">
                    <h3>Produits</h3>
                    <p>Il y a actuellement <b><?= $nb_produits ?></b> produits<br>
                        dont <b><?= $nb_lb_prod ?></b> de libres.
                    </p>
                </div>
                <div class="col-3">
                    <h3>Salles</h3>
                    <p>Switch possedent actuellement <b><?= $salle  ?></b> salles.</p>
                </div>
            </divrow>
        </section>
    </div>
<?php

include '../inc/footer.inc.php';
