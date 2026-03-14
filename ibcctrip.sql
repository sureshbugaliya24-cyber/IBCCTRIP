-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 14, 2026 at 02:40 PM
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
(1, 2, 1, 1, NULL, 'Top 10 Places to Visit in Rajasthan', 'top-10-places-rajasthan', 'Rajasthan, the Land of Kings, is a treasure trove of ancient forts, opulent palaces, and vibrant festivals. Here are the top 10 must-visit places.', '<p>Rajasthan is one of India\'s most captivating states, offering a unique blend of royal heritage and desert landscapes. Let\'s explore the top 10 places you must visit.</p><h2>1. Jaipur — The Pink City</h2><p>Jaipur, Rajasthan\'s capital, is famous for its pink-hued buildings, magnificent Amber Fort, and vibrant bazaars. Don\'t miss the Hawa Mahal, City Palace, and Jantar Mantar observatory.</p><h2>2. Udaipur — The City of Lakes</h2><p>Often called the Venice of the East, Udaipur enchants visitors with its serene lakes, City Palace perched above Lake Pichola, and romantic boat rides.</p><h2>3. Jodhpur — The Blue City</h2><p>Mehrangarh Fort towers over the blue-painted old city. The views from the fort walls are spectacular, and the bustling Sardar Market below is a shopping delight.</p><h2>4. Jaisalmer — The Golden City</h2><p>Rising from the Thar Desert like a mirage, Jaisalmer\'s golden sandstone fort (the only \"living fort\" in the world) and camel safaris are unforgettable.</p><p>...and much more!</p>', 'https://images.unsplash.com/photo-1599661046827-dacde9ec8a9a?w=1200', 'IBCC Trip Team', 'Rajasthan,Heritage,India Travel,Forts,Palaces', 'Published', 'Top 10 Places to Visit in Rajasthan 2026 | IBCC Trip Travel Guide', 'Discover the best places to visit in Rajasthan! From Jaipur\'s Pink City to Jaisalmer\'s golden sands — our complete guide covers top 10 Rajasthan destinations.', NULL, NULL, 4, '2026-03-11 10:56:36', '2026-03-14 09:20:27'),
(2, 2, 1, 2, NULL, 'Complete Guide to Goa Beaches', 'complete-guide-goa-beaches', 'Goa is synonymous with sun, sand and surf. With over 50 beaches stretching across 100km of coastline, here is your ultimate guide to Goa\'s best beaches.', '<p>Goa\'s beaches range from lively party spots to secluded coves. Here\'s your comprehensive guide to navigating them all.</p><h2>North Goa Beaches</h2><p><strong>Baga Beach</strong> is the most popular, known for water sports, beach shacks, and nightlife. Nearby <strong>Calangute Beach</strong> (the Queen of Beaches) is broader and busier.</p><p><strong>Anjuna Beach</strong> hosts the legendary flea market every Wednesday. <strong>Vagator</strong> and <strong>Ozran</strong> are quieter with dramatic cliffs.</p><h2>South Goa Beaches</h2><p><strong>Palolem Beach</strong> is a paradise — a crescent-shaped bay with calm waters perfect for kayaking. <strong>Agonda Beach</strong> is serene and eco-friendly.</p>', 'https://images.unsplash.com/photo-1587922546307-776227941871?w=1200', 'IBCC Trip Team', 'Goa,Beaches,India Travel,Water Sports', 'Published', 'Complete Guide to Goa Beaches 2026 | Best Beaches in Goa — IBCC Trip', 'Planning a Goa trip? Our complete guide covers the best beaches in North & South Goa, water sports, beach shacks, and tips for your perfect Goa vacation.', NULL, NULL, 0, '2026-03-11 10:56:36', '2026-03-11 10:56:36'),
(3, 4, 3, 5, NULL, 'Dubai Luxury Travel Guide 2026', 'dubai-luxury-travel-guide-2026', 'Dubai sets the standard for luxury travel. From the world\'s tallest hotel to gold-plated supercars, here is how to experience Dubai like a VIP.', '<p>Dubai is the ultimate luxury playground. Here\'s how to make the most of your luxury Dubai experience in 2026.</p><h2>Where to Stay</h2><p>The <strong>Burj Al Arab</strong> is the world\'s only 7-star hotel, with a helipad and its own submarine. The <strong>Atlantis The Palm</strong> sits on the iconic Palm Jumeirah.</p><h2>Must-Do Luxury Experiences</h2><p>Book a private desert safari with a personal chef. Take a seaplane over the Palm Jumeirah. Dine at Ossiano underwater restaurant. Book the Burj Khalifa Sky private lounge.</p>', 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=1200', 'IBCC Trip Team', 'Dubai,Luxury Travel,UAE,5-star', 'Published', 'Dubai Luxury Travel Guide 2026 | Ultimate Dubai VIP Experience — IBCC Trip', 'Experience Dubai like a VIP! Our 2026 Dubai luxury travel guide covers the best 5-star hotels, exclusive experiences, desert safaris, and shopping tips.', NULL, NULL, 2, '2026-03-11 10:56:36', '2026-03-13 07:05:29'),
(4, 2, 2, 4, NULL, 'Thailand Island Hopping Guide', 'thailand-island-hopping-guide', 'Thailand has over 1,400 islands — but which ones are worth visiting? Our expert guide narrows down the best islands for every type of traveler.', '<p>Thailand\'s islands each have their own unique character. Here\'s where to go based on what you\'re looking for.</p><h2>Best for Parties: Koh Samui & Koh Phangan</h2><p>Koh Samui has a sophisticated party scene, while Koh Phangan is legendary for its Full Moon Party on Haad Rin Beach.</p><h2>Best for Nature: Koh Lanta & Koh Tarutao</h2><p>These islands offer pristine national park beaches, mangrove kayaking, and minimal development.</p><h2>Best for Diving: Koh Tao</h2><p>Koh Tao is one of Asia\'s best value dive destinations, with excellent visibility and a huge variety of marine life.</p>', 'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=1200', 'IBCC Trip Team', 'Thailand,Islands,Beach,Phuket,Diving', 'Published', 'Thailand Island Hopping Guide 2026 | Best Thai Islands — IBCC Trip', 'Discover Thailand\'s best islands! From Phuket to Phi Phi, our complete island hopping guide helps you plan the perfect Thai island adventure.', NULL, NULL, 2, '2026-03-11 10:56:36', '2026-03-14 09:19:52'),
(5, 1, 1, NULL, NULL, 'Budget Travel Tips for India 2026', 'budget-travel-tips-india-2026', 'India is one of the world\'s best budget travel destinations — but knowing the tricks can save you thousands. Here are 15 expert budget tips for traveling India.', '<p>India can be traveled on almost any budget. Here are our top tips to save money without sacrificing the experience.</p><h2>Transportation Tips</h2><p>Book train tickets on IRCTC at least 3 months in advance, especially for 2AC (second class air-conditioned) — comfortable and affordable. Use IndiGo or SpiceJet for short flights.</p><h2>Accommodation Tips</h2><p>Heritage havelis (mansions) in Rajasthan often cost less than chain hotels but offer far more character. OYO and Treebo are reliable mid-range options across India.</p><h2>Food Tips</h2><p>Eat at dhabas (roadside restaurants) — the food is authentic, freshly made, and costs a fraction of restaurant prices.</p>', 'https://images.unsplash.com/photo-1604999565976-8913ad2ddb7c?w=1200', 'IBCC Trip Team', 'India,Budget Travel,Travel Tips,Backpacking', 'Published', 'Budget Travel Tips for India 2026 | Save Money in India — IBCC Trip', 'Planning a budget trip to India? Our 2026 guide covers cheap transport, affordable stays, free attractions, and money-saving tips for the smart India traveler.', NULL, NULL, 1, '2026-03-11 10:56:36', '2026-03-11 12:02:46');

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
(1, 'Travel Tips', 'travel-tips', 0),
(2, 'Destinations', 'destinations', 0),
(3, 'Adventure', 'adventure', 0),
(4, 'Luxury', 'luxury', 0),
(5, 'Food & Culture', 'food-culture', 0);

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

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_ref`, `user_id`, `trip_id`, `start_date`, `end_date`, `duration_days`, `num_members`, `full_name`, `email`, `phone`, `special_notes`, `status`, `total_price`, `currency_code`, `base_price`, `trip_details`, `created_at`, `updated_at`) VALUES
(2, 'IBCC-2026-0002', 3, 2, '2026-05-15', '2026-05-20', 6, 4, 'Priya Singh', 'priya@example.com', '9822345678', NULL, 'Pending', 79996.00, 'INR', 79996.00, '{\"title\":\"Golden Triangle Tour\",\"slug\":\"golden-triangle-tour\",\"cover_image\":\"https:\\/\\/images.unsplash.com\\/photo-1564507592333-c60657eea523?w=1200\",\"duration_days\":6,\"base_price\":\"22000.00\",\"discounted_price\":\"19999.00\",\"trip_type\":\"Domestic\",\"difficulty\":\"Easy\"}', '2026-03-11 10:56:36', '2026-03-13 04:47:05'),
(3, 'IBCC-2026-0003', 4, 3, '2026-03-20', '2026-03-23', 4, 3, 'Amit Patel', 'amit@example.com', '9833456789', NULL, 'In Progress', 29997.00, 'INR', 29997.00, '{\"title\":\"Goa Beach Holiday\",\"slug\":\"goa-beach-holiday\",\"cover_image\":\"https:\\/\\/images.unsplash.com\\/photo-1587922546307-776227941871?w=1200\",\"duration_days\":4,\"base_price\":\"12000.00\",\"discounted_price\":\"9999.00\",\"trip_type\":\"Domestic\",\"difficulty\":\"Easy\"}', '2026-03-11 10:56:36', '2026-03-13 04:47:05'),
(4, 'IBCC-2026-0004', 5, 4, '2026-06-05', '2026-06-09', 5, 2, 'Sunita Verma', 'sunita@example.com', '9844567890', NULL, 'Scheduled', 69998.00, 'INR', 69998.00, '{\"title\":\"Thailand Island Adventure\",\"slug\":\"thailand-island-adventure\",\"cover_image\":\"https:\\/\\/images.unsplash.com\\/photo-1552465011-b4e21bf6e79a?w=1200\",\"duration_days\":5,\"base_price\":\"38000.00\",\"discounted_price\":\"34999.00\",\"trip_type\":\"International\",\"difficulty\":\"Moderate\"}', '2026-03-11 10:56:36', '2026-03-13 04:47:05'),
(5, 'IBCC-2026-0005', 6, 5, '2026-04-01', '2026-04-04', 4, 1, 'Mohammed Khan', 'mohammed@example.com', '9855678901', NULL, 'Completed', 49999.00, 'INR', 49999.00, '{\"title\":\"Dubai Luxury Experience\",\"slug\":\"dubai-luxury-experience\",\"cover_image\":\"https:\\/\\/images.unsplash.com\\/photo-1512453979798-5ea266f8880c?w=1200\",\"duration_days\":4,\"base_price\":\"55000.00\",\"discounted_price\":\"49999.00\",\"trip_type\":\"International\",\"difficulty\":\"Easy\"}', '2026-03-11 10:56:36', '2026-03-13 04:47:05'),
(9, 'IBCC-2026-49157', 2, 5, '2026-03-31', '2026-04-01', 1, 2, 'Rajesh Sharma', 'rajesh@example.com', '9811234567', 'hello', 'Pending', 99998.00, 'INR', 99998.00, '{\"title\":\"Dubai Luxury Experience\",\"slug\":\"dubai-luxury-experience\",\"cover_image\":\"https:\\/\\/images.unsplash.com\\/photo-1512453979798-5ea266f8880c?w=1200\",\"duration_days\":4,\"base_price\":\"55000.00\",\"discounted_price\":\"49999.00\",\"trip_type\":\"International\",\"difficulty\":\"Easy\"}', '2026-03-13 07:18:22', '2026-03-13 07:18:22'),
(10, 'IBCC-2026-96132', 2, 5, '2026-03-18', '2026-03-19', 1, 2, 'Rajesh Sharma', 'rajesh@example.com', '9811234567', '', 'Scheduled', 99998.00, 'INR', 99998.00, '{\"title\":\"Dubai Luxury Experience\",\"slug\":\"dubai-luxury-experience\",\"cover_image\":\"https:\\/\\/images.unsplash.com\\/photo-1512453979798-5ea266f8880c?w=1200\",\"duration_days\":4,\"base_price\":\"55000.00\",\"discounted_price\":\"49999.00\",\"trip_type\":\"International\",\"difficulty\":\"Easy\"}', '2026-03-13 07:20:23', '2026-03-14 08:16:57'),
(11, 'IBCC-2026-26115', 2, 5, '2026-03-17', '2026-03-18', 1, 1, 'Rajesh Sharma', 'rajesh@example.com', '9811234567', '', 'Pending', 1.00, 'INR', 1.00, '{\"title\":\"Dubai Luxury Experience\",\"slug\":\"dubai-luxury-experience\",\"cover_image\":\"https:\\/\\/images.unsplash.com\\/photo-1512453979798-5ea266f8880c?w=1200\",\"duration_days\":4,\"base_price\":\"55000.00\",\"discounted_price\":\"1.00\",\"trip_type\":\"International\",\"difficulty\":\"Easy\"}', '2026-03-13 07:26:34', '2026-03-14 01:19:41');

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
(1, 1, 'Jaipur', 'jaipur', 'The Pink City, Jaipur is Rajasthan\'s capital, famous for Hawa Mahal, Amber Fort, City Palace, and vibrant bazaars.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL),
(2, 3, 'New Delhi', 'new-delhi', 'New Delhi, India\'s capital, is a city that wears its history on its sleeve — from Mughal monuments to colonial boulevards.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL),
(3, 2, 'Panaji', 'panaji', 'Panaji, Goa\'s capital, is a charming riverside city with colorful Portuguese-era houses, quirky cafes, and a laid-back vibe.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL),
(4, 4, 'Phuket City', 'phuket-city', 'Phuket City, the cultural heart of the island, features Sino-Portuguese architecture, local markets, and street art.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL),
(5, 5, 'Dubai City', 'dubai-city', 'Dubai City is the world\'s most visited city — home to the Burj Khalifa, Dubai Mall, and the iconic Palm Jumeirah.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL);

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

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `is_read`, `created_at`, `status`, `admin_notes`) VALUES
(2, 'hell', 'kjkj@jj.o', '0987654321', 'New Booking', 'fuihvui', 1, '2026-03-13 06:26:28', 'Talked', 'hello');

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
(1, 'India', 'india', 'IN', '<p>India, the land of diverse cultures, ancient temples, royal palaces, and breathtaking landscapes. From the snow-capped Himalayas to tropical beaches, India offers an unparalleled travel experience.</p>', 'http://localhost/ibcctrip/uploads/img_3a5c5658.jpg', '🇮🇳', 1, 1, '2026-03-11 10:56:35', '', '', ''),
(2, 'Thailand', 'thailand', 'TH', 'Thailand, the Land of Smiles, enchants travelers with its ornate temples, vibrant street food, idyllic beaches, and world-class hospitality.', NULL, '🇹🇭', 1, 2, '2026-03-11 10:56:35', NULL, NULL, NULL),
(3, 'UAE', 'uae', 'AE', 'The United Arab Emirates blends ultramodern cities with desert adventures and traditional Arabian culture. Dubai and Abu Dhabi are world-class luxury destinations.', NULL, '🇦🇪', 1, 3, '2026-03-11 10:56:35', NULL, NULL, NULL),
(4, 'France', 'france', 'FR', 'France captivates visitors with the Eiffel Tower, world-renowned cuisine, romantic countryside, and iconic art museums. Paris remains the most visited city in the world.', NULL, '🇫🇷', 1, 4, '2026-03-11 10:56:35', NULL, NULL, NULL),
(5, 'Singapore', 'singapore', 'SG', 'Singapore, a dazzling city-state, seamlessly blends futuristic architecture, multicultural neighborhoods, lush gardens, and exceptional food culture.', NULL, '🇸🇬', 1, 5, '2026-03-11 10:56:35', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(10) NOT NULL,
  `symbol` varchar(10) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `exchange_rate` decimal(12,6) NOT NULL DEFAULT 1.000000,
  `is_default` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `code`, `symbol`, `name`, `exchange_rate`, `is_default`, `is_active`) VALUES
(1, 'INR', '₹', 'Indian Rupee', 1.000000, 1, 1),
(2, 'USD', '$', 'US Dollar', 0.012000, 0, 1),
(3, 'EUR', '€', 'Euro', 0.011000, 0, 1),
(4, 'AED', 'د.إ', 'UAE Dirham', 0.044000, 0, 1),
(5, 'GBP', '£', 'British Pound', 0.009500, 0, 1);

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
(2, 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800', 'Taj Mahal', 'The iconic Taj Mahal at sunrise', 'India', 0, '2026-03-11 10:56:36'),
(3, 'https://images.unsplash.com/photo-1587922546307-776227941871?w=800', 'Goa Beach', 'Palm-fringed beaches of Goa', 'India', 0, '2026-03-11 10:56:36'),
(4, 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800', 'Burj Khalifa', 'Burj Khalifa & downtown Dubai', 'Dubai', 0, '2026-03-11 10:56:36'),
(5, 'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=800', 'Phi Phi Islands', 'Crystal waters of Phi Phi Islands', 'Thailand', 0, '2026-03-11 10:56:36'),
(6, 'https://images.unsplash.com/photo-1506665531195-3566af2b4dfa?w=800', 'Phuket Beach', 'Sunset at Phuket Beach, Thailand', 'Thailand', 0, '2026-03-11 10:56:36'),
(7, 'https://images.unsplash.com/photo-1547637589-f54c34f5d7a4?w=800', 'Dubai Marina', 'Dubai Marina at night', 'Dubai', 0, '2026-03-11 10:56:36'),
(8, 'https://images.unsplash.com/photo-1604999565976-8913ad2ddb7c?w=800', 'India Travel', 'Traveler exploring India', 'India', 0, '2026-03-11 10:56:36');

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

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `invoice_number`, `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`, `amount`, `currency_code`, `payment_method`, `status`, `paid_at`, `created_at`) VALUES
(2, 2, 'INV-2026-0002', NULL, NULL, NULL, 79996.00, 'INR', 'Bank Transfer', 'Pending', NULL, '2026-03-11 10:56:36'),
(3, 3, 'INV-2026-0003', NULL, NULL, NULL, 29997.00, 'INR', 'Cash', 'Paid', '2026-03-18 08:30:00', '2026-03-11 10:56:36'),
(4, 4, 'INV-2026-0004', NULL, NULL, NULL, 69998.00, 'INR', 'Online - Card', 'Paid', '2026-05-28 03:45:00', '2026-03-11 10:56:36'),
(5, 5, 'INV-2026-0005', NULL, NULL, NULL, 49999.00, 'INR', 'Cash', 'Paid', '2026-03-25 11:15:00', '2026-03-11 10:56:36'),
(8, 9, 'INV-2026-22840', 'order_SQcnZzdwWpQEvz', NULL, NULL, 99998.00, 'INR', 'Razorpay', 'Pending', NULL, '2026-03-13 07:18:23'),
(9, 10, 'INV-2026-14834', 'order_SQcphSZ4mPW1QD', NULL, NULL, 99998.00, 'INR', 'Razorpay', 'Pending', '2026-03-14 08:16:57', '2026-03-13 07:20:23'),
(10, 11, 'INV-2026-74100', 'order_SQcwEGFhWNTXnI', 'pay_SQcwfK5HDMwBwX', 'a2c7d99a68164149ad1cd1d894bcefe8fbe220fb2b85a98e651ae46ca738241b', 1.00, 'INR', 'Razorpay', 'Paid', NULL, '2026-03-13 07:26:34');

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
(1, 1, 'Amer Fort', 'amer-fort', 'Amer Fort (Amber Fort), perched on a hilltop overlooking Maota Lake, is Jaipur\'s most visited attraction. Its grand Rajput architecture and elephant rides make it iconic.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL),
(2, 1, 'Hawa Mahal', 'hawa-mahal', 'The Palace of Winds, Hawa Mahal is a stunning five-storey pink sandstone palace with 953 small windows that allowed royal ladies to observe street life.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL),
(3, 2, 'Red Fort', 'red-fort', 'The iconic Red Fort (Lal Qila), built by Emperor Shah Jahan in 1639, served as the main residence of Mughal emperors for 200 years.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL),
(4, 3, 'Baga Beach', 'baga-beach', 'Baga Beach in North Goa is among the country\'s most popular beaches, famous for water sports, shacks, and vibrant nightlife.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL),
(5, 5, 'Burj Khalifa', 'burj-khalifa', 'Standing at 828 meters, the Burj Khalifa is the world\'s tallest building. Its observation deck offers panoramic views of the entire Dubai skyline.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `seo_meta`
--

CREATE TABLE `seo_meta` (
  `id` int(10) UNSIGNED NOT NULL,
  `page_type` varchar(50) NOT NULL COMMENT 'home|trip|blog|country|state|city|place',
  `reference_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'The entity ID',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `canonical_url` varchar(500) DEFAULT NULL,
  `og_title` varchar(255) DEFAULT NULL,
  `og_description` text DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `twitter_title` varchar(255) DEFAULT NULL,
  `twitter_description` text DEFAULT NULL,
  `twitter_image` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seo_meta`
--

INSERT INTO `seo_meta` (`id`, `page_type`, `reference_id`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `og_title`, `og_description`, `og_image`, `twitter_title`, `twitter_description`, `twitter_image`, `updated_at`) VALUES
(1, 'home', NULL, 'IBCC Trip | Premium Travel Agency — Book Tours to India, Dubai, Thailand & Europe', 'IBCC Trip is India\'s leading premium travel agency. Book curated tours to Rajasthan, Goa, Dubai, Thailand, France & more. Best prices guaranteed. 24/7 support.', NULL, '/', 'IBCC Trip | Your Journey, Our Passion', 'Discover India & the world with IBCC Trip. Premium travel packages, expert guides, and unforgettable experiences. Book your dream holiday today.', NULL, NULL, NULL, NULL, '2026-03-11 10:56:36'),
(2, 'trips', NULL, 'All Tour Packages 2026 | Domestic & International Trips — IBCC Trip', 'Browse all travel packages at IBCC Trip. Domestic tours to Rajasthan, Goa & Delhi. International packages to Dubai, Thailand & France. Book online today.', NULL, '/trips', 'All Tour Packages | IBCC Trip', 'Explore our curated collection of domestic and international tour packages. Find the perfect trip for couples, families, and solo travelers.', NULL, NULL, NULL, NULL, '2026-03-11 10:56:36'),
(3, 'blogs', NULL, 'Travel Blog | Tips, Guides & Destination Insights — IBCC Trip', 'Read expert travel tips, destination guides, and travel inspiration on the IBCC Trip blog. Updated regularly by our team of travel experts.', NULL, '/blog', 'Travel Blog | IBCC Trip', 'Expert travel tips, destination guides & travel photography. Your go-to resource for planning the perfect trip.', NULL, NULL, NULL, NULL, '2026-03-11 10:56:36'),
(4, 'countries', NULL, 'Destinations | Countries to Explore — IBCC Trip', 'Discover amazing destinations across India, Dubai, Thailand, France, Singapore & more. IBCC Trip curates premium travel experiences worldwide.', NULL, '/countries', 'Destinations | IBCC Trip', 'Explore our handpicked destinations across Asia, Europe & the Middle East.', NULL, NULL, NULL, NULL, '2026-03-11 10:56:36');

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
(251, 'placeholders', 'placeholder_general', 'https://images.unsplash.com/photo-1488646953014-85cb44e25828', '2026-03-14 03:27:40'),
(252, 'placeholders', 'placeholder_trip', 'https://images.unsplash.com/photo-1488646953014-85cb44e25828', '2026-03-14 03:27:40'),
(253, 'placeholders', 'placeholder_blog', 'https://images.unsplash.com/photo-1488646953014-85cb44e25828', '2026-03-14 03:27:40'),
(254, 'placeholders', 'placeholder_city', 'https://images.unsplash.com/photo-1488646953014-85cb44e25828', '2026-03-14 03:27:40'),
(256, 'placeholders', 'placeholder_destination', 'https://images.unsplash.com/photo-1488646953014-85cb44e25828', '2026-03-14 06:41:36'),
(257, 'placeholders', 'placeholder_trip_cover', 'https://images.unsplash.com/photo-1488646953014-85cb44e25828', '2026-03-14 06:41:36'),
(258, 'placeholders', 'placeholder_trip_map', 'https://images.unsplash.com/photo-1488646953014-85cb44e25828', '2026-03-14 06:41:36'),
(259, 'placeholders', 'placeholder_trip_gallery', 'https://images.unsplash.com/photo-1488646953014-85cb44e25828', '2026-03-14 06:41:36'),
(263, 'social', 'youtube_url', 'https://youtube.com', '2026-03-14 06:43:40'),
(264, 'social', 'linkedin_url', 'https://linkedin.com', '2026-03-14 06:43:40'),
(265, 'social', 'whatsapp_url', '', '2026-03-14 06:43:40'),
(266, 'placeholders', 'placeholder_blog_image', 'http://localhost/ibcctrip/uploads/blog-dummy-img.png', '2026-03-14 09:17:03'),
(267, 'placeholders', 'placeholder_destination_image', 'https://via.placeholder.com/800x600?text=Destination+Placeholder', '2026-03-14 06:49:22'),
(268, 'placeholders', 'placeholder_trip_cover_image', 'https://via.placeholder.com/1200x800?text=Trip+Cover+Placeholder', '2026-03-14 06:49:22'),
(269, 'placeholders', 'placeholder_trip_map_image', 'https://via.placeholder.com/800x600?text=Trip+Map+Placeholder', '2026-03-14 06:49:22'),
(270, 'placeholders', 'placeholder_trip_gallery_image', 'https://via.placeholder.com/800x600?text=Trip+Gallery+Placeholder', '2026-03-14 06:49:22');

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
(1, 1, 'Rajasthan', 'rajasthan', 'The Land of Kings — Rajasthan is famous for its majestic forts, lavish palaces, and colorful festivals set amid the Thar Desert.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL),
(2, 1, 'Goa', 'goa', 'India\'s smallest state packs a punch with golden beaches, Portuguese architecture, vibrant nightlife, and delicious seafood.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL),
(3, 1, 'Delhi', 'delhi', 'India\'s capital is a thrilling mix of Mughal history, colonial architecture, modern malls, and legendary street food.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL),
(4, 2, 'Phuket', 'phuket', 'Phuket, Thailand\'s largest island, is famous for its crystalline beaches, vibrant nightlife, and luxury resorts.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL),
(5, 3, 'Dubai Emirate', 'dubai-emirate', 'Dubai, a glittering jewel of the Middle East, is the epitome of luxury, innovation, and ambition rising from the desert.', NULL, 1, 0, '2026-03-11 10:56:35', NULL, NULL, NULL);

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

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `role`, `rating`, `comment`, `image`, `status`, `created_at`) VALUES
(1, 'Rajesh Sharma', 'Explorer from Delhi', 5, 'Booked Rajasthan trip. Absolutely flawless experience — hotel, transport, guides, everything was top-notch!', 'http://localhost/ibcctrip/uploads/testimonial-user_a3789496.jpg', 'Published', '2026-03-13 06:32:48'),
(2, 'Priya Mehta', 'Traveler from Mumbai', 5, 'Our Bali honeymoon was a dream come true. The itinerary was perfect, with just the right balance.', '', 'Published', '2026-03-13 06:32:48'),
(3, 'Amit Patel', 'Explorer from Gujarat', 5, 'Third trip with IBCC Trip. Dubai package was amazing value. Will keep coming back!', '', 'Published', '2026-03-13 06:32:48');

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

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `title`, `slug`, `description`, `highlights`, `inclusions`, `exclusions`, `country_id`, `state_id`, `city_id`, `place_id`, `base_price`, `discounted_price`, `duration_days`, `max_members`, `cover_image`, `map_image`, `difficulty`, `trip_type`, `is_featured`, `is_active`, `sort_order`, `views`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `created_at`, `updated_at`) VALUES
(2, 'Golden Triangle Tour', 'golden-triangle-tour', '<p>India\'s legendary Golden Triangle connects three of the country\'s most iconic cities — Delhi, Agra, and Jaipur. This classic circuit showcases the Mughal Empire\'s grandeur, the British Raj\'s legacy, and Rajput magnificence.</p><p>From the Taj Mahal\'s white marble perfection to Jaipur\'s pink-hued fortresses, this tour is the quintessential Indian experience.</p>', '[\"Taj Mahal at sunrise\",\"Agra Fort tour\",\"Qutub Minar & India Gate\",\"Fatehpur Sikri day trip\",\"Amber Fort & Jaipur palaces\",\"Chandni Chowk food walk\"]', '[\"4-star hotel (5 nights)\",\"Daily breakfast\",\"AC Innova vehicle\",\"Expert guide\",\"All entry fees\",\"Airport transfers\"]', '[\"Flights\",\"Lunch & dinner\",\"Personal shopping\",\"Tips\",\"Camera charges\"]', 1, 1, 2, 3, 22000.00, 19999.00, 6, 16, 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=1200', NULL, 'Easy', 'Domestic', 1, 1, 0, 6, 'Golden Triangle Tour 2026 | Delhi Agra Jaipur Package — IBCC Trip', 'Experience India\'s iconic Golden Triangle — Delhi, Agra & Jaipur. 6 days luxury tour from ₹19,999. Taj Mahal sunrise included. Book today!', NULL, NULL, '2026-03-11 10:56:36', '2026-03-14 08:52:09'),
(3, 'Goa Beach Holiday', 'goa-beach-holiday', '<p>Let the waves kiss your feet as you explore Goa\'s legendary beaches, Portuguese colonial churches, lively beach shacks, and thrilling water sports.</p><p>Goa is not just a beach destination — it\'s a mood. From peaceful sunrise yoga to pulsating nightlife, this trip caters to every traveler\'s desire.</p>', '[\"Water sports at Baga Beach\",\"Dudhsagar Waterfall trek\",\"Old Goa churches tour\",\"Sunset cruise on Mandovi River\",\"Spice plantation visit\",\"Casino night (optional)\"]', '[\"3-star beach resort (3 nights)\",\"Daily breakfast\",\"AC vehicle\",\"Guide\",\"Dudhsagar trip\",\"Cruise tickets\"]', '[\"Airfare\",\"Lunch & dinner\",\"Water sports fees\",\"Casino entry\",\"Personal expenses\"]', 1, 2, 3, 4, 12000.00, 9999.00, 4, 25, 'https://images.unsplash.com/photo-1587922546307-776227941871?w=1200', NULL, 'Easy', 'Domestic', 1, 1, 0, 0, 'Goa Beach Holiday Package 2026 | 4 Days 3 Nights — IBCC Trip', 'Experience Goa\'s stunning beaches, water sports & nightlife. 4 days package from ₹9,999. Book your Goa trip with IBCC Trip today!', NULL, NULL, '2026-03-11 10:56:36', '2026-03-11 10:56:36'),
(4, 'Thailand Island Adventure', 'thailand-island-adventure', '<p>Thailand\'s island paradise offers crystal-clear turquoise waters, dramatic limestone cliffs, vibrant coral reefs, and some of the world\'s most beautiful beaches.</p><p>From Phi Phi Islands to James Bond Island, this adventure-packed tour delivers unforgettable experiences in the Land of Smiles.</p>', '[\"Phi Phi Island boat tour\",\"James Bond Island kayaking\",\"Phang Nga Bay exploration\",\"Snorkeling in coral reefs\",\"Thai cooking class\",\"Muay Thai show\"]', '[\"4-star resort (4 nights)\",\"Daily breakfast\",\"Airport transfers\",\"Island tour by speedboat\",\"Snorkeling equipment\",\"English-speaking guide\"]', '[\"International flights\",\"Visa fees\",\"Lunch & dinner\",\"Scuba diving\",\"Personal expenses\",\"Travel insurance\"]', 2, 4, 4, NULL, 38000.00, 34999.00, 5, 15, 'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=1200', NULL, 'Moderate', 'International', 1, 1, 0, 3, 'Thailand Island Adventure 2026 | Phuket & Phi Phi Tour — IBCC Trip', 'Explore Thailand\'s breathtaking islands! 5-day Phuket adventure from ₹34,999. Speedboat tours, snorkeling & luxury resort included.', NULL, NULL, '2026-03-11 10:56:36', '2026-03-12 04:21:47'),
(5, 'Dubai Luxury Experience', 'dubai-luxury-experience', '<p>Dubai — where the impossible becomes reality. Experience ultra-luxury in one of the world\'s most spectacular cities, from the world\'s tallest skyscraper to man-made islands and indoor ski slopes.</p><p>This exclusive luxury tour includes the finest hotels, private desert safari, and VIP access to Dubai\'s most iconic attractions.</p>', '[\"Burj Khalifa observation deck (level 148)\",\"Private desert safari with dinner\",\"Dubai Mall & fountain show\",\"Palm Jumeirah & Atlantis visit\",\"Dubai Frame\",\"Dhow cruise\",\"Gold & Spice Souk\"]', '[\"5-star hotel (3 nights)\",\"Daily breakfast\",\"Business class airport transfers\",\"Private guide\",\"Burj Khalifa tickets\",\"Desert safari\",\"Dhow cruise\",\"Dubai Mall tourr\"]', '[\"International flights\",\"UAE visa fees\",\"Lunch (except safari dinner)\",\"Personal shopping\",\"Alcohol\",\"Tips\"]', 3, 5, 5, 5, 55000.00, 1.00, 4, 12, 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=1200', 'http://localhost/ibcctrip/uploads/dubai-luxury-experience-map.png', 'Easy', 'International', 1, 1, 0, 51, 'Dubai Luxury Tour 2026 | 4 Days 3 Nights Package — IBCC Trip', 'Experience ultimate luxury in Dubai! 4-day exclusive tour from ₹49,999. Burj Khalifa, desert safari, 5-star hotel. Book now with IBCC Trip.', '', NULL, '2026-03-11 10:56:36', '2026-03-14 08:51:54');

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

--
-- Dumping data for table `trip_gallery`
--

INSERT INTO `trip_gallery` (`id`, `trip_id`, `image_url`, `alt_text`, `sort_order`) VALUES
(5, 2, 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800', 'Taj Mahal Agra', 1),
(6, 2, 'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=800', 'India Gate Delhi', 2),
(7, 2, 'https://images.unsplash.com/photo-1573165087-f71065e7ad13?w=800', 'Red Fort Delhi', 3),
(8, 3, 'https://images.unsplash.com/photo-1587922546307-776227941871?w=800', 'Baga Beach Goa', 1),
(9, 3, 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?w=800', 'Goa Beach Sunset', 2),
(10, 3, 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?w=800', 'Dudhsagar Falls', 3),
(11, 4, 'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=800', 'Phi Phi Islands Thailand', 1),
(12, 4, 'https://images.unsplash.com/photo-1506665531195-3566af2b4dfa?w=800', 'Phuket Beach', 2),
(13, 4, 'https://images.unsplash.com/photo-1490077476659-095159692ab5?w=800', 'James Bond Island', 3),
(14, 5, 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800', 'Burj Khalifa Dubai', 1),
(15, 5, 'https://images.unsplash.com/photo-1547637589-f54c34f5d7a4?w=800', 'Dubai Marina', 2),
(16, 5, 'https://images.unsplash.com/photo-1582672060674-bc2bd808a8b5?w=800', 'Desert Safari Dubai', 3),
(21, 5, 'http://localhost/ibcctrip/uploads/dubai-luxury-experience-gallery-4.png', '', 3);

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

--
-- Dumping data for table `trip_itinerary`
--

INSERT INTO `trip_itinerary` (`id`, `trip_id`, `day_number`, `title`, `description`, `meals`, `accommodation`) VALUES
(4, 2, 1, 'Arrival Delhi — Capital of Contrasts', 'Arrive at Delhi\'s Indira Gandhi International Airport. Hotel check-in. Afternoon: India Gate & Rajpath. Evening stroll in Connaught Place. Welcome dinner.', 'Dinner', 'Delhi Premium Hotel'),
(5, 2, 2, 'Delhi Sightseeing', 'Old Delhi: Red Fort (exterior), Jama Masjid, Chandni Chowk food walk (parathas at Paranthe Wali Gali). New Delhi: Qutub Minar, Humayun\'s Tomb. Evening Laser Show at Red Fort (seasonal).', 'Breakfast', 'Delhi Premium Hotel'),
(6, 2, 3, 'Delhi to Agra — City of the Taj', 'Drive to Agra (3–4 hrs via Yamuna Expressway). En route visit Sikandra (Akbar\'s Tomb). Afternoon: Agra Fort. Check-in. Sunset view of Taj Mahal from Mehtab Bagh.', 'Breakfast', 'Agra 4-star Hotel'),
(7, 2, 4, 'Taj Mahal & Fatehpur Sikri', 'Sunrise visit to the Taj Mahal — the most romantic monument on earth. Post-breakfast: Fatehpur Sikri (Mughal ghost town, 40 km). Drive to Jaipur via scenic highway.', 'Breakfast', 'Jaipur 4-star Hotel'),
(8, 2, 5, 'Jaipur — The Pink City', 'Morning: Amber Fort elephant ride, Sheesh Mahal. Afternoon: City Palace, Jantar Mantar, Hawa Mahal. Evening: traditional Rajasthani dinner with folk music and dance performance.', 'Breakfast, Dinner', 'Jaipur 4-star Hotel'),
(9, 2, 6, 'Jaipur Markets & Departure', 'Morning bazaar walk — block prints, blue pottery, lac bangles. Drive to Delhi airport for departure. Tour ends.', 'Breakfast', '—'),
(10, 3, 1, 'Goa Arrival — Beach Life Begins', 'Arrive at Goa (Dabolim/Mopa Airport). Check into beachside resort. Afternoon: relax at Baga Beach. Sunset at Anjuna Flea Market. Welcome dinner at a seafood shack on the beach.', 'Dinner', 'Goa Beach Resort'),
(11, 3, 2, 'North Goa Beach Hopping & Water Sports', 'Calangute and Baga Beach: jet skiing, parasailing, banana boat rides. Afternoon: Aguada Fort. Evening: Cruise on the Mandovi River with cultural show and Goan dinner.', 'Breakfast', 'Goa Beach Resort'),
(12, 3, 3, 'Dudhsagar Waterfall & Spice Plantation', 'Full day excursion to Dudhsagar Falls (monsoon of milk) via 4x4 jeep safari. Visit a spice plantation with authentic lunch. Return to resort. Free evening at leisure.', 'Breakfast, Lunch', 'Goa Beach Resort'),
(13, 3, 4, 'Old Goa & Departure', 'Morning: Old Goa churches — Basilica of Bom Jesus (UNESCO), Se Cathedral. Souvenirs at local market. Afternoon transfer to airport.', 'Breakfast', '—'),
(14, 4, 1, 'Welcome to Phuket — Island Paradise', 'Arrive at Phuket International Airport. Transfer to resort. Afternoon: Patong Beach exploration and shopping at Jungceylon Mall. Welcome dinner at a Thai restaurant.', 'Dinner', 'Phuket 4-star Resort'),
(15, 4, 2, 'Phi Phi Islands Speedboat Tour', 'Full-day speedboat tour: Phi Phi Don, Phi Phi Leh (Maya Bay famously seen in The Beach), Viking Cave, Monkey Beach. Snorkeling in coral-filled waters. Return by sunset.', 'Breakfast', 'Phuket 4-star Resort'),
(16, 4, 3, 'Phang Nga Bay — James Bond Island', 'Kayak through sea caves and mangroves. James Bond Island (The Man with the Golden Gun filming location). Panyee Floating Village. Afternoon: Tiger Kingdom or Big Buddha.', 'Breakfast', 'Phuket 4-star Resort'),
(17, 4, 4, 'Thai Cooking Class & Free Day', 'Morning: authentic Thai cooking class — learn to make Pad Thai, Tom Yum, Mango Sticky Rice. Afternoon free for shopping at Patong or spa. Evening: Muay Thai show.', 'Breakfast', 'Phuket 4-star Resort'),
(18, 4, 5, 'Departure Day', 'Morning at leisure. Enjoy resort amenities. Afternoon transfer to Phuket Airport for departure. Safe travels!', 'Breakfast', '—');

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

--
-- Dumping data for table `trip_videos`
--

INSERT INTO `trip_videos` (`id`, `trip_id`, `title`, `youtube_url`, `thumbnail`) VALUES
(10, 5, NULL, 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4', NULL);

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
(2, 'Rajesh Sharma', 'rajesh@example.com', '0987654321', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, 1, '2026-03-11 10:56:35', '2026-03-14 08:06:22'),
(3, 'Priya Singh', 'priya@example.com', '9822345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, 1, '2026-03-11 10:56:35', '2026-03-11 10:56:35'),
(4, 'Amit Patel', 'amit@example.com', '9833456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, 1, '2026-03-11 10:56:35', '2026-03-11 10:56:35'),
(5, 'Sunita Verma', 'sunita@example.com', '9844567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, 1, '2026-03-11 10:56:35', '2026-03-11 10:56:35'),
(6, 'Mohammed Khan', 'mohammed@example.com', '9855678901', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, 1, '2026-03-11 10:56:35', '2026-03-11 10:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

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
-- Indexes for table `seo_meta`
--
ALTER TABLE `seo_meta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_page` (`page_type`,`reference_id`);

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
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `seo_meta`
--
ALTER TABLE `seo_meta`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=479;

--
-- AUTO_INCREMENT for table `site_stats`
--
ALTER TABLE `site_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `trip_gallery`
--
ALTER TABLE `trip_gallery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `trip_itinerary`
--
ALTER TABLE `trip_itinerary`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `trip_videos`
--
ALTER TABLE `trip_videos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

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
