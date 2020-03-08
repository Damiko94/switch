<?php
include '../inc/init.inc.php';
include '../inc/fonction.inc.php';

if(!user_is_admin()){
    header('location:../connexion.php');
    exit();
}

/**********************************************************
 * ********************************************************
 *  \ SUPPRESSION D'UNE SALLE *****************************
 * ********************************************************
 *********************************************************/
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_salle'])) {
    $suppression = $pdo->prepare("DELETE FROM salle WHERE id_salle = :id_salle");
    $suppression->bindParam(":id_salle", $_GET['id_salle'], PDO::PARAM_STR);
    $suppression->execute();

    $_GET['action'] = 'affichage'; // pour provoquer l'affichege des articles
}
/**********************************************************
 **********************************************************
 *  \ FIN DE SUPPRESSION D'UNE SALLE *********************
 **********************************************************
 *********************************************************/



// création de variable vide que l'on remplira avec les données du formulaire

$id_salle ='';
$titre ='';
$description ='';
$pays ='';
$ville = '';
$adresse = '';
$cp = '';
$capacite = '';
$categorie = '';
$photo_bdd = '';
$dossier_photo ='';

/********************************************************************
 * ******************************************************************
 *  \ MODIFICATION  RECUPERATION DES INFOS DE LA SALLE EN BDD *******
 * ******************************************************************
 *******************************************************************/

if(isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_salle'])) {
    $infos_salle = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
    $infos_salle->bindParam(":id_salle", $_GET['id_salle'], PDO::PARAM_STR);
    $infos_salle->execute();

    if($infos_salle->rowCount() > 0){
        $_salle_actuelle = $infos_salle->fetch(PDO::FETCH_ASSOC);

        $id_salle =$_salle_actuelle['id_salle'];
        $titre =$_salle_actuelle['titre'];
        $description =$_salle_actuelle['description'];
        $pays =$_salle_actuelle['pays'];
        $ville = $_salle_actuelle['ville'];
        $adresse = $_salle_actuelle['adresse'];
        $cp = $_salle_actuelle['cp'];
        $capacite = $_salle_actuelle['capacite'];
        $categorie = $_salle_actuelle['categorie'];
        $photo_actuelle = $_salle_actuelle['photo'];
    }
}

/*********************************************************************
 * *******************************************************************
 *  \ FIN MODIFICATION  RECUPERATION DES INFOS DE LA SALLE EN BDD ****
 * *******************************************************************
 ********************************************************************/

/***************************************************************************
****************************************************************************
********* DEBUT GESTION DES SALLES ENREGISTREMENT ET MODIFICATION **********
****************************************************************************
 **************************************************************************/

// verification de l'existence de variables POST, si un formulaire a été validé

if (
    isset($_POST['titre']) &&
    isset($_POST['description']) &&
    isset($_POST['pays']) &&
    isset($_POST['ville']) &&
    isset($_POST['adresse']) &&
    isset($_POST['cp']) &&
    isset($_POST['capacite']) &&
    isset($_POST['categorie'])){

//si les variables POST existent, on les enregistrent dans d'autre variables en passant par un trim

        $titre = trim($_POST['titre']);
        $description = trim($_POST['description']);
        $pays = trim($_POST['pays']);
        $ville = trim($_POST['ville']);
        $adresse = trim($_POST['adresse']);
        $cp = trim($_POST['cp']);
        $capacite = trim($_POST['capacite']);
        $categorie = trim($_POST['categorie']);


        /*******************************************
         *******************************************
         ******* TRAITEMENT DE L'INPUT PHOTO *******
         *******************************************
         ******************************************/


            // si une photo a été téléchargé on doit vérifier son extension
            // récupération de l'extension
            $extension =strrchr($_FILES['photo']['name'], '.');
            // on enlève le point de l'extension tout en la passant en minuscule, pour pouvoir la comparer
            $extension =strtolower(substr($extension, 1));

            //déclaration d'un tableau contenant les extensions acceptées
            $valid_extension = array('png', 'gif', 'jpg', 'jpeg');

            //grace à la fonction in array, l'on va comparer l'extension récuperé dans le formulaire aux extensions acceptées dans notre tableau
            // pour cela création d'une variable qui enregistre le résultat de la fonction in_array(); in_array() enregistrera la valeur true or false dans la variable,
            // ce qui nous permettra de verifier si l'extension est valide.
            $verif_extension = in_array($extension, $valid_extension);

            // si l'extension est valide, on enregistre le nom de la photo dans la variable $photo_bdd
            $photo_bdd = $_FILES['photo']['name'];


            // preparation du chemin pour l'enregistrement de l'image
            $dossier_photo = SERVER_ROOT . SITE_ROOT . 'img/' . $photo_bdd;

            // l'on va utiliser la fonction copy pour telecharger l'image dans le dossier voulut, grace à la variable $dossier_photo pour lui indiquer le chemin
            copy($_FILES['photo']['tmp_name'], $dossier_photo);

        // preparation à l'enregistrement  des variables en BDD

        //gestion de l'enregistrement avec soit la création d'une nouvelle salle ou la modification des elements d'une salle existante

        //
        if(!empty($_POST['id_salle'])){
            // si $id_salle n'est pas vide c'est un UPDATE
            $enregistrement_salle = $pdo->prepare("UPDATE salle 
                                                       SET titre = :titre,
                                                        description = :description,
                                                        photo = :photo,
                                                        pays = :pays,
                                                        ville = :ville, 
                                                        adresse = :adresse,
                                                        cp = :cp,
                                                        capacite = :capacite,
                                                        categorie = :categorie
                                                        WHERE id_salle = :id_salle");
            $enregistrement_salle->bindParam(":id_salle", $_POST['id_salle']);
        } else {
            $enregistrement_salle = $pdo->prepare("INSERT INTO salle (titre, description, photo, pays, ville, adresse, cp, capacite, categorie)
                                                         VALUES (:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");
        }


        $enregistrement_salle->bindParam(":titre", $titre, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":description", $description, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":photo", $photo_bdd, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":pays", $pays, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":ville", $ville, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":adresse", $adresse, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":cp", $cp, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":capacite", $capacite, PDO::PARAM_STR);
        $enregistrement_salle->bindParam(":categorie", $categorie, PDO::PARAM_STR);
        $enregistrement_salle->execute();
    }

/**************************************************************************
 **************************************************************************
 ********* FIN GESTION DES SALLES ENREGISTREMENT ET MODIFICATION **********
 **************************************************************************
 *************************************************************************/

include '../inc/header.inc.php';
include '../inc/nav.inc.php';

/**************************************************************************
 **************************************************************************
 ************** AFFICHAGE DES SALLES POUR LA GESTION ADMIN ****************
 **************************************************************************
 *************************************************************************/
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
<?php
$liste_salle = $pdo->query("SELECT * FROM salle");

echo '<div class="col-10 table-responsive">';

echo '<table class="table table-bordered">';
echo '<tr>';
echo '<th>id_salle</th>';
echo '<th>titre</th>';
echo '<th>description</th>';
echo '<th>photo</th>';
echo '<th>pays</th>';
echo '<th>ville</th>';
echo '<th>adresse</th>';
echo '<th>cp</th>';
echo '<th>capacite</th>';
echo '<th>categorie</th>';
echo '<th>actions</th>';
echo '</tr>';

while($salle = $liste_salle->fetch(PDO::FETCH_ASSOC)) {

    echo'<tr>';

    echo '<td>'. $salle['id_salle'] . '</td>';
    echo '<td>'. $salle['titre'] . '</td>';
    echo '<td>'. substr($salle['description'],0 ,14) . '...</td>';
    echo '<td><img src="'. URL . 'img/' . $salle['photo'] . '" class="img-thumbnail" width="140"></td>';
    echo '<td>'. $salle['pays'] . '</td>';
    echo '<td>'. $salle['ville'] . '</td>';
    echo '<td>'. $salle['adresse'] . '</td>';
    echo '<td>'. $salle['cp'] . '</td>';
    echo '<td>'. $salle['capacite'] . '</td>';
    echo '<td>'. $salle['categorie'] . '</td>';

    echo '<td>
              <a href="?action=modifier&id_salle='. $salle['id_salle'] . '" class="btn btn-warning"><i class="fas fa-pen-nib"></i></a>
              <a href="?action=supprimer&id_salle='. $salle['id_salle'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fas fa-minus-square"></i></a>
          </td>';

    echo'</tr>';
    echo '</div>';
    if(isset($_GET['action']) && $_GET['action'] == 'modifier' && isset($_GET['id_salle']) && $_GET['id_salle'] == $salle['id_salle']){
        ?>
        <tr>
            <td colspan="11">
                <form action="?action=enregistrer" method="POST" enctype="multipart/form-data">
                    <h1 class="text-center pt-5">Modifier votre salle</h1>
                    <input type="hidden" name="id_salle" value="<?php echo $id_salle; ?>">
                    <p class="text-center"><a href="?action=annuler" class="btn btn-danger">Annuler votre modification</a></p>
                    <div class="row p-3">
                        <div class="col-6 p-3">
                            <div class="form-group">
                                <label for="titre">Titre</label>
                                <input type="text" id="titre" name="titre" value="<?php echo $titre; ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control"><?php echo $description; ?></textarea>
                            </div>
                            <?php
                            if (!empty($photo_actuelle)){
                                echo '<img src="'. URL . 'img/' . $photo_actuelle . '" class="img-thumbnail" width="140">';
                            }
                            ?>
                            <div class="form-group">
                                <label for="photo">Photo</label>
                                <input type="file" name="photo" id="photo" value="<?php echo $photo_actuelle; ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="pays">Pays</label>
                                <input type="text" id="pays" name="pays" value="<?php echo $pays; ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="ville">Ville</label>
                                <input type="text" id="ville" name="ville" value="<?php echo $ville; ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-6 p-3">
                            <div class="form-group">
                                <label for="adresse">Adresse</label>
                                <input type="text" id="adresse" name="adresse" value="<?php echo $adresse; ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="cp">Code postal</label>
                                <input type="number" id="cp" name="cp" value="<?php echo $cp; ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="capacite">Capacité</label>
                                <input type="number" id="capacite" name="capacite" value="<?php echo $capacite; ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="categorie">Catégorie</label>
                                <select name="categorie" id="categorie" class="form-control">
                                    <option <?php if($categorie == 'reunion') { echo 'selected';} ?> >réunion</option>
                                    <option <?php if($categorie == 'bureau') { echo 'selected';} ?> >bureau</option>
                                    <option <?php if($categorie == 'formation') { echo 'selected';} ?> >formation</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="enregistrement" id="enregistrement"
                                   class="form-control btn btn-primary"> Enregistrer les modifications</button>
                            </div>
                        </div>
                    </div>
                </form>
            </td>
        </tr>

        <?php
        vd($_GET);
    }
}
echo '</table>';
echo '</div>';


/**************************************************************************
 **************************************************************************
 *********** FIN AFFICHAGE DES SALLES POUR LA GESTION ADMIN ***************
 **************************************************************************
 *************************************************************************/
?>
</div>
<?php
// vd($_POST);
// vd($photo_bdd);

// cacher le formulaire d'ajout d'une salle quand le formulaire de modification des salles aparait
if(empty($_GET['action']) || $_GET['action'] != 'modifier' && $_GET['action'] != 'supprimer') {
?>
    <section>
        <!-- formulaire d'ajout d'une salle -->
        <form action="" method="POST" enctype="multipart/form-data">
            <!-- <input type="hidden" name="id_salle" value="<?php echo $id_salle; ?>"> -->
            <h1 class="text-center pt-5">Gestion des salles</h1>
            <div class="row p-5">
                <div class="col-6 p-5">
                    <div class="form-group">
                        <label for="titre">Titre</label>
                        <input type="text" id="titre" name="titre" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="photo">Photo</label>
                        <input type="file" name="photo" id="photo" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="pays">Pays</label>
                        <input type="text" id="pays" name="pays" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="ville">Ville</label>
                        <input type="text" id="ville" name="ville" class="form-control">
                    </div>
                </div>
                <div class="col-6 p-5">
                    <div class="form-group">
                        <label for="adresse">Adresse</label>
                        <input type="text" id="adresse" name="adresse" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="cp">Code postal</label>
                        <input type="number" id="cp" name="cp" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="capacite">Capacité</label>
                        <input type="number" id="capacite" name="capacite" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <select name="categorie" id="categorie" class="form-control">
                            <option>réunion</option>
                            <option>bureau</option>
                            <option>formation</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="enregistrement" id="enregistrement"
                                class="form-control btn btn-primary"> Enregistrer la salle dans la base de donnée.
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
<?php
}
include '../inc/footer.inc.php';
