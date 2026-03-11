<?php
require '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

$artist = trim($data['artist']);
$album  = trim($data['album']);

if (!$artist || !$album) {
    echo json_encode(["status" => "error"]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT 
        artists.artist_name,
        artists.artist_mbid,
        albums.album_name,
        albums.album_mbid
    FROM albums
    JOIN artists 
        ON albums.artist_mbid = artists.artist_mbid
    WHERE artists.artist_name LIKE ?
    AND albums.album_name LIKE ?
    ");

$stmt->execute([$artist, $album]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
  echo json_encode([
    "status" => "found_db",
    "data" => $row
  ]);
  exit;
}

// musicbrainz bullshit
$query = urlencode("artist:$artist AND release:$album");
$url = "https://musicbrainz.org/ws/2/release/?query=$query&fmt=json";

$options = [
    "http" => [
        "header" => "User-Agent: university-project/1.0 (qqqdyz@gmail.com.com)"
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response) {
    $mbData = json_decode($response, true);

    if (!empty($mbData['releases'])) {
        echo json_encode([
            "status" => "found_musicbrainz",
            "data" => $mbData['releases'][0]
        ]);
        exit;
    }
}

echo json_encode(["status" => "not_found"]);
