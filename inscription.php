<?php
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


/****************************************************************************
 ****************************************************************************
 **************** DEBUT GESTION ENREGISTREMENT DES MEMBRES ******************
 ****************************************************************************
 **************************************************************************/

// création de variables vides pour l'enregistrement
$pseudo = '';
$mdp = '';
$nom = '';
$prenom = '';
$email = '';
$civilite = '';

if (
    isset($_POST['pseudo']) &&
    isset($_POST['mdp']) &&
    isset($_POST['nom']) &&
    isset($_POST['prenom']) &&
    isset($_POST['email']) &&
    isset($_POST['civilite'])) {

    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $civilite = trim($_POST['civilite']);

    // vérification du pseudo, pour voir s'il ne contient pas de caracteres spéciaux grace à une regex
    $verif_pseudo = preg_match('#^[a-zA-Z0-9._-]+$#', $pseudo);
    //  affichage d'un message d'erreur si le pseudo est vide ou contient des caracteres interdits
    if (!$verif_pseudo && !empty($pseudo)) {
        // cas d'erreur, caractere non autorisé
        $msg .= '<div class="alert alert-danger mt-3">Pseudo invalide, caractères autorisés : a-z et de 0-9</div>';
    }

    // on répète l'opération de vérification pour le nom et le prenom
    $verif_nom = preg_match('#^[a-zA-Z0-9._-]+$#', $nom);
    if (!$verif_nom && !empty($nom)) {
        // cas d'erreur, caractere non autorisé
        $msg .= '<div class="alert alert-danger mt-3">Le format de votre nom est invalide, caractères autorisés : a-z et de 0-9</div>';
    }

    $verif_prenom = preg_match('#^[a-zA-Z0-9._-]+$#', $prenom);
    if (!$verif_prenom && !empty($prenom)) {
        // cas d'erreur, caractere non autorisé
        $msg .= '<div class="alert alert-danger mt-3">Le format de votre prenom est invalide, caractères autorisés : a-z et de 0-9</div>';
    }

    // verification de l'adresse email
    $verif_email = preg_match(" /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ ", $email);
    if (!$verif_email && !empty($email)) {
        // cas d'erreur, caractere non autorisé
        $msg .= '<div class="alert alert-danger mt-3">Le format de votre email est invalide.</div>';
    }
    // on verifie si il n'existe pas de message d'erreur avtn de rentrer les informations en base de données
    if (empty($msg)) {

        // verification de la disponibilité du pseudo
        // on vérifie si le pseudo est disponible.
        $dispo_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
        $dispo_pseudo->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
        $dispo_pseudo->execute();

        if ($dispo_pseudo->rowCount() > 0) {
            // si le nombre de ligne est supérieur à zéro, alors le pseudo est déja utilisé.
            $msg .= '<div class="alert alert-danger mt-3">Pseudo non disponible !</div>';
        } else {
            // insert into
            // cryptage du mot de passe pour l'insertion dans la BDD
            $mdp = password_hash($mdp, PASSWORD_DEFAULT);

            // connexion et préparation à l'insertion des variables dans la BDD

            $enregistrement_membre = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, 1, NOW())");
            $enregistrement_membre->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
            $enregistrement_membre->bindParam(":mdp", $mdp, PDO::PARAM_STR);
            $enregistrement_membre->bindParam(":nom", $nom, PDO::PARAM_STR);
            $enregistrement_membre->bindParam(":prenom", $prenom, PDO::PARAM_STR);
            $enregistrement_membre->bindParam(":email", $email, PDO::PARAM_STR);
            $enregistrement_membre->bindParam(":civilite", $civilite, PDO::PARAM_STR);
            $enregistrement_membre->execute();

            echo '<div class="container text-center mt-3">';
            echo '<h3>Félicitations, vous etes maintenant membre de switch</h3>';
            echo '<a href="connexion.php"><button type="button" class="btn btn-success">Cliquez ici pour vous rendre sur la page de connexion</button></a>';
            echo '</div>';
        }
    }
}
// vd($_POST);

/****************************************************************************
 *****************************************************************************
 ***************** FIN GESTION ENREGISTREMENT DES MEMBRES ********************
 *****************************************************************************
 ****************************************************************************/
include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>
    <div class="container w-25">
        <div class="text-center">
            <h1>Bienvenue chez switch</h1>
            <h3>Remplissez ce formulaire d'inscription</h3>
            <p class="lead"><?php echo $msg ?></p>
        </div>
        <form action="" method="post">
            <div class="form-group m-2 p-2">
                <label for="pseudo">choisissez votre pseudo</label>
                <input type="text" name="pseudo" class="form-control">
            </div>
            <div class="form-group m-2 p-2">
                <label for="mdp">choisissez votre mot de passe</label>
                <input type="password" name="mdp" class="form-control">
            </div>
            <div class="form-group m-2 p-2">
                <label for="nom">Votre nom</label>
                <input type="text" name="nom" class="form-control">
            </div>
            <div class="form-group m-2 p-2">
                <label for="prenom">Votre prénom</label>
                <input type="text" name="prenom" class="form-control">
            </div>
            <div class="form-group m-2 p-2">
                <label for="email">Votre email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group m-2 p-2">
                <label for="civilite">Civilité</label>
                <select name="civilite" class="form-control">
                    <option>m</option>
                    <option>f</option>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success">Valider votre inscription</button>
            </div>
        </form>
    </div>
<?php
/*if (empty($msg)) {

}*/
include 'inc/footer.inc.php';
