<?php
require_once '../components/header.php';
if ($role == 2) {
    $coach = getCoachData($conn, $id);
    $players = getPlayers($conn, $coach->teamID);
    $today = date("Y-m-d");
    $tomorrow = strtotime("$today +1 day");
?>  
    <div class="table-responsive text-light">
        <div class="text-center">
            <h1 class="display-3 p-3">Mérkőzés keret hirdetése</h1>
        </div>
        <form action="../includes/addGameSquad.inc.php?teamID=<?php echo $coach->teamID ?>" method="post" id="squadForm" class="p-3" style="min-width: 500px ;">
            <div class="row">
                <div class="col-sm-3 col-xl-2 mx-auto">
                    <label for="inputTeam">Csapat</label>
                    <input name="inputTeam" type="text" class="form-control" id="inputTeam" disabled value=<?php
                                                                                                            if ($coach->teamID == 1) {
                                                                                                                echo "U9";
                                                                                                            } elseif ($coach->teamID == 2) {
                                                                                                                echo "U11";
                                                                                                            } elseif ($coach->teamID == 3) {
                                                                                                                echo "U14";
                                                                                                            } elseif ($coach->teamID == 4) {
                                                                                                                echo "U16";
                                                                                                            } elseif ($coach->teamID == 5) {
                                                                                                                echo "U19";
                                                                                                            } elseif ($coach->teamID == 6) {
                                                                                                                echo "Felnőtt";
                                                                                                            }
                                                                                                            ?>>
                </div>
                <div class="col-sm-3 col-xl-2 mx-auto">
                    <label for="inputDate">Időpont</label>
                    <input name="inputDate" type="date" class="form-control" id="inputDate" min="<?php echo date("Y-m-d",$tomorrow) ?>" max="2100-01-01" required>
                </div>
                <div class="col-sm-3 col-xl-2 mx-auto">
                    <label for="inputPlace">Helyszín</label>
                    <input name="inputPlace" type="text" class="form-control" id="inputPlace" placeholder="Helyszín" required>
                </div>
            </div>
            <div class="row" id="squadPlayerInputs">

            </div>
            <div class="row d-flex justify-content-end">

                <script>
                    players = <?php echo json_encode($players); ?>;
                </script>
                <a id="addPlayerLink" class="btn btn-secondary col-1 m-3" onclick="AddSquadPlayerInput(players)">+</a>
            </div>
            <div class="text-center">
                <button type="submit" name="submit" class="btn btn-success">Hozzáad</button>
            </div>
        </form>
        <div class="" id="unavailablePlayers">
        </div>

    </div>

<?php
} else {
    header("Location: ../pages/home.php");
    exit();
}
require_once '../components/footer.php';
?>