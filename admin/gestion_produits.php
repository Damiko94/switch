<?php
include '../inc/init.inc.php';
include '../inc/fonction.inc.php';

if (!user_is_admin()) {
    header('location:../connexion.php');
    exit();
}
// recuperation de la date d'aujourd'hui pour la gestion de l'enregistrement produit
$today = date('Y-m-d');

// création de variable vide que l'on remplira avec les données du formulaire

$id_produit = '';
$date_arrivee = '';
$date_depart = '';
$salle = '';
$prix = '';
$etat = '';
$control_date = '';
$nb_produit = '';
$mod_prod = '';

if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_produit'])) {
    $suppression = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $suppression->bindParam(":id_produit", $_GET['id_produit'], PDO::PARAM_STR);
    $suppression->execute();

    $_GET['action'] = 'affichage'; // pour provoquer l'affichege des articles
}
/**********************************************************
 **********************************************************
 *  \ FIN DE SUPPRESSION D'UNE SALLE **********************
 **********************************************************
 *********************************************************/


/***************************************************************************
 ****************************************************************************
 ********* DEBUT GESTION DES PRODUITS ENREGISTREMENT ET MODIFICATION **********
 ****************************************************************************
 **************************************************************************/

// verification de l'existence de variables POST, si un formulaire a été validé

if (
    isset($_POST['date_arrivee']) &&
    isset($_POST['date_depart']) &&
    isset($_POST['salle']) &&
    isset($_POST['prix'])) {

    //si les variables POST existent, on les enregistrent dans d'autre variables en passant par un trim

    $date_arrivee = trim($_POST['date_arrivee']);
    $date_depart = trim($_POST['date_depart']);
    $salle = trim($_POST['salle']);
    $prix = trim($_POST['prix']);
    $etat = $_POST['etat'];
    if(isset($_POST['mod_prod'])) {$mod_prod = $_POST['mod_prod'];}

    /******************************************************************************************
     ************************ controle des dates avant enregistrement produit ******************
     ******************************************************************************************/

    $date_produit = $pdo->prepare("SELECT date_arrivee, date_depart FROM produit WHERE id_salle = :salle AND id_produit != :produit");
    $date_produit->bindParam(":salle", $salle, PDO::PARAM_STR);
    $date_produit->bindParam(":produit", $mod_prod, PDO::PARAM_STR);
    $date_produit->execute();
    $nb_produit = $date_produit->rowcount();
    $control_date = $date_produit->fetchAll(PDO::FETCH_ASSOC);

    // condition de date, tester si les dates d'arrivée et de départ sont inférieurs à la date d'arrivée et la date de départ enregistrer pour un produit
    // ou si elles sont supérieur aux dates arrivee et depart.
    // si oui tester avec les dates du produit suivant, sachant que l'on ne recupere que les produits ayant la meme id_salle pour les tester dans la variable $control_date

    for ($i = 0; $i < $nb_produit; $i++) {
        if (($date_arrivee < $control_date[$i]['date_arrivee'] && $date_depart < $control_date[$i]['date_arrivee'])
            || ($date_arrivee > $control_date[$i]['date_depart'] && $date_depart > $control_date[$i]['date_depart'])) {
        } else {
            $msg = '<div class="alert alert-danger mt-3">Dates indisponibles pour créer un nouveau produit avec cette salle</div>';
        }
    }
    /*******************************************************************************************
     ************************** fin controle des date ******************************************
     *******************************************************************************************/

    // FONCTIONNE POUR EMPECHER LA CREATION DE NOUVEAU PRODUIT SUR DES DATES EXISTANTES , MAIS BLOQUE LA MODIFICATION DES DATES

    // condition pour que la date d'entrèe du formulaire soit supérieur à la date d'aujourd'hui
    if ($date_arrivee < $today) {
        $msg = '<div class="alert alert-danger mt-3">Attention votre date d\'arrivée est antérieur à la date actuelle !</div>';
    } else {
        if (empty($msg)) {
            // preparation à l'enregistrement des variables en BDD
            // conditions pour faire une modification du produit dans la base de donnée
            if (!empty($_POST['mod_prod'])) {
                // si $id_salle n'est pas vide c'est un UPDATE
                $enregistrement_produit = $pdo->prepare("UPDATE produit 
                                                       SET date_arrivee = :date_arrivee,
                                                           date_depart = :date_depart,
                                                           id_salle = :id_salle,
                                                           prix = :prix,
                                                           etat = :etat                                                  
                                                        WHERE id_produit = :id_produit");
                $enregistrement_produit->bindParam(":id_produit", $_POST['mod_prod']);
            } else {
                $enregistrement_produit = $pdo->prepare("INSERT INTO produit (id_salle, date_arrivee, date_depart, prix, etat)
                                                         VALUES (:id_salle, :date_arrivee, :date_depart, :prix, :etat)");
            }
            $enregistrement_produit->bindParam(":id_salle", $salle, PDO::PARAM_STR);
            $enregistrement_produit->bindParam(":date_arrivee", $date_arrivee, PDO::PARAM_STR);
            $enregistrement_produit->bindParam(":date_depart", $date_depart, PDO::PARAM_STR);
            $enregistrement_produit->bindParam(":prix", $prix, PDO::PARAM_STR);
            $enregistrement_produit->bindParam(":etat", $etat, PDO::PARAM_STR);
            $enregistrement_produit->execute();
        }
    }
}

/**************************************************************************
 **************************************************************************
 ********* FIN GESTION DES PRODUITS ENREGISTREMENT ET MODIFICATION **********
 **************************************************************************
 *************************************************************************/

include '../inc/header.inc.php';
include '../inc/nav.inc.php';

/**************************************************************************
 **************************************************************************
 ************** AFFICHAGE DES PRODUITS POUR LA GESTION ADMIN ****************
 **************************************************************************
 *************************************************************************/

// récupération d'infos dans la table salle, pour le formulaire de gestion des produits

$infos_salle = $pdo->query("SELECT id_salle, titre, ville, adresse, cp, capacite FROM salle");
// enregistrement des infos des salles dans des variables;

// récupération des infos dans la table produit
$liste_produit = $pdo->query("SELECT * FROM produit");

echo '<div class="table-responsive">';
echo '<table class="table table-bordered">';
echo '<tr>';
echo '<th>Id produit</th>';
echo '<th>Date d\'arrivee</th>';
echo '<th>Date de depart</th>';
echo '<th>Id salle</th>';
echo '<th>Prix</th>';
echo '<th>Etat</th>';
echo '<th>Actions</th>';
echo '</tr>';

while ($produit = $liste_produit->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr>';

    echo '<td>' . $produit['id_produit'] . '</td>';
    echo '<td>' . $produit['date_arrivee'] . '</td>';
    echo '<td>' . $produit['date_depart'] . '</td>';
    echo '<td>' . $produit['id_salle'] . '</td>';
    echo '<td>' . $produit['prix'] . '</td>';
    echo '<td>' . $produit['etat'] . '</td>';
    echo '<td>
              <a href="?action=modifier&id_produit=' . $produit['id_produit'] . '&id_salle=' . $produit['id_salle'] . '" class="btn btn-warning"><i class="fas fa-pen-nib"></i></a>
              <a href="?action=supprimer&id_produit=' . $produit['id_produit'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fas fa-minus-square"></i></a>
          </td>';

    echo '</tr>';

    if (isset($_GET['action']) && $_GET['action'] == 'modifier' && isset($_GET['id_produit']) && $_GET['id_produit'] == $produit['id_produit']) {
        ?>
        <tr>
            <td colspan="7">
                <form action="?enregistrer" method="POST" enctype="multipart/form-data">
                    <h1 class="text-center pt-5">Modifier votre produit</h1>
                    <input type="hidden" name="mod_prod" value="<?php echo $_GET['id_produit']; ?>">
                    <p class="text-center"><a href="?action=annuler" class="btn btn-danger">Annuler votre
                            modification</a></p>
                    <div class="row p-3">
                        <div class="col-6 p-3">
                            <div class="form-group">
                                <label for="date_arrivee">Date d'arrivée</label>
                                <input type="date" id="date_arrivee" name="date_arrivee"
                                       value="<?php echo $produit['date_arrivee'] ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="date">Date de départ</label>
                                <input type="date" id="date_depart" name="date_depart"
                                       value="<?php echo $produit['date_depart'] ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="salle">Salle</label>
                                <select name="salle" id="salle" class="form-control">
                                    <?php
                                    while ($salle = $infos_salle->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $salle['id_salle'] . '"';
                                        if ($salle['id_salle'] == $produit['id_salle']) {
                                            echo 'selected';
                                        }
                                        echo '>' . $salle['id_salle'] . ' - ' . $salle['titre'] . ' - ' . $salle['adresse'] . ', ' . $salle['cp'] . ', ' . $salle['ville'] . ' - ' . $salle['capacite'] . ' pers</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6 p-3">

                            <div class="form-group">
                                <label for="prix">Tarif</label>
                                <input type="number" id="prix" name="prix" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="salle">Salle</label>
                                <select name="etat" id="etat" class="form-control">
                                    <option value="libre">Libre</option>
                                    <option value="reservation">Réservation</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="enregistrement" id="enregistrement"
                                        class="btn btn-primary"> Enregistrer
                                </button>
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
 *********** FIN AFFICHAGE DES PRODUITS POUR LA GESTION ADMIN ***************
 **************************************************************************
 *************************************************************************/

// controle de la recuperation des dates produits quand on enregistre une nouvelle salle
vd($control_date);
vd($nb_produit);
// vd($mod_prod);

if (empty($_GET['action']) || $_GET['action'] != 'modifier' && $_GET['action'] != 'supprimer') {
    ?>
    <section>
        <?php echo $msg; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <h1 class="text-center pt-5">Gestion des produits</h1>
            <div class="row p-5">
                <div class="col-6 p-5">
                    <div class="form-group">
                        <label for="date_arrivee">Date d'arrivée</label>
                        <input type="date" id="date_arrivee" name="date_arrivee" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="date">Date de départ</label>
                        <input type="date" id="date_depart" name="date_depart" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="salle">Salle</label>
                        <select name="salle" id="salle" class="form-control">
                            <?php
                            while ($salle = $infos_salle->fetch(PDO::FETCH_ASSOC)) {
                                echo '<option value="' . $salle['id_salle'] . '">' . $salle['id_salle'] . ' - ' . $salle['titre'] . ' - ' . $salle['adresse'] . ', ' . $salle['cp'] . ', ' . $salle['ville'] . ' - ' . $salle['capacite'] . ' pers</option>';
                                // echo '<input type="hidden" name="id_salle" value="$salle[\'id_salle\']">';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-6 p-5">

                    <div class="form-group">
                        <label for="prix">Tarif</label>
                        <input type="number" id="prix" name="prix" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="salle">Salle</label>
                        <select name="etat" id="etat" class="form-control">
                            <option value="libre">Libre</option>
                            <option value="reservation">Réservation</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="enregistrement" id="enregistrement"
                                class="btn btn-primary"> Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <?php
}
include '../inc/footer.inc.php';
