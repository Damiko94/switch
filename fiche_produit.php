<?php
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';

$id_produit = '';
if (isset($_GET['id_produit'])) {
    $id_produit = $_GET['id_produit'];

    // recuperation des infos du produits pour l'affichage de la fiche produit
    $infos_produit = $pdo->query("SELECT date_arrivee, date_depart, prix, etat, titre, description, photo, ville, adresse, cp, capacite, categorie 
                                            FROM produit, salle
                                            WHERE produit.id_produit = $id_produit AND produit.id_salle= salle.id_salle");
    $infos = $infos_produit->fetchAll(PDO::FETCH_ASSOC);
}
// recuperation des photos des salles pour l'affichage des autres produits
$photo_salle = $pdo->query("SELECT photo FROM salle");

include 'inc/header.inc.php';
include 'inc/nav.inc.php';
//vd($infos);

?>
    <section class="container">
        <div class="row justify-content-between pt-3">
            <div class="col-3"><?php echo $infos[0]['titre']; ?></div>
            <div class="col-3"><a href="?action=reserver&id_produit=<?php echo $id_produit; ?>"><button type="button">Réserver</button></a></div>
        </div>
        <hr>
        <div class="row">
            <div class="col-7">
                <img src="<?php echo URL . 'img/' . $infos[0]['photo']; ?>">
            </div>
            <div class="col-5">
                <p><b>Description</b></p>
                <p><?php echo $infos[0]['description']; ?></p>
                <p><b>Locatisation</b></p>

                <iframe width="100%" height="auto" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.it/maps?q=<?php echo $infos[0]['adresse'] . ', ' . $infos[0]['cp'] . ', ' . $infos[0]['ville']; ?>&output=embed"></iframe>
            </div>
        </div>
        <hr>
        <p><b>Infos complémentaires</b></p>
        <div class="row">
            <div class="col-4">
                <p>Arrivée : <?php echo $infos[0]['date_arrivee']; ?></p>
                <p>Départ : <?php echo $infos[0]['date_depart']; ?></p>
            </div>
            <div class="col-4">
                <p>Capacité : <?php echo $infos[0]['capacite'] ?></p>
                <p>Catégorie : <?php echo $infos[0]['categorie'] ?></p>
            </div>
            <div class="col-4">
                <p>Adresse : <?php echo $infos[0]['adresse'] . ', ' . $infos[0]['cp'] . ', ' . $infos[0]['ville']; ?></p>
                <p>Tarif : <?php echo $infos[0]['prix'] ?></p>
            </div>
        </div>
    </section>
    <section class="container">
        <div class="row">
        <?php
            while ($photo = $photo_salle->fetch(PDO::FETCH_ASSOC)){
        ?>
            <div class="col-3"><img src="<?php echo URL . 'img/' . $photo['photo']; ?>"class="img-thumbnail"></div>
        <?php } ?>
        </div>
        <hr>
        <div class="row justify-content-between pb-3">
            <a href="avis.php?id_produit=<?php $id_produit;?>" class="btn-link">Déposer un commentaire et une note</a>
            <a href="acceuil.php" class="btn-link">Retour vers le catalogue</a>
        </div>
    </section>
<?php

include 'inc/footer.inc.php';
