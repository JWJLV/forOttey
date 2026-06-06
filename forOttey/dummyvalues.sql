INSERT INTO fish_entries (
    fish_id,
    common_name,
    scientific_name,
    description
)
VALUES
(1, 'Clownfish', 'Amphiprion ocellaris', 'Small orange reef fish commonly associated with sea anemones.'),
(2, 'Blue Tang', 'Paracanthurus hepatus', 'Bright blue reef fish with a yellow tail.'),
(3, 'Lionfish', 'Pterois volitans', 'Venomous fish with distinctive striped fins.'),
(4, 'Great White Shark', 'Carcharodon carcharias', 'Large predatory shark found in coastal waters worldwide.'),
(5, 'Moorish Idol', 'Zanclus cornutus', 'Tropical reef fish with long dorsal streamer.'),
(6, 'Mandarinfish', 'Synchiropus splendidus', 'Colorful reef fish known for intricate patterns.'),
(7, 'Yellow Tang', 'Zebrasoma flavescens', 'Bright yellow surgeonfish common on Pacific reefs.'),
(8, 'Whale Shark', 'Rhincodon typus', 'Largest living fish species and a filter feeder.');


INSERT INTO sightings (
    sighting_id,
    fish_id,
    sighting_date,
    location,
    notes
)
VALUES
(1, 1, '2026-05-28 09:15:00', 'Great Barrier Reef', 'Pair observed near anemone.'),
(2, 2, '2026-05-27 11:40:00', 'Coral Bay', 'Swimming alongside reef edge.'),
(3, 3, '2026-05-25 14:10:00', 'Shipwreck Reef', 'Hidden beneath wreck structure.'),
(4, 4, '2026-05-20 08:30:00', 'False Bay', 'Observed from dive cage.'),
(5, 1, '2026-05-15 10:05:00', 'Great Barrier Reef', 'Juvenile clownfish.'),
(6, 5, '2026-05-14 13:20:00', 'Bora Bora', 'Single adult specimen.'),
(7, 6, '2026-05-10 15:45:00', 'Coral Garden', 'Seen during dusk dive.'),
(8, 7, '2026-05-08 09:50:00', 'Maui Reef', 'Large school feeding.'),
(9, 8, '2026-05-05 12:15:00', 'Ningaloo Reef', 'Slow-moving individual.'),
(10, 2, '2026-04-29 11:25:00', 'Coral Bay', 'Group of three.'),
(11, 3, '2026-04-18 16:10:00', 'Shipwreck Reef', 'Resting beneath ledge.'),
(12, 1, '2026-04-10 10:30:00', 'Great Barrier Reef', 'Anemone colony with multiple fish.'); 


INSERT INTO photos (
    photo_id,
    sighting_id,
    fish_id,
    filepath,
    caption
)
VALUES
(1, 1, 1, '/uploads/clownfish_01.jpg', 'Adult clownfish in anemone'),
(2, 1, 1, '/uploads/clownfish_02.jpg', 'Close-up portrait'),
(3, 2, 2, '/uploads/bluetang_01.jpg', 'Blue tang near coral wall'),
(4, 3, 3, '/uploads/lionfish_01.jpg', 'Lionfish under wreck'),
(5, 4, 4, '/uploads/greatwhite_01.jpg', 'Great white approaching cage'),
(6, 5, 1, '/uploads/clownfish_03.jpg', 'Juvenile clownfish'),
(7, 6, 5, '/uploads/moorishidol_01.jpg', 'Moorish idol profile'),
(8, 7, 6, '/uploads/mandarinfish_01.jpg', 'Mandarinfish at dusk'),
(9, 8, 7, '/uploads/yellowtang_01.jpg', 'Yellow tang school'),
(10, 9, 8, '/uploads/whaleshark_01.jpg', 'Whale shark overhead'),
(11, 10, 2, '/uploads/bluetang_02.jpg', 'Blue tang group'),
(12, 11, 3, '/uploads/lionfish_02.jpg', 'Lionfish beneath ledge'),
(13, 12, 1, '/uploads/clownfish_04.jpg', 'Clownfish colony'),
(14, 12, 1, '/uploads/clownfish_05.jpg', 'Wide reef shot');