<?php
require_once '../components/header.php';
if(isset($_GET["addTraining"])){
  if($_GET["addTraining"] == "success"){
  ?>
      <script>
          swal("Az edzés rögzítése megtörtént!","", "success").then(function(){window.location.href="../pages/trainings.php"});
      </script>
  <?php
}
}
if(isset($_GET["Date"])){
  if($_GET["Date"] == "isNotFree"){
    ?>
        <script>
            swal("A választott időpontban a kiválasztott hely már foglalt!","", "danger").then(function(){window.location.href="../pages/trainings.php"});
        </script>
    <?php
  }
}
if(isset($_GET["addReply"])){
  if($_GET["addReply"] == "success"){
    ?>
        <script>
            swal("Visszajelzését rögzítettük!","", "success").then(function(){window.location.href="../pages/trainings.php"});
        </script>
    <?php
  }
}
if(isset($_GET["updateReply"])){
  if($_GET["updateReply"] == "success"){
    ?>
        <script>
            swal("Visszajelzését módosítottuk!","", "success").then(function(){window.location.href="../pages/trainings.php"});
        </script>
    <?php
  }
}
if(isset($_GET["deleteTraining"])){
  if($_GET["deleteTraining"] == "success"){
    ?>
        <script>
            swal("Az edzést töröltük!","", "success").then(function(){window.location.href="../pages/trainings.php"});
        </script>
    <?php
  }
}
?>
<h1 class="text-center text-light">Edzések</h1>
<div class="container text-light">
  <?php
  if ($role == 3) { ?>
    <h2 class="text-center">Korosztályodhoz tartozó edzések időpontjai</h2>
    <?php
    $teamID = getTeamID($conn, $_SESSION["playerid"]);
    showMyTrainings($conn, $teamID, $role);
    $today = date("Y-m-d");
    $yesterday = strtotime("$today -1 day");
    $tomorrow = strtotime("$today +1 day");
} else {
?> 
  
  <div class="mt-3 d-flex justify-content-end">
        <a data-bs-toggle="modal" data-bs-target="#addTraining<?php echo $_SESSION["coachid"];?>" class="btn btn-outline-light bi-plus-square mb-3"></a>
    </div>
    <div class="modal fade" id="addTraining<?php echo $_SESSION["coachid"]; ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Edzés rögzítése</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../includes/addTraining.inc.php?coachID=<?php echo $_SESSION["coachid"] ?>" method="post" id="trainingForm">
                        <label for="inputStartDate">Edzés kezdete</label>
                        <input type="datetime-local" name="inputStartDate" class="form-control" min="<?php echo $today ?>" max="2100-01-01" required>
                        <label for="inputEndDate">Edzés vége</label>
                        <input type="datetime-local" name="inputEndDate" class="form-control" min="<?php echo $today ?>" max="2100-01-01" required>
                        <label for="selectPlace"></label>
                        <select id="selectTrainingPlace" name="selectPlace" class="form-select" onchange="showOtherInput()">
                            <option value="Nagypálya">Nagypálya</option>
                            <option value="Kispálya">Kispálya</option>
                            <option value="Csarnok">Csarnok</option>
                            <option value="Egyéb">Egyéb</option>
                        </select>
                        <label hidden id="hiddenLabel" for="inputOther">Eltérő helyszín</label>
                        <input hidden id="hiddenInput" type="text" name="inputOther" class="form-control">
                        <button type="submit" name="submit" class="btn btn-success my-3">Rögzít</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                </div>
            </div>
        </div>
    </div>
  <?php
    showNextTrainings($conn, $role);
  }
?>
</div>
<?php
require_once '../components/footer.php';
?>