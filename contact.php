<?php
include 'inc/init.inc.php';
include  'inc/fonction.inc.php';


include  'inc/header.inc.php';
include  'inc/nav.inc.php';

?>

    <section class="container">
        <h3 class="text-center mt-5 pt-5">Contactez-nous</h3>
        <form class="w-75 mx-auto py-5 my-5">
            <div class="form-group">
                <label name="topic">Sujet de votre message</label>
                <input type="text" name="topic" class="form-control">
            </div>
            <div class="form-group">
                <label name="mail">Votre email</label>
                <input type="email" name="mail" class="form-control">
            </div>
            <div class="form-group">
                <label name="message">Votre message</label>
                <textarea class="form-control" name="message"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="form-control btn btn-primary w-25">Envoyer</button>
            </div>
        </form>
    </section>
<?php

include 'inc/footer.inc.php';
