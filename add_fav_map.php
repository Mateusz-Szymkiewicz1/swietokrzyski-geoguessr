<?php
require_once "connect.php";
$login = $_GET['login'];
$map = $_GET['map'];
$type = $_GET['type'];
if($type == "add"){
    $sql = "SELECT * FROM uzytkownicy WHERE login='$login';";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $wiersz = $stmt->fetch(PDO::FETCH_ASSOC);
    $fav_maps = $wiersz['fav_maps'];
    $new_fav_maps = $fav_maps.$map.",";
    $sql = "UPDATE uzytkownicy SET fav_maps='$new_fav_maps' WHERE login='$login';";
    $stmt = $db->prepare($sql);
    $stmt->execute();
}else{
     $sql = "SELECT * FROM uzytkownicy WHERE login='$login';";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $wiersz = $stmt->fetch(PDO::FETCH_ASSOC);
    $fav_maps = $wiersz['fav_maps'];
    $fav_maps_tab = explode(",",$fav_maps);
    array_pop($fav_maps_tab);
    if(in_array($map, $fav_maps_tab)){
        $index = array_search($map, $fav_maps_tab);
        unset($fav_maps_tab[$index]);
    }
    $new_fav_maps = implode(",",$fav_maps_tab).",";
    if($new_fav_maps == ","){
        $new_fav_maps = "";
    }
    $sql = "UPDATE uzytkownicy SET fav_maps='$new_fav_maps' WHERE login='$login';";
    $stmt = $db->prepare($sql);
    $stmt->execute();
}
?>