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
include 'inc/header.inc.php';
include 'inc/nav.inc.php';
vd($infos);

?>
    <section class="container">
        <div class="row justify-content-between">
            <div class="col-3"><?php echo $infos[0]['titre']; ?></div>
            <div class="col-3"><a href="?action=reserver&id_produit=<?php echo $id_produit; ?>"><button type="button">Réserver</button></a></div>
        </div>
        <div class="row">
            <div class="col-8">
                <img src="<?php echo URL . 'img/' . $infos[0]['photo']; ?>">
            </div>
            <div class="col-4">
                <p><b>Description</b></p>
                <p><?php echo $infos[0]['description']; ?></p>
                <p><b>Locatisation</b></p>
                <iframe width="auto" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.it/maps?q=<?php echo $infos[0]['adresse'] . ', ' . $infos[0]['cp'] . ', ' . $infos[0]['ville']; ?>&output=embed"></iframe>
                <p>Adresse : <?php echo $infos[0]['adresse'] . ', ' . $infos[0]['cp'] . ', ' . $infos[0]['ville'] ; ?></p>
                <p>Tarif : <?php echo $infos[0]['prix'] . ' €'; ?></p>
            </div>
        </div>
        <p><b>Infos complémentaires</b></p>
        <div class="row">
            <div class="col-4">
                <p>Arrivée : <?php echo $infos[0]['date_arrivee']; ?></p>
                <p>Départ : <?php echo $infos[0]['date_depart']; ?></p>
            </div>
            <div class="col-4">
                <p></p>
            </div>
            <div class="col-4"></div>
        </div>
    </section>
<?php

include 'inc/footer.inc.php';
