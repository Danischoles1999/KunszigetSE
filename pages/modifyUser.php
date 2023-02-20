<?php
require_once '../components/header.php';
if (isset($_GET["id"])){
    $id = $_GET["id"];
    showModifyUserForm($conn, $id);
}
?>


<?php
require '../components/footer.php';
?>