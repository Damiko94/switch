<?php
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';

    $succes = '';
// MISE EN PLACE DE L'ENREGISTREMENT DE L'AVIS DANS LA BDD
if (isset($_POST['commentaire']) && isset($_POST['note']) && isset($_GET['id_salle'])) {
    $id_membre = $_SESSION['membre']['id_membre'];
    $commentaire = $_POST['commentaire'];
    $note = trim($_POST['note']);
    $id_salle = trim($_GET['id_salle']);

    // verification du commentaire, contre l'introduction de code malveillant grace aux regex
    $verif_commentaire = preg_match('#^[\wéèàêâùäï,\.,;:\-\'\s]+$#', $commentaire);

    if (!$verif_commentaire && !empty($commentaire)) {
        // caractères non autorisé dans le commentaire
        $msg .= '<div class="alert alert-danger mt-3">Votre commentaire comporte des caractères non autorisés</div>';
    }

    // verif de la note
    $verif_note = preg_match('#^[0-9]{0,10}$#', $note);

    if (!$verif_note && !empty($note)) {
        $msg .= '<div class="alert alert-danger mt-3">Votre note doit être comprise en tre 0 et 10</div>';
    }
    if (empty($msg)) {

        $enregistrement_avis = $pdo->prepare("INSERT INTO avis (id_membre, id_salle, commentaire, note, date_enregistrement)
                                                    VALUES (:id_membre, :id_salle, :commentaire, :note, NOW())");
        $enregistrement_avis->bindParam(":id_membre", $id_membre, PDO::FETCH_ASSOC);
        $enregistrement_avis->bindParam(":id_salle", $id_salle, PDO::FETCH_ASSOC);
        $enregistrement_avis->bindParam(":commentaire", $commentaire, PDO::FETCH_ASSOC);
        $enregistrement_avis->bindParam(":note", $note, PDO::FETCH_ASSOC);
        $enregistrement_avis->execute();
        $succes = '<div class="alert-info">Votre avis à bien été enregsitré, merci beaucoup pour votre impression.</div>';
    }
}

include 'inc/header.inc.php';
include 'inc/nav.inc.php';

?>

    <section class="container" style="height: 600px;  ">
        <h2 class="text-center">Donnez nous votre avis sur cette salle.</h2>
        <?php
        echo $msg;
        echo $succes;
        ?>
        <form action="" method="post">
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
// vd($_SESSION);
include 'inc/footer.inc.php';
