<?php
require_once '../components/header.php';
if(isset($_GET["addTravelOption"])){
    if($_GET["addTravelOption"] == "ThereIsAnOptionThatDay"){
    ?>
    <script>
              swal("Az adott napon már van felvett utazási lehetőség!","", "info").then(function(){window.location.href="../pages/travels.php"});
    </script>
  <?php
    }
    if($_GET["addTravelOption"] == "success"){
    ?>
    <script>
              swal("Utazási lehetőség rögzítve!","", "success").then(function(){window.location.href="../pages/travels.php"});
    </script>
  <?php
    }
    if($_GET["addTravelOption"] == "ThereIsNoMatch"){
    ?>
    <script>
              swal("Nincs az adott napon idegenbeli mérkőzése a csapatnak!","", "error").then(function(){window.location.href="../pages/travels.php"});
    </script>
  <?php
    }
  }
if(isset($_GET["deleteTravel"])){
    if($_GET["deleteTravel"] == "success"){
    ?>
    <script>
              swal("Utazási lehetőség sikeresen törölve!","", "success").then(function(){window.location.href="../pages/travels.php"});
    </script>
  <?php
    }
  }
if(isset($_GET["addTravelReply"])){
    if($_GET["addTravelReply"] == "success"){
    ?>
    <script>
              swal("A jelzett igényt rögzítettük!","", "success").then(function(){window.location.href="../pages/travels.php"});
    </script>
  <?php
    }
  }
?>  
<h1 class="text-center text-light">Utazás, idegenbeli mérkőzések esetén</h1>
<?php
    $today = date("Y-m-d");
    $tomorrow = strtotime("$today +1 day");
    if($role == 2){
?>
        <div class="mt-3 d-flex justify-content-end"><a data-bs-toggle="modal" data-bs-target="#addTravelOption" class="btn btn-outline-light bi-plus-square mb-3"></a></div>
        <div class="modal fade" id="addTravelOption" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content text-dark">
                    <div class="modal-header">
                        <h5 class="modal-title">Utazási opció hozáadása</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../includes/addTravelOption.inc.php?coachID=<?php echo $_SESSION["coachid"] ?>" method="post">
                            <label for="dateOfTravel">Indulás</label>
                            <input type="datetime-local" name="dateOfTravel" class="form-control" min="<?php echo $tomorrow ?>" max="2100-01-01" required>
                            <label for="placeOfDepartment">Gyülekező helye</label>
                            <input type="text" name="placeOfDepartment" class="form-control" min="<?php echo $tomorrow ?>" max="2100-01-01" required>
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
    showTravelOptions($conn, $_SESSION["coachid"]);
    }
    elseif($role == 3){
        showTravelOptionsIAmInvolved($conn, $_SESSION["teamid"], $_SESSION["playerid"]);
    }
?>

<?php
require_once '../components/footer.php';
?>