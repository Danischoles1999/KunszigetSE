<?php
require_once '../components/header.php';
if($role == 2){
    $today = date("Y-m-d");
    $yesterday = strtotime("$today -1 day");
?>

<div class="table-responsive">
<form action="../includes/addGame.inc.php" method="post" id="matchForm" class="text-light p-3" >
    <div class="text-center text-nowrap overflow-auto">
        <h1 class="display-3 p-3">Mérkőzés hozzáadása</h1>
    </div>
    <div class="row">
    <div class="col-sm-3 col-xl-2 mx-auto">
            <label for="inputSeason">Szezon</label>
            <select name="season" id="inputSeason" class="form-control">
                <option value="1" selected>2022/2023</option>
                <option value="2">2023/2024</option>
                <option value="3">2024/2025</option>
                <option value="4">2025/2026</option>
                <option value="5">2026/2027</option>
                <option value="6">2027/2028</option>
            </select>
        </div>
        <div class="col-sm-3 col-xl-2 mx-auto">
            <label for="inputDateOfGame">Időpont</label>
            <input name="date" type="date" class="form-control" id="inputDateOfGame" min="2022-01-01" max="<?php echo date("Y-m-d",$yesterday) ?>" required>
        </div>
        <div class="col-sm-3 col-xl-2 mx-auto">
            <label for="inputPlaceOfGame">Helyszín</label>
            <input name="place" type="text" class="form-control" id="inputPlaceOfGame" placeholder="Helyszín" required>
        </div>
        <div class="col-sm-3 col-xl-2 mx-auto">
            <label for="inputTeam">Csapat/Korosztály</label>
            <select name="team" id="inputTeam" class="form-control">
                <option value="1">U9</option>
                <option value="2">U11</option>
                <option value="3">U14</option>
                <option value="4">U16</option>
                <option value="5">U19</option>
                <option value="6" selected>Felnőtt</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-xl-2 mx-auto">
            <label for="inputHomeTeam">Hazai csapat</label>
            <input name="homeTeam" type="text" class="form-control" id="inputHomeTeam" placeholder="Hazai csapat" required>
        </div>
        <div class="col-sm-3 col-xl-2 mx-auto">
            <label for="inputHomeTeamGoals">Gólszám</label>
            <input name="homeTeamGoals" type="number" class="form-control" id="inputHomeTeamGoals" placeholder="Gólszám" required>
        </div>
        <div class="col-sm-3 col-xl-2 mx-auto">
            <label for="inputAwayTeam">Vendég csapat</label>
            <input name="awayTeam" type="text" class="form-control" id="inputAwayTeam" placeholder="Vendég csapat" required>
        </div>
        <div class="col-sm-3 col-xl-2 mx-auto">
            <label for="inputAwayTeamGoals">Gólszám</label>
            <input name="awayTeamGoals" type="number" class="form-control" id="inputAwayTeamGoals" placeholder="Gólszám" required>
        </div>
    </div>
    <div class="" id="playersInputs">
    <div class="row">
        <div class="col-6 d-flex justify-content-evenly text-center ">
            <label class="col-6 mx-auto">Játékos neve</label>
            <label class="col-1 mx-auto"><img id="addGameIconStart" src="../images/start.png" title="Kezdő" alt="Kezdő"></label>
            <label class="col-1 mx-auto"><img id="addGameIconBall" src="../images/ball.png" title="Gól" alt="Gól"></label>
            <label class="col-1 mx-auto"><img id="addGameIconCard" src="../images/yellow.png" title="Sárgalap" alt="Sárgalap"></label>
            <label class="col-1 mx-auto"><img id="addGameIconCard" src="../images/red.png" title="Piroslap" alt="Piroslap"></label>
        </div>
        <div class="col-6 d-flex justify-content-evenly text-center">
            <label class="col-6 mx-auto">Játékos neve</label>
            <label class="col-1 mx-auto"><img id="addGameIconStart" src="../images/start.png" title="Kezdő" alt="Kezdő"></label>
            <label class="col-1 mx-auto"><img id="addGameIconBall" src="../images/ball.png" title="Gól" alt="Gól"></label>
            <label class="col-1 mx-auto"><img id="addGameIconCard" src="../images/yellow.png" title="Sárgalap" alt="Sárgalap"></label>
            <label class="col-1 mx-auto"><img id="addGameIconCard" src="../images/red.png" title="Piroslap" alt="Piroslap"></label>
        </div>
    </div>
    </div>
    <div class="row d-flex justify-content-end">
    <a class="btn btn-secondary col-1 m-3" onclick="AddMatchPlayerInput()">+</a>
    </div>
    <button type="submit" name="submit" class="btn btn-success mx-auto">Hozzáad</button>
</form>
</div>

<?php
}
else{
    header("Location: ../pages/home.php");
    exit(); 
}
require_once '../components/footer.php';
?>