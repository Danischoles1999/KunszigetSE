</div>
<?php
if (!isset($_SESSION['loggedin'])) { // mindig le kell ellenőrizni hogy be van e jelentkezve a felhasználó , különben ne tudja elérni az oldalt
  header('Location: ../pages/login.php');
  exit;
}
?>
<div class="footer-basic">
  <footer>
    <div class="social"><a href="#"><i class="fa fa-instagram"></i></a><a href="#"><i class="fa fa-snapchat"></i></a><a href="#"><i class="fa fa-twitter"></i></a><a href="#"><i class="fa fa-facebook"></i></a></div>
    <?php
    if ($role != 1) {
    ?>
      <ul class="list-inline">
        <li class="list-inline-item"><a href="../pages/home.php">Kezdőoldal</a></li>
        <li class="list-inline-item"><a href="../pages/profile.php">Profil</a></li>
        <li class="list-inline-item"><a href="../pages/games.php">Meccsnaptár</a></li>
        <li class="list-inline-item"><a href="../pages/trainings.php">Edzések</a></li>
        <li class="list-inline-item"><a href="../pages/travels.php">Menetrend</a></li>
      </ul>
    <?php
    }
    ?>
    <p class="copyright">Kunsziget SE © </p>
  </footer>
</div>
</body>

</html>