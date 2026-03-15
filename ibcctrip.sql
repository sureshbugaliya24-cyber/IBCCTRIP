-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 15, 2026 at 08:43 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ibcctrip`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `country_id` int(10) UNSIGNED DEFAULT NULL,
  `state_id` int(10) UNSIGNED DEFAULT NULL,
  `city_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT 'IBCC Trip Team',
  `tags` varchar(500) DEFAULT NULL COMMENT 'Comma separated tags',
  `status` enum('Draft','Published') DEFAULT 'Draft',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `views` int(10) UNSIGNED DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `category_id`, `country_id`, `state_id`, `city_id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `author`, `tags`, `status`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `views`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 2, 5, 'Top 10 Places to Visit in Rajasthan', 'top-10-places-rajasthan', 'A curated collection of moments and memories from across the globe. Every photo tells a story of adventure and discovery.\r\n\r\n', '<p>A curated collection of moments and memories from across the globe. Every photo tells a story of adventure and discovery.</p><p><br></p><h2>jhh</h2><p><br></p><p>A curated collection of moments and memories from across the globe. Every photo tells a story of adventure and discovery.</p><p><br></p>', 'http://localhost/ibcctrip/uploads/blog/top-10-places-to-visit-in-rajasthan-2026-ibcc-trip-travel-guide-1773603750.png', 'IBCC Trip Team', 'Rajasthan,Heritage,India Travel,Forts,Palaces', 'Published', 'Top 10 Places to Visit in Rajasthan 2026 | IBCC Trip Travel Guide', 'A curated collection of moments and memories from across the globe. Every photo tells a story of adventure and discovery.', 'Top 10 Places to Visit in Rajasthan 2026 kk', NULL, 7, '2026-03-15 19:27:39', '2026-03-15 19:42:36');

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `name`, `slug`, `sort_order`) VALUES
(3, 'rajas', 'rajas', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_ref` varchar(20) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `trip_id` int(10) UNSIGNED DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration_days` int(10) UNSIGNED NOT NULL,
  `num_members` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `special_notes` text DEFAULT NULL,
  `status` enum('Pending','Scheduled','In Progress','Completed','Cancelled') DEFAULT 'Pending',
  `total_price` decimal(12,2) NOT NULL,
  `currency_code` varchar(10) NOT NULL DEFAULT 'INR',
  `base_price` decimal(12,2) NOT NULL COMMENT 'Price in INR at booking time',
  `trip_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`trip_details`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_members`
--

CREATE TABLE `booking_members` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `age` int(10) UNSIGNED DEFAULT NULL,
  `id_type` varchar(50) DEFAULT NULL,
  `id_number` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(10) UNSIGNED NOT NULL,
  `state_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `state_id`, `name`, `slug`, `description`, `featured_image`, `is_featured`, `sort_order`, `created_at`, `meta_title`, `meta_description`, `meta_keywords`) VALUES
(5, 2, 'jpr', 'jpr', '<p>jprjprjprjprjpr</p><p>jprjpr</p><p><br></p><h2>jprjpr</h2><p><br></p><p>hh</p>', 'http://localhost/ibcctrip/uploads/cities/jprr-t-1773600882.jpg', 1, 0, '2026-03-15 18:35:21', 'jprr t', 'jpr d', 'jpr k');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('New','Trying to Contact','Talked','Replied') DEFAULT 'New',
  `admin_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `code` varchar(5) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `flag_icon` varchar(10) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `slug`, `code`, `description`, `featured_image`, `flag_icon`, `is_featured`, `sort_order`, `created_at`, `meta_title`, `meta_description`, `meta_keywords`) VALUES
(2, 'India', 'india', 'IN', '<p>hello</p><p>I have completely fixed both issues!</p><p><br></p><p><strong>1. Dashboard Not Visually Updating Image </strong>: The system was saving the image perfectly behind the scenes, but because the new image had the exact same URL (.../uploads/destination-dummy-img.png), your web browser thought \"Oh, I already have this downloaded!\" and was displaying the cached old image to save load time.</p><p><br></p><p>I fixed this by adding a dynamic invisible timestamp (?t=123...) to the preview image in the Dashboard code! Now, whenever you save Settings or update a fallback image, your browser realizes it\'s new and will immediately swap it on screen without needing a hard reload.</p><h2>2. Missing Hero Banners on Destination Pages:</h2><p>I investigated country.php and found that the main Hero image at the very top of those pages was unfortunately written to just output a blank space instead of requesting a fallback!</p><p><br></p><p>I completely swept country.php, state.php, city.php, and place.php and wrapped their Hero Banners in the img_url(..., \'destination\') fallback logic to mirror exactly what the Country Cards do below them.</p><p>Now, if you forget to add a specific cover photo for India, Delhi, or the Taj Mahal, it will flawlessly default to your new uploaded Destination Placeholder at the top of the page!</p><p>Please take a look and try uploading the dummy image one more time, and then check country.php on the frontend! How does it look now?</p>', 'http://localhost/IBCCTRIP/uploads/countries/india-meta-titlee-test-1773598755.png', '🇮🇳', 1, 0, '2026-03-15 13:48:11', 'india Meta Titlee test', 'in Meta Description hh', 'india Meta, Keywords');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(10) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `caption` varchar(500) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `image_url`, `alt_text`, `caption`, `category`, `sort_order`, `created_at`) VALUES
(1, 'http://localhost/ibcctrip/uploads/gallery/gallery-item_f7091c14.png', NULL, 'gallery-2. gallery-2 gallery-2', 'gallery-2', 0, '2026-03-15 19:00:26');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `invoice_number` varchar(30) NOT NULL,
  `razorpay_order_id` varchar(100) DEFAULT NULL,
  `razorpay_payment_id` varchar(100) DEFAULT NULL,
  `razorpay_signature` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency_code` varchar(10) DEFAULT 'INR',
  `payment_method` varchar(50) DEFAULT 'Offline',
  `status` enum('Pending','Paid','Refunded','Failed') DEFAULT 'Pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

CREATE TABLE `places` (
  `id` int(10) UNSIGNED NOT NULL,
  `city_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `places`
--

INSERT INTO `places` (`id`, `city_id`, `name`, `slug`, `description`, `featured_image`, `is_featured`, `sort_order`, `created_at`, `meta_title`, `meta_description`, `meta_keywords`) VALUES
(1, 5, 'hawa', 'hawa', '<p>jprjprjprjprjpr</p><p>jprjpr</p><p><br></p><h2><strong>jprjpr</strong></h2><p><br></p><p>hh</p>', 'http://localhost/ibcctrip/uploads/places/hawaa-t-1773602559.png', 1, 0, '2026-03-15 18:58:11', 'hawaa t', 'hawa d', 'hawa k');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `key_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key_name`, `key_value`, `updated_at`) VALUES
(1, 'site_name', 'IBCC Trip', '2026-03-11 10:56:35'),
(2, 'site_tagline', 'Your Journey, Our Passion', '2026-03-11 10:56:35'),
(3, 'site_email', 'info@ibcctrip.com', '2026-03-11 10:56:35'),
(4, 'site_phone', '+91 98765 43210', '2026-03-11 10:56:35'),
(5, 'site_address', '123, Travel Hub, Connaught Place, New Delhi - 110001', '2026-03-11 10:56:35'),
(6, 'whatsapp_number', '919876543210', '2026-03-11 10:56:35'),
(7, 'currency_default', 'INR', '2026-03-11 10:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `s_key` varchar(50) NOT NULL,
  `s_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `category`, `s_key`, `s_value`, `updated_at`) VALUES
(1, 'branding', 'site_name_part1', 'IBCC', '2026-03-13 06:31:33'),
(2, 'branding', 'site_name_part2', 'TRIP', '2026-03-14 01:21:56'),
(3, 'branding', 'site_icon_svg', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"512.000000pt\" height=\"512.000000pt\" viewBox=\"0 0 512.000000 512.000000\" preserveAspectRatio=\"xMidYMid meet\"><g transform=\"translate(0.000000,512.000000) scale(0.100000,-0.100000)\" fill=\"#ffffff\" stroke=\"none\"><path d=\"M2518 4936 c-96 -50 -172 -216 -215 -471 -37 -222 -16 -1354 43 -2325 48 -802 107 -1284 195 -1612 11 -42 34 -50 41 -15 3 12 19 83 36 157 62 272 90 456 121 795 18 190 28 333 56 775 43 663 69 1879 46 2110 -25 249 -84 433 -173 539 -49 58 -99 73 -150 47z\"></path><path d=\"M540 4350 c0 -11 7 -20 15 -20 46 0 133 -47 186 -99 98 -99 93 -51 99 -853 l5 -698 110 72 c61 40 184 121 275 181 l165 109 5 521 5 522 28 57 c35 71 110 140 183 167 30 12 61 21 69 21 8 0 15 9 15 20 0 20 -7 20 -580 20 -573 0 -580 0 -580 -20z\"></path><path d=\"M3152 4351 c-34 -3 -62 -9 -62 -13 0 -5 0 -14 0 -20 0 -9 44 -21 123 -37 465 -89 703 -267 763 -571 42 -212 -25 -462 -158 -592 -32 -32 -58 -59 -58 -62 0 -2 37 -32 81 -66 l81 -63 52 13 c266 66 467 253 536 502 94 337 -51 644 -376 794 -90 42 -259 87 -389 104 -104 13 -500 21 -593 11z\"></path><path d=\"M1885 3269 c-308 -200 -430 -280 -695 -454 -135 -89 -369 -242 -520 -340 -392 -256 -397 -260 -441 -328 -50 -78 -71 -150 -77 -274 -2 -57 -3 -103 -1 -103 2 0 459 182 1014 404 l1010 404 -2 79 c-1 44 -16 222 -33 396 -16 173 -30 322 -30 331 0 9 -6 16 -12 15 -7 0 -103 -59 -213 -130z\"></path><path d=\"M3026 3382 c-3 -4 -12 -116 -21 -248 -9 -131 -20 -288 -25 -349 -6 -60 -10 -128 -10 -151 l0 -41 723 -288 c397 -159 848 -339 1002 -401 l280 -112 3 52 c4 66 -15 197 -38 264 -39 109 -49 117 -590 462 -351 223 -934 595 -1022 652 -266 171 -288 182 -302 160z\"></path><path d=\"M1115 2070 l-270 -108 -5 -303 c-6 -317 -9 -338 -54 -405 -39 -58 -137 -122 -214 -139 -18 -3 -32 -13 -32 -21 0 -12 86 -14 580 -14 487 0 580 2 580 14 0 8 -19 18 -46 25 -74 19 -138 56 -182 106 -71 81 -72 87 -72 547 0 224 -3 408 -7 407 -5 0 -129 -49 -278 -109z\"></path><path d=\"M4175 1857 c-1 -148 -3 -169 -28 -242 -86 -259 -321 -407 -701 -445 -125 -12 -288 -3 -431 24 l-70 14 34 -29 c111 -92 344 -129 684 -109 472 28 787 179 944 452 36 61 74 178 80 244 5 59 4 62 -18 69 -25 8 -320 119 -426 161 -34 13 -63 24 -65 24 -2 0 -3 -73 -3 -163z\"></path><path d=\"M2238 1103 c-52 -38 -190 -138 -306 -223 l-212 -155 0 -127 c0 -88 4 -128 11 -128 6 0 107 41 223 91 116 51 263 114 326 141 63 27 118 52 122 56 6 6 -58 401 -66 409 -2 2 -46 -27 -98 -64z\"></path><path d=\"M2796 1143 c-21 -109 -56 -377 -49 -384 10 -10 610 -278 641 -285 22 -6 22 -6 22 118 l0 125 -292 219 c-160 120 -297 222 -304 227 -8 5 -14 -2 -18 -20z\"></path></g></svg>', '2026-03-14 01:19:26'),
(4, 'contact', 'contact_phone', '+91 7878335572', '2026-03-13 06:37:24'),
(5, 'contact', 'contact_email', 'info@ibcctrip.com', '2026-03-13 06:31:33'),
(6, 'contact', 'contact_address', '3rd Floor, Krishna Nagar , A Vistar, Kartarpura, Gopal Pura Mode, Jaipur, Rajasthan 302016', '2026-03-14 03:26:56'),
(7, 'contact', 'whatsapp_no', '917878335572', '2026-03-13 06:31:33'),
(8, 'social', 'facebook_url', 'https://www.facebook.com/people/Ibcc-Trip/61586842741654/', '2026-03-14 01:23:15'),
(9, 'social', 'instagram_url', 'https://www.instagram.com/ibcctrip/', '2026-03-14 01:23:15'),
(10, 'social', 'twitter_url', 'https://twitter.com/ibcctrip', '2026-03-13 06:31:33'),
(11, 'style', 'color_primary', '#1E1B7A', '2026-03-14 01:16:21'),
(12, 'style', 'color_secondary', '#FF6B00', '2026-03-13 06:31:33'),
(37, 'payment', 'payment_razorpay_enabled', '1', '2026-03-13 07:14:24'),
(38, 'payment', 'payment_razorpay_key', 'rzp_live_SQcpDeWRGhMxBU', '2026-03-13 07:20:12'),
(39, 'payment', 'payment_razorpay_secret', 'pTRYDGYql96pcAyOFMBKu1Hv', '2026-03-13 07:20:12'),
(40, 'payment', 'payment_cod_enabled', '1', '2026-03-14 07:31:49'),
(105, 'branding', 'company_name', 'IBCC TRIP', '2026-03-14 01:21:56'),
(106, 'branding', 'company_gst', 'GST07AAADI1234A1Z5', '2026-03-14 01:19:06'),
(253, 'placeholders', 'placeholder_blog', 'http://localhost/ibcctrip/uploads/placeholders/blog-dummy-img.png', '2026-03-15 19:29:14'),
(256, 'placeholders', 'placeholder_destination', 'http://localhost/ibcctrip/uploads/placeholders/destination-dummy-img.png', '2026-03-15 17:39:27'),
(257, 'placeholders', 'placeholder_trip_cover', 'http://localhost/IBCCTRIP/uploads/placeholders/trip-cover-dummy-img.png', '2026-03-15 17:34:40'),
(258, 'placeholders', 'placeholder_trip_map', 'http://localhost/IBCCTRIP/uploads/placeholders/trip-map-dummy-img.png', '2026-03-15 17:34:40'),
(259, 'placeholders', 'placeholder_trip_gallery', 'http://localhost/IBCCTRIP/uploads/placeholders/trip-gallery-dummy-img.png', '2026-03-15 17:34:40'),
(263, 'social', 'youtube_url', 'https://youtube.com', '2026-03-14 06:43:40'),
(264, 'social', 'linkedin_url', 'https://linkedin.com', '2026-03-14 06:43:40'),
(265, 'social', 'whatsapp_url', '', '2026-03-14 06:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `site_stats`
--

CREATE TABLE `site_stats` (
  `id` int(11) NOT NULL,
  `stat_key` varchar(50) NOT NULL,
  `stat_value` varchar(50) NOT NULL,
  `stat_label` varchar(100) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_stats`
--

INSERT INTO `site_stats` (`id`, `stat_key`, `stat_value`, `stat_label`, `icon`, `updated_at`) VALUES
(1, 'happy_travelers', '15,000+', 'Happy Travelers', '😊', '2026-03-13 06:32:48'),
(2, 'curated_tours', '500+', 'Curated Tours', '🌍', '2026-03-13 06:32:48'),
(3, 'destinations', '50+', 'Destinations', '📍', '2026-03-13 06:32:48'),
(4, 'experience', '12+', 'Years Experience', '⭐', '2026-03-13 06:32:48');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `country_id`, `name`, `slug`, `description`, `featured_image`, `is_featured`, `sort_order`, `created_at`, `meta_title`, `meta_description`, `meta_keywords`) VALUES
(2, 2, 'rajasthan', 'rajasthan', '<p>1. Dashboard Not Visually Updating Image : The system was saving the image perfectly behind the scenes, but because the new image had the exact same URL (.../uploads/destination-dummy-img.png), your web browser thought \"Oh, I already have this downloaded!\" and was displaying the cached old image to save load time.</p><p><br></p><p><br></p><p><br></p><p>I fixed this by adding a dynamic invisible timestamp (?t=123...) to the preview image in the Dashboard code! Now, whenever you save Settings or update a fallback image, your browser realizes it\'s new and will immediately swap it on screen without needing a hard reload.</p><p><br></p><h2>2. Missing Hero Banners on Destination Pages:</h2><p>I investigated country.php and found that the main Hero image at the very top of those pages was unfortunately written to just output a blank space instead of requesting a fallback!</p><p><br></p><p><br></p><p><br></p><p>I completely swept country.php, state.php, city.php, and place.php and wrapped their Hero Banners in the img_url(..., \'destination\') fallback logic to mirror exactly what the Country Cards do below them.</p><p><br></p>', 'http://localhost/IBCCTRIP/uploads/states/rajasthan-t-1773598916.jpg', 1, 0, '2026-03-15 18:21:56', 'rajasthan t', 'rajasthand', 'rajasthan k');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(100) DEFAULT 'Traveler',
  `rating` tinyint(4) DEFAULT 5,
  `comment` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Draft','Published') DEFAULT 'Published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `highlights` text DEFAULT NULL COMMENT 'JSON array of highlight strings',
  `inclusions` text DEFAULT NULL COMMENT 'JSON array',
  `exclusions` text DEFAULT NULL COMMENT 'JSON array',
  `country_id` int(10) UNSIGNED DEFAULT NULL,
  `state_id` int(10) UNSIGNED DEFAULT NULL,
  `city_id` int(10) UNSIGNED DEFAULT NULL,
  `place_id` int(10) UNSIGNED DEFAULT NULL,
  `base_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discounted_price` decimal(12,2) DEFAULT NULL COMMENT 'NULL = no discount',
  `duration_days` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `max_members` int(10) UNSIGNED DEFAULT 20,
  `cover_image` varchar(255) DEFAULT NULL,
  `map_image` varchar(255) DEFAULT NULL,
  `difficulty` enum('Easy','Moderate','Hard') DEFAULT 'Easy',
  `trip_type` enum('Domestic','International','Adventure','Luxury') DEFAULT 'Domestic',
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `views` int(10) UNSIGNED DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip_gallery`
--

CREATE TABLE `trip_gallery` (
  `id` int(10) UNSIGNED NOT NULL,
  `trip_id` int(10) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip_itinerary`
--

CREATE TABLE `trip_itinerary` (
  `id` int(10) UNSIGNED NOT NULL,
  `trip_id` int(10) UNSIGNED NOT NULL,
  `day_number` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `meals` varchar(100) DEFAULT NULL COMMENT 'e.g. Breakfast, Lunch',
  `accommodation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip_videos`
--

CREATE TABLE `trip_videos` (
  `id` int(10) UNSIGNED NOT NULL,
  `trip_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password`, `role`, `avatar`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'IBCC Admin', 'admin@ibcctrip.com', '+917878335572', '$2y$10$pNkPByasczYvPL3Mim0DbO4INrtKI6Ytr7/tRxnsNO6z8ElUJT.k2', 'admin', NULL, 1, '2026-03-11 10:56:35', '2026-03-12 06:39:17'),
(2, 'Rajesh Sharma', 'rajesh@example.com', '09610238230', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, 1, '2026-03-11 10:56:35', '2026-03-15 16:54:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_country` (`country_id`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_ref` (`booking_ref`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_trip` (`trip_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_ref` (`booking_ref`);

--
-- Indexes for table `booking_members`
--
ALTER TABLE `booking_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_booking` (`booking_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_state` (`state_id`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_featured` (`is_featured`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `idx_booking` (`booking_id`),
  ADD KEY `idx_invoice` (`invoice_number`);

--
-- Indexes for table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_city` (`city_id`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `s_key` (`s_key`);

--
-- Indexes for table `site_stats`
--
ALTER TABLE `site_stats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stat_key` (`stat_key`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_country` (`country_id`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_country` (`country_id`),
  ADD KEY `idx_city` (`city_id`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `place_id` (`place_id`);

--
-- Indexes for table `trip_gallery`
--
ALTER TABLE `trip_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_trip` (`trip_id`);

--
-- Indexes for table `trip_itinerary`
--
ALTER TABLE `trip_itinerary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_trip` (`trip_id`);

--
-- Indexes for table `trip_videos`
--
ALTER TABLE `trip_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_trip` (`trip_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_members`
--
ALTER TABLE `booking_members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=815;

--
-- AUTO_INCREMENT for table `site_stats`
--
ALTER TABLE `site_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip_gallery`
--
ALTER TABLE `trip_gallery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip_itinerary`
--
ALTER TABLE `trip_itinerary`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip_videos`
--
ALTER TABLE `trip_videos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blogs_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blogs_ibfk_3` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blogs_ibfk_4` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_trip_fk` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `booking_members`
--
ALTER TABLE `booking_members`
  ADD CONSTRAINT `booking_members_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `places`
--
ALTER TABLE `places`
  ADD CONSTRAINT `places_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `states`
--
ALTER TABLE `states`
  ADD CONSTRAINT `states_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `trips_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `trips_ibfk_3` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `trips_ibfk_4` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `trip_gallery`
--
ALTER TABLE `trip_gallery`
  ADD CONSTRAINT `trip_gallery_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trip_itinerary`
--
ALTER TABLE `trip_itinerary`
  ADD CONSTRAINT `trip_itinerary_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trip_videos`
--
ALTER TABLE `trip_videos`
  ADD CONSTRAINT `trip_videos_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
