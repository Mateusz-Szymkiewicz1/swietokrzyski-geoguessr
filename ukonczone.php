<?php
require_once "connect.php";
$login = $_GET['login'];
$ukonczone = intval($_GET['ukonczone']);
$sql = "UPDATE uzytkownicy SET ukonczone='$ukonczone' WHERE login='$login';";
$stmt = $db->prepare($sql);
$stmt->execute();
?>