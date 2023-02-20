<?php
require_once '../components/header.php';
if(isset($_POST["submitPassword"])){
    $userData = getUserData($conn,$id);
    $user=$userData["usersID"];
    $newPassword = $_POST["newPassword"];
    $confirmNewPassword = $_POST["confirmPassword"];
    $i=0;
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    if($_POST["currentPassword"] != $userData["usersPwd"]){
        echo("<script>location.href = '../components/changePasswordForm.php?changePassword=wrongOldPassword';</script>");
        // header("location: ../components/changePasswordForm.php?changePassword=wrongOldPassword");
        // exit();
    }
    elseif($newPassword != $confirmNewPassword){
        echo("<script>location.href = '../components/changePasswordForm.php?changePassword=passwordsDontMatch';</script>");
        // header("location: ../components/changePasswordForm.php?changePassword=passwordsDontMatch");
        // exit();
    }
    elseif(($_POST["currentPassword"] == $userData["usersPwd"]) && ($newPassword == $confirmNewPassword)) {
        changePassword($conn, $hashedPassword, $i, $user);
    }
}