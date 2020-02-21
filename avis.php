<?php
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


include 'inc/header.inc.php';
include 'inc/nav.inc.php';

?>

    <section class="container" style="height: 600px;  ">
        <h2 class="text-center">Donnez nous votre avis sur cette salle.</h2>
        <form action="fiche_produit.php" method="post">
            <div class="form-group">
                <label for="commentaire"><b>Laissez nous un commentaire</b></label>
                <textarea name="commentaire" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="note"><b>Laissez une note sur 10 : </b></label>
                <input type="number" name="note" class="form-control w-25"
                min="0" max="10"/>
            </div>
            <div>
                <button type="submit" class="form-control btn btn-dark">Envoyer</button>
            </div>
        </form>
    </section>
<?php
vd($_SESSION);
include 'inc/footer.inc.php';
