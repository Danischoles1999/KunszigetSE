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
  <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <!-- <script src="../javascript/livePlayerSearch.js"></script>
  <script src="../javascript/liveGameSearch.js"></script> -->
  <script src="../javascript/addMatchPlayerInputs.js"></script>
  <script src="../javascript/addSquadPlayerInput.js"></script>
  <script src="../javascript/main.js"></script>
</head>

<body>
  <?php
  require_once '../includes/dbhandler.inc.php';
  require_once '../includes/functions.inc.php';
  session_start();
  $role = $_SESSION["userrole"];
  $id = $_SESSION["userid"];
  if (!isset($_SESSION['loggedin'])) { // mindig le kell ellenőrizni hogy be van e jelentkezve a felhasználó , különben ne tudja elérni az oldalt
    header('location: ../pages/login.php');
    exit;
  } else {
  ?>
    <nav class="navbar navbar-expand-md navbar-dark nav-background mb-5">
      <div class="container-fluid">
        <a class="navbar-brand" href="../pages/home.php"><img src="../images/logo.png" width="50" height="40" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainnavbar">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainnavbar">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link text-light btn" href="../pages/profile.php" id="userItem"><span class="mx-auto"><img src="../images/user.png" id="userLogo" alt=""> Profil</span></a>
            </li>
            <?php
            if ($role == 2 || $role == 3) {
            ?>
              <li class="nav-item">
                <a class="nav-link text-light btn" href="../pages/players.php">Játékosok</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-light btn" href="../pages/trainings.php">Edzések</a>
              </li>
              <li class="nav-item dropdown my-auto">
                <a class="nav-link dropdown-toggle text-light btn" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Mérkőzések</a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item nav-link text-light btn" href="../pages/games.php">Eredmények</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item nav-link text-light btn" href="../pages/gameSquads.php">Keretek</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link text-light btn" href="../pages/squadUpdate.php">Hiányzások</a>
              </li>
              </li>
              <li class="nav-item">
                <a class="nav-link text-light btn" href="../pages/travels.php">Utazás</a>
              </li>
            <?php
            if($role == 2){
            ?>
              <li class="nav-item">
                <a class="nav-link text-light btn" href="../pages/contracts.php">Igazolások</a>
              </li>
            <?php
            }
            }
            elseif($role == 1){
            ?>
              <li class="nav-item">
                <a class="nav-link text-light btn" href="../pages/users.php">Felhasználói fiókok</a>
              </li>
            <?php
            }
            ?>
            <ul class="navbar-nav navbar-right">
              <li><a class="nav-link text-light btn" href="../includes/logout.inc.php?logout=true" id="logoutItem"><span class="mx-auto"><img id="logoutLogo" src="../images/logout.png" alt=""> Kijelentkezés</span></a></li>
            </ul>
        </div>
      </div>
    </nav>
    <div class="container mainContainer p-3 my-3 rounded">
    <?php
  } ?>