<?php
session_start();
require '../db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$search       = trim($_GET['search'] ?? '');
$artist_mbid  = trim($_GET['artist_mbid'] ?? '');
$album_mbid   = trim($_GET['album_mbid'] ?? '');
$location = $_GET['location'] ?? '';
$userLat = $_GET['lat'] ?? null;
$userLon = $_GET['lon'] ?? null;

$hasUserLocation = $userLat !== null && $userLon !== null && is_numeric($userLat) && is_numeric($userLon);

try {
    $params = [];
    $where  = ["l.status = 'available'"];

    if ($artist_mbid) {
        $where[]  = 'ar.artist_mbid = ?';
        $params[] = $artist_mbid;
    }

    if ($album_mbid) {
        $where[]  = 'al.album_mbid = ?';
        $params[] = $album_mbid;
    }

    if ($search) {
        $where[]  = '(al.album_name LIKE ? OR ar.artist_name LIKE ? OR u.username LIKE ?)';
        $like     = '%' . $search . '%';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    if ($location) {
        $where[]  = '(u.country LIKE ? OR u.address LIKE ?)';
        $like     = '%' . $location . '%';
        $params[] = $like;
        $params[] = $like;
    }

    $where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $selectFields = "
        l.listing_id,
        l.created_at,
        l.price,
        al.album_name,
        al.album_mbid,
        ar.artist_name,
        ar.artist_mbid,
        u.username AS seller,
        u.country,
        u.address,
        u.latitude AS seller_latitude,
        u.longitude AS seller_longitude
    ";

    if (isset($_SESSION['user_id'])) {
        $user_id = (int) $_SESSION['user_id'];
        $selectFields .= ", (SELECT COUNT(*) FROM wishlist w WHERE w.user_id = $user_id AND w.album_mbid = al.album_mbid) > 0 AS is_wishlisted";
    } else {
        $selectFields .= ", 0 AS is_wishlisted";
    }

    if ($hasUserLocation) {
        // Haversine formula - all 3 parameters are the user's location
        $selectFields .= ",
            (6371 * acos(
            GREATEST(-1, LEAST(1, 
            cos(radians(?)) * cos(radians(u.latitude)) * 
            cos(radians(u.longitude) - radians(?)) + 
            sin(radians(?)) * sin(radians(u.latitude))
            ))
            )
            ) AS distance_km
        ";
        
        // Add the user's coordinates 3 times (in order: lat, lon, lat)
        array_unshift($params, $userLat, $userLon, $userLat);
    }

    if ($hasUserLocation) {
        $orderBy = "ORDER BY distance_km ASC";
    } else {
        $orderBy = "ORDER BY l.created_at DESC";
    }

    $stmt = $pdo->prepare("
        SELECT
            $selectFields
        FROM listings l
        JOIN albums al  ON l.album_mbid  = al.album_mbid
        JOIN artists ar ON al.artist_mbid = ar.artist_mbid
        JOIN users u    ON l.seller_id   = u.id
        $where_sql
        $orderBy
    ");
    
    $stmt->execute($params);
    $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($hasUserLocation) {
        foreach ($listings as &$listing) {
            if (isset($listing['distance_km'])) {
                $listing['distance_km'] = round($listing['distance_km'], 2);
            }
        }
    }

    echo json_encode(['status' => 'ok', 'data' => $listings]);
} 
catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>