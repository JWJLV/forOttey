<?php
// db.php

$host = "localhost";
$dbname = "forOttey";
$username = "fishuser";
$password = "yourStrongPassword";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    error_log($e->getMessage());
    die("Service unavailable.");
}
?>
