CREATE DATABASE IF NOT EXISTS property_station CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE property_station;

CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS projects (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(190) NOT NULL,
  slug VARCHAR(220) NOT NULL UNIQUE,
  category ENUM('Residential','Commercial','Plots','Villa','Other') NOT NULL DEFAULT 'Residential',
  location VARCHAR(190) NOT NULL,
  price VARCHAR(120) DEFAULT 'Price on Request',
  status ENUM('New Launch','Under Construction','Ready to Move','Upcoming') NOT NULL DEFAULT 'New Launch',
  description TEXT,
  amenities TEXT,
  rera_number VARCHAR(120) DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  featured TINYINT(1) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS enquiries (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id INT UNSIGNED NULL,
  name VARCHAR(120) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  email VARCHAR(190) DEFAULT NULL,
  budget VARCHAR(120) DEFAULT NULL,
  message TEXT,
  status ENUM('New','Contacted','Closed') NOT NULL DEFAULT 'New',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_enquiry_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
);

-- Create your first admin password hash with PHP:
-- php -r "echo password_hash('ChangeMe123!', PASSWORD_DEFAULT), PHP_EOL;"
-- Then run the INSERT below using the generated hash.
-- INSERT INTO admins (name,email,password_hash) VALUES ('Administrator','admin@example.com','PASTE_HASH_HERE');
