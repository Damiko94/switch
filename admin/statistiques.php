<?php
include '../inc/init.inc.php';
include '../inc/fonction.inc.php';

/**********************************************************************************************************************
 * ********************************************************************************************************************
 * ********* RECUPERATION DES MOYENNES DES NOTES DES SALLES PAR ORDRE DECROISSANT DANS LA LIMITE DE 5 SALLES **********
 * ********************************************************************************************************************
 *********************************************************************************************************************/

$notes_salle = $pdo->query("SELECT titre, AVG(note) AS NOTE
                                      FROM salle, avis
                                      WHERE salle.id_salle = avis.id_salle 
                                      GROUP BY salle.id_salle 
                                      ORDER BY NOTE DESC 
                                      LIMIT 5");
$notes = $notes_salle->fetchAll(PDO::FETCH_ASSOC);

/**********************************************************************************************************************
 * ********************************************************************************************************************
 * ************* FIN DES MOYENNES DES NOTES DES SALLES PAR ORDRE DECROISSANT DANS LA LIMITE DE 5 SALLES ***************
 * ********************************************************************************************************************
 *********************************************************************************************************************/


/**********************************************************************************************************************
 * ********************************************************************************************************************
 * *********** RECUPERATION DES SALLES LES PLUS COMMANDEES PAR ORDRE CROISSANT DANS LA LIMITE DE 5 SALLES *************
 * ********************************************************************************************************************
 *********************************************************************************************************************/

$commandes_salle = $pdo->query("SELECT titre, COUNT(*) AS TITRECOUNT FROM commande, produit, salle
                                          WHERE commande.id_produit = produit.id_produit 
                                          AND produit.id_salle = salle.id_salle 
                                          GROUP BY titre 
                                          ORDER BY TITRECOUNT DESC 
                                          LIMIT 5");
$commande = $commandes_salle->fetchAll(PDO::FETCH_ASSOC);

/**********************************************************************************************************************
 * ********************************************************************************************************************
 * ************ LES MEMBRES QUI COMMANDE LE PLUS DE SALLE PAR ORDRE CROISSANT DANS LA LIMITE DE 5 SALLES **************
 * ********************************************************************************************************************
 *********************************************************************************************************************/

$membre_salle = $pdo->query("SELECT COUNT(commande.id_membre) AS MEMBRECOUNT, prenom, nom 
                                      FROM commande, membre
                                      WHERE commande.id_membre = membre.id_membre 
                                      GROUP BY commande.id_membre 
                                      ORDER BY MEMBRECOUNT DESC 
                                      LIMIT 5");
$membre_quantite = $membre_salle->fetchAll(PDO::FETCH_ASSOC);

/**********************************************************************************************************************
 * ********************************************************************************************************************
 * ******************************* FIN DES MEMBRES QUI COMMANDE LE PLUS DE SALLES *************************************
 * ********************************************************************************************************************
 *********************************************************************************************************************/

/**********************************************************************************************************************
 * ********************************************************************************************************************
 * ********************************* LES MEMBRES QUI DEPENSENT LE PLUS EN COMMANDE ************************************
 * ********************************************************************************************************************
 *********************************************************************************************************************/

$membre_depense = $pdo->query("SELECT prenom, nom, SUM(produit.prix) AS TOTAL
                                        FROM membre, commande, produit 
                                        WHERE membre.id_membre = commande.id_membre
                                        AND commande.id_produit = produit.id_produit
                                        GROUP BY commande.id_membre
                                        ORDER BY TOTAL DESC 
                                        LIMIT 5");
$depense = $membre_depense->fetchAll(PDO::FETCH_ASSOC);
/**********************************************************************************************************************
 * ********************************************************************************************************************
 * ******************************** FIN DES MEMBRES QUI DEPENSENT LE PLUS EN COMMANDE *********************************
 * ********************************************************************************************************************
 *********************************************************************************************************************/

include '../inc/header.inc.php';
include '../inc/nav.inc.php';
// vd($depense);

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
        <p>
            <a href="?action=note">Top 5 des salles les mieux notées</a>
        </p>
        <p>
            <a href="?action=commande">Top 5 des salles les plus commandées</a>
        </p>
        <p>
            <a href="?action=quantite">Top 5 des membres qui achetent le plus (quantité)</a>
        </p>
        <p>
            <a href="?action=prix">Top 5 des membres qui achhètent le plus en prix</a>
        </p>
    </section>
    <div class="container justify-content-center">
        <?php
        if (isset($_GET['action']) && $_GET['action'] == 'note') {
            for ($i = 0; $i < 5; $i++) {
                echo '<div class="row w-50 mx-auto justify-content-between my-2">
                    <p>' . ($i + 1) . ' - ' . $notes[$i]['titre'] . '</p>
                    <button type="none" class="btn btn-primary">' . $notes[$i]['NOTE'] . ' </button>
                  </div>';
            }
        }

        if (isset($_GET['action']) && $_GET['action'] == 'commande') {
            for ($i = 0; $i < 5; $i++) {
                echo '<div class="row w-50 mx-auto justify-content-between my-2">
                    <p>' . ($i + 1) . ' - ' . $commande[$i]['titre'] . '</p>
                    <button type="none" class="btn btn-primary">' . $commande[$i]['TITRECOUNT'] . ' </button>
                  </div>';
            }
        }

        if (isset($_GET['action']) && $_GET['action'] == 'quantite') {
            for ($i = 0; $i < 5; $i++) {
                echo '<div class="row w-50 mx-auto justify-content-between my-2">
                    <p>' . ($i + 1) . ' - ' . $membre_quantite[$i]['prenom'] . ' ' . $membre_quantite[$i]['nom'] . '</p>
                    <button type="none" class="btn btn-primary">' . $membre_quantite[$i]['MEMBRECOUNT'] . ' </button>
                  </div>';
            }
        }

        if (isset($_GET['action']) && $_GET['action'] == 'prix') {
            for ($i = 0; $i < 5; $i++) {
                echo '<div class="row w-50 mx-auto justify-content-between my-2">
                    <p>' . ($i + 1) . ' - ' . $depense[$i]['prenom'] . ' ' . $depense[$i]['nom'] . '</p>
                    <button type="none" class="btn btn-primary">' . $depense[$i]['TOTAL'] . ' €</button>
                  </div>';
            }
        }
        ?>
    </div>
</div>

<?php

include '../inc/footer.inc.php';
