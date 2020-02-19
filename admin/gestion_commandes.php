<?php
include '../inc/init.inc.php';
include  '../inc/fonction.inc.php';

$date_produit = $pdo->query("SELECT date_arrivee, date_depart FROM produit");
$liste_date = $date_produit->fetchAll(PDO::FETCH_ASSOC);
vd($liste_date);
if($liste_date[0]['date_arrivee'] > $liste_date[1]['date_arrivee']){
    $msg .= '<div class="alert alert-danger mt-3">Votre date est bien supérieur à l\'autre date !</div>';
}else{
    $msg .= '<div class="alert alert-danger mt-3">Attention votre date est inférieur à l\'autre date !</div>';
}
$today = date('Y-m-d');
/*$year = $today['year'];
$month = $today['mon'];
$day = $today['mday'];*/

include  '../inc/header.inc.php';
include  '../inc/nav.inc.php';

?>

    <section style="height: 600px;padding-bottom: 100px;">
<h1 style="margin-top:100px; ">teste gestion de dates</h1>
        <p><?php echo $msg; ?></p>
        <p><?php vd($today); ?></p>
    </section>
<?php

include '../inc/footer.inc.php';
