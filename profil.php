<?php
require "connect.php";
session_start();
$login = $_GET['login'] ?? null;
$wlasciciel = 0;
$prof = "";
$bg = "";
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
</head>
<?php
    ?>
<body id="body">
 <div class="top_bar">
     <a href="index.php"><img src="favicon.ico"><span>Żiogeser</span></a>
 </div>
   <div class="bg_wrapper">
        <img src="images/<?=$bg?>" class="img_bg">
        <button class="bg_button">Zmień</button>
   </div>
   <div class="prof_wrapper">
       <img src="images/<?=$prof?>" class="img_prof">
   </div>
   <span class="span_login"><?=$login?></span>
   <?php
    if($wlasciciel == 1){
        echo '<script src="change_pic.js"></script>';
    }
    ?>
</body>
</html>