<?php
include 'inc/init.inc.php';
include  'inc/fonction.inc.php';


include  'inc/header.inc.php';
include  'inc/nav.inc.php';
if (!user_is_connect()){
    echo '<div class="container"><p>Vous devez être membre et connecté pour accéder à votre page de profil</p></div>';
} else {
    $id_membre = $_SESSION['membre']['id_membre'];
    $profil_commandes = $pdo->query("SELECT id_commande, date_arrivee, date_depart, titre 
                                               FROM commande, produit, salle 
                                               WHERE commande.id_membre = $id_membre
                                               AND commande.id_produit = produit.id_produit
                                               AND produit.id_salle = salle.id_salle
                                               ");
    $nb_commande = $profil_commandes->rowCount();
}
?>

<div class="container table-responsive">
    <table  class="table table-bordered">
        <tr>
            <td>Pseudo :</td>
            <td><?= $_SESSION['membre']['pseudo'] ?></td>
        </tr>
        <tr>
            <td>Prénom : </td>
            <td><?= $_SESSION['membre']['prenom'] ?></td>
        </tr>
        <tr>
            <td>Nom :</td>
            <td><?= $_SESSION['membre']['nom'] ?></td>
        </tr>
        <tr>
            <td>email :</td>
            <td><?= $_SESSION['membre']['email'] ?></td>
        </tr>
        <tr>
            <td>statut</td>
            <td><?php
                if ($_SESSION['membre']['prenom'] == 1){
                    echo 'client';
                } else {
                    echo 'administrateur';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Vos commandes</td>
            <td>
                <p>Vous avez <?= $nb_commande ?> commande(s).</p>
                <?php
                while($commande = $profil_commandes->fetch(PDO::FETCH_ASSOC)){
                    echo'<p>réference commande : ' . $commande['id_commande'] . ' , ' . $commande['titre'] . ' du ' . $commande['date_arrivee'] . ' au ' . $commande['date_depart'] . '</p>';
                }
                ?>
            </td>
        </tr>
    </table>
</div>

<?php

include 'inc/footer.inc.php';
