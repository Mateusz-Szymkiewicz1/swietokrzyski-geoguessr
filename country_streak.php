<html>
<head>
    <meta charset="utf8">
    <title>Żiogeser - Country Streak</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-2VsB6HLBe9m2ZSXmQbGR1b2gzFNijLQ&callback=initMap&v=weekly" defer></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/maps.js"></script>
    <script src="js/country_streak.js"></script>
</head>
<?php
require "connect.php";
session_start();
$wiersz = [];
if(isset($_SESSION['zalogowany'])){
    if($_SESSION['expire'] < time()){
        session_destroy();
        echo "<script>window.location.href='index.php'</script>";
    }else{
        $_SESSION['expire'] = time()+(15 * 60);
    }
    $login = $_SESSION['login'];
    echo '<span id="login" style="display: none;">'.$login.'</span>';
    $sql = "SELECT * FROM uzytkownicy WHERE login='$login';";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $wiersz = $stmt->fetch(PDO::FETCH_ASSOC);
    $cs_max = $wiersz['cs_max'];
    $cs_sum = $wiersz['cs_sum'];
    echo '<span id="cs_sum" style="display: none;">'.$cs_sum.'</span>';
    echo '<span id="cs_max" style="display: none;">'.$cs_max.'</span>';
    echo '<script>window.cs_sum = parseInt(document.querySelector("#cs_sum").innerText);</script>';
    $rozegrane = $wiersz['cs_games'];
    $rozegrane_new = $wiersz['cs_games']+1;
    $sql = "UPDATE uzytkownicy SET cs_games='$rozegrane_new' WHERE login='$login';";
    $stmt = $db->prepare($sql);
    $stmt->execute();
}
?>
<body id="body">
   <a href="index.php"><img src="images/back.png" height="49px" width="50px" id="back"></a>
    <span id="runda">Streak: 0</span><span id="czas"></span>
    <div id="pano" style="background: #171717;"></div>
    <div class="kordy" onclick="kordy()" id="kordy"><img src="images/next.png" height="50px" width="50px" class="next" style="margin-left: 5vw;"></div>
    <div class="kordy" id="start" onclick="start()" style="margin-left: 1vw;"><img src="images/start.png" height="50px" width="30px"></div>
    <div id="mapa" class="mapa"></div>
</body>
</html>
