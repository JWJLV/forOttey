<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=forOttey", "fishuser", "StrongPass123!");
    echo "DB CONNECT OK";
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
