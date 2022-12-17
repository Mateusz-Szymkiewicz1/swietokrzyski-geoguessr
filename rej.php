<?php
$form = $_GET['form'] ?? "rej";
if($form == "log"){
    echo '<style>#log_form{display: block !important;}</style>';
}else{
    echo '<style>#rej_form{display: block !important;}</style>';
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
<form action="rej.php" method="post" id="rej_form">
    <div class="login-box">
  <h2>Rejestracja</h2>
  <form>
    <div class="user-box">
      <input type="text" name="rej_login" required="true" value="">
      <label>Login</label>
    </div>
    <div class="user-box">
      <input type="password" name="rej_haslo" required="true" value="">
      <label>Hasło</label>
    </div>
     <div class="user-box">
      <input type="password" name="rej_haslo2" required="true" value="">
      <label>Powtórz hasło</label>
    </div>
    <div class="user-box">
      <input type="email" name="rej_email" required="true" value="">
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
<form action="rej.php" method="post" id="log_form">
    <div class="login-box">
  <h2>Logowanie</h2>
  <form>
    <div class="user-box">
      <input type="text" name="log_login" required="true" value="">
      <label>Login</label>
    </div>
    <div class="user-box">
      <input type="password" name="log_haslo" required="true" value="">
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