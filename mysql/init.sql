CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    country VARCHAR(5) DEFAULT 'SG',
    latitude DECIMAL(10,8) DEFAULT NULL,
    longitude DECIMAL(11,8) DEFAULT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    status ENUM('active', 'banned') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS artists (
    artist_mbid VARCHAR(200) PRIMARY KEY,
    artist_name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS albums (
    album_mbid VARCHAR(200) PRIMARY KEY,
    artist_mbid VARCHAR(200) NOT NULL,
    album_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (artist_mbid) REFERENCES artists(artist_mbid)
);

CREATE TABLE IF NOT EXISTS listings (
    listing_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    album_mbid VARCHAR(200),
    seller_id INT,
    buyer_id INT DEFAULT NULL,
    price DECIMAL(10,2),
    status ENUM('pending', 'rejected', 'available', 'complete') NOT NULL DEFAULT 'pending',
    rejection_reason VARCHAR(255) DEFAULT NULL,
    stripe_session_id VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    purchased_at TIMESTAMP DEFAULT NULL,
    approved_at DATETIME DEFAULT NULL,
    FOREIGN KEY (album_mbid) REFERENCES albums(album_mbid),
    FOREIGN KEY (seller_id) REFERENCES users(id),
    FOREIGN KEY (buyer_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    used TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS password_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_created (user_id, created_at)
);

CREATE TABLE IF NOT EXISTS rate_limits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifier VARCHAR(255) NOT NULL, 
    action VARCHAR(50) NOT NULL,
    attempts INT DEFAULT 1,
    first_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_identifier_action (identifier, action),
    INDEX idx_last_attempt (last_attempt)
);

CREATE TABLE IF NOT EXISTS artist_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    artist_mbid VARCHAR(200) NOT NULL,
    uploaded_by INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (artist_mbid) REFERENCES artists(artist_mbid),
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS album_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    album_mbid VARCHAR(200) NOT NULL,
    uploaded_by INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (album_mbid) REFERENCES albums(album_mbid),
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS artist_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    artist_mbid VARCHAR(200) NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (artist_mbid) REFERENCES artists(artist_mbid),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS album_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    album_mbid VARCHAR(200) NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (album_mbid) REFERENCES albums(album_mbid),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS chats (
  id INT AUTO_INCREMENT PRIMARY KEY,
  listing_id INT,
  buyer_id INT,
  seller_id INT,
  FOREIGN KEY (listing_id) REFERENCES listings(listing_id),
  FOREIGN KEY (buyer_id) REFERENCES users(id),
  FOREIGN KEY (seller_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    album_mbid VARCHAR(200) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_album (user_id, album_mbid),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (album_mbid) REFERENCES albums(album_mbid) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  chat_id INT,
  sender_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  content TEXT NOT NULL,
  FOREIGN KEY (sender_id) REFERENCES users(id)
);


/* password for testuser & testuser1 = !TestPW123 */
INSERT IGNORE INTO `users` (id, username, email, password, phone, address, country, latitude, longitude, role, status, created_at) VALUES
(1,'admin','admin@musicmarket.com','$2y$12$9aPVvCPwYYIaMEF/RSSZe.cAz3JfXEvhoB.hB9RHeH0DYqi3uTH6m',NULL,NULL,'SG', NULL, NULL, 'admin','active','2026-03-09 12:12:39'),
(2,'testuser','testuser@gmail.com','$2y$10$gl4/BDzYmdQ1XbcdLLs4/.YjWkCot3/UYEvemfxjkfzHQOR95kdqW','91234567','123 Orchard Road, #05-01, Singapore 238858','SG', 1.30420, 103.83200, 'user','active','2026-03-09 12:17:44'),
(3,'testuser1','testuser1@gmail.com','$2y$10$u5Bb6UdXqQJNiY1us7G3N.b6JKAuRC8Y6zdBo9hF/da3u4NBBoj9i','98765432','456 Jurong West Ave 1, #12-88, Singapore 640456','SG', 1.34040, 103.70500, 'user','active','2026-03-09 12:26:03');

/* 
some data below displayed in 
listings.php & especially index.php 
else the front page will look empty 
*/ 
INSERT INTO `artists` VALUES 
('0103c1cc-4a09-4a5d-a344-56ad99a77193','AVRIL LAVIGNE'),
('197450cd-0124-4164-b723-3c22dd16494d','FRANK SINATRA'),
('381086ea-f511-4aba-bdf9-71c753dc5077','KENDRICK LAMAR'),
('4e4ebde4-0c56-4dec-844b-6c73adcdd92d','JUICE WRLD'),
('5cbef01b-cc35-4f52-af7b-d0df0c4f61b9','SONIC YOUTH'),
('5f000e69-3cfd-4871-8f1b-faa7f0d4bcbc','WESTLIFE'),
('61af87f4-16ee-4431-8504-cc06187079fb','XXXTENTACION');

INSERT INTO `albums` VALUES 
('0CA732BA-1796-3592-AD4D-86B21C1D966A','197450cd-0124-4164-b723-3c22dd16494d','MY WAY'),
('1395477C-A71E-4058-A893-D33DFCAD6A4B','61af87f4-16ee-4431-8504-cc06187079fb','17'),
('16AE3FA2-C7DA-4CC4-A92D-A0D23546172A','0103c1cc-4a09-4a5d-a344-56ad99a77193','GOODBYE LULLABY'),
('3466B3D4-2AEA-49E1-8769-7CD1E98092A8','5f000e69-3cfd-4871-8f1b-faa7f0d4bcbc','COAST TO COAST'),
('9CAA4160-4F12-4379-92E0-CA81EC6AD64B','5cbef01b-cc35-4f52-af7b-d0df0c4f61b9','DAYDREAM NATION'),
('B54CC188-AC86-4821-95D5-FA32841DFAF1','4e4ebde4-0c56-4dec-844b-6c73adcdd92d','GOODBYE & GOOD RIDDANCE'),
('FE7E674A-C44C-4B73-AD5C-C19BE212B7B4','381086ea-f511-4aba-bdf9-71c753dc5077','GOOD KID, M.A.A.D CITY');

INSERT INTO `listings` (listing_id, album_mbid, seller_id, buyer_id, price, status, rejection_reason, stripe_session_id, created_at, purchased_at, approved_at) VALUES
-- Available listings (admin approved, visible to buyers)
(10,'1395477C-A71E-4058-A893-D33DFCAD6A4B',2,NULL,39.00,'available',NULL,NULL,'2026-03-10 17:09:14',NULL,'2026-03-11 09:42:00'),
(11,'B54CC188-AC86-4821-95D5-FA32841DFAF1',2,NULL,999.00,'available',NULL,NULL,'2026-03-10 17:09:53',NULL,'2026-03-12 14:17:00'),
(12,'3466B3D4-2AEA-49E1-8769-7CD1E98092A8',2,NULL,79.00,'available',NULL,NULL,'2026-03-10 17:10:11',NULL,'2026-03-13 10:05:00'),
(16,'0CA732BA-1796-3592-AD4D-86B21C1D966A',3,NULL,405.00,'available',NULL,NULL,'2026-03-10 17:26:55',NULL,'2026-03-11 11:30:00'),
-- Pending listings (awaiting admin approval)
(15,'16AE3FA2-C7DA-4CC4-A92D-A0D23546172A',2,NULL,77.00,'pending',NULL,NULL,'2026-03-10 17:18:49',NULL,NULL),
(17,'9CAA4160-4F12-4379-92E0-CA81EC6AD64B',3,NULL,215.00,'pending',NULL,NULL,'2026-03-14 09:15:00',NULL,NULL),
-- Rejected listings (admin rejected with reason)
(14,'9CAA4160-4F12-4379-92E0-CA81EC6AD64B',2,NULL,128.00,'rejected','Listing price is significantly above market value. Please revise.',NULL,'2026-03-10 17:17:55',NULL,NULL),
(18,'FE7E674A-C44C-4B73-AD5C-C19BE212B7B4',3,NULL,50.00,'rejected','Item description is incomplete. Please provide more details on the condition of the record.',NULL,'2026-03-15 14:22:00',NULL,NULL),
-- Complete listings (sold — buyer, stripe session, and timestamps all set)
(13,'FE7E674A-C44C-4B73-AD5C-C19BE212B7B4',2,3,138.00,'complete',NULL,'cs_test_seller2_to_buyer3_listing13','2026-03-10 17:11:36','2026-03-20 13:45:00','2026-03-12 08:30:00'),
(19,'16AE3FA2-C7DA-4CC4-A92D-A0D23546172A',3,2,120.00,'complete',NULL,'cs_test_seller3_to_buyer2_listing19','2026-03-16 10:00:00','2026-03-25 18:10:00','2026-03-17 09:00:00');