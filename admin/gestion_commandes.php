<?php
include '../inc/init.inc.php';
include '../inc/fonction.inc.php';

// recuperation des infos de la table commande

$liste_commande = $pdo->query("SELECT id_commande, id_membre, date_enregistrement, produit.id_produit, prix, date_arrivee, date_depart FROM commande, produit WHERE commande.id_produit = produit.id_produit");

include '../inc/header.inc.php';
include '../inc/nav.inc.php';

echo '<div class="table-responsive container">';
echo '<table class="table table-bordered">';
echo '<tr>';
echo '<th>id commande</th>';
echo '<th>id membre</th>';
echo '<th>id produit</th>';
echo '<th>prix</th>';
echo '<th>date d\'enregistrement</th>';
echo '<th>actions</th>';
echo '</tr>';

while ($commande = $liste_commande->fetch(PDO::FETCH_ASSOC)){
    echo '<tr>';
    echo '<td>' . $commande['id_commande'] . '</td>';
    echo '<td>' . $commande['id_membre'] .'</td>';
    echo '<td>' . $commande['id_produit'] . '</td>';
    echo '<td>' . $commande['prix'] . '</td>';
    echo '<td>' . $commande['date_enregistrement'] . '</td>';
    echo '<td><a href="?action=supprimer&id_membre=' . $commande['id_membre'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fas fa-minus-square"></i></a></td>';
    echo '</tr>';
}

echo '</table>';
echo '</div>';


include '../inc/footer.inc.php';
