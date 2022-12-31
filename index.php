<?php
require "connect.php";
session_start();
if(isset($_SESSION['zalogowany'])){
    if($_SESSION['expire'] < time()){
        session_destroy();
        echo "<script>window.location.href='index.php'</script>";
    }else{
        $_SESSION['expire'] = time()+(15 * 60);
        $login = $_SESSION['login'];
        echo '<span class="login" hidden>'.$_SESSION['login'].'</span>';
        $sql = "SELECT * FROM uzytkownicy WHERE login='$login';";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $wiersz = $stmt->fetch(PDO::FETCH_ASSOC);
        $fav_maps = $wiersz['fav_maps'];
        if($fav_maps != ""){
            echo '<script>window.fav_maps = "'.$fav_maps.'"</script>';
            echo '<script src="set_favs.js" defer></script>';
        }
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
<?php
    if(isset($_SESSION['zalogowany'])&& isset($_SESSION['login'])){
        echo '<script src="index.js" defer></script>';
    }
?>
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
    <svg class="card__like"  viewBox="0 0 24 24" data-map="Świat">
    <path fill="#ffffff" viewBox="0 0 24 24" d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
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
    <svg class="card__like"  viewBox="0 0 24 24" data-map="Polska">
    <path fill="#ffffff" viewBox="0 0 24 24" d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
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
    <svg class="card__like"  viewBox="0 0 24 24" data-map="UE">
    <path fill="#ffffff" viewBox="0 0 24 24" d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
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
    <svg class="card__like"  viewBox="0 0 24 24" data-map="USA">
    <path fill="#ffffff" viewBox="0 0 24 24" d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
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