<?php
require_once '../components/header.php';
if($role == 2){
    $today = date("Y-m-d");
    $yesterday = strtotime("$today -1 day");
    $tomorrow = strtotime("$today +1 day");
?>

<form action="../includes/addPlayer.inc.php" method="post"  class="text-light p-3">
    <div class="text-center">
        <h1 class="display-3 p-3">Új játékos hozzáadása</h1>
    </div>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <label for="inputID">Játékos azonosító száma</label>
            <input name="inputID" type="text" class="form-control" id="inputID" placeholder="Azonosító" required>
        </div>
        <div class="col-md-6 mx-auto">
            <label for="inputFullName">Teljes név</label>
            <input name="inputFullName" type="text" class="form-control" id="inputFullName" placeholder="Név" required>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-xl-2 mx-auto">
            <label for="inputBirthdate">Születési dátum</label>
            <input name="birthdate" type="date" class="form-control" id="inputBirthdate" min="1900-01-01" max="<?php echo $yesterday ?>" required>
        </div>
        <div class="col-sm-4 col-xl-2 mx-auto">
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
        <div class="col-sm-4 col-xl-2 mx-auto">
            <label for="inputPosition">Poszt</label>
            <input name="position" type="text" class="form-control" id="inputPosition" placeholder="Poszt" required>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-xl-2 mx-auto">
            <label for="inputHeight">Magasság</label>
            <input name="height" type="number" id="inputHeight" class="form-control" placeholder="Magasság" required>
        </div>
        <div class="col-sm-4 col-xl-2 mx-auto">
            <label for="inputWeight">Súly</label>
            <input name="weight" type="number" id="inputWeight" class="form-control" placeholder="Súly" required>
        </div>
        <div class="col-sm-4 col-xl-2 mx-auto">
            <label for="inputStrongLeg">Ügyesebb láb</label>
            <select name="strongFoot" id="inputStrongLeg" class="form-control">
                <option value="1">jobb</option>
                <option value="2">bal</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-4 mx-auto">
            <label for="inputMobile">Telefon</label>
            <input name="mobile" type="text" id="inputMobile" class="form-control" placeholder="Telefon" required>
        </div>
        <div class="col-md-6 col-lg-4 mx-auto">
            <label for="inputEmail">Email</label>
            <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email" required>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-xl-2 mx-auto">
            <label for="inputDateOfSigning">Leigazolás dátuma</label>
            <input name="contractDate" type="date" class="form-control" id="inputDateOfSigning" min="1900-01-01" max="<?php echo $yesterday ?>" required>
        </div>
        <div class="col-sm-3 col-xl-2 mx-auto">
            <label for="inputDateOfExpire">Igazolás lejárata</label>
            <input name="contractExpires" type="date" class="form-control" id="inputDateOfExpire" min="<?php echo $tomorrow ?>" max="2100-01-01" required>
        </div>
        <div class="col-sm-3 col-xl-2 mx-auto">
            <label for="inputDateOfMedical">Utolsó orvosi ideje</label>
            <input name="lastMedical" type="date" class="form-control" id="inputDateOfMedical" min="1900-01-01" max="<?php echo $yesterday ?>" required>
        </div>
        <div class="col-sm-3 col-xl-2 mx-auto">
            <label for="inputPlayerImage">Kép URL-je</label>
            <input name="image" type="text" class="form-control" id="inputPlayerImage" placeholder="URL">
        </div>
    </div>
    <div class="text-center mt-3">
        <button type="submit" name="submitPlayer" class="btn btn-success ">Hozzáad</button>
    </div>
</form>

<?php
}
else{
    header("Location: ../pages/home.php");
    exit();  
}
require_once '../components/footer.php';
?>
