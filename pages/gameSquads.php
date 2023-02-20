<?php
  require_once '../components/header.php';
?>
<div class="container text-center py-3">
  <?php 
    if($role == 2){ //ha nem játékos
      ?>
        <h1 class="text-light">Hirdetett keretek</h1>
      <?php
      require_once '../components/addGameSquadFormButton.php';
      showAllGameSquads($conn);
      if(isset($_GET["success"])){
        if($_GET["success"] == "squadAdded"){
      ?>
          <script>
              swal("A keret sikeresen fel lett véve!","", "success").then(function(){window.location.href="../pages/gameSquads.php"});
          </script>
    <?php
    }
      elseif(isset($_GET["error"])){
        if($_GET["error"] == "noSquadsInvolved"){
      ?>
          <script>
              swal("Nincs keret, melyben szerepel!","", "success").then(function(){window.location.href="../pages/gameSquads.php"});
          </script>
    <?php
    }
  ?>
<?php
  }
      }
}
if($_SESSION["playerid"] != null){
  ?>
  <h1 class="text-light">Következő meccsek, melyeken számítanak Önre</h1>
  <?php
  showNextGameSquadsInvolved($conn, $_SESSION["playerid"]);
  if(isset($_GET["SquadID"]) && isset($_GET["PlayerID"]) && isset($_GET["Available"])){
    setSquadAvailability($conn,$_GET["SquadID"],$_GET["PlayerID"],$_GET["Available"]);
  }
  if(isset($_GET["availability"])){
    if($_GET["availability"] == "changed"){
    ?>
    <script>
              swal("Visszajelzését rögzítettük!","", "success").then(function(){window.location.href="../pages/gameSquads.php"});
    </script>
  <?php
    }
  }
}
?>
</div>
<?php
  require_once '../components/footer.php';
?>