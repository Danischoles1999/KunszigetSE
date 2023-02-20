<?php
  require '../components/header.php';
  if($role == 2){
?>
<h1 class="text-light text-center">Lejárati dátumok</h1>
<div class="row justify-content-evenly">
<div class="col-3 mt-3 mx-auto text-center">
  <a onclick="showContacts()" class="btn btn-outline-light mb-3">Igazolások</a>
</div>
<div class="col-3 mt-3 mx-auto text-center">
  <a onclick="showMedicals()" class="btn btn-outline-light mb-3">Orvosik</a>
</div>
</div>
<div class="p-0 m-0"  id="contactDiv" hidden>
<h1 class="text-center text-light">Igazolások lejárati dátuma</h1>
<div class="container">
  <?php 
  showExpireDatesOfAllPlayers($conn, $_SESSION["coachid"], "contracts");
  ?>
</div>
</div>

<div class="p-0 m-0" id="medicalDiv" hidden>
<h1 class="text-center text-light">Orvosik lejárati dátuma</h1>
<div class="container">
  <?php 
  showExpireDatesOfAllPlayers($conn, $_SESSION["coachid"], "medicals");
  ?>
</div>
</div>


<?php
  }else{
    header("Location: ../pages/home.php");
    exit();
  }
  require '../components/footer.php';
?>