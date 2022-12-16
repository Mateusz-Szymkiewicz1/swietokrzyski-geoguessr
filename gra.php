<html>
<head>
    <meta charset="utf8">
    <title>Żiogeser - Gra</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-2VsB6HLBe9m2ZSXmQbGR1b2gzFNijLQ&callback=initMap&v=weekly" defer></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="script.js"></script>
</head>
<body>
   <a href="index.php"><img src="images/back.png" height="49px" width="50px" id="back"></a>
    <span id="runda">Runda 1/5</span>
    <div id="pano"></div>
    <div class="kordy" onclick="kordy()" id="kordy"><img src="images/next.png" height="50px" width="50px"></div>
    <div class="kordy" id="start" onclick="start()"><img src="images/start.png" height="50px" width="30px"></div>
    <div id="mapa" class="mapa"></div>
</body>
</html>
