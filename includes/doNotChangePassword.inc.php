<?php
    session_start();
    $id = $_SESSION["userid"];
    $pwd = $_SESSION["userpwd"];
    require_once '../includes/dbhandler.inc.php';
    $result = mysqli_query($conn,"SELECT * from users WHERE usersID='" . $id . "';");
    $row=mysqli_fetch_array($result);
    $hashedPassword = password_hash($pwd, PASSWORD_DEFAULT);
    mysqli_query($conn,"UPDATE users SET usersPwd='" . $hashedPassword . "', FirstLogin=0 WHERE usersID='" . $id . "';");
    header("location: ../pages/home.php?password=notChanged");