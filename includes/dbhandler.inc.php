<?php

$serverName = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "kunszigetse";

$conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);

if(!$conn){
    die("Csatlakozás sikertelen: " . mysqli_connect_error());
}