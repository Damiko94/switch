<?php
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';

// recupération des produits pour affichage sur la page d'acceuil
if (isset($_GET['action']) && $_GET['action'] == 'plus') {
    $liste_produits = $pdo->query("SELECT id_produit, prix, date_arrivee, date_depart, titre, description, photo FROM produit, salle 
                                         WHERE produit.id_salle = salle.id_salle 
                                         AND etat = 'libre'
                                         AND date_arrivee >= NOW()");
} else {
    $liste_produits = $pdo->query("SELECT id_produit, prix, date_arrivee, date_depart, titre, description, photo FROM produit, salle 
                                         WHERE produit.id_salle = salle.id_salle 
                                         AND etat = 'libre'
                                         AND date_arrivee >= NOW()
                                         LIMIT 0,6");
}


/******************************************************************************
 ******************************************************************************
 ********** FILTRAGE DE L'AFFICHAGE DES PRODUITS VIA UN FORMULAIRE ************
 ******************************************************************************
 *****************************************************************************/

// création de variables vides pour receuillir les données du formulaire
$categorie = '';
$ville = '';
$capacite = '';
$prix = '';
$date_arrivee = '';
$date_depart = '';
$nb_resultats = $liste_produits->rowCount();

// recuperation des données du formulaire et enregaistrements dans des variables
if (isset($_POST) && !empty($_POST)) {
    $categorie = $_POST['categorie'];
    $ville = $_POST['ville'];
    $capacite = $_POST['capacite'];
    $prix = $_POST['prix'];
    $date_arrivee = $_POST['date_arrivee'];
    $date_depart = $_POST['date_depart'];

// nouvelle requete pour appliquer les filtres sur la page d'acceuil

    $liste_prod_filtre = $pdo->prepare("SELECT id_produit, prix, date_arrivee, date_depart, titre, description, photo 
                                              FROM produit, salle 
                                              WHERE produit.id_salle = salle.id_salle
                                              AND categorie = :categorie
                                              AND ville = :ville
                                              AND capacite >= :capacite
                                              AND prix <= :prix
                                              AND date_arrivee >= :date_arrivee
                                              AND date_arrivee >= NOW()
                                              AND date_depart <= :date_depart
                                              AND etat = 'libre'");
    $liste_prod_filtre->bindParam(":categorie", $categorie, PDO::FETCH_ASSOC);
    $liste_prod_filtre->bindParam(":ville", $ville, PDO::FETCH_ASSOC);
    $liste_prod_filtre->bindParam(":capacite", $capacite, PDO::FETCH_ASSOC);
    $liste_prod_filtre->bindParam(":prix", $prix, PDO::FETCH_ASSOC);
    $liste_prod_filtre->bindParam(":date_arrivee", $date_arrivee, PDO::FETCH_ASSOC);
    $liste_prod_filtre->bindParam(":date_depart", $date_depart, PDO::FETCH_ASSOC);
    $liste_prod_filtre->execute();

    $nb_resultats = $liste_prod_filtre->rowCount();
}
/******************************************************************************
 ******************************************************************************
 ***************************** FIN DU FILTRAGE ********************************
 ******************************************************************************
 *****************************************************************************/

include 'inc/header.inc.php';
include 'inc/nav.inc.php';

vd($_POST);
vd($_SESSION);
?>

    <div class="row">
        <aside class="col-2" style="background-color: #9fcdff;">
            <form method="post" action="#">
                <div class="form-group">
                    <label for="categorie">Categorie</label>
                    <select name="categorie" class="form-control">
                        <option>Réunion</option>
                        <option>Bureau</option>
                        <option>Formation</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ville">Ville</label>
                    <select class="form-control" name="ville">
                        <option>Paris</option>
                        <option>Lyon</option>
                        <option>Marseille</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="capacite">Capacité</label>
                    <input type="number" name="capacite" class="form-control">
                </div>

                <div class="form-group">
                    <label for="prix"><b>Prix</b></label>
                    <input type="range" name="prix" min="0" max="1500" step="50" onchange="updateTextInput(this.value);"
                           class="form-control">
                    maximum :<input type="text" id="textInput" value="" class="w-50"> €
                </div>
                <p><b>Période</b></p>
                <div class="form-group">
                    <label for="date_arrivee">Date d'arrivée</label>
                    <input type="date" name="date_arrivee" class="form-control">
                </div>
                <div class="form-group">
                    <label for="date_depart">Date de départ</label>
                    <input type="date" name="date_depart" class="form-control">
                </div>
                <div class="form-group">
                    <button type="submit" class="form-control btn btn-dark">Rechercher</button>
                </div>
            </form>
            <div>
                <p><?php echo $nb_resultats; ?> résultats</p>
            </div>
        </aside>

        <div class="col-10">
            <div class="row justify-content-center">
                <?php
                if (isset($_POST) && !empty($_POST)) {
                    while ($produits = $liste_prod_filtre->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="col-3 text-center m-1 border rounded border-primary">';
                        echo '<div><img src="' . URL . 'img/' . $produits['photo'] . '" class="img-thumbnail" width="100%"></div>';
                        echo '<p>' . $produits['titre'] . ' : ' . $produits['prix'] . '</p>';
                        echo '<p style="overflow: hidden">' . $produits['description'] . '</p>';
                        echo '<p><i class="far fa-calendar-alt"></i>' . $produits['date_arrivee'] . ' au ' . $produits['date_depart'] . '</p>';
                        echo '<a href="fiche_produit.php?id_produit=' . $produits['id_produit'] . '" class="btn btn-primary"><i class="fas fa-search">Voir</i></a>';
                        echo '</div>';
                    }
                } else {
                    while ($produits = $liste_produits->fetch(PDO::FETCH_ASSOC)) {

                        echo '<div class="col-3 text-center m-1 border rounded border-primary">';
                        echo '<div><img src="' . URL . 'img/' . $produits['photo'] . '" class="img-thumbnail" width="100%"></div>';
                        echo '<p>' . $produits['titre'] . ' : ' . $produits['prix'] . '</p>';
                        echo '<p style="overflow: hidden">' . $produits['description'] . '</p>';
                        echo '<p><i class="far fa-calendar-alt"></i>' . $produits['date_arrivee'] . ' au ' . $produits['date_depart'] . '</p>';
                        echo '<a href="fiche_produit.php?id_produit=' . $produits['id_produit'] . '" class="btn btn-primary"><i class="fas fa-search">Voir</i></a>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <a href="?action=plus">voir plus...</a>
        </div>

    </div>
    <script>
        function updateTextInput(val) {
            document.getElementById('textInput').value = val;
        }
    </script>

<?php

include 'inc/footer.inc.php';
