<?php
    require_once 'functions.inc.php';
    require_once 'dbhandler.inc.php';
    if (isset($_GET["id"])){
        $id = $_GET["id"];
        deleteAbsence($conn, $id);
    }