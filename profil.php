<html>
<head>
    <meta charset="utf8">
    <title>Żiogeser - Profil</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
      <link rel="stylesheet" href="css/profil.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<?php
require "connect.php";
session_start();
$login = $_GET['login'] ?? null;
$wlasciciel = 0;
$prof = "";
$bg = "";
$rozegrane = 0;
$ragequity = 0;
$max_score = 0;
$avg_score = 0;
$wyloguj = $_POST['wyloguj'] ?? null;
if($wyloguj){
    session_destroy();
    echo "<script>window.location.href='index.php'</script>";
    die;
}
if($login == null){
    echo "<script>window.location.href='index.php'</script>";
    die;
}else{
    if($error == 0){
   $sql = "SELECT * FROM uzytkownicy WHERE login='$login';";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $ilu_userow = $stmt->rowCount();
     if($ilu_userow < 1){
       echo "<script>window.location.href='index.php'</script>"; 
        die;
    }
    $wiersz_user = $stmt->fetch(PDO::FETCH_ASSOC); 
    $prof = $wiersz_user['prof'];
    $bg = $wiersz_user['bg'];
    $rozegrane = $wiersz_user['rozegrane'];
    $ragequity = $wiersz_user['rozegrane']-$wiersz_user['ukonczone'];
    $max_score = $wiersz_user['max_score'];
    $fav_maps = $wiersz_user['fav_maps'];
    $fav_maps_tab = explode(",",$fav_maps);
    array_pop($fav_maps_tab);
    if($wiersz_user['ukonczone'] > 0){
        $avg_score = $wiersz_user['sum_score']/$wiersz_user['ukonczone'];
    }else{
        $avg_score = 0;
    }
    if($prof == ""){
        $prof = "user.png";
    }else{
        $prof = "user_uploads/".$login."/".$prof;
    }
    if($bg == ""){
        $bg = "bg.jpg";
    }else{
        $bg = "user_uploads/".$login."/".$bg;
    }
    }else{
        die;
    }
}
if(isset($_SESSION['zalogowany'])){
    if($_SESSION['expire'] < time()){
        session_destroy();
        echo "<script>window.location.href='index.php'</script>";
    }else{
        $_SESSION['expire'] = time()+(15 * 60);
        if($_SESSION['login'] == $login){
            $wlasciciel = 1;
        }
    }
}
?>
<body id="body">
 <div class="top_bar">
     <a href="index.php"><img src="favicon.ico"><span>Żiogeser</span></a>
 </div>
   <div class="bg_wrapper">
        <img src="images/<?=$bg?>" class="img_bg">
        <button class="bg_button"><label for="bg_file" form="bg_form"><i class="material-icons">create</i></label></button>
   </div>
   <div class="prof_wrapper">
       <img src="images/<?=$prof?>" class="img_prof">
       <button class="prof_button"><i class="material-icons">create</i></button>
   </div>
   <span class="span_login"><?=$login?></span>
   <?php
    if($wlasciciel == 1){
        echo '<script src="change_pic.js" defer></script>';
    }
    ?>   
<!-- formularz do zdjęcia w tle -->
<form action="profil.php?login='.$login.'" method="post" enctype="multipart/form-data" id="bg_form" hidden>
       <input type="file" name="bg_file" id="bg_file">
       <input type="submit" value="Przeslij" id="bg_submit">
</form>
<table>
    <tr>
        <th>Gry rozegrane</th>
        <th>Ragequity</th>
        <th>Średni wynik</th>
        <th>Max wynik</th>
    </tr>
    <tr>
        <td><?=$rozegrane?></td>
        <td><?=$ragequity?></td>
        <td><?=$avg_score?></td>
        <td><?=$max_score?></td>
    </tr>
</table>
<h2>Ulubione mapy</h2>
<?php
    if($fav_maps != ""){
        echo '<div class="cards">';
    }
    foreach ($fav_maps_tab as $map) {
        if($map == "Świat"){
           $map_desc = "Lorem Ipsum";
           $map_level = "Trudna";
           $map_picture = 1;
       }
       if($map == "Polska"){
           $map_desc = "Lorem Ipsum";
           $map_level = "Łatwa";
           $map_picture = 2;
       }
         if($map == "UE"){
           $map_desc = "Lorem Ipsum";
           $map_level = "Średnia";
           $map_picture = 3;
       }
         if($map == "USA"){
           $map_desc = "Lorem Ipsum";
           $map_level = "Średnia";
           $map_picture = 4;
       }
      switch($map_level){
          case "Łatwa":
              $level_color = "#2ecc71";
              break;
           case "Średnia":
              $level_color = "#f1c40f";
              break;
            case "Trudna":
              $level_color = "#d63031";
              break;
      }
       echo '<article class="card card--'.$map_picture.'">
          <div class="card__info-hover">
              <div class="card__clock-info">
                <span class="card__time" style="color:'.$level_color.';">'.$map_level.'</span>
              </div>

          </div>
          <div class="card__img"></div>
          <a href="gra.php?map='.$map.'" class="card_link">
             <div class="card__img--hover"></div>
           </a>
           <a href="gra.php?map='.$map.'">
          <div class="card__info">
            <span class="card__category">'.$map.'</span>
            <h3 class="card__title">'.$map_desc.'</h3>
          </div>
          </a>
        </article>';
    }
    if($fav_maps != ""){
        echo '</div>';
    }
?>
<?php
    if($wlasciciel == 1){
        echo '<form action="profil.php?login='.$login.'" method="post" hidden><input type="text" name="wyloguj" value="1">
            <input type="submit" id="wyloguj_submit">
        </form>';
        echo '<label for="wyloguj_submit" class="wyloguj_label">Wyloguj</label>';
    }
?>
</body>
</html>
