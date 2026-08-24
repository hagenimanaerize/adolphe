-- Adolphe Civil Engineering - Complete MySQL Database
-- PHP 8+ / MySQL 8 / MariaDB compatible
-- Location: Muganza, Rwanda
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

CREATE DATABASE IF NOT EXISTS `adolphe_engineering` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `adolphe_engineering`;

-- Admins
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(191) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Projects
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT NOT NULL,
  `category` ENUM('Residential','Commercial','Roads','Bridges','Infrastructure','Structural','Other') NOT NULL DEFAULT 'Residential',
  `location` VARCHAR(255) DEFAULT 'Muganza, Rwanda',
  `start_date` DATE DEFAULT NULL,
  `completion_date` DATE DEFAULT NULL,
  `status` ENUM('Ongoing','Completed','Planned') NOT NULL DEFAULT 'Completed',
  `main_image` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`category`), INDEX (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Project Images
DROP TABLE IF EXISTS `project_images`;
CREATE TABLE `project_images` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `project_id` INT UNSIGNED NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_project_images_project` FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Services
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `icon` VARCHAR(100) DEFAULT 'fa-helmet-safety',
  `image` VARCHAR(255) DEFAULT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Gallery
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) DEFAULT NULL,
  `image` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) DEFAULT NULL,
  `category` VARCHAR(100) DEFAULT 'General',
  `project_id` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_gallery_project` FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Testimonials
DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `client_name` VARCHAR(255) NOT NULL,
  `client_photo` VARCHAR(255) DEFAULT NULL,
  `review` TEXT NOT NULL,
  `rating` TINYINT UNSIGNED NOT NULL DEFAULT 5 CHECK (rating BETWEEN 1 AND 5),
  `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Messages
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `project_type` VARCHAR(100) DEFAULT NULL,
  `location` VARCHAR(255) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('unread','read') NOT NULL DEFAULT 'unread',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Site Settings (key-value)
DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Activity Logs
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `admin_id` INT UNSIGNED DEFAULT NULL,
  `action` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_logs_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- About (single row editable content)
DROP TABLE IF EXISTS `about`;
CREATE TABLE `about` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `engineer_name` VARCHAR(255) NOT NULL DEFAULT 'Adolphe',
  `title` VARCHAR(255) NOT NULL DEFAULT 'Civil Engineer',
  `bio` TEXT,
  `experience` TEXT,
  `skills` TEXT,
  `philosophy` TEXT,
  `location` VARCHAR(255) DEFAULT 'Muganza, Rwanda',
  `profile_image` VARCHAR(255) DEFAULT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Stats for homepage
DROP TABLE IF EXISTS `site_stats`;
CREATE TABLE `site_stats` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `stat_key` VARCHAR(50) NOT NULL UNIQUE,
  `stat_value` INT NOT NULL DEFAULT 0,
  `stat_label` VARCHAR(100) NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Admin (password: Admin@123) -> hash generated via password_hash('Admin@123', PASSWORD_DEFAULT)
INSERT INTO `admins` (`username`,`email`,`password`) VALUES
('adolphe','admin@adolphe.rw','$2y$10$T5gWaTYK.IW7xIodVchNhetEI4RavdwMb361FNY4t8FwMghGga8wS');

INSERT INTO `about` (`engineer_name`,`title`,`bio`,`experience`,`skills`,`philosophy`,`location`) VALUES
('Adolphe','Civil Engineer & Structural Designer',
'Adolphe is a dedicated civil engineer based in Muganza, Rwanda, specializing in structural design, construction supervision and infrastructure solutions. With a focus on precision, safety and sustainable construction, he delivers projects that stand the test of time.',
'10+ years experience in structural engineering, site supervision, and project management across residential, commercial and infrastructure projects in Rwanda.',
'Structural Analysis, Reinforced Concrete Design, Steel Structures, AutoCAD, Revit, Quantity Surveying, Project Management, Site Supervision',
'Building with precision, integrity and innovation — every structure is designed for safety, functionality and lasting value for the community.',
'Muganza, Rwanda');

INSERT INTO `site_stats` (`stat_key`,`stat_value`,`stat_label`) VALUES
('projects',120,'Projects Completed'),
('experience',10,'Years Experience'),
('services',8,'Services Offered'),
('clients',85,'Happy Clients');

INSERT INTO `site_settings` (`setting_key`,`setting_value`) VALUES
('site_name','Adolphe Civil Engineering'),
('tagline','Engineering the Future. Building with Precision.'),
('hero_title','ADOLPHE CIVIL ENGINEERING'),
('hero_subtitle','Engineering the Future. Building with Precision.'),
('hero_description','Professional civil engineering, structural design, construction supervision and infrastructure solutions based in Muganza, Rwanda.'),
('phone','+250 788 000 000'),
('email','info@adolphe.rw'),
('whatsapp','+250788000000'),
('location','Muganza, Rwanda'),
('working_hours','Mon - Sat: 08:00 - 18:00'),
('facebook','https://facebook.com'),
('instagram','https://instagram.com'),
('linkedin','https://linkedin.com'),
('footer_text','© 2026 Adolphe Civil Engineering, Muganza Rwanda. Engineering the Future. Building with Precision.'),
('map_embed',''),
('logo',''),
('favicon','');

INSERT INTO `services` (`title`,`description`,`icon`,`status`,`sort_order`) VALUES
('Structural Engineering','Comprehensive structural analysis and design for safe, efficient and code-compliant buildings and infrastructure.','fa-building-columns',1,1),
('Building Design','Architectural and structural building designs tailored to client needs, site conditions and budget.','fa-drafting-compass',1,2),
('Construction Supervision','On-site supervision ensuring quality, safety and adherence to design specifications throughout construction.','fa-helmet-safety',1,3),
('Road & Infrastructure','Design and supervision of roads, drainage, and rural infrastructure for resilient connectivity.','fa-road',1,4),
('Quantity & Cost Estimation','Accurate bill of quantities and cost estimates for informed budgeting and tendering.','fa-calculator',1,5),
('Site Inspection','Thorough site inspections and technical reports for compliance and quality assurance.','fa-magnifying-glass',1,6),
('Project Management','End-to-end project planning, coordination and delivery on time and within budget.','fa-diagram-project',1,7),
('Engineering Consultation','Expert advice on feasibility, structural integrity, materials and construction best practices.','fa-comments',1,8);

INSERT INTO `projects` (`title`,`slug`,`description`,`category`,`location`,`status`) VALUES
('Modern Residential Villa - Muganza','modern-residential-villa-muganza','A contemporary two-storey residential villa designed with reinforced concrete frame, optimized natural lighting and sustainable materials. Full structural design and construction supervision.','Residential','Muganza, Rwanda','Completed'),
('Muganza Commercial Complex','muganza-commercial-complex','Multi-use commercial complex comprising retail and office spaces with robust structural systems and efficient space planning.','Commercial','Muganza, Rwanda','Ongoing'),
('Rural Access Road Upgrade','rural-access-road-upgrade','Upgrading of rural access roads including drainage design, pavement layers and quality supervision for all-weather connectivity.','Roads','Muganza District, Rwanda','Completed');

INSERT INTO `testimonials` (`client_name`,`review`,`rating`,`status`) VALUES
('Jean Bosco','Adolphe delivered our home with exceptional structural quality and on schedule. Highly professional supervision.','5','approved'),
('Claudine M.','Accurate cost estimation and transparent consultation. Saved us significant budget overruns.','5','approved');

SET FOREIGN_KEY_CHECKS=1;
