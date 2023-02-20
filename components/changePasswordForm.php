<?php
require_once '../components/header.php';

if(isset($_GET["changePassword"])){
    if($_GET["changePassword"] == "wrongOldPassword"){
  ?>
      <script>
          swal("A megadott régi jelszó nem megfelelő!","", "error").then(function(){window.location.href="../components/changePasswordForm.php"});
      </script>
  <?php
    }
}
if(isset($_GET["changePassword"])){
    if($_GET["changePassword"] == "passwordsDontMatch"){
  ?>
      <script>
          swal("A megadott jelszavak nem egyeznek meg!","", "error").then(function(){window.location.href="../components/changePasswordForm.php"});
      </script>
  <?php
    }
}
?>
<h1 class="display-3 text-center">Jelszó megváltoztatása</h1>
<form action="../includes/changePassword.inc.php" method="post" class="text-light p-3">
    <div class="row my-5">
        <div class="col-md-4 mx-auto">
            <label for="currentPassword">Jelenlegi jelszó</label>
            <input name="currentPassword" type="password" class="form-control " id="currentPassword" placeholder="Régi jelszó" required>
        </div>
        <div class="col-md-4 mx-auto">
            <label for="newPassword">Új jelszó</label>
            <input name="newPassword" type="password" class="form-control" id="newPassword" placeholder="Új jelszó" required>
        </div>
        <div class="col-md-4 mx-auto">
            <label for="confirmPassword">Jelszó megerősítése</label>
            <input name="confirmPassword" type="password" class="form-control" id="confirmPassword" placeholder="Jelszó megerősítése" required>
        </div>
    </div>
    <div class="text-center mt-3">
        <button type="submit" name="submitPassword" class="btn btn-success ">Megváltoztat</button>
    </div>
</form>

<?php
require_once '../components/footer.php';
?>
