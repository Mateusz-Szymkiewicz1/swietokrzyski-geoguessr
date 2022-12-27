<?php
require "connect.php";
session_start();
if(isset($_SESSION['zalogowany'])){
    if($_SESSION['expire'] < time()){
        session_destroy();
        echo "<script>window.location.href='index.php'</script>";
    }else{
        $_SESSION['expire'] = time()+(15 * 60);
    }
}
?>
<html>
<head>
    <meta charset="utf8">
    <title>Żiogeser - Strona główna</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="top_bar">
        <img src="favicon.ico" class="logo"><h1>Żiogeser<span class="dot">.</span></h1>
        <div class="buttons">
        <?php
        if(isset($_SESSION['zalogowany'])&& isset($_SESSION['login'])){
            echo '<a href="profil.php?login='.$_SESSION['login'].'"><img src="images/user.png" class="prof_pic"><span class="span_login">'.$_SESSION['login'].'</span></a>';
        }else{
            echo '<a href="rej.php?form=log"><button class="login">Zaloguj</button></a>
            <a href="rej.php?form=rej"><button class="rejestracja">Utwórz konto</button></a>';
        }   
        ?>
        </div>
    </div>
    <h2 class="h2_mapy">Mapy</h2>
    <section class="cards">
<article class="card card--1">
  <div class="card__info-hover">
    <svg class="card__like"  viewBox="0 0 24 24">
    <path fill="#ffffff" d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
</svg>
      <div class="card__clock-info">
        <span class="card__time" style="color:#d63031;">Trudna</span>
      </div>
    
  </div>
  <div class="card__img"></div>
  <a href="gra.php?map=Świat" class="card_link">
     <div class="card__img--hover"></div>
   </a>
   <a href="gra.php?map=Świat">
  <div class="card__info">
    <span class="card__category">Świat</span>
    <h3 class="card__title">Lorem Ipsum dolor sit amet</h3>
  </div>
  </a>
</article>
 <article class="card card--2">
  <div class="card__info-hover">
    <svg class="card__like"  viewBox="0 0 24 24">
    <path fill="#ffffff" d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
</svg>
      <div class="card__clock-info">
        <span class="card__time" style="color: #2ecc71;">Łatwa</span>
      </div>
    
  </div>
  <div class="card__img"></div>
  <a href="gra.php ?map=Polska" class="card_link">
     <div class="card__img--hover"></div>
   </a>
   <a href="gra.php?map=Polska">
  <div class="card__info">
    <span class="card__category">Polska</span>
    <h3 class="card__title">Lorem Ipsum dolor sit amet</h3>
  </div>
  </a>
</article> 
 <article class="card card--3">
  <div class="card__info-hover">
    <svg class="card__like"  viewBox="0 0 24 24">
    <path fill="#ffffff" d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
</svg>
      <div class="card__clock-info">
        <span class="card__time" style="color: #f1c40f;">Średnia</span>
      </div>
    
  </div>
  <div class="card__img"></div>
  <a href="gra.php?map=UE" class="card_link">
     <div class="card__img--hover"></div>
   </a>
   <a href="gra.php?map=UE">
  <div class="card__info">
    <span class="card__category">Unia Europejska</span>
    <h3 class="card__title">Lorem Ipsum dolor sit amet</h3>
  </div>
  </a>
</article> 
  <article class="card card--4">
  <div class="card__info-hover">
    <svg class="card__like"  viewBox="0 0 24 24">
    <path fill="#ffffff" d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
</svg>
      <div class="card__clock-info">
        <span class="card__time" style="color: #f1c40f;">Średnia</span>
      </div>
    
  </div>
  <div class="card__img"></div>
  <a href="gra.php?map=USA" class="card_link">
     <div class="card__img--hover"></div>
   </a>
   <a href="gra.php?map=USA">
  <div class="card__info">
    <span class="card__category">USA</span>
    <h3 class="card__title">Lorem Ipsum dolor sit amet</h3>
  </div>
  </a>
</article>
  </section>
</body>
</html>