<?php
require_once '../components/guestHeader.php';
?>
<div class="container mainContainer p-3 my-3 rounded">
    <h1 class="text-center text-light border border-light rounded mb-3 p-2">Csapataink</h1>
    <div class="">
        <?php showEachTeam($conn); ?>
    </div>
<?php
require_once '../components/guestFooter.php';
?>