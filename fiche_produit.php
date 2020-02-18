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
                <iframe src="https://www.google.com/maps/embed?<?php echo $infos[0]['adresse'] . '+' .$infos[0]['cp'] . '+' . $infos[0]['ville'];  ?>" width="300" height="auto" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2782.5658888785483!2d3.0810466155680496!3d45.779889579105976!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47f71bdda2957945%3A0x107323d08e78f66a!2s3%20Rue%20Dulaure%2C%2063000%20Clermont-Ferrand!5e0!3m2!1sfr!2sfr!4v1582041573986!5m2!1sfr!2sfr" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
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
