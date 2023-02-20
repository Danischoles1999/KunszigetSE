<!DOCTYPE html>
<html lang="hu">

<head>
    <title>Kunsziget SE</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../styles/mainStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="../javascript/main.js"></script>
</head>

<body>
    <?php
    require_once "../includes/dbhandler.inc.php";
    require_once "../includes/functions.inc.php";
    ?>
    <nav class="navbar navbar-expand-md navbar-dark nav-background">
        <div class="container-fluid">
            <a class="navbar-brand" href="../pages/guest.php"><img src="../images/logo.png" width="50" height="40" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainnavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainnavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item ">
                        <a class="nav-link text-light btn" href="../pages/teams.php">Csapataink</a>
                    </li>
                    <!-- <li class="nav-item ">
                        <a class="nav-link text-light btn" href="">Eredmények</a>
                    </li> -->
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light btn" href="" role="button" data-bs-toggle="dropdown">Az Egyesületről</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item text-light btn" href="">Kezdetek</a></li>
                            <li><a class="dropdown-item text-light btn" href="">Eredmények</a></li>
                            <li><a class="dropdown-item text-light btn" href="">Jelen</a></li>
                        </ul>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link text-light btn" href="">Kapcsolat</a>
                    </li> -->
                    <ul class="navbar-nav navbar-right">
                        <li><a class="nav-link text-light btn bi bi-box-arrow-in-right" id="logInItem" href="../pages/login.php"> Bejelentkezés</a></li>
                    </ul>
            </div>
        </div>
    </nav>