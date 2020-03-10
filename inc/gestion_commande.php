<?php
include '../inc/init.inc.php';
include '../inc/fonction.inc.php';

// création de variable vide que l'on remplira avec les données de la table avis, membre et salle

$id_commande = '';
$id_membre = '';
$email = '';
$id_produit = '';
$titre_salle = '';
$date_arrivee = '';
$date_depart ='';
$prix = '';
$commentaire = '';
$date_enregistrement = '';

/**********************************************************
 * ********************************************************
 ************  \ SUPPRESSION D'UNE COMMANDE ********************
 * ********************************************************
 *********************************************************/
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_commande'])) {
    $suppression = $pdo->prepare("DELETE FROM commande WHERE id_commande = :id_commande");
    $suppression->bindParam(":id_commande", $_GET['id_commande'], PDO::PARAM_STR);
    $suppression->execute();
    $changement_statut_produit = $pdo->prepare("UPDATE produit SET etat = :etat WHERE id_produit = :id_produit");
    $changement_statut_produit->bindParam(":id_produit", $id_produit, PDO::PARAM_STR);
    $changement_statut_produit->bindParam(":etat", $etat, PDO::PARAM_STR);
    $changement_statut_produit->execute();

    $_GET['action'] = 'affichage'; // pour provoquer l'affichege des articles
}
/**********************************************************
 **********************************************************
 ***********  \ FIN DE SUPPRESSION D'UNE COMMANDE **************
 **********************************************************
 *********************************************************/


include '../inc/header.inc.php';
include '../inc/nav.inc.php';

/**************************************************************************
 **************************************************************************
 ************** AFFICHAGE DES COMMANDES POUR LA GESTION ADMIN ****************
 **************************************************************************
 *************************************************************************/

// récupération des infos dans la table commande
$liste_commande = $pdo->query("SELECT * FROM commande");
// enregistrement des infos de la table commande dans une variable
while($commande = $liste_commande->fetch(PDO::FETCH_ASSOC)){
    $id_commande = $commande['id_commande'];
    $id_membre = $commande['id_membre'];
    $id_produit = $commande['produit'];
    $date_enregistrement = $commande['date_enregistrement'];
}

// recuperation des infos de la table produit pour affichage dans le tableau commande
$liste_produit = $pdo->query("SELECT date_arrivee date_depart prix id_salle FROM produit WHERE id_produit IN (
                                        SELECT id_produit FROM commande)");
// enregistrement des infos de la table produit dans une variable
while($produit = $liste_produit->fetch(PDO::FETCH_ASSOC)){
    $date_arrivee = $produit['date_arrivee'];
    $date_depart = $produit['date_depart'];
    $id_salle = $produit['id_salle'];
}
// recuperation des infos de la table membre pour affichage dans le tableau commande
$liste_membre = $pdo->query("SELECT email FROM membre WHERE id_membre IN (
                                        SELECT id_membre FROM commande)");
// enregistrement des infos dans une variable
while($membre = $liste_membre->fetch(PDO::FETCH_ASSOC)) {
    $email = $membre['email'];
}

//recuperation des infos salle pour affichage dans le tableau commande
$liste_salle = $pdo->query("SELECT titre FROM salle WHERE id_salle $id_salle ");
$salle = $liste_salle->fetch(PDO::FETCH_ASSOC);
while()

echo '<div class="table-responsive">';
echo '<table class="table table-bordered">';
echo '<tr>';
echo '<th>Id avis</th>';
echo '<th>Id membre</th>';
echo '<th>Id salle</th>';
echo '<th>commentaire</th>';
echo '<th>note</th>';
echo '<th>date_enregistrement</th>';
echo '<th>actions</th>';
echo '</tr>';

echo '<tr>';

echo '<td>' . $id_avis . '</td>';
echo '<td>' . $id_membre . '-' . $email . '</td>';
echo '<td>' . $salle . '-' . $titre . '</td>';
echo '<td>' . $commentaire . '</td>';
echo '<td>' . $note  . '</td>';
echo '<td>' . $date_enregistrement . '</td>';
echo '<td>
              <a href="?action=modifier&id_avis=' . $id_avis . '" class="btn btn-warning"><i class="fas fa-pen-nib"></i></a>
              <a href="?action=supprimer&id_avis=' . $id_avis . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fas fa-minus-square"></i></a>
          </td>';

echo '</tr>';

echo '</table>';
echo '</div>';

/**************************************************************************
 **************************************************************************
 *********** FIN AFFICHAGE DES COMMANDES POUR LA GESTION ADMIN ***************
 **************************************************************************
 *************************************************************************/


vd($_POST);
vd($date_enregistrement);

include '../inc/footer.inc.php';
