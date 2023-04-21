<?php
require_once "connect.php";
$login = $_GET['login'];
$type = $_GET['type'];
if($type == "max"){
  $cs_max = intval($_GET['cs_max']);
  $sql = "UPDATE uzytkownicy SET cs_max='$cs_max' WHERE login='$login';";
  $stmt = $db->prepare($sql);
  $stmt->execute();
}
if($type == "sum"){
  $cs_sum = intval($_GET['cs_sum']); 
  $sql = "UPDATE uzytkownicy SET cs_sum='$cs_sum' WHERE login='$login';";
  $stmt = $db->prepare($sql);
  $stmt->execute();
}
?>
