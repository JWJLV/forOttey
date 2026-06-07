<?php
header('Content-Type: application/json');
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

$sighting_id = intval($_POST['sighting_id'] ?? 0);
if (!$sighting_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid sighting ID.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Get photo filenames before deleting
    $stmt = $pdo->prepare("SELECT filename FROM photos WHERE sighting_id = ?");
    $stmt->execute([$sighting_id]);
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Delete photos and sighting
    $pdo->prepare("DELETE FROM photos WHERE sighting_id = ?")->execute([$sighting_id]);
    $pdo->prepare("DELETE FROM sightings WHERE sighting_id = ?")->execute([$sighting_id]);

    $pdo->commit();

    // Delete photo files from disk
    foreach ($photos as $photo) {
        $path = __DIR__ . '/uploads/' . $photo['filename'];
        if (file_exists($path)) unlink($path);
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}