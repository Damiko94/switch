<?php
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';

// variable vide pour eviter des erreurs
$commande_enregistre = '';
$etat = '';

/**************************************************************************
 * ************************************************************************
 ******************* DEBUT AFFICHAGE DE LA FICHE PRODUIT*******************
 **************************************************************************
 * ***********************************************************************/

$id_produit = '';
if (isset($_GET['id_produit'])) {
    $id_produit = $_GET['id_produit'];

    // recuperation des infos du produits pour l'affichage de la fiche produit
    $infos_produit = $pdo->query("SELECT AVG(avis.note) AS NOTE, produit.id_salle, date_arrivee, date_depart, prix, etat, titre, description, photo, ville, adresse, cp, capacite, categorie 
                                            FROM avis, produit, salle
                                            WHERE avis.id_salle = salle.id_salle 
                                            AND produit.id_produit = $id_produit 
                                            AND produit.id_salle= salle.id_salle");
    $infos = $infos_produit->fetchAll(PDO::FETCH_ASSOC);
    $id_salle = $infos[0]['id_salle'];
}
// recuperation des photos des salles pour l'affichage des autres produits
$photo_salle = $pdo->query("SELECT photo FROM salle LIMIT 0, 4");

/**************************************************************************
 * ************************************************************************
 ******************** FIN AFFICHAGE DE LA FICHE PRODUIT********************
 **************************************************************************
 * ***********************************************************************/

/**************************************************************************
 * ************************************************************************
 ******************* DEBUT ENREGISTREMENT COMMANDE ************************
 **************************************************************************
 * ***********************************************************************/

if (isset($_GET['action']) && $_GET['action'] == 'reserver') {
    if (!user_is_connect()) {
        $msg .= '<div class="alert alert-danger mt-3">Veuillez vous connecter ou vous inscrire pour passer votre commande !</div>';
    } else {
        $id_membre = $_SESSION['membre']['id_membre'];
        $etat = 'reservation';
        $enregistrement_commande = $pdo->prepare("INSERT INTO commande (id_membre, id_produit, date_enregistrement) VALUES (:id_membre, :id_produit, NOW())");
        $enregistrement_commande->bindParam(":id_membre", $id_membre,PDO::PARAM_STR );
        $enregistrement_commande->bindParam(":id_produit", $id_produit,PDO::PARAM_STR );
        $enregistrement_commande->execute();

        $changement_statut_produit = $pdo->prepare("UPDATE produit SET etat = :etat WHERE id_produit = :id_produit");
        $changement_statut_produit->bindParam(":id_produit", $id_produit, PDO::PARAM_STR);
        $changement_statut_produit->bindParam(":etat", $etat, PDO::PARAM_STR);
        $changement_statut_produit->execute();

        $commande_enregistre = '<div class="alert alert-success mt-3">Votre commande est bien enregistrée, merci pour votre confiance.</div>';
    }
}

/**************************************************************************
 * ************************************************************************
 ********************* FIN ENREGISTREMENT COMMANDE ************************
 **************************************************************************
 * ***********************************************************************/

include 'inc/header.inc.php';
include 'inc/nav.inc.php';
// vd($_SESSION);
// vd($infos);

?>
    <section class="container">
        <?php
        echo $msg;
        echo $commande_enregistre;
        ?>
        <div class="row justify-content-between pt-3">
            <div class="col-3"><?php echo '<b>' . $infos[0]['titre'] . '</b>, note moyenne: ' . $infos[0]['NOTE']; ?></div>
            <div class="col-3"><a href="?action=reserver&id_produit=<?php echo $id_produit; ?>">
                    <button type="button">Réserver</button>
                </a></div>
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

                <iframe width="100%" height="auto" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                        src="https://maps.google.fr/maps?q=<?php echo $infos[0]['adresse'] . ', ' . $infos[0]['cp'] . ', ' . $infos[0]['ville']; ?>&output=embed"></iframe>
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
                <p>Adresse
                    : <?php echo $infos[0]['adresse'] . ', ' . $infos[0]['cp'] . ', ' . $infos[0]['ville']; ?></p>
                <p>Tarif : <?php echo $infos[0]['prix'] ?></p>
            </div>
        </div>
    </section>
    <section class="container">
        <div class="row">
            <?php
            while ($photo = $photo_salle->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="col-3"><img src="<?php echo URL . 'img/' . $photo['photo']; ?>" class="img-thumbnail"></div>
            <?php } ?>
        </div>
        <hr>
        <div class="row justify-content-between pb-3">
            <?php if (user_is_connect()){ ?>
            <a href="avis.php?&id_salle=<?php echo $infos[0]['id_salle']; ?>" class="btn-link">Déposer un commentaire et une note</a>
            <?php } else{ ?>
            <a href="connexion.php" class="btn-link">Veuillez vous connecter pour deposez un avis</a>
            <?php } ?>
            <a href="acceuil.php" class="btn-link">Retour vers le catalogue</a>
        </div>
    </section>
<?php

include 'inc/footer.inc.php';
