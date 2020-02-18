<?php
include '../inc/init.inc.php';
include '../inc/fonction.inc.php';

// création de variable vide que l'on remplira avec les données de la table avis, membre et salle

$id_avis = '';
$id_membre = '';
$email = '';
$id_salle = '';
$titre = '';
$titre_salle = '';
$commentaire = '';
$note = '';
$date_enregistrement = '';

/**********************************************************
 * ********************************************************
 ************  \ SUPPRESSION D'UN AVIS ********************
 * ********************************************************
 *********************************************************/
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_membre'])) {
    $suppression = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id_avis");
    $suppression->bindParam(":id_avis", $_GET['id_avis'], PDO::PARAM_STR);
    $suppression->execute();

    $_GET['action'] = 'affichage'; // pour provoquer l'affichege des articles
}
/**********************************************************
 **********************************************************
 ***********  \ FIN DE SUPPRESSION D'UN AVIS **************
 **********************************************************
 *********************************************************/


include '../inc/header.inc.php';
include '../inc/nav.inc.php';

/**************************************************************************
 **************************************************************************
 ************** AFFICHAGE DES AVIS POUR LA GESTION ADMIN ****************
 **************************************************************************
 *************************************************************************/

// récupération des infos dans la table AVIS
$liste_avis = $pdo->query("SELECT * FROM avis");
// enregistrement des infos de la table avis dans une variable
while($avis = $liste_avis->fetch(PDO::FETCH_ASSOC)){
    $id_avis = $avis['id_avis'];
    $id_membre = $avis['id_membre'];
    $id_salle = $avis['id_salle'];
    $commentaire = $avis['commentaire'];
    $note = $avis['note'];
    $date_enregistrement = $avis['date_enregistrement'];
}

// recuperation des infos de la table membre pour affichge dans le tableau avis
$liste_membre = $pdo->query("SELECT email FROM membre WHERE id_membre IN (
                                        SELECT id_membre FROM avis)
                                        ");
// enregistrement des infos de la table membre dans une variable
while($membre = $liste_membre->fetch(PDO::FETCH_ASSOC)){
    $email = $membre['email'];
}

$liste_salle = $pdo->query("SELECT titre FROM salle WHERE id_salle IN (
                                        SELECT id_salle FROM avis)");
while($salle = $liste_salle->fetch(PDO::FETCH_ASSOC)) {
    $titre = $salle['titre'];
}
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
 *********** FIN AFFICHAGE DES MEMBRES POUR LA GESTION ADMIN ***************
 **************************************************************************
 *************************************************************************/


vd($_POST);
vd($date_enregistrement);

include '../inc/footer.inc.php';
