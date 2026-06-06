<?php

header('Content-Type: application/json');
require 'db.php';

try {
    $stmt = $pdo->query(
        "SELECT
            p.photo_id,
            p.filepath,
            p.caption,
            f.fish_id,
            f.common_name,
            f.scientific_name,
            f.habitat,
            f.description,
            s.sighting_id,
            s.sighting_date,
            s.location,
            s.notes
         FROM photos p
         JOIN fish_entries f  ON p.fish_id     = f.fish_id
         JOIN sightings    s  ON p.sighting_id = s.sighting_id
         ORDER BY s.sighting_date DESC, p.photo_id ASC"
    );

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}