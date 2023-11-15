<html>
<head>
    <meta charset="utf8">
    <title>Żiogeser - Strona główna</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
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
            echo '<script src="js/set_favs.js" defer></script>';
        }
    }
}
if(isset($_SESSION['zalogowany']) && isset($_SESSION['login'])){
    echo '<script src="js/index.js" defer></script>';
}
?>
<body class="bg-stone-950">
<div class="hero text-white">

  <!--  HEADER -->
  <header class="absolute inset-x-0 top-0 z-50">
    <nav class="flex items-center justify-between p-6 lg:px-8">
      <div class="flex lg:flex-1"><a href="#"><img class="h-10 w-auto" src="favicon.ico"></a></div>
      <div class="flex flex-1 justify-end">
        <?php
          if(isset($_SESSION['zalogowany'])&& isset($_SESSION['login'])){
              echo '<a draggable="false" class="flex items-center" href="profil.php?login='.$_SESSION['login'].'"><img draggable="false" src="images/user.png" class="rounded-full h-10 w-auto mr-3 prof_pic"><span class="text-lg font-semibold">'.$_SESSION['login'].'</span></a>';
          }else if($error == 0){
              echo '<a href="rej.php" class="text-md font-semibold leading-6">Zaloguj <span aria-hidden="true">&rarr;</span></a>';
          }   
        ?>
      </div>
    </nav>
  </header>

  <!-- HERO -->
  <div class="relative isolate px-6 pt-14 lg:px-8">
    <div class="mx-auto max-w-2xl py-32 sm:py-48">
      <div class="text-center">
        <h1 class="text-5xl font-bold tracking-tight sm:text-6xl">Żioguessr</h1>
        <p class="mt-6 text-xl leading-8">Nie chcesz płacić swoich ciężko zarobionych szeklów na geoguessra? Oto rozwiązanie! Geoguessr, tylko że gorszy. Ale darmowy !!</p>
        <div class="mt-10 flex items-center justify-center gap-x-6">
          <a href="#mapy" class="rounded-md bg-sky-500 px-4 py-3 text-md font-semibold text-white shadow-sm hover:bg-sky-400 transition-all duration-400">Rozpocznij grę</a>
          <a href="https://github.com/Mateusz-Szymkiewicz1/swietokrzyski-geoguessr" target="_blank" class="text-md font-semibold leading-6">Github <span aria-hidden="true">→</span></a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MAPY -->
<h2 id="mapy" class="text-4xl text-center font-semibold mt-24 pt-20">Mapy</h2>
<hr class="w-32 m-auto my-3">
<div class="cards m-auto justify-center mt-24 p-10">

  <!-- ŚWIAT -->
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
    <a href="gra.php?map=Świat" class="card_link" draggable="false"><div class="card__img--hover"></div></a>
    <a href="gra.php?map=Świat" draggable="false">
      <div class="card__info">
        <span class="card__category">Świat</span>
        <h3 class="card__title">Lorem Ipsum dolor sit amet</h3>
      </div>
    </a>
  </article>

  <!-- POLSKA -->
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
    <a href="gra.php ?map=Polska" class="card_link" draggable="false"><div class="card__img--hover"></div></a>
    <a href="gra.php?map=Polska" draggable="false">
      <div class="card__info">
        <span class="card__category">Polska</span>
        <h3 class="card__title">Lorem Ipsum dolor sit amet</h3>
      </div>
    </a>
  </article> 

  <!-- UE -->
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
    <a href="gra.php?map=UE" class="card_link" draggable="false"><div class="card__img--hover"></div></a>
    <a href="gra.php?map=UE" draggable="false">
      <div class="card__info">
        <span class="card__category">UE</span>
        <h3 class="card__title">Lorem Ipsum dolor sit amet</h3>
      </div>
    </a>
  </article> 

  <!-- USA -->
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
    <a href="gra.php?map=USA" class="card_link" draggable="false"><div class="card__img--hover"></div></a>
    <a href="gra.php?map=USA" draggable="false">
      <div class="card__info">
        <span class="card__category">USA</span>
        <h3 class="card__title">Lorem Ipsum dolor sit amet</h3>
      </div>
    </a>
  </article>
</div>

<!-- COUNTRY STREAK -->
<h2 class="text-4xl text-center font-semibold mt-48">Country Streak</h2>
<hr class="w-32 m-auto my-3">
<div class="cards m-auto justify-center mt-24 p-10">

  <!-- ŚWIAT -->
  <article class="card card--5">
    <div class="card__img"></div>
    <a href="country_streak.php?map=Świat" class="card_link" draggable="false">
      <div class="card__img--hover"></div>
    </a>
    <a href="country_streak.php?map=Świat" draggable="false">
      <div class="card__info">
        <span class="card__category">World Streak</span>
        <h3 class="card__title">Lorem Ipsum dolor sit amet</h3>
      </div>
    </a>
  </article>

  <!-- UE -->
  <article class="card card--6">
    <div class="card__img"></div>
    <a href="country_streak.php?map=Europa" class="card_link" draggable="false">
      <div class="card__img--hover"></div>
    </a>
    <a href="country_streak.php?map=Europa" draggable="false">
      <div class="card__info">
        <span class="card__category">Europe Streak</span>
        <h3 class="card__title">Lorem Ipsum dolor sit amet</h3>
      </div>
    </a>
  </article>

</div>

<!-- FOOTER -->
<div class="bg-stone-900 mt-32">
  <div class="flex flex-wrap gap-x-9 gap-y-5 text-2xl p-10">
    <div>
      <i class="fa fa-user mr-2"></i><span>Mateusz Szymkiewicz 2023 ©</span>
    </div>
    <div>
      <i class="fa fa-code mr-2"></i><span><a href="https://github.com/Mateusz-Szymkiewicz1/swietokrzyski-geoguessr" target="_blank" class="text-sky-500">Github</a></span>
    </div>
    <div>
      <i class="fa fa-envelope mr-2"></i><a class="text-sky-500" href="mailto:szymkiewiczmateusz1@gmail.com"><span>szymkiewiczmateusz1@gmail.com</span></a>
    </div>
  </div>
</div>

<!-- GAME SETUP -->
<div class="game_setup" style="display: none;">
    <div class="close font-semibold mr-5">x</div>
    <div class="image h-full w-full">
        <h2 class="font-semibold p-0 m-0 text-center text-3xl pt-24"></h2>
        <label>Poruszanie się &nbsp;<input type="checkbox" checked class="check_move"></label>
        <label>Kompas &nbsp;<input type="checkbox" checked class="check_pan"></label>
        <label>Zoom &nbsp;<input type="checkbox" checked class="check_zoom"></label>
        <label>Limit czasu na 1 rundę <input type="time" value="00:00" class="time_limit"></label>
        <button class="rounded-md bg-sky-500 px-4 py-3 text-md font-semibold text-white shadow-sm hover:bg-sky-400 transition-all duration-400">Rozpocznij grę</button>
    </div>
</div>

<!-- JS ;) -->
<script>
    document.querySelectorAll("article a").forEach(el => {
        el.addEventListener("click", function(e){
            e.preventDefault();
            document.querySelector(".game_setup").style.display = "block";  
            let link = el.href;
            document.body.querySelectorAll("*").forEach(el => {
                if(el.className != "game_setup" && !document.querySelector(".game_setup").contains(el)){
                    el.style = el.style.cssText+"filter: blur(4px);pointer-events:none;cursor:auto;user-select:none;";
                }
            })
            document.querySelector(".image").className = "image "+el.parentElement.classList[1];
            document.querySelector(".image h2").innerText = el.parentElement.querySelector(".card__category").innerText;
            document.querySelector(".game_setup button").addEventListener("click", function(){
                if(!document.querySelector(".check_move").checked){
                    link = link+'&move=false';
                }
                if(!document.querySelector(".check_pan").checked){
                    link = link+'&pan=false';
                }
                if(!document.querySelector(".check_zoom").checked){
                    link = link+'&zoom=false';
                }
                if(document.querySelector(".time_limit").value != "00:00"){
                    let time = document.querySelector(".time_limit").value.split(":");
                    let time2 = parseInt(time[1])+(parseInt(time[0])*60);
                    if(Number.isInteger(time2)){
                        link = link+`&time_limit=${time2}`;
                    }
                }
                document.location.href = link;
            })
        })
    })
    document.querySelector(".close").addEventListener("click", function(){
        document.querySelector(".game_setup").style.display = "none";  
        document.body.querySelectorAll("*").forEach(el => {
            if(el.className != "game_setup" && el.parentElement.className != "game_setup"){
                el.style.filter = "";
                el.style.pointerEvents = "";
                el.style.userSelect = "";
                el.style.cursor = "";
            }
        })
    })
    function error_close(){
      document.querySelector("#error").remove();
    }
</script>
</body>
</html>