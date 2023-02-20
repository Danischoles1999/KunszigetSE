<?php
require_once '../components/header.php';
if (isset($_GET["id"])){
    $id = $_GET["id"];
    showModifyPlayerForm($conn, $id);
}
?>


<?php
require '../components/footer.php';
?>