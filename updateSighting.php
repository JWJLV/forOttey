<?php
header('Content-Type: application/json');
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

$sighting_id   = intval($_POST['sighting_id'] ?? 0);
$sighting_date = trim($_POST['sighting_date'] ?? '');
$location      = trim($_POST['location'] ?? '') ?: null;
$notes         = trim($_POST['notes'] ?? '') ?: null;

if (!$sighting_id || !$sighting_date) {
    http_response_code(400);
    echo json_encode(['error' => 'Sighting ID and date are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "UPDATE sightings SET sighting_date=?, location=?, notes=? WHERE sighting_id=?"
    );
    $stmt->execute([$sighting_date, $location, $notes, $sighting_id]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}