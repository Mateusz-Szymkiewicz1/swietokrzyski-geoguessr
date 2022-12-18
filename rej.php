<?php
require_once "connect.php";
session_start();
$form = $_GET['form'] ?? "rej";
if($error == 0){
if($form == "log"){
    echo '<style>#log_form{display: block !important;}</style>';
}else{
    echo '<style>#rej_form{display: block !important;}</style>';
}
}
?>
<html>
<head>
    <meta charset="utf8">
    <title>Żiogeser - Rejestracja</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="css/rej.css">
</head>
<body>
<a href="index.php"><img src="images/arrows.png" width="50px" height="50px"></a>
<!-- Rejestracja -->
<form action="rej.php?form=rej" method="post" id="rej_form">
    <div class="login-box">
  <h2>Rejestracja</h2>
  <?php
    $rej_login = $_POST['rej_login'] ?? null;
    $rej_haslo = $_POST['rej_haslo'] ?? null;
    $rej_haslo2 = $_POST['rej_haslo2'] ?? null;
    $rej_email = $_POST['rej_email'] ?? null;
    function rej($login, $haslo, $haslo2, $email){
        require "connect.php";
        if($login and $haslo and $haslo2 and $email){
            if($haslo != $haslo2){
                echo '<span class="error">Hasła nie są zgodne!</span>';
                return;
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo '<span class="error">Podany email jest nieprawidłowy!</span>';
                return;
            }
            $sql = "SELECT * FROM uzytkownicy WHERE login='$login';";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $ilu_userow = $stmt->rowCount();
            if($ilu_userow > 0){
                echo '<span class="error">Użykownik o podanym loginie już istnieje!</span>';
                return;
            }
            $sql2 = "SELECT * FROM uzytkownicy WHERE email='$email';";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();
            $ilu_userow2 = $stmt2->rowCount();
            if($ilu_userow2 > 0){
                echo '<span class="error">Założono już konto na podanym emailu!</span>';
                return;
            }
            $haslo_hashed = password_hash($haslo, PASSWORD_DEFAULT);
            $sql = "INSERT INTO uzytkownicy (login, haslo, email) VALUES ('$login', '$haslo_hashed', '$email');";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $_SESSION['zalogowany'] = 'True';
            $_SESSION['time'] = time();
            $_SESSION['expire'] = $_SESSION['time'] + (15 * 60);
            $_SESSION['login'] = $login;
            echo "<script>window.location.href='index.php'</script>";
        }
    }
    rej($rej_login, $rej_haslo, $rej_haslo2, $rej_email);
    ?>
  <form>
    <div class="user-box">
      <input type="text" name="rej_login" required="true" value="" pattern="^(?=.*[A-Za-z0-9]$)[A-Za-z][A-Za-z\d.-_]{0,19}$" oninvalid="this.setCustomValidity('Login może zawierać tylko litery, cyfry, . , - i _')"
  oninput="this.setCustomValidity('')" autocomplete="off">
      <label>Login</label>
    </div>
    <div class="user-box">
      <input type="password" name="rej_haslo" required="true" value="" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" oninvalid="this.setCustomValidity('Hasło musi zawierać: \n- 8 znaków\n- min. 1 wielką literę\n- min. 1 małą literę\n- min. 1 liczbę')"
  oninput="this.setCustomValidity('')" autocomplete="off">
      <label>Hasło</label>
    </div>
     <div class="user-box">
      <input type="password" name="rej_haslo2" required="true" value="" autocomplete="off">
      <label>Powtórz hasło</label>
    </div>
    <div class="user-box">
      <input type="email" name="rej_email" required="true" value="" autocomplete="off">
      <label>E-mail</label>
    </div>
    <a href="rej.php?form=log">Masz już konto?</a>
    <label class="label_submit" for="rej_submit">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      Załóż konto
    </label>
    <input type="submit" hidden id="rej_submit">
  </form>
</div>
<!-- Logowanie -->
</form>
<form action="rej.php?form=log" method="post" id="log_form">
    <div class="login-box">
  <h2>Logowanie</h2>
  <?php
    // Logowanie
$log_login = $_POST['log_login'] ?? null;
$log_haslo = $_POST['log_haslo'] ?? null;
if($log_login && $log_haslo){
    $sql = "SELECT * FROM uzytkownicy WHERE login='$log_login';";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $ilu_userow = $stmt->rowCount();
    $wiersz_user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($ilu_userow>0 and password_verify($log_haslo, $wiersz_user['haslo'])){
        $_SESSION['zalogowany'] = 'True';
        $_SESSION['time'] = time();
        $_SESSION['expire'] = $_SESSION['time'] + (15 * 60);
        $_SESSION['login'] = $wiersz_user['login'];
        header("Location: index.php");
    }else{
        echo '<span class="error">Nie znaleziono użytkownika z podanym loginem/hasłem</span>';
    }
}    
    ?>
  <form>
    <div class="user-box">
      <input type="text" name="log_login" required="true" value="" autocomplete="off">
      <label>Login</label>
    </div>
    <div class="user-box">
      <input type="password" name="log_haslo" required="true" value="" autocomplete="off">
      <label>Hasło</label>
    </div>
    <a href="rej.php?form=rej">Nie masz konta?</a>
    <label class="label_submit" for="log_submit">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      Zaloguj
    </label>
    <input type="submit" hidden id="log_submit">
  </form>
<!-- JS :) -->
<script>
    document.querySelectorAll("input").forEach(el =>{
       el.addEventListener("focus", function(e){
        e.target.parentElement.querySelector("label").setAttribute("style", "top: -20px;left: 0;color: #03e9f4;font-size: 12px;")
        }) 
    })
    document.querySelectorAll("input").forEach(el =>{
       el.addEventListener("focusout", function(e){
           if(el.value == ""){
        e.target.parentElement.querySelector("label").setAttribute("style", "")
           }
        }) 
    })
</script>
</body>
</html>