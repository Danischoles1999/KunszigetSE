<?php

require_once '../includes/dbhandler.inc.php';
require_once '../includes/functions.inc.php';
session_start();
$role = $_SESSION["userrole"];

if(isset($_POST['input'])){
    $input = $_POST['input'];
    $sql = "SELECT * FROM absences WHERE (absences_start_date < '{$input}' AND absences_end_date > '{$input}');";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    $unavailablePlayers = array();
    if($resultCheck > 0){
        while($player = mysqli_fetch_assoc($result)){
            $playerName = getPlayerNameFromID($conn, $player["player_ID"]);
            array_push($unavailablePlayers,$player["player_ID"]);
        }
    }
    $i=1;
    ?>
    <h1 class="text-center">Nem elérhető játékosok</h1>
    <?php
    foreach($unavailablePlayers as $unavailablePlayer){
        $playerName = getPlayerNameFromID($conn, $unavailablePlayer);
        ?>
        <span hidden id="unavailable<?php echo $i ?>"><?php echo $unavailablePlayer?></span><br>
        <span class="badge bg-danger col-2"><?php echo $playerName?></span>
        <?php
        $i++;
    }?>
    <input type="text" id="i" value="<?php echo $i-1 ?>" hidden>
    <?php
}
