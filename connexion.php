<?php
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';

// deconnexion
if (isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
    session_destroy();
    header('location:' . URL . 'index.php');
}

include 'inc/header.inc.php';
include 'inc/nav.inc.php';
$pseudo = '';
$mdp = '';
// verification de la validation du formulaire
if (isset($_POST['pseudo']) && isset($_POST['mdp'])) {
    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);

    // on recupere les informations en BDD de l'utilisateur sur la base du pseudo (unique en BDD)
    $verif_connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $verif_connexion->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
    $verif_connexion->execute();

    if ($verif_connexion->rowCount() > 0) {
        // si il y a une ligne dans $verif_connexion alors le pseudo est bon
        $info = $verif_connexion->fetch(PDO::FETCH_ASSOC);

        // verification du mot de passe
        if (password_verify($mdp, $info['mdp'])) {
            // le pseudo et le mot de passe sont corrects, on enregistre les informations dans la session
            // on enregistre les infos dans la session en créant une session membre
            $_SESSION['membre'] = array();

            $_SESSION['membre'] ['id_membre'] = $info['id_membre'];
            $_SESSION['membre'] ['pseudo'] = $info['pseudo'];
            $_SESSION['membre'] ['nom'] = $info['nom'];
            $_SESSION['membre'] ['prenom'] = $info['prenom'];
            $_SESSION['membre'] ['civilite'] = $info['civilite'];
            $_SESSION['membre'] ['email'] = $info['email'];
            $_SESSION['membre'] ['statut'] = $info['statut'];

            if (user_is_admin()){
                header('location:' . URL . 'admin/gestion_admin.php');
            }else{
                header('location:' . URL . 'index.php');
            }
        } else {
            $msg .= '<div class="alert alert-danger mt-3">Erreur sur le pseudo et / ou le mot de passe</div>';
        }
    } else {
        $msg .= '<div class="alert alert-danger mt-3">Erreur sur le pseudo et / ou le mot de passe</div>';
    }
}
?>
    <div style="height: 300px"></div>
    <section class=" container text-center">
        <h1>Identifiez vous s'il vous plait</h1>
            <p><?php echo $msg; ?></p>
        </div>
        <form action="#" method="post">
            <div class="form-group">
                <label for="pseudo">Votre pseudo</label>
                <input type="text" name="pseudo" class="form-control">
            </div>
            <div class="form-group">
                <label>Votre mot de passe</label>
                <input type="password" name="mdp" class="form-control">
            </div>
            <div class="form-group">
                <button type="submit" class="form-control btn btn-success">Connexion</button>
            </div>
        </form>
    </section>
    <div style="height: 300px"></div>
<?php

include 'inc/footer.inc.php';
