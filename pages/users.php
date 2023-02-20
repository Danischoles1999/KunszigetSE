<?php
require_once '../components/header.php';
if($role == 1){
  if(isset($_GET["emailorusername"])){
    if($_GET["emailorusername"] == "taken"){
      ?>
      <script>
          swal("A megadott felhasználónév vagy email foglalt!","", "error").then(function(){window.location.href="../pages/users.php"});
      </script>
    <?php
    }
  }
  if(isset($_GET["password"])){
    if($_GET["password"] == "dontmatch"){
      ?>
      <script>
          swal("A megadott jelszavak nem egyeznek!","", "error").then(function(){window.location.href="../pages/users.php"});
      </script>
    <?php
    }
  }
if(isset($_GET["addUser"])){
  if($_GET["addUser"] == "CantFindPlayer"){
    ?>
    <script>
        swal("Nincs ilyen játékos az adatbázisban! Csak létező játékoshoz hozhat létre fiókot!","", "error").then(function(){window.location.href="../pages/users.php"});
    </script>
  <?php
  }
  if($_GET["addUser"] == "CantFindCoach"){
    ?>
    <script>
        swal("Nincs ilyen edző az adatbázisban! Csak létező edzőhöz hozhat létre fiókot!","", "error").then(function(){window.location.href="../pages/users.php"});
    </script>
  <?php
  }
  if($_GET["addUser"] == "success"){
    ?>
    <script>
        swal("Sikeres törlés!","", "success").then(function(){window.location.href="../pages/users.php"});
    </script>
  <?php
  }
}
if(isset($_GET["deleteUser"])){
    if($_GET["deleteUser"] == "success"){
  ?>
    <script>
        swal("Sikeres törlés!","", "success").then(function(){window.location.href="../pages/users.php"});
    </script>
  <?php
    }else{
      ?>
          <script>
          swal("Sikertelen törlés!","", "error").then(function(){window.location.href="../pages/users.php"});
          </script>
        <?php
        }
  }
if(isset($_GET["modifyUser"])){
  if($_GET["modifyUser"] == "success"){
?>
  <script>
      swal("Sikeres szerkesztés!","", "success").then(function(){window.location.href="../pages/users.php"});
  </script>
<?php
  }else{
    ?>
        <script>
        swal("Sikertelen szerkesztés!","", "error").then(function(){window.location.href="../pages/users.php"});
        </script>
      <?php
      }
}
?>
<div class="container">
<h1 class="text-light text-center">Felhasználók</h1>
<?php
require_once '../components/addUserButton.php';
showAllUsers($conn,$id);
?> 
</div>

<?php
}
else{
  header('location: ../pages/home.php');
  exit;
}
require_once '../components/footer.php';
?>