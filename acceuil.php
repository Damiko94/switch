<?php
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';

// recupération des produits pour affichage sur la page d'acceuil
$liste_produits = $pdo->query("SELECT id_produit, prix, date_arrivee, date_depart, titre, description, photo FROM produit, salle WHERE produit.id_salle = salle.id_salle");

include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

<div class="row">
    <aside class="col-2" style="background-color: #9fcdff;">
        <section>
            <h4>Catégorie</h4>
            <div class="list-group">
                <a href="?categorie=reunion" class="list-group-item">Réunion</a>
                <a href="?categorie=bureau" class="list-group-item">Bureau</a>
                <a href="?categorie=formation" class="list-group-item">Formation</a>
            </div>
        </section>
        <section>
            <h4>Ville</h4>
            <div class="list-group">
                <a href="?ville=paris" class="list-group-item">Paris</a>
                <a href="?ville=lyon" class="list-group-item">Lyon</a>
                <a href="?ville=bordeaux" class="list-group-item">Bordeaux</a>
            </div>
        </section>
        <section class="border border-dark mt-3 p-2">
            <form action="" method="post">
                <h4>Capacité</h4>
                <select>
                    <option>10</option>
                    <option>20</option>
                    <option>50</option>
                    <option>100</option>
                </select>
                <h4>Prix</h4>
                <input type="range" name="prix">
            <h4>Période</h4>
                <label for="date_arrivee">Date d'arrivée : </label>
                <input type="date" name="date_arrivee">
                <label for="date_depart">Date de départ : </label>
                <input type="date" name="date_depart">
                <button type="submit" class="btn btn-primary">Soumettre vos critères</button>
            </form>
        </section>
        <div>
            <p>résultats</p>
        </div>
    </aside>

    <div class="col-10">
        <div class="row justify-content-center">
            <?php
            while($produits = $liste_produits->fetch(PDO::FETCH_ASSOC)) {

                echo '<div class="col-3 text-center m-1 border rounded border-primary">';
                echo '<div><img src="' . URL . 'img/' . $produits['photo'] . '" class="img-thumbnail" width="100%"></div>';
                echo '<p>' . $produits['titre'] . ' : ' . $produits['prix'] . '</p>';
                echo '<p style="overflow: hidden">' . $produits['description'] . '</p>';
                echo '<p><i class="far fa-calendar-alt"></i>' . $produits['date_arrivee'] . ' au ' . $produits['date_depart'] . '</p>';
                echo '<a href="fiche_produit.php?id_produit=' . $produits['id_produit'] . '" class="btn btn-primary"><i class="fas fa-search">Voir</i></a>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

</div>

<?php

include 'inc/footer.inc.php';
