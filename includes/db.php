<?php
$host = 'localhost' ;
$dbname = 'ecoride' ;
$username = 'root' ;
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username , $password);
    $pdo-> setAttribute (pdo::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOeXCEPTION $e) {
    die ("Erreur de connexion : " .$e->getMessage());
}
?>