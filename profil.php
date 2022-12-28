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
    $avg_score = $wiersz_user['avg_score'];
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
<html>
<head>
    <meta charset="utf8">
    <title>Żiogeser - Profil</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/profil.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<?php
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
