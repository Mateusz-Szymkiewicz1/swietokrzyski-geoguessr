<?php
require_once "connect.php";
$login = $_GET['login'];
$ukonczone = intval($_GET['ukonczone']);
$wynik = intval($_GET['wynik']);
$sql = "UPDATE uzytkownicy SET ukonczone='$ukonczone' WHERE login='$login';";
$stmt = $db->prepare($sql);
$stmt->execute();

$sql = "SELECT * FROM uzytkownicy WHERE login='$login';";
$stmt = $db->prepare($sql);
$stmt->execute();
$wiersz = $stmt->fetch(PDO::FETCH_ASSOC);
$max_score = $wiersz['max_score'];
$sum_score = $wiersz['sum_score'];
$sum_score_new = $sum_score+$wynik;

$sql = "UPDATE uzytkownicy SET sum_score='$sum_score_new' WHERE login='$login';";
$stmt = $db->prepare($sql);
$stmt->execute();

if($wynik > $max_score){
  $sql = "UPDATE uzytkownicy SET max_score='$wynik' WHERE login='$login';";
  $stmt = $db->prepare($sql);
  $stmt->execute();
}
?>
