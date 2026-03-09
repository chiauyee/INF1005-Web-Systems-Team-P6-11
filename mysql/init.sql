CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
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
    price FLOAT,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE password_resets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token_hash CHAR(64) NOT NULL,
  expires_at DATETIME NOT NULL,
  used TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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

INSERT IGNORE INTO users (username, email, password, role, status) VALUES
('admin', 'admin@musicmarket.com', '$2y$12$9aPVvCPwYYIaMEF/RSSZe.cAz3JfXEvhoB.hB9RHeH0DYqi3uTH6m', 'admin', 'active');
;
