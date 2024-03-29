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
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<?php
require "connect.php";
session_start();
$login = $_GET['login'] ?? null;
echo '<span id="login" hidden>'.$login.'</span>';
$wlasciciel = 0;
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
}else if($error == 0){
    $sql = "SELECT * FROM uzytkownicy WHERE login='$login';";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $ilu_userow = $stmt->rowCount();
    if($ilu_userow < 1){
        echo "<script>window.location.href='index.php'</script>"; 
        die;
    }
    $wiersz_user = $stmt->fetch(PDO::FETCH_ASSOC); 
    $rozegrane = $wiersz_user['rozegrane'];
    $cs_games = $wiersz_user['cs_games'];
    $cs_max = $wiersz_user['cs_max'];
    $cs_sum = $wiersz_user['cs_sum'];
    if($cs_sum == 0){
        $cs_avg = 0;
    }else{
        $cs_avg = round($cs_sum/$cs_games, 2);
    }
    $ragequity = $wiersz_user['rozegrane']-$wiersz_user['ukonczone'];
    $max_score = $wiersz_user['max_score'];
    $fav_maps = $wiersz_user['fav_maps'];
    $fav_maps_tab = explode(",",$fav_maps);
    array_pop($fav_maps_tab);
    if($wiersz_user['ukonczone'] > 0){
        $avg_score = round($wiersz_user['sum_score']/$wiersz_user['ukonczone'], 2);
    }else{
        $avg_score = 0;
    } 
}else{
    die;
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
<body id="body" class="bg-stone-950 p-0 m-0 text-white overflow-x-hidden">
 <div class="top_bar bg-sky-400 fixed top-0 left-0 w-full z-50">
    <a href="index.php" draggable="false">
        <img draggable="false" src="favicon.ico">
        <span class="font-semibold block">Żiogeser</span>
    </a>
 </div>
   <div class="bg_wrapper">
        <?php
            if(is_dir("user_uploads/".$login)){
                if(file_exists("user_uploads/".$login."/bg.jpg")){
                    echo '<img src="'."user_uploads/".$login."/bg.jpg".'" class="img_bg">';
                }
                else if(file_exists("user_uploads/".$login."/bg.png")){
                    echo '<img src="'."user_uploads/".$login."/bg.png".'" class="img_bg">';
                }else{
                    echo '<img src="images/bg.jpg" class="img_bg">';
                }
            }else{
               echo '<img src="images/bg.jpg" class="img_bg">';  
            }
        ?>  
        <button class="bg_button hover:text-white hover:bg-sky-400"><label for="bg_file" form="bg_form"><i class="material-icons">create</i></label></button>
        <button class="bg_button hover:text-white hover:bg-sky-400" id="delete_bg"><label for="bg_submit"><i class="material-icons">close</i></label></button>
   </div>
   <div class="prof_wrapper">
       <?php
            if(is_dir("user_uploads/".$login)){
                if(file_exists("user_uploads/".$login."/prof.jpg")){
                    echo '<img src="'."user_uploads/".$login."/prof.jpg".'" class="img_prof">';
                }
                else if(file_exists("user_uploads/".$login."/prof.png")){
                    echo '<img src="'."user_uploads/".$login."/prof.png".'" class="img_prof">';
                }else{
                    echo '<img src="images/user.png" class="img_prof">';
                }
            }else{
               echo '<img src="images/user.png" class="img_prof">';  
            }
        ?> 
       <button class="prof_button"><label for="prof_file"><i class="material-icons">create</i></label></button>
       <button class="prof_button" id="delete_prof"><label for="prof_submit"><i class="material-icons">close</i></label></button>
   </div>
   <span class="span_login text-center block text-4xl mt-28 font-semibold"><?=$login?></span>
   <?php
    if($wlasciciel == 1){
        echo '<script src="js/change_pic.js" defer></script>';
    }
    ?>   
<!-- formularz do zdjęcia w tle -->
<form action="profil.php?login=<?=$login?>" method="post" enctype="multipart/form-data" id="bg_form" hidden>
       <input type="file" name="bg_file" id="bg_file">
       <input type="text" value="1" name="bg_submit">
       <input type="submit" value="Przeslij" id="bg_submit">
</form>
<form action="profil.php?login=<?=$login?>" method="post" enctype="multipart/form-data" id="prof_form" hidden>
       <input type="file" name="prof_file" id="prof_file">
       <input type="text" value="1" name="prof_submit">
       <input type="submit" value="Przeslij" id="prof_submit">
</form>
<?php
// Zmiana tła
$bg_submit = $_POST['bg_submit'] ?? null;
if($bg_submit){
    if($_FILES['bg_file']["name"] == ""){
        if(file_exists("user_uploads/".$login."/bg.jpg")){
            unlink("user_uploads/".$login."/bg.jpg");
            echo "<script>window.location.href='profil.php?login=".$login."';</script>";
        }
        if(file_exists("user_uploads/".$login."/bg.png")){
            unlink("user_uploads/".$login."/bg.png");
            echo "<script>window.location.href='profil.php?login=".$login."';</script>";
        }
    }else{
        if(!is_dir("user_uploads/".$login)){
            mkdir("user_uploads/".$login, 0700);
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if(false === $ext = array_search(
            $finfo->file($_FILES['bg_file']['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
            ),
            true
        )) {
             echo '<div class="error" id="img_error">Złe rozszerzenie pliku!</div>';
        }else{
            if ($_FILES['bg_file']['size'] > 2000000) {
                 echo '<div class="error" id="img_error">Plik jest za duży!</div>';
            }else{
                if($_FILES['bg_file']["type"] == "image/jpeg"){
                    $type = ".jpg";
                }else{
                    $type = ".png";
                }
                if(file_exists("user_uploads/".$login."/bg.png")){
                    unlink("user_uploads/".$login."/bg.png");
                }
                if(file_exists("user_uploads/".$login."/bg.jpg")){
                    unlink("user_uploads/".$login."/bg.jpg");
                }
                move_uploaded_file($_FILES["bg_file"]["tmp_name"], "user_uploads/".$login."/bg".$type);
                echo "<script>window.location.href='profil.php?login=".$login."';</script>";
            }
        }
    }
}
// Zmiana prof
$prof_submit = $_POST['prof_submit'] ?? null;
if($prof_submit){
    if($_FILES['prof_file']["name"] == ""){
        if(file_exists("user_uploads/".$login."/prof.jpg")){
            unlink("user_uploads/".$login."/prof.jpg");
            echo "<script>window.location.href='profil.php?login=".$login."';</script>";
        }
        if(file_exists("user_uploads/".$login."/prof.png")){
            unlink("user_uploads/".$login."/prof.png");
            echo "<script>window.location.href='profil.php?login=".$login."';</script>";
        }
    }else{
        if(!is_dir("user_uploads/".$login)){
            mkdir("user_uploads/".$login, 0700);
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if(false === $ext = array_search(
            $finfo->file($_FILES['prof_file']['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
            ),
            true
        )) {
            echo '<div class="error" id="img_error">Złe rozszerzenie pliku!</div>';
        }else{
            if ($_FILES['prof_file']['size'] > 2000000) {
                 echo '<div class="error" id="img_error">Plik jest za duży!</div>';
            }else{
                if($_FILES['prof_file']["type"] == "image/jpeg"){
                    $type = ".jpg";
                }else{
                    $type = ".png";
                }
                if(file_exists("user_uploads/".$login."/prof.png")){
                    unlink("user_uploads/".$login."/prof.png");
                }
                if(file_exists("user_uploads/".$login."/prof.jpg")){
                    unlink("user_uploads/".$login."/prof.jpg");
                }
                move_uploaded_file($_FILES["prof_file"]["tmp_name"], "user_uploads/".$login."/prof".$type);
                echo "<script>window.location.href='profil.php?login=".$login."';</script>";
            }
        }
    }
}
?>
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
<h2 class="font-semibold text-3xl">Country Streak</h2>
<table>
    <tr>
        <th>Rozegrane</th>
        <th>Rekord</th>
        <th>Średnia</th>
    </tr>
    <tr>
        <td><?=$cs_games?></td>
        <td><?=$cs_max?></td>
        <td><?=$cs_avg?></td>
    </tr>
</table>
<?php
    if($fav_maps != ""){
        echo '<h2 class="font-semibold text-3xl mb-20">Ulubione mapy</h2><div class="cards">';
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
          <div class="card__info-hover">';
        if($wlasciciel == 1){
          echo '<svg class="card__like"  viewBox="0 0 24 24" data-map="'.$map.'">
           <path data-map="'.$map.'" fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" fill="white"></path>
        </svg>';
        }
        echo '<div class="card__clock-info">
                <span class="card__time" style="color:'.$level_color.';">'.$map_level.'</span>
              </div>

          </div>
          <div class="card__img"></div>
          <a href="gra.php?map='.$map.'" class="card_link" draggable="false">
             <div class="card__img--hover"></div>
           </a>
           <a href="gra.php?map='.$map.'" draggable="false">
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
    if($wlasciciel == 1){
        echo '<form action="profil.php?login='.$login.'" method="post" hidden><input type="text" name="wyloguj" value="1">
            <input type="submit" id="wyloguj_submit">
        </form>';
        echo '<label for="wyloguj_submit" class="wyloguj_label">Wyloguj</label>';
    }
?>
<script>
  document.addEventListener("click", function(e){
    if(e.target.className.baseVal == ""){
        e.target.parentElement.parentElement.parentElement.remove();
        if(!document.querySelector(".cards").innerHTML){
            document.querySelector("h2").remove();
        }
        var xmlHttp = new XMLHttpRequest();
        let login = document.querySelector("#login").innerText;
        let map = e.target.dataset.map;
        xmlHttp.open("GET", `add_fav_map.php?login=${login}&map=${map}&type=remove`, true);
        xmlHttp.send(null);
    }
  })
  if(document.querySelector("#img_error")){
      document.querySelector("#img_error").addEventListener("click", function(){
          document.querySelector("#img_error").remove();
      })
  }
</script>
</body>
</html>
