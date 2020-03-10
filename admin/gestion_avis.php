<?php
include '../inc/init.inc.php';
include '../inc/fonction.inc.php';

if (!user_is_admin()) {
    header('location:../connexion.php');
    exit();
}

/**********************************************************
 * ********************************************************
 ************  \ SUPPRESSION D'UN AVIS ********************
 * ********************************************************
 *********************************************************/
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_membre'])) {
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


// récupération des infos dans la table AVIS
$liste_avis = $pdo->query("SELECT avis.*, membre.email, salle.titre 
                                      FROM avis, membre, salle 
                                      WHERE avis.id_membre = membre.id_membre
                                      AND avis.id_salle = salle.id_salle");

    /**************************************************************************
    **************************************************************************
    ************** AFFICHAGE DES AVIS POUR LA GESTION ADMIN ******************
    **************************************************************************
    *************************************************************************/
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
                <a class="nav-link active" href="<?php echo URL ?>admin/gestion_avis.php">Gestion des avis</a>
            </li>
            </li><li class="nav-item">
                <a class="nav-link" href="<?php echo URL ?>admin/statistiques.php">Statistiques</a>
            </li>
        </ul>
    </aside>
    <div class="col-10 container table-responsive">
        echo '<h1 class="alert-info text-dark text-center">Gestion des avis</h1>';
    <table class="table table-bordered">
    <tr>
        <th>id avis</th>
        <th>id membre</th>
        <th>id salle</th>
        <th>commentaire</th>
        <th>note</th>
        <th>date enregistrement</th>
        <th>actions</th>
    </tr>
<?php
    // boucle pour affichage des infos table avis
while ($avis = $liste_avis->fetch(PDO::FETCH_ASSOC)):
    echo '<tr>';

        echo '<td>' . $avis['id_avis'] . '</td>';
        echo '<td>' . $avis['id_membre'] . '</td>';
        echo '<td>' . $avis['id_salle'] . '</td>';
        echo '<td>' . $avis['commentaire'] . '</td>';
        echo '<td>' . $avis['note'] . '</td>';
        echo '<td>' . $avis['date_enregistrement'] . '</td>';
        echo '<td>
                <a href="?action=supprimer&id_avis='.$avis['id_avis'].'" class="btn btn-danger"onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fas fa-minus-square"></i></a>
                </td>';
    echo '</tr>';
    endwhile;
        ?>
    </table>
    </div>

</div>

<?php

    include '../inc/footer.inc.php';
