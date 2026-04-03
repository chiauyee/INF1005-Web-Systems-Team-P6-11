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
    cover_url VARCHAR(500) DEFAULT NULL,
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
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE
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
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS album_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    album_mbid VARCHAR(200) NOT NULL,
    uploaded_by INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (album_mbid) REFERENCES albums(album_mbid),
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS artist_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    artist_mbid VARCHAR(200) NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (artist_mbid) REFERENCES artists(artist_mbid),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS album_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    album_mbid VARCHAR(200) NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (album_mbid) REFERENCES albums(album_mbid),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS chats (
  id INT AUTO_INCREMENT PRIMARY KEY,
  listing_id INT,
  buyer_id INT,
  seller_id INT,
  FOREIGN KEY (listing_id) REFERENCES listings(listing_id),
  FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
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
  FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
);


/* password for testuser & testuser1 = !TestPW123 */
INSERT IGNORE INTO `users` (id, username, email, password, phone, address, country, latitude, longitude, role, status, created_at) VALUES
(1,'admin','admin@musicmarket.com','$2y$12$9aPVvCPwYYIaMEF/RSSZe.cAz3JfXEvhoB.hB9RHeH0DYqi3uTH6m',NULL,NULL,'SG', NULL, NULL, 'admin','active','2026-03-09 12:12:39'),
(2,'testuser','testuser@gmail.com','$2y$10$gl4/BDzYmdQ1XbcdLLs4/.YjWkCot3/UYEvemfxjkfzHQOR95kdqW','91234567','123 Orchard Road, #05-01, Singapore 238858','SG', 1.30420, 103.83200, 'user','active','2026-03-09 12:17:44'),
(3,'testuser1','testuser1@gmail.com','$2y$10$u5Bb6UdXqQJNiY1us7G3N.b6JKAuRC8Y6zdBo9hF/da3u4NBBoj9i','98765432','456 Jurong West Ave 1, #12-88, Singapore 640456','SG', 1.34040, 103.70500, 'user','active','2026-03-09 12:26:03');

-- Insert artists
INSERT INTO artists (artist_mbid, artist_name) VALUES
('1a19b0cd-fa9d-4363-b130-b3eb394cf373', 'MASS OF THE FERMENTING DREGS'),
('cd689e77-dfdd-4f81-b50c-5e5a3f5e38a4', 'BLADEE'),
('fa58cf24-0e44-421d-8519-8bf461dcfaa5', 'MITSKI');

-- Insert albums
INSERT INTO albums (album_mbid, artist_mbid, album_name, cover_url) VALUES
('a802c9ce-5017-421f-9a16-b18740f677e4', 'cd689e77-dfdd-4f81-b50c-5e5a3f5e38a4', 'ICEDANCER', 'https://coverartarchive.org/release/d98d0f2e-8fc2-421d-951d-7bd0d1565f74/41906829537-250.jpg'),
('aeeba3c1-b0bd-3376-9787-04b1e80b6f85', '1a19b0cd-fa9d-4363-b130-b3eb394cf373', 'MASS OF THE FERMENTING DREGS', 'http://coverartarchive.org/release/6b2f20b8-7600-4950-b390-19fa0da56349/11123417375-250.jpg'),
('de3537cf-8dac-475a-b456-42227e314d7e', 'fa58cf24-0e44-421d-8519-8bf461dcfaa5', 'PUBERTY 2', 'http://coverartarchive.org/release/db82edc7-8166-46da-b471-242c9efaa9e7/15377591762-250.jpg');

-- Insert listings
INSERT INTO listings (listing_id, album_mbid, seller_id, buyer_id, price, status, rejection_reason, stripe_session_id, created_at, purchased_at, approved_at) VALUES
(1, 'de3537cf-8dac-475a-b456-42227e314d7e', 1, NULL, 10.00, 'available', NULL, NULL, '2026-03-29 17:56:24', NULL, '2026-03-29 17:56:32'),
(2, 'a802c9ce-5017-421f-9a16-b18740f677e4', 1, NULL, 10.00, 'available', NULL, NULL, '2026-03-29 17:57:43', NULL, '2026-03-29 17:58:19'),
(3, 'aeeba3c1-b0bd-3376-9787-04b1e80b6f85', 1, NULL, 10.00, 'available', NULL, NULL, '2026-03-29 17:58:12', NULL, '2026-03-29 17:58:19');
