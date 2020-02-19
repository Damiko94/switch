<?php
include '../inc/init.inc.php';
include '../inc/fonction.inc.php';

if(!user_is_admin()){
    header('location:../connexion.php');
    exit();
}

// création de variable vide que l'on remplira avec les données du formulaire

$pseudo ='';
$mdp = '';
$nom ='';
$prenom = '';
$email = '';
$civilite = '';
$statut = '';
$date_enregistrement ='';

/**********************************************************
 * ********************************************************
 *  \ SUPPRESSION D'UN MEMBRE ****************************
 * ********************************************************
 *********************************************************/
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_membre'])) {
    $suppression = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
    $suppression->bindParam(":id_membre", $_GET['id_membre'], PDO::PARAM_STR);
    $suppression->execute();

    $_GET['action'] = 'affichage'; // pour provoquer l'affichege des articles
}
/**********************************************************
 **********************************************************
 *  \ FIN DE SUPPRESSION D'UN MEMBRE *********************
 **********************************************************
 *********************************************************/


/***************************************************************************
 ****************************************************************************
 ********* DEBUT GESTION DES MEMBRES ENREGISTREMENT ET MODIFICATION **********
 ****************************************************************************
 **************************************************************************/

// verification de l'existence de variables POST, si un formulaire a été validé

if (
    isset($_POST['pseudo']) &&
    isset($_POST['mdp']) &&
    isset($_POST['nom']) &&
    isset($_POST['prenom']) &&
    isset($_POST['email']) &&
    isset($_POST['civilite']) &&
    isset($_POST['statut'])) {

//si les variables POST existent, on les enregistrent dans d'autre variables en passant par un trim

    $pseudo = $_POST['pseudo'];
    $nom = $_POST['nom'];
    $mdp = $_POST['mdp'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $civilite = $_POST['civilite'];
    $statut = $_POST['statut'];
    $date_enregistrement = date("D, d M Y H:i:s");

    // preparation à l'enregistrement des variables en BDD
    // conditions pour faire une modification du produit dasn la base de donnée
    if (!empty($_POST['id_membre'])) {
        // si $id_salle n'est pas vide c'est un UPDATE
        $enregistrement_membre = $pdo->prepare("UPDATE membre 
                                                       SET pseudo = :pseudo,
                                                           mdp = :mdp,
                                                           nom = :nom,
                                                           prenom = :prenom,
                                                           email = :email,
                                                           civilite = :civilite,
                                                           statut = :statut,
                                                        WHERE id_membre = :id_membre");
        $enregistrement_membre->bindParam(":id_membre", $_POST['id_membre']);
    } else {

        $enregistrement_membre = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement)
                                                         VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut, NOW())");

    }
    $enregistrement_membre->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
    $enregistrement_membre->bindParam(":mdp", $mdp, PDO::PARAM_STR);
    $enregistrement_membre->bindParam(":nom", $nom, PDO::PARAM_STR);
    $enregistrement_membre->bindParam(":prenom", $prenom, PDO::PARAM_STR);
    $enregistrement_membre->bindParam(":email", $email, PDO::PARAM_STR);
    $enregistrement_membre->bindParam(":civilite", $civilite, PDO::PARAM_STR);
    $enregistrement_membre->bindParam(":statut", $statut, PDO::PARAM_STR);
    $enregistrement_membre->execute();

}

/**************************************************************************
 **************************************************************************
 ********* FIN GESTION DES MEMBRES ENREGISTREMENT ET MODIFICATION **********
 **************************************************************************
 *************************************************************************/

include '../inc/header.inc.php';
include '../inc/nav.inc.php';

/**************************************************************************
 **************************************************************************
 ************** AFFICHAGE DES MEMBRES POUR LA GESTION ADMIN ****************
 **************************************************************************
 *************************************************************************/

// récupération des infos dans la table membre
$liste_membre = $pdo->query("SELECT * FROM membre");

echo '<div class="table-responsive">';
echo '<table class="table table-bordered">';
echo '<tr>';
echo '<th>Id membre</th>';
echo '<th>Pseudo</th>';
echo '<th>Nom</th>';
echo '<th>Prénom</th>';
echo '<th>email</th>';
echo '<th>civilité</th>';
echo '<th>statut</th>';
echo '<th>date_enregistrement</th>';
echo '<th>actions</th>';
echo '</tr>';

while($membre = $liste_membre->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr>';

    echo '<td>' . $membre['id_membre'] . '</td>';
    echo '<td>' . $membre['pseudo'] . '</td>';
    echo '<td>' . $membre['nom'] . '</td>';
    echo '<td>' . $membre['prenom'] . '</td>';
    echo '<td>' . $membre['email'] . '</td>';
    echo '<td>' . $membre['civilite'] . '</td>';
    echo '<td>' . $membre['statut'] . '</td>';
    echo '<td>' . $membre['date_enregistrement'] . '</td>';
    echo '<td>
              <a href="?action=modifier&id_membre=' . $membre['id_membre'] . '" class="btn btn-warning"><i class="fas fa-pen-nib"></i></a>
              <a href="?action=supprimer&id_membre=' . $membre['id_membre'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fas fa-minus-square"></i></a>
          </td>';

    echo '</tr>';

    if (isset($_GET['action']) && $_GET['action'] == 'modifier' && isset($_GET['id_membre']) && $_GET['id_membre'] == $membre['id_membre']) {
        ?>
        <tr>
            <td colspan="9">
                <form action="" method="POST">
                    <h1 class="text-center pt-5">Gestion des membres</h1>
                    <p class="text-center"><a href="?action=annuler" class="btn btn-danger">Annuler votre modification</a></p>
                    <div class="row p-3">
                        <div class="col-6 p-3">
                            <div class="form-group">
                                <label for="pseudo">Pseudo</label>
                                <input type="text" id="pseudo" name="pseudo" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="mdp">Mot de passe</label>
                                <input type="password" id="mdp" name="mdp" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <input type="text" id="nom" name="nom" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="prenom">Prénom</label>
                                <input type="text" id="prenom" name="prenom" class="form-control">
                            </div>
                        </div>
                        <div class="col-6 p-3">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="civilite">Civilité</label>
                                <select name="civilite" id="civilite" class="form-control">
                                    <option>Homme</option>
                                    <option>Femme</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="statut">Civilité</label>
                                <select name="statut" id="statut" class="form-control">
                                    <option>Admin</option>
                                    <option>Membre</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="enregistrement" id="enregistrement"
                                        class="btn btn-primary"> Enregistrer </button>
                            </div>
                        </div>
                    </div>
                </form>
            </td>
        </tr>
        <?php
    }
}
echo '</table>';
echo '</div>';

/**************************************************************************
 **************************************************************************
 *********** FIN AFFICHAGE DES MEMBRES POUR LA GESTION ADMIN ***************
 **************************************************************************
 *************************************************************************/


vd($_POST);
vd($date_enregistrement);
if(empty($_GET['action']) || $_GET['action'] != 'modifier' && $_GET['action'] != 'supprimer') {
    ?>
    <section>
        <form action="" method="POST">
            <h1 class="text-center pt-5">Gestion des membres</h1>
            <div class="row p-5">
                <div class="col-6 p-5">
                    <div class="form-group">
                        <label for="pseudo">Pseudo</label>
                        <input type="text" id="pseudo" name="pseudo" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="mdp">Mot de passe</label>
                        <input type="password" id="mdp" name="mdp" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" class="form-control">
                    </div>
                </div>
                <div class="col-6 p-5">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="civilite">Civilité</label>
                        <select name="civilite" id="civilite" class="form-control">
                            <option>Homme</option>
                            <option>Femme</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="statut">Civilité</label>
                        <select name="statut" id="statut" class="form-control">
                            <option>Admin</option>
                            <option>Membre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="enregistrement" id="enregistrement"
                                class="btn btn-primary"> Enregistrer </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <?php
}
include '../inc/footer.inc.php';
