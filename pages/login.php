<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kunsziget SE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/loginPageStyle.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<form action="../includes/login.inc.php" method="post" class="needs-validation">
  <div class="imgcontainer">
    <img src="../images/logo.png" alt="Kunsziget SE" class="logo">
  </div>
<?php
    if (isset($_GET["error"])){
        if($_GET["error"] == "wronguser"){
            echo "<p>A megadott felhasználónév nem létezik!</p>";
        }
        else if($_GET["error"] == "wrongpassword"){
          echo "<p>A megadott jelszó nem megfelelő!</p>";
      }
    }
?>
  <div class="form-group was-validated">
    <label class="form-label" for="uid"><b>Felhasználónév:</b></label>
    <input type="text" placeholder="Kérem a felhasználónevet" name="uid" class="form-control loginInput" required>
    <div class="invalid-feedback"> 
      Adja meg felhasználónevét
    </div>
  </div>
  <div class="form-group was-validated">
    <label class="form-label" for="pwd"><b>Jelszó:</b></label>
    <input type="password" placeholder="Kérem a jelszót" name="pwd" class="form-control loginInput" required>
    <div class="invalid-feedback"> 
      Adja meg jelszavát
    </div>
  </div>
  <button class="signInBtn" type="submit" name="submit">Bejelentkezés</button>
  

  <div class="container">
    <span class="copy">&copy Kunsziget SE</span>
  </div>
</form>
</body>
</html>