<?php
// db.php

$host = "localhost";
$dbname = "forOttey";
$username = "fishuser";
$password = "StrongPass123!";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} } catch (PDOException $e) {
    die(json_encode(['error' => $e->getMessage()]));  // temporary — remove after fixing!
}
?>
