<?php
require_once '../components/header.php';
if (firstLogin($conn, $_SESSION['useruid'])) {
  require "../pages/firstLogin.php";
  exit;
}
if (isset($_GET["password"])) {
  if ($_GET["password"] == "changed") {
?>
    <script>
      swal("A jelszavát sikeresen megváltoztatta!", "", "success").then(function() {
        window.location.href = "../pages/home.php"
      });
    </script>
  <?php
  } elseif ($_GET["password"] == "notChanged") {
  ?>
    <script>
      swal("Jelszava maradt a régi!", "", "success").then(function() {
        window.location.href = "../pages/home.php"
      });
    </script>
  <?php
  }
}
if ($_SESSION["playerid"] != null) {
  $squads = IsThereSquadInvolved($conn, $_SESSION["playerid"]);
  if($squads != false) {
  ?>
    <script>
      swal("Van <?php echo $squads ?> darab mérkőzés, melyen számítanak Önre", {
        buttons: {
          show: "Visszajelzek!",
          later: "Később",
        }
      }).then((value) => {
        switch (value) {
          case "show":
            window.location.href = "../pages/gameSquads.php";
            break;
          case "later":
            swal("A 'játékos' fül 'keretek' menüpontján belül tud visszajelzést adni később", "", "info");
        }
      });
    </script>
<?php
  }
}
?>
<div id="greeting" class="mx-5 text-center rounded p-3">
  <h1>Üdvözöljük, <b><?php echo $_SESSION['username'] ?>!</b> </h1>
</div>
<?php

if ($_SESSION["playerid"]) {
  $playedMatches = getNumberOfPlayedMatches($conn, $_SESSION["playerid"]);
  $allMatches = getNumberOfMatches($conn, $_SESSION["teamid"]);
  $trainingsParticipated = getNumberOfTrainingParticipated($conn, $_SESSION["playerid"]);
  $allTrainings = getNumberOfTrainings($conn, $_SESSION["teamid"]);
  $startedMatches = getNumberOfStartedMatches($conn, $_SESSION["playerid"]);
?>
  <script src="../javascript/playerCharts.js"></script>
  <input id="numberOfPlayedMatches" type="number" value="<?php echo $playedMatches ?>" hidden>
  <input id="numberOfMatches" type="number" value="<?php echo $allMatches ?>" hidden>
  <input id="numberOfTrainingsParticipated" type="number" value="<?php echo $trainingsParticipated ?>" hidden>
  <input id="numberOfTrainings" type="number" value="<?php echo $allTrainings ?>" hidden>
  <input id="numberOfMatchesStarted" type="number" value="<?php echo $startedMatches ?>" hidden>
  <div class="row my-5 py-3">
    <div class="col col-sm-6 col-md-4 col-lg-3 mx-auto">
      <div class="bg-light text-primary rounded p-3 mb-3">
        <h6 class="text-center mb-2">Részvétel a meccseken</h6>
        <div class="chartConatiner">
          <canvas id="playedMatchesOfAllChart"></canvas>
          <div class="chartLabel mt-5 text-center">
            <h2 class="mb-0"><?php echo $allMatches ?></h2>
            <span>össz</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col col-sm-6 col-md-4 col-lg-3 mx-auto">
      <div class="bg-light text-primary rounded p-3 mb-3">
        <h6 class="text-center mb-2">Kezdőcsapat tagja</h6>
        <div class="chartConatiner">
          <canvas id="stratedMatchesChart"></canvas>
          <div class="chartLabel mt-5 text-center">
            <h2 class="mb-0"><?php echo $playedMatches ?></h2>
            <span>össz</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col col-sm-6 col-md-4 col-lg-3 mx-auto">
      <div class="bg-light text-primary rounded p-3 mb-3">
        <h6 class="text-center mb-2">Részvétel az edzéseken</h6>
        <div class="chartConatiner">
          <canvas id="trainingsParticipatedOfAllChart"></canvas>
          <div class="chartLabel mt-5 text-center">
            <h2 class="mb-0"><?php echo $allTrainings ?></h2>
            <span>össz</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
  $nextMatch = getNextGame($conn, $_SESSION["teamid"], $_SESSION["playerid"]);
  $nextTraining = getNextTraining($conn, $_SESSION["teamid"]);
  ?>


  <div class="row">
    <div class="col-sm-6 col-lg-4 p-3 mx-auto">
      <div class="card ">
        <img src="../images/adult2.jpg" class="card-img-top" alt="...">
        <div class="card-body text-dark">
          <h5 class="card-title">Következő mérkőzés</h5>
          <p class="card-text">
            <?php
            if ($nextMatch == false) {
            ?>
              <span>Nincs feljegyezve mérkőzés</span>
            <?php
            } else {
            ?>
              <span>Ideje: <?php echo $nextMatch["squad_matchDate"] ?></span><br>
              <span>Helye: <?php echo $nextMatch["squad_matchPlace"] ?></span>
            <?php
            }
            ?>
          </p>
          <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-4 p-3 mx-auto">
      <div class="card">
        <img src="../images/u19.jpg" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Következő edzés</h5>
          <p class="card-text">
            <?php
            if ($nextTraining == false) {
            ?>
              <span>Nincs feljegyezve edzés</span>
            <?php
            } else {
            ?>
              <span>Ideje: <?php echo $nextTraining["training_startDate"] ?></span><br>
              <span>Helye: <?php echo $nextTraining["training_place"] ?></span>
            <?php
            }
            ?>
          </p>
          <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
      </div>
    </div>
  </div>
<?php } elseif ($role == 2) {
  $numOfPlayersEachTraining = getNumberOfPlayerOnEachTraining($conn, $_SESSION["coachid"], 10);
  $numOfPlayersEachMatch = getNumberOfPlayersOnEachMatch($conn, $_SESSION["coachid"], 10);
  $numOfAllPlayers = getNumberOfAllPlayers($conn, $_SESSION["coachid"]);
?>
  <script src="../javascript/coachCharts.js"></script>
  <script>
    var numOfPlayersEachTraining = <?php echo json_encode($numOfPlayersEachTraining) ?>
  </script>
  <?php
  ?>
  <h1 class="text-light text-center my-5 display-4">Egy kis statisztika a csapatáról...</h1>
  <hr class="text-light">
  <div class="row">
    <div class="col-6">
      <h1 class="text-light text-center my-3 display-4">Edzésen résztvevők létszáma</h1>
      <div class="chartConatiner bg-light mt-3">
        <canvas id="numberOfPlayersOnTrainings"></canvas>
      </div>
    </div>
  <script>
    var numOfPlayersEachMatch = <?php echo json_encode($numOfPlayersEachMatch) ?>
  </script>
  <?php
  ?>
    <div class="col-6">
      <h1 class="text-light text-center my-3 display-4">Mérkőzésre járók létszáma</h1>
      <div class="chartConatiner bg-light mt-3">
        <canvas id="numberOfPlayersOnMatches"></canvas>
      </div>
    </div>
  </div>

<?php
} ?>
<div class="slider-head">
  <div id="homePageCarousel" class="carousel slide w-50 mx-auto m-5" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#homePageCarousel" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#homePageCarousel" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#homePageCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="../images/u16.jpg" alt="" class="d-block" style="width:100%">
      </div>
      <div class="carousel-item">
        <img src="../images/u19.jpg" alt="" class="d-block" style="width:100%">
      </div>
      <div class="carousel-item">
        <img src="../images/adult2.jpg" alt="" class="d-block" style="width:100%">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#homePageCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#homePageCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</div>

<?php

require '../components/footer.php';
?>