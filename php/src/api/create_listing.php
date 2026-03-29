<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$artist      = trim($_POST['artist'] ?? '');
$album       = trim($_POST['album'] ?? '');
$price       = $_POST['price'] ?? null;
$artist_mbid = trim($_POST['artist_mbid'] ?? '');
$album_mbid  = trim($_POST['album_mbid'] ?? '');
$cached      = isset($_POST['cached']) && $_POST['cached'] === 'on';

if (!$artist || !$album || !$artist_mbid || !$album_mbid || $price === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$price = filter_var($price, FILTER_VALIDATE_FLOAT);
if ($price === false || $price < 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid price']);
    exit;
}

try {
    $pdo->beginTransaction();

    if (!$cached) {
        $stmt = $pdo->prepare("
            INSERT INTO artists (artist_mbid, artist_name)
            VALUES (:artist_mbid, :artist_name)
            ON DUPLICATE KEY UPDATE artist_name = VALUES(artist_name)
        ");
        $stmt->execute([
            ':artist_mbid' => $artist_mbid,
            ':artist_name' => $artist,
        ]);

        // Fetch cover art from Cover Art Archive using the release-group MBID.
        // CAA is free, requires no API key, and uses the same MBIDs stored in the DB.
        $cover_url = null;
        $caa_url   = "https://coverartarchive.org/release-group/" . urlencode($album_mbid);
        $caa_opts  = [
            "http" => [
                "header"        => "User-Agent: university-project/1.0 (qqqdyz@gmail.com)\r\nAccept: application/json\r\n",
                "timeout"       => 5,
                "ignore_errors" => true,
            ]
        ];
        $caa_ctx      = stream_context_create($caa_opts);
        $caa_response = @file_get_contents($caa_url, false, $caa_ctx);
        if ($caa_response) {
            $caa_data = json_decode($caa_response, true);
            if (!empty($caa_data['images'])) {
                // Prefer the image flagged as front; fall back to first available
                $front = null;
                foreach ($caa_data['images'] as $img) {
                    if (!empty($img['front'])) { $front = $img; break; }
                }
                if (!$front) $front = $caa_data['images'][0];
                // Use 250px thumbnail for card display; fall back to full image
                $cover_url = $front['thumbnails']['250']
                          ?? $front['thumbnails']['small']
                          ?? $front['image']
                          ?? null;
            }
        }

        $stmt = $pdo->prepare("
            INSERT INTO albums (album_mbid, artist_mbid, album_name, cover_url)
            VALUES (:album_mbid, :artist_mbid, :album_name, :cover_url)
            ON DUPLICATE KEY UPDATE
                album_name = VALUES(album_name),
                cover_url  = COALESCE(VALUES(cover_url), cover_url)
        ");
        $stmt->execute([
            ':album_mbid'  => $album_mbid,
            ':artist_mbid' => $artist_mbid,
            ':album_name'  => $album,
            ':cover_url'   => $cover_url,
        ]);
    }

    $stmt = $pdo->prepare("
        INSERT INTO listings (album_mbid, seller_id, price)
        VALUES (:album_mbid, :seller_id, :price)
    ");

    $stmt->execute([
        ':album_mbid' => $album_mbid,
        ':seller_id'  => $_SESSION['user_id'],
        ':price'      => $price
    ]);

    $pdo->commit();

    header('Location: /listings.php');
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}
