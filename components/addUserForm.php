<?php
require_once '../components/header.php';
?>

<form action="../includes/addUser.inc.php" method="post" class="text-light p-3">
    <div class="text-center">
        <h1 class="display-3 p-3">Felhasználó hozzáadása</h1>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-4 mx-auto">
            <label for="inputUserFullName">Teljes név</label>
            <input type="text" class="form-control" name="inputUserFullName" placeholder="Név" required>
        </div>
        <div class="col-md-6 col-lg-4 mx-auto">
            <label for="inputUserEmail">Email-cím</label>
            <input type="email" class="form-control" name="inputUserEmail" placeholder="Email" required>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 mx-auto">
            <label for="inputUserName">Felhasználónév</label>
            <input type="text" class="form-control" name="inputUserName" placeholder="Felhasználónév" required>
        </div>
        <div class="col-sm-3 mx-auto">
            <label for="inputUserPassword">Jelszó</label>
            <input type="password" class="form-control" name="inputUserPassword" placeholder="Jelszó" required>
        </div>
        <div class="col-sm-3 mx-auto">
            <label for="inputUserPasswordRe">Jelszó megerősítése</label>
            <input type="password" class="form-control" name="inputUserPasswordRe" placeholder="Jelszó megerősítése" required>
        </div>
        <div class="col-sm-3 mx-auto">
            <label for="inputUserRole">Jogosultság</label>
            <select name="inputUserRole" class="form-control">
                <option value="1">admin</option>
                <option value="2">edző</option>
                <option value="3" selected>játékos</option>
            </select>
        </div>
    </div>
    <div class="text-center mt-3">
        <button type="submit" name="submitUser" class="btn btn-success ">Hozzáad</button>
    </div>
</form>

<?php
require_once '../components/footer.php';
?>