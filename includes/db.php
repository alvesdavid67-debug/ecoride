<?php
$host = 'sql312.infinityfree.com';
$dbname = 'if0_41974956_ecoride';
$username = 'if0_41974956';
$password = '44884884DAv';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>