<?php
require_once '../pages/header.php';
if($role == 2){
    $today = date("Y-m-d");
    $tomorrow = strtotime("$today +1 day");
?>

<form class="text-light p-3">
    <div class="text-center">
        <h1 class="display-3 p-3">Edzés hozzáadása</h1>
    </div>
    <div class="row">
        <div class="col-md-5 col-lg-4 mx-auto">
            <label class="display-6 mb-2" for="inputTeam">Csapat/Korosztály</label>
            <select id="inputTeam" class="form-control">
                <option value="1">U9</option>
                <option value="2">U11</option>
                <option value="3">U14</option>
                <option value="4">U16</option>
                <option value="5">U19</option>
                <option value="6" selected>Felnőtt</option>
            </select>
        </div>
        <div class="col-md-3 col-lg-4 mx-auto">
            <label class="display-6 mb-2" for="inputTimeOfTraining">Időpont</label>
            <input type="date" class="form-control" id="inputTimeOfTraining" min="<?php echo $tomorrow ?>" max="2100-01-01" required>
        </div>
        <div class="col-md-4 col-lg-4 mx-auto">
            <label class="display-6 mb-2" for="inputPlaceOfTraining">Helyszín</label>
            <input type="text" class="form-control" id="inputPlaceOfTraining" placeholder="Helyszín" required>
        </div>
    </div>
    <div class="text-center mt-3">
        <button type="submit" class="btn btn-success ">Hozzáad</button>
    </div>
</form>

<?php
}
else{
    header("Location: ../pages/home.php");
    exit();  
}