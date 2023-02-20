<?php
  require  '../components/header.php';
?>
<div class="container text-center py-3">
<h1 class="text-light">Játékosok</h1>
<!-- <?php
require '../components/livePlayerSearch.php';
?> -->
<?php
require '../components/addPlayerButton.php';
?>
</div>
<?php
if(isset($_GET["playerID"])){
  if($_GET["playerID"] == "alreadyExists"){
?>
    <script>
        swal("A megadott Azonosító már létezik az adatbázisban!","", "error").then(function(){window.location.href="../pages/players.php"});
    </script>
<?php
  }
}

if(isset($_GET["playerID"])){
  if($_GET["playerID"] == "wrongLength"){
?>
    <script>
        swal("Az azonosítónak 6 karakterből kell állnia!","", "error").then(function(){window.location.href="../pages/players.php"});
    </script>
<?php
  }
}

if(isset($_GET["playerID"])){
  if($_GET["playerID"] == "isNotNumeric"){
?>
    <script>
        swal("Az azonosító csak számokat tartalmazhat!","", "error").then(function(){window.location.href="../pages/players.php"});
    </script>
<?php
  }
}

if(isset($_GET["deletePlayer"])){
  if($_GET["deletePlayer"] == "success"){
?>
  <script>
      swal("Sikeres törlés!","", "success").then(function(){window.location.href="../pages/players.php"});
  </script>
<?php
  }else{
    ?>
        <script>
        swal("Sikertelen törlés!","", "error").then(function(){window.location.href="../pages/players.php"});
        </script>
      <?php
      }
}
if(isset($_GET["modifyPlayer"])){
  if($_GET["modifyPlayer"] == "success"){
?>
  <script>
      swal("Sikeres szerkesztés!","", "success").then(function(){window.location.href="../pages/players.php"});
  </script>
<?php
  }else{
    ?>
        <script>
        swal("Sikertelen szerkesztés!","", "error").then(function(){window.location.href="../pages/players.php"});
        </script>
      <?php
      }
}
if(isset($_GET["addPlayer"])){
  if($_GET["addPlayer"] == "success"){
?>
  <script>
      swal("Sikeres hozzáadás!","", "success").then(function(){window.location.href="../pages/players.php"});
  </script>
<?php
  }else{
?>
    <script>
    swal("Sikertelen hozzáadás!","", "error").then(function(){window.location.href="../pages/players.php"});
    </script>
  <?php
  }
  
}

?>
<div class="container mt-3" id="allPlayers">
    <?php 

    showPlayersMainData($conn, $role); 
    ?> 
</div>

<?php
  require_once '../components/footer.php';
?>