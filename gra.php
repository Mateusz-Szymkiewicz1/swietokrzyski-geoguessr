<html>
<head>
    <meta charset="utf8">
    <title>Żiogeser - Gra</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-2VsB6HLBe9m2ZSXmQbGR1b2gzFNijLQ&callback=initMap&v=weekly" defer></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/maps.js"></script>
    <script src="js/script.js"></script>
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
    $rozegrane = $wiersz['rozegrane'];
    $rozegrane_new = $wiersz['rozegrane']+1;
    $ukonczone = $wiersz['ukonczone'];
    echo '<span id="ukonczone" style="display: none;">'.($ukonczone+1).'</span>';
    $sql = "UPDATE uzytkownicy SET rozegrane='$rozegrane_new' WHERE login='$login';";
    $stmt = $db->prepare($sql);
    $stmt->execute();
}
?>
<body id="body">
   <a href="index.php" draggable="false"><img draggable="false" src="images/back.png" height="49px" width="50px" id="back"></a>
    <span id="runda">Runda 1/5</span><span id="czas"></span>
    <div id="pano"></div>
    <div class="kordy" onclick="kordy()" id="kordy"><img draggable="false" src="images/next.png" height="50px" width="50px" class="next"></div>
    <div class="kordy" id="start" onclick="start()"><img draggable="false" src="images/start.png" height="50px" width="30px"></div>
    <div id="mapa" class="mapa"></div>
</body>
</html>
