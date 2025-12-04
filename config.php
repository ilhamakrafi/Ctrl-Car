<?php
// config.php : PDO 

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

$dsn = 'mysql:host=localhost;dbname=covoiturage;charset=utf8';
$username = 'root';
$password = 'root';

try {
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base : ' . $e->getMessage());
}
?>