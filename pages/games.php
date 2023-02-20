<?php
    require '../components/header.php';
?>
<div class="container text-center py-3">
<h1 class="text-light">Mérkőzések</h1>
<!-- <?php
require '../components/liveGameSearch.php';
?> -->
<?php
require '../components/addGameButton.php';
?>
</div>

<div class="container mt-3" id="allGames">
    <?php 

    showGamesMainData($conn, $role); 
    ?> 
</div>

<?php
    require '../components/footer.php';
 
?>