<?php
require_once '../components/header.php';
?>
<h1 class="text-center text-light display-3">Üdvözöljük első bejelentkezése alkalmából, javasolt egy új jelszó beállítása!</h1>
<div class="row py-5">
    <div class="col text-center">
        <a class="btn btn-success btn-do-change" href="../components/changePasswordForm.php">Megváltoztatom!</a>
    </div>
    <div class="col text-center">
        <a class="btn btn-danger btn-do-not-change" href="../includes/doNotChangePassword.inc.php">Maradok a réginél!</a><span>(nem ajánlott)</span>
    </div>
</div>

<?php
require_once '../components/footer.php';
?>