<?php
header('Content-Type: application/json');
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

$sighting_date   = trim($_POST['sighting_date']   ?? '');
$location        = trim($_POST['location']         ?? '');
$notes           = trim($_POST['notes']            ?? '');
$aphia_id = !empty($_POST['aphia_id']) ? intval($_POST['aphia_id']) : null;
$scientific_name = trim($_POST['scientific_name']  ?? '');
$common_name     = trim($_POST['common_name']      ?? '');
$habitat         = trim($_POST['habitat']          ?? '');
$description     = trim($_POST['description']      ?? '');

if (!$sighting_date) {
    http_response_code(400);
    echo json_encode(['error' => 'Sighting date is required.']);
    exit;
}
if (!$scientific_name) {
    http_response_code(400);
    echo json_encode(['error' => 'A valid species selection is required.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // ── 1. Find or create fish entry (keyed on aphia_id) ─────────────────────
    // NOTE: add aphia_id column first if it doesn't exist:
    // ALTER TABLE fish_entries ADD COLUMN aphia_id INT DEFAULT NULL UNIQUE;

    $existing = false;
    if ($aphia_id) {
        $stmt = $pdo->prepare("SELECT fish_id FROM fish_entries WHERE aphia_id = ? LIMIT 1");
        $stmt->execute([$aphia_id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($existing) {
        $fish_id = (int) $existing['fish_id'];
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO fish_entries
             (aphia_id, common_name, scientific_name, habitat, description)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $aphia_id,
            $common_name  ?: null,
            $scientific_name,
            $habitat      ?: null,
            $description  ?: null,
        ]);
        $fish_id = (int) $pdo->lastInsertId();
    }

    // ── 2. Insert sighting ────────────────────────────────────────────────────
    $stmt = $pdo->prepare(
        "INSERT INTO sightings (fish_id, sighting_date, location, notes)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$fish_id, $sighting_date, $location ?: null, $notes ?: null]);
    $sighting_id = (int) $pdo->lastInsertId();

    // ── 3. Upload photos ──────────────────────────────────────────────────────
    $upload_dir    = __DIR__ . '/uploads/';
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    $photos   = $_FILES['photos']  ?? [];
    $captions = $_POST['captions'] ?? [];

    if (!empty($photos['name'][0])) {
        $count = count($photos['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($photos['error'][$i] !== UPLOAD_ERR_OK) continue;
            $mime = mime_content_type($photos['tmp_name'][$i]);
            if (!in_array($mime, $allowed_types)) continue;
            $ext      = strtolower(pathinfo($photos['name'][$i], PATHINFO_EXTENSION));
            $filename = uniqid('photo_', true) . '.' . $ext;
            $filepath = '/uploads/' . $filename;
            move_uploaded_file($photos['tmp_name'][$i], $upload_dir . $filename);
            $caption = trim($captions[$i] ?? '');
            $stmt = $pdo->prepare(
                "INSERT INTO photos (fish_id, sighting_id, filename, filepath, caption)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$fish_id, $sighting_id, $filename, $filepath, $caption ?: null]);
        }
    }

    $pdo->commit();

    echo json_encode([
        'success'    => true,
        'fish_id'    => $fish_id,
        'sighting_id'=> $sighting_id,
        'new_fish'   => !$existing,
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
