<?php 

header('Content-Type: application/json'); 
require 'db.php'; 
$method = $_SERVER['REQUEST_METHOD']; 
$fish_id = intval($_GET['fish_id'] ?? 0); 


if ($fish_id > 0) { 
    // Single fish lookup 
    $stmt = 
    $pdo->prepare( 
    "SELECT f.fish_id, f.common_name, f.scientific_name, s.sighting_id,
     s.sighting_date, s.location, s.notes, p.photo_id, p.filepath, p.caption
     FROM fish_entries f
     LEFT JOIN sightings s
     ON f.fish_id = s.fish_id
     LEFT JOIN photos p
     ON s.sighting_id = p.sighting_id
     WHERE f.fish_id = ?
     ORDER BY s.sighting_date DESC");
    $stmt->execute([$fish_id]); 
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)); 
}

else 
{ // All fish entries
     
    $stmt = $pdo->query( "SELECT f.fish_id, f.common_name, f.scientific_name, f.habitat, f.aphia_id, f.date_added,
                          COUNT(DISTINCT s.sighting_id) AS sighting_count, COUNT(DISTINCT p.photo_id) AS photo_count
                          FROM fish_entries f
                          LEFT JOIN sightings s
                          ON f.fish_id = s.fish_id
                          LEFT JOIN photos p
                          ON s.sighting_id = p.sighting_id
                          GROUP BY f.fish_id
                          ORDER BY common_name DESC" ); 
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)); 
}




    
