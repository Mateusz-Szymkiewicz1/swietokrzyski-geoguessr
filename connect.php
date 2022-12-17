<?php
$error = 0;
try {
    $db = new PDO("mysql:host=localhost;dbname=ziogeser", "root", "",array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
  ));
}
catch(PDOException $e) {
    echo '<div class="error"> Błąd '.$e->getCode().' '." - Nie udało się połączyć z bazą".'</div>';
    $error = 1;
}
?>