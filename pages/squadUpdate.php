<?php
  require '../components/header.php';
  if(isset($_GET["addAbsence"])){
    if($_GET["addAbsence"] == "success"){
  ?>
      <script>
          swal("A hiányzást rögzítettük!","", "success").then(function(){window.location.href="../pages/squadUpdate.php"});
      </script>
  <?php
  }
}
  if(isset($_GET["deleteAbsence"])){
    if($_GET["deleteAbsence"] == "success"){
  ?>
      <script>
          swal("A hiányzást töröltük!","", "success").then(function(){window.location.href="../pages/squadUpdate.php"});
      </script>
  <?php
  }
}
  if(isset($_GET["modifyAbsence"])){
    if($_GET["modifyAbsence"] == "success"){
  ?>
      <script>
          swal("A hiányzást frissítettük!","", "success").then(function(){window.location.href="../pages/squadUpdate.php"});
      </script>
  <?php
  }
}
?>


<div class="container text-light text-center">
  <?php 
   if($role == 3){
    $today = date("Y-m-d");
    $yesterday = strtotime("$today -1 day");
    $tomorrow = strtotime("$today +1 day");
    ?>
    <h1>Hiányzásaid</h1>
    <div class="mt-3 d-flex justify-content-end">
        <a data-bs-toggle="modal" data-bs-target="#addAbsence<?php echo $_SESSION["playerid"];?>" class="btn btn-outline-light bi-plus-square mb-3"></a>
    </div>
    <div class="modal fade" id="addAbsence<?php echo $_SESSION["playerid"]; ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Hiányzás rögzítése</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../includes/addAbsence.inc.php?playerID=<?php echo $_SESSION["playerid"] ?>&teamID=<?php echo $_SESSION["teamid"] ?>" method="post" id="absenceForm">
                        <label for="inputStartDate">Hiányzás kezdete</label>
                        <input type="date" name="inputStartDate" class="form-control" min="<?php echo $today ?>" max="2100-01-01" required>
                        <label for="inputEndDate">Hiányzás vége</label>
                        <input type="date" name="inputEndDate" class="form-control" min="<?php echo $today ?>" max="2100-01-01" required>
                        <label for="selectReason"></label>
                        <select name="selectReason" class="form-select">
                            <option value="sérülés">sérülés</option>
                            <option value="munka">munka</option>
                            <option value="családi okok">családi okok</option>
                            <option value="egyéb">egyéb</option>
                        </select>
                        <label for="inputReason">Bővebb indoklás</label>
                        <input type="text" name="inputReason" class="form-control">
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
    showAbsences($conn, $_SESSION["playerid"]);
   }
   elseif($role == 2){
   ?>
    <h1>Feljegyzett hiányzások a csapatában</h1>
    <?php
    showAllAbsences($conn, $_SESSION["teamid"]);
   }
   ?>
</div>


<?php
  require '../components/footer.php';
?>