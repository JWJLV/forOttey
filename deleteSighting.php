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

    // Get fish_id and photo filenames before deleting
    $stmt = $pdo->prepare("SELECT fish_id FROM sightings WHERE sighting_id = ?");
    $stmt->execute([$sighting_id]);
    $sighting = $stmt->fetch(PDO::FETCH_ASSOC);
    $fish_id = $sighting ? (int)$sighting['fish_id'] : null;

    $stmt = $pdo->prepare("SELECT filename FROM photos WHERE sighting_id = ?");
    $stmt->execute([$sighting_id]);
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Delete photos and sighting
    $pdo->prepare("DELETE FROM photos WHERE sighting_id = ?")->execute([$sighting_id]);
    $pdo->prepare("DELETE FROM sightings WHERE sighting_id = ?")->execute([$sighting_id]);

    // If no sightings remain for this fish, delete the fish entry too
    $fish_deleted = false;
    if ($fish_id) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM sightings WHERE fish_id = ?");
        $stmt->execute([$fish_id]);
        if ((int)$stmt->fetchColumn() === 0) {
            $pdo->prepare("DELETE FROM photos WHERE fish_id = ?")->execute([$fish_id]);
            $pdo->prepare("DELETE FROM fish_entries WHERE fish_id = ?")->execute([$fish_id]);
            $fish_deleted = true;
        }
    }

    $pdo->commit();

    // Delete photo files from disk
    foreach ($photos as $photo) {
        $path = __DIR__ . '/uploads/' . $photo['filename'];
        if (file_exists($path)) unlink($path);
    }

    echo json_encode(['success' => true, 'fish_deleted' => $fish_deleted]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}