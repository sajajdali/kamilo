-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2018 at 01:53 PM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rejimfit`
--

-- --------------------------------------------------------

--
-- Table structure for table `selp_blogs`
--

DROP TABLE IF EXISTS `selp_blogs`;
CREATE TABLE `selp_blogs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `home_text` text COLLATE utf8_unicode_ci NOT NULL,
  `body_text` text COLLATE utf8_unicode_ci NOT NULL,
  `counter` int(11) NOT NULL,
  `comment` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `show_at_home_top` tinyint(1) NOT NULL DEFAULT '0',
  `show_at_home_bottom` tinyint(1) NOT NULL DEFAULT '0',
  `main_page` tinyint(4) NOT NULL DEFAULT '1',
  `details` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_blogs`
--

INSERT INTO `selp_blogs` (`id`, `user_id`, `title`, `home_text`, `body_text`, `counter`, `comment`, `type`, `published`, `show_at_home_top`, `show_at_home_bottom`, `main_page`, `details`, `created_at`, `updated_at`) VALUES
(1, 1, 'سیب شسیب سیشب', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>شیسب شسیب شسیب</p>\r\n</body>\r\n</html>', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>شیس بیسش بشیس ب</p>\r\n</body>\r\n</html>', 0, 1, 0, 1, 0, 0, 1, '[]', '2018-07-29 11:37:50', '2018-07-31 06:05:07');

-- --------------------------------------------------------

--
-- Table structure for table `selp_blog_categories`
--

DROP TABLE IF EXISTS `selp_blog_categories`;
CREATE TABLE `selp_blog_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `counter` int(11) NOT NULL,
  `parent` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_blog_category`
--

DROP TABLE IF EXISTS `selp_blog_category`;
CREATE TABLE `selp_blog_category` (
  `blog_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_blog_comments`
--

DROP TABLE IF EXISTS `selp_blog_comments`;
CREATE TABLE `selp_blog_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `blog_id` int(10) UNSIGNED NOT NULL,
  `parent` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_blog_meta`
--

DROP TABLE IF EXISTS `selp_blog_meta`;
CREATE TABLE `selp_blog_meta` (
  `id` int(10) UNSIGNED NOT NULL,
  `blog_id` int(10) UNSIGNED NOT NULL,
  `meta_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_blog_meta`
--

INSERT INTO `selp_blog_meta` (`id`, `blog_id`, `meta_key`, `meta_value`) VALUES
(5, 1, 'MAIN_IMAGE', '46');

-- --------------------------------------------------------

--
-- Table structure for table `selp_educational`
--

DROP TABLE IF EXISTS `selp_educational`;
CREATE TABLE `selp_educational` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_educationalable`
--

DROP TABLE IF EXISTS `selp_educationalable`;
CREATE TABLE `selp_educationalable` (
  `educational_id` int(10) UNSIGNED NOT NULL,
  `educationable_id` int(10) UNSIGNED NOT NULL,
  `educationalable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_educational_packages`
--

DROP TABLE IF EXISTS `selp_educational_packages`;
CREATE TABLE `selp_educational_packages` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `category` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_educational_package_metas`
--

DROP TABLE IF EXISTS `selp_educational_package_metas`;
CREATE TABLE `selp_educational_package_metas` (
  `id` int(10) UNSIGNED NOT NULL,
  `educational_package_id` int(10) UNSIGNED NOT NULL,
  `meta_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_educational_package_pivot`
--

DROP TABLE IF EXISTS `selp_educational_package_pivot`;
CREATE TABLE `selp_educational_package_pivot` (
  `educational_package_id` int(10) UNSIGNED NOT NULL,
  `term_id` int(10) UNSIGNED NOT NULL,
  `term_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_exam_packages`
--

DROP TABLE IF EXISTS `selp_exam_packages`;
CREATE TABLE `selp_exam_packages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `counter` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_exam_user_packages`
--

DROP TABLE IF EXISTS `selp_exam_user_packages`;
CREATE TABLE `selp_exam_user_packages` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `total_exam` tinyint(4) NOT NULL,
  `used_exam` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_files`
--

DROP TABLE IF EXISTS `selp_files`;
CREATE TABLE `selp_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_media`
--

DROP TABLE IF EXISTS `selp_media`;
CREATE TABLE `selp_media` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` char(200) COLLATE utf8_unicode_ci NOT NULL,
  `path` text COLLATE utf8_unicode_ci NOT NULL,
  `type` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_media`
--

INSERT INTO `selp_media` (`id`, `user_id`, `name`, `path`, `type`, `created_at`, `updated_at`) VALUES
(26, 1, 'SecretQuestion-Password', 'SecretQuestion-Password.pdf', 'application/pdf', '2018-07-19 07:34:50', '2018-07-19 07:34:50'),
(27, 1, 'winter_snowfall_2-wallpaper-1920x1080', 'winter_snowfall_2-wallpaper-1920x1080.jpg', 'image/jpeg', '2018-07-19 07:35:51', '2018-07-19 07:35:51'),
(28, 1, 'beautiful_red_rose_dew_drops-wallpaper-1600x900', 'beautiful_red_rose_dew_drops-wallpaper-1600x900.jpg', 'image/jpeg', '2018-07-19 07:36:36', '2018-07-19 07:36:36'),
(29, 1, 'SecretQuestion-Password2', 'SecretQuestion-Password2.pdf', 'application/pdf', '2018-07-19 07:37:34', '2018-07-19 07:37:34'),
(30, 1, 'very_beautiful_red_rose_flower-wallpaper-1600x900', 'very_beautiful_red_rose_flower-wallpaper-1600x900.jpg', 'image/jpeg', '2018-07-19 07:49:02', '2018-07-19 07:49:02'),
(31, 1, 'milky_way_mountain_by_yakub_nihat-wallpaper-1600x900', 'milky_way_mountain_by_yakub_nihat-wallpaper-1600x900.jpg', 'image/jpeg', '2018-07-19 08:09:34', '2018-07-19 08:09:34'),
(32, 1, 'pinkish_sunset-wallpaper-1920x1080', 'pinkish_sunset-wallpaper-1920x1080.jpg', 'image/jpeg', '2018-07-19 08:09:37', '2018-07-19 08:09:37'),
(34, 1, 'moscow_international_business_center_russia-wallpaper-1600x900', 'moscow_international_business_center_russia-wallpaper-1600x900.jpg', 'image/jpeg', '2018-07-19 08:09:49', '2018-07-19 08:09:49'),
(36, 1, 'question__5b125e5cf3862', 'question__5b125e5cf3862.png', 'image/png', '2018-07-19 08:16:07', '2018-07-19 08:16:07'),
(39, 1, 'Schermafbeelding-2017-10-27-om-18.10.27', 'Schermafbeelding-2017-10-27-om-18.10.27.jpg', 'image/jpeg', '2018-07-19 08:27:35', '2018-07-19 08:27:35'),
(44, 1, 'photo_۲۰۱۷-۰۸-۲۰_۲۲-۴۶-۱۸', 'photo_۲۰۱۷-۰۸-۲۰_۲۲-۴۶-۱۸.jpg', 'image/jpeg', '2018-07-21 07:21:47', '2018-07-21 07:21:47'),
(46, 1, 'amazing_mountain_milky_way_by_yakub_nihat-wallpaper-2560x1440', 'amazing_mountain_milky_way_by_yakub_nihat-wallpaper-2560x1440.jpg', 'image/jpeg', '2018-07-21 08:15:13', '2018-07-21 08:15:13'),
(47, 1, 'milky_way_mountain_by_yakub_nihat-wallpaper-1600x900-1', 'milky_way_mountain_by_yakub_nihat-wallpaper-1600x900-1.jpg', 'image/jpeg', '2018-07-21 08:16:47', '2018-07-21 08:16:47'),
(49, 1, 'c686382a5c7e90a872ce1b75089492fe', 'c686382a5c7e90a872ce1b75089492fe.jpg', 'image/jpeg', '2018-07-21 08:20:27', '2018-07-21 08:20:27'),
(51, 1, 'Lierac-Mesolift-Vitamin-Enriched-Fondant-Anti-Ageing-Cream-24ab55', 'Lierac-Mesolift-Vitamin-Enriched-Fondant-Anti-Ageing-Cream-24ab55.jpg', 'image/jpeg', '2018-07-21 08:32:50', '2018-07-21 08:32:50'),
(52, 1, 'atlas_book-wallpaper-1920x1080', 'atlas_book-wallpaper-1920x1080.jpg', 'image/jpeg', '2018-07-21 09:27:23', '2018-07-21 09:27:23'),
(53, 1, 'a_moonlit_night-wallpaper-1920x1080', 'a_moonlit_night-wallpaper-1920x1080.jpg', 'image/jpeg', '2018-07-21 09:28:44', '2018-07-21 09:28:44'),
(54, 1, '08-1', '08-1.jpg', 'image/jpeg', '2018-07-21 09:29:39', '2018-07-21 09:29:39'),
(55, 1, 'loader-1', 'loader-1.svg', 'image/svg+xml', '2018-07-21 09:29:58', '2018-07-21 09:29:58'),
(57, 1, 'logo', 'logo.gif', 'image/gif', '2018-07-21 11:30:08', '2018-07-21 11:30:08'),
(58, 1, 'cleaner-large', 'cleaner-large.png', 'image/png', '2018-07-21 11:34:46', '2018-07-21 11:34:46'),
(59, 1, 'pinkish_sunset-wallpaper-1920x1080-1', 'pinkish_sunset-wallpaper-1920x1080-1.jpg', 'image/jpeg', '2018-07-21 11:40:54', '2018-07-21 11:40:54'),
(60, 1, 'favicon', 'favicon.ico', 'image/x-icon', '2018-07-22 05:39:15', '2018-07-22 05:39:15');

-- --------------------------------------------------------

--
-- Table structure for table `selp_menu`
--

DROP TABLE IF EXISTS `selp_menu`;
CREATE TABLE `selp_menu` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `title` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `menu_key` char(100) COLLATE utf8_unicode_ci NOT NULL,
  `setting` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_menu`
--

INSERT INTO `selp_menu` (`id`, `title`, `menu_key`, `setting`) VALUES
(1, 'منو اصلی', 'main_menu', '');

-- --------------------------------------------------------

--
-- Table structure for table `selp_menu_items`
--

DROP TABLE IF EXISTS `selp_menu_items`;
CREATE TABLE `selp_menu_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `menu_id` smallint(5) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `link` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `priority` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_menu_items`
--

INSERT INTO `selp_menu_items` (`id`, `menu_id`, `parent_id`, `title`, `link`, `content`, `priority`) VALUES
(2, 1, 0, '{\"fa_title\":\"asdfasdfasdf\"}', 'asdfasdfasdf', '', 0),
(3, 1, 2, '{\"fa_title\":\"asdfasdfasdf\"}', 'asdfasdfasdf', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `selp_migrations`
--

DROP TABLE IF EXISTS `selp_migrations`;
CREATE TABLE `selp_migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_migrations`
--

INSERT INTO `selp_migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_05_23_094312_create_user_metas_table', 1),
(4, '2018_05_23_095838_create_permission_tables', 1),
(5, '2018_05_26_061232_create_pages_table', 1),
(6, '2018_05_28_072021_create_educational_table', 1),
(7, '2018_05_28_072129_create_educational_pivot_table', 1),
(8, '2018_05_28_102805_create_blogs_table', 1),
(9, '2018_05_28_102829_create_blog_categories_table', 1),
(10, '2018_05_28_103144_create_blog_category_table', 1),
(11, '2018_05_28_103359_create_blog_comments_table', 1),
(12, '2018_05_30_065208_create_blog_meta_table', 1),
(13, '2018_05_30_102001_create_exam_packages_table', 1),
(14, '2018_06_02_044800_create_questions_table', 1),
(15, '2018_06_02_045055_create_question_metas_table', 1),
(16, '2018_06_03_095908_create_parts_table', 1),
(17, '2018_06_03_095918_create_tickets_table', 1),
(18, '2018_06_03_102011_create_part_user_table', 1),
(19, '2018_06_11_065619_create_varify_codes_table', 1),
(20, '2018_06_15_091748_create_user_tokens_table', 1),
(21, '2018_06_17_095744_create_transactions_table', 1),
(22, '2018_06_17_100458_create_payment_logs_table', 1),
(23, '2018_06_20_080522_create_exam_user_packages_table', 1),
(24, '2018_06_23_054759_create_user_exams_table', 1),
(25, '2018_06_23_055603_create_user_exam_question_table', 1),
(26, '2018_06_23_161226_create_files_table', 1),
(27, '2018_06_23_161253_create_educational_packages_table', 1),
(28, '2018_06_23_161300_create_educational_package_metas_table', 1),
(29, '2018_06_25_162659_create_educational_package_pivot_table', 1),
(30, '2018_07_02_082051_create_static_text_table', 1),
(35, '2018_07_08_111035_create_menu_table', 2),
(36, '2018_07_08_113031_create_menu_items_table', 2),
(37, '2018_07_10_121659_create_options_table', 3),
(39, '2018_07_16_112407_create_media_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `selp_model_has_permissions`
--

DROP TABLE IF EXISTS `selp_model_has_permissions`;
CREATE TABLE `selp_model_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_model_has_permissions`
--

INSERT INTO `selp_model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `selp_model_has_roles`
--

DROP TABLE IF EXISTS `selp_model_has_roles`;
CREATE TABLE `selp_model_has_roles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_model_has_roles`
--

INSERT INTO `selp_model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(8, 'App\\User', 2);

-- --------------------------------------------------------

--
-- Table structure for table `selp_options`
--

DROP TABLE IF EXISTS `selp_options`;
CREATE TABLE `selp_options` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` char(200) COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_options`
--

INSERT INTO `selp_options` (`id`, `name`, `value`) VALUES
(1, 'blogname', 'رژیم فیت'),
(2, 'admin_email', ''),
(3, 'pmw_setting', '{\"slider_slides\":{\"title\":[\"طراح خودت باش\",\"طراح خودت باش\",\"طراح خودت باش\",\"طراح خودت باش\",\"طراح خودت باش\"],\"url\":[\"0\",\"0\",\"1\",\"1\",\"1\"],\"choices\":[\"choice4\",\"choice1\",\"choice4\",\"choice3\",\"choice3\"],\"image\":[\"36\",\"52\",\"31\",\"55\",\"55\"]}}'),
(5, 'site_name', 'رژیم فیت'),
(6, 'logo', '58'),
(7, 'radio_test', 'value2'),
(8, 'checkbox_test', '1'),
(9, 'adfadsfadsf', ''),
(10, 'test2', '3');

-- --------------------------------------------------------

--
-- Table structure for table `selp_pages`
--

DROP TABLE IF EXISTS `selp_pages`;
CREATE TABLE `selp_pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `counter` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_parts`
--

DROP TABLE IF EXISTS `selp_parts`;
CREATE TABLE `selp_parts` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `setting` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_part_user`
--

DROP TABLE IF EXISTS `selp_part_user`;
CREATE TABLE `selp_part_user` (
  `part_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_password_resets`
--

DROP TABLE IF EXISTS `selp_password_resets`;
CREATE TABLE `selp_password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_payment_logs`
--

DROP TABLE IF EXISTS `selp_payment_logs`;
CREATE TABLE `selp_payment_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `section` tinyint(4) NOT NULL DEFAULT '0',
  `ref_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tracking_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pay_at` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_permissions`
--

DROP TABLE IF EXISTS `selp_permissions`;
CREATE TABLE `selp_permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_permissions`
--

INSERT INTO `selp_permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'SUPERADMIN_ACCESS', 'web', '2018-07-03 19:30:00', '2018-07-03 19:30:00'),
(2, 'ADMIN', 'web', '2018-07-03 19:30:00', '2018-07-03 19:30:00'),
(3, 'admin_pages', 'web', '2018-07-03 19:30:00', '2018-07-03 19:30:00'),
(5, 'view_admin_statictext', 'web', '2018-07-05 08:02:46', '2018-07-05 08:02:46'),
(6, 'edit_admin_statictext', 'web', '2018-07-05 08:02:46', '2018-07-05 08:02:46'),
(7, 'delete_admin_statictext', 'web', '2018-07-05 08:02:46', '2018-07-05 08:02:46'),
(8, 'admin_StaticText_access', 'web', '2018-07-05 08:03:32', '2018-07-05 08:03:32'),
(9, 'admin_Options_access', 'web', '2018-07-23 06:15:38', '2018-07-23 06:15:38'),
(10, 'show_options_in_admin', 'web', '2018-07-23 06:15:39', '2018-07-23 06:15:39'),
(11, 'update_options_in_admin', 'web', '2018-07-23 06:15:39', '2018-07-23 06:15:39'),
(21, 'add_admin_role', 'web', '2018-07-23 06:15:39', '2018-07-23 06:15:39'),
(22, 'view_admin_role', 'web', '2018-07-23 06:15:39', '2018-07-23 06:15:39'),
(23, 'edit_admin_role', 'web', '2018-07-23 06:15:39', '2018-07-23 06:15:39'),
(24, 'delete_admin_role', 'web', '2018-07-23 06:15:39', '2018-07-23 06:15:39'),
(25, 'view_admin_user', 'web', '2018-07-22 19:30:00', '2018-07-22 19:30:00'),
(26, 'add_admin_user', 'web', '2018-07-22 19:30:00', '2018-07-22 19:30:00'),
(27, 'edit_admin_user', 'web', '2018-07-22 19:30:00', '2018-07-22 19:30:00'),
(28, 'delete_admin_user', 'web', '2018-07-22 19:30:00', '2018-07-22 19:30:00'),
(29, 'admin_all_user_access', 'web', '2018-07-22 19:30:00', '2018-07-22 19:30:00'),
(30, 'admin_limit_user_access', 'web', '2018-07-22 19:30:00', '2018-07-22 19:30:00'),
(31, 'admin_all_role_user_access', 'web', '2018-07-22 19:30:00', '2018-07-22 19:30:00'),
(32, 'admin_limit_role_user_access', 'web', '2018-07-22 19:30:00', '2018-07-22 19:30:00'),
(33, 'admin_blog_access', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(34, 'view_admin_blog', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(35, 'add_admin_blog', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(36, 'edit_admin_blog', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(37, 'delete_admin_blog', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(38, 'change_group_admin_blog', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(39, 'upload_admin_blog', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(40, 'remove_image_admin_blog', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(41, 'change_status_admin_blog', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(42, 'view_admin_blog_category', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(43, 'add_admin_blog_category', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(44, 'edit_admin_blog_category', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(45, 'delete_admin_blog_category', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(46, 'delete_group_admin_blog_category', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(47, 'view_admin_blog_comments', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(48, 'add_admin_blog_comments', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(49, 'edit_admin_blog_comments', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(50, 'change_group_admin_blog_comments', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(51, 'change_status_admin_blog_comments', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(52, 'admin_all_blog_access', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(53, 'admin_limit_blog_access', 'web', '2018-07-23 06:48:18', '2018-07-23 06:48:18'),
(54, 'admin_page_access', 'web', '2018-07-23 06:49:30', '2018-07-23 06:49:30'),
(55, 'view_admin_pages', 'web', '2018-07-23 06:49:31', '2018-07-23 06:49:31'),
(56, 'edit_admin_pages', 'web', '2018-07-23 06:49:31', '2018-07-23 06:49:31'),
(57, 'add_admin_pages', 'web', '2018-07-23 06:49:31', '2018-07-23 06:49:31'),
(58, 'delete_admin_pages', 'web', '2018-07-23 06:49:31', '2018-07-23 06:49:31'),
(59, 'admin_all_page_access', 'web', '2018-07-23 06:49:31', '2018-07-23 06:49:31'),
(60, 'admin_limit_page_access', 'web', '2018-07-23 06:49:31', '2018-07-23 06:49:31'),
(61, 'SUPERADMIN', 'web', '2018-07-03 19:30:00', '2018-07-03 19:30:00'),
(62, 'admin_personalize_access', 'web', '2018-07-29 09:46:06', '2018-07-29 09:46:06'),
(63, 'personalize_edit_menus', 'web', '2018-07-29 09:46:06', '2018-07-29 09:46:06');

-- --------------------------------------------------------

--
-- Table structure for table `selp_questions`
--

DROP TABLE IF EXISTS `selp_questions`;
CREATE TABLE `selp_questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `difficulty` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `option_1` longtext COLLATE utf8_unicode_ci NOT NULL,
  `option_2` longtext COLLATE utf8_unicode_ci NOT NULL,
  `option_3` longtext COLLATE utf8_unicode_ci NOT NULL,
  `option_4` longtext COLLATE utf8_unicode_ci NOT NULL,
  `answer` tinyint(4) NOT NULL,
  `question_type` tinyint(4) NOT NULL,
  `main_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `direction` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `describtion` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_question_metas`
--

DROP TABLE IF EXISTS `selp_question_metas`;
CREATE TABLE `selp_question_metas` (
  `id` int(10) UNSIGNED NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `meta_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_roles`
--

DROP TABLE IF EXISTS `selp_roles`;
CREATE TABLE `selp_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `guard_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_roles`
--

INSERT INTO `selp_roles` (`id`, `name`, `display_name`, `guard_name`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'مدیر کل', 'web', 1, '2018-07-03 19:30:00', '2018-07-03 19:30:00'),
(8, 'مدیر', 'مدیر', 'web', 0, '2018-07-23 06:25:48', '2018-07-23 06:25:48');

-- --------------------------------------------------------

--
-- Table structure for table `selp_role_has_permissions`
--

DROP TABLE IF EXISTS `selp_role_has_permissions`;
CREATE TABLE `selp_role_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_role_has_permissions`
--

INSERT INTO `selp_role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(2, 8),
(9, 8),
(10, 8),
(21, 8),
(22, 8),
(23, 8),
(24, 8),
(25, 8),
(26, 8),
(27, 8),
(28, 8),
(29, 8),
(31, 8),
(52, 8),
(59, 8);

-- --------------------------------------------------------

--
-- Table structure for table `selp_static_text`
--

DROP TABLE IF EXISTS `selp_static_text`;
CREATE TABLE `selp_static_text` (
  `key` char(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` char(100) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `has_editor` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_static_text`
--

INSERT INTO `selp_static_text` (`key`, `name`, `category`, `value`, `has_editor`, `created_at`, `updated_at`) VALUES
('site_name', 'عنوان سایت', 'FRONTEND', 'رژیم فیت', '0', '2018-07-06 19:30:00', '2018-07-06 19:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `selp_tickets`
--

DROP TABLE IF EXISTS `selp_tickets`;
CREATE TABLE `selp_tickets` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `doctor_id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `koa` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priority` tinyint(4) NOT NULL DEFAULT '1',
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_transactions`
--

DROP TABLE IF EXISTS `selp_transactions`;
CREATE TABLE `selp_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `au` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `bank_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `transaction_meta` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_users`
--

DROP TABLE IF EXISTS `selp_users`;
CREATE TABLE `selp_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_users`
--

INSERT INTO `selp_users` (`id`, `mobile`, `email`, `password`, `remember_token`, `last_login`, `created_at`, `updated_at`) VALUES
(1, '09386803231', 'info@selakpardaz.com', '$2y$10$ClkbhlrJByip2xme1229WeWj3ocwhJp0HZMRQlmFlG4NOgek0WqnO', 'KcNKlR37MuRmX2GrhAaMeHeDLXGXpFrf57NoWSeUgNx8r6ThnLR7SOt2XRwX', '2018-07-31 15:13:58', '2018-07-05 06:56:49', '2018-07-31 10:43:58'),
(2, '09379201889', 'payam@selakpardaz.com', '$2y$10$cSA1riZF0PXNP/8vHzKJZOkRt/AvNn4EJsWQIUizbRAncdfS5MZAS', 'AOG0rUj3bxYLaJ533BfdVfWiHIesqAdWs4yQffnVDNiZFMsD9MaiCIsWDHS1', '2018-07-23 10:59:46', '2018-07-11 06:16:42', '2018-07-23 06:29:46');

-- --------------------------------------------------------

--
-- Table structure for table `selp_user_exams`
--

DROP TABLE IF EXISTS `selp_user_exams`;
CREATE TABLE `selp_user_exams` (
  `id` int(10) UNSIGNED NOT NULL,
  `exam_user_package_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_user_exam_question`
--

DROP TABLE IF EXISTS `selp_user_exam_question`;
CREATE TABLE `selp_user_exam_question` (
  `user_exam_id` int(10) UNSIGNED NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_user_metas`
--

DROP TABLE IF EXISTS `selp_user_metas`;
CREATE TABLE `selp_user_metas` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `meta_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `selp_user_metas`
--

INSERT INTO `selp_user_metas` (`id`, `user_id`, `meta_key`, `meta_value`, `created_at`, `updated_at`) VALUES
(1, 2, 'FIRST_NAME', 'payam', '2018-07-11 06:16:42', '2018-07-11 06:16:42'),
(2, 2, 'LAST_NAME', 'jafari', '2018-07-11 06:16:42', '2018-07-11 06:16:42'),
(3, 2, 'AGENT', '1', '2018-07-11 06:16:42', '2018-07-11 06:16:42'),
(4, 2, 'AVATAR', '2__img_5b45a0ca79d6f.jpg', '2018-07-11 06:16:42', '2018-07-11 06:16:42'),
(5, 2, 'BIRTHDAY_YEAR', '1397', '2018-07-23 06:28:42', '2018-07-23 06:28:42'),
(6, 2, 'BIRTHDAY_MONTH', '05', '2018-07-23 06:28:42', '2018-07-23 06:28:42'),
(7, 2, 'BIRTHDAY_DAY', '01', '2018-07-23 06:28:42', '2018-07-23 06:28:42');

-- --------------------------------------------------------

--
-- Table structure for table `selp_user_tokens`
--

DROP TABLE IF EXISTS `selp_user_tokens`;
CREATE TABLE `selp_user_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sdk` int(11) NOT NULL,
  `device_id` char(30) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selp_verify_codes`
--

DROP TABLE IF EXISTS `selp_verify_codes`;
CREATE TABLE `selp_verify_codes` (
  `id` int(10) UNSIGNED NOT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` char(4) COLLATE utf8_unicode_ci NOT NULL,
  `expire_at` datetime NOT NULL,
  `is_used` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `selp_blogs`
--
ALTER TABLE `selp_blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogs_id_index` (`id`),
  ADD KEY `blogs_user_id_index` (`user_id`);

--
-- Indexes for table `selp_blog_categories`
--
ALTER TABLE `selp_blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_categories_id_index` (`id`);

--
-- Indexes for table `selp_blog_category`
--
ALTER TABLE `selp_blog_category`
  ADD KEY `blog_category_blog_id_index` (`blog_id`),
  ADD KEY `blog_category_category_id_index` (`category_id`);

--
-- Indexes for table `selp_blog_comments`
--
ALTER TABLE `selp_blog_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_comments_blog_id_foreign` (`blog_id`),
  ADD KEY `blog_comments_id_index` (`id`);

--
-- Indexes for table `selp_blog_meta`
--
ALTER TABLE `selp_blog_meta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_meta_blog_id_index` (`blog_id`);

--
-- Indexes for table `selp_educational`
--
ALTER TABLE `selp_educational`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `selp_educationalable`
--
ALTER TABLE `selp_educationalable`
  ADD KEY `educationalable_educational_id_foreign` (`educational_id`),
  ADD KEY `educationalable_educationable_id_foreign` (`educationable_id`);

--
-- Indexes for table `selp_educational_packages`
--
ALTER TABLE `selp_educational_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `educational_packages_user_id_foreign` (`user_id`);

--
-- Indexes for table `selp_educational_package_metas`
--
ALTER TABLE `selp_educational_package_metas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `educational_package_metas_educational_package_id_foreign` (`educational_package_id`);

--
-- Indexes for table `selp_exam_packages`
--
ALTER TABLE `selp_exam_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `selp_exam_user_packages`
--
ALTER TABLE `selp_exam_user_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_user_packages_user_id_foreign` (`user_id`),
  ADD KEY `exam_user_packages_package_id_foreign` (`package_id`),
  ADD KEY `exam_user_packages_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `selp_files`
--
ALTER TABLE `selp_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_user_id_foreign` (`user_id`);

--
-- Indexes for table `selp_media`
--
ALTER TABLE `selp_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `selp_menu`
--
ALTER TABLE `selp_menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menu_id_unique` (`id`);

--
-- Indexes for table `selp_menu_items`
--
ALTER TABLE `selp_menu_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menu_items_id_unique` (`id`),
  ADD KEY `menu_items_menu_id_index` (`menu_id`);

--
-- Indexes for table `selp_migrations`
--
ALTER TABLE `selp_migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `selp_model_has_permissions`
--
ALTER TABLE `selp_model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `selp_model_has_roles`
--
ALTER TABLE `selp_model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `selp_options`
--
ALTER TABLE `selp_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `options_name_unique` (`name`);

--
-- Indexes for table `selp_pages`
--
ALTER TABLE `selp_pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pages_user_id_foreign` (`user_id`);

--
-- Indexes for table `selp_parts`
--
ALTER TABLE `selp_parts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `selp_part_user`
--
ALTER TABLE `selp_part_user`
  ADD KEY `part_user_part_id_index` (`part_id`),
  ADD KEY `part_user_user_id_index` (`user_id`);

--
-- Indexes for table `selp_password_resets`
--
ALTER TABLE `selp_password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `selp_payment_logs`
--
ALTER TABLE `selp_payment_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `selp_permissions`
--
ALTER TABLE `selp_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `selp_questions`
--
ALTER TABLE `selp_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_user_id_foreign` (`user_id`);

--
-- Indexes for table `selp_question_metas`
--
ALTER TABLE `selp_question_metas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_metas_question_id_foreign` (`question_id`);

--
-- Indexes for table `selp_roles`
--
ALTER TABLE `selp_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `selp_role_has_permissions`
--
ALTER TABLE `selp_role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `selp_static_text`
--
ALTER TABLE `selp_static_text`
  ADD PRIMARY KEY (`key`),
  ADD UNIQUE KEY `static_text_key_unique` (`key`);

--
-- Indexes for table `selp_tickets`
--
ALTER TABLE `selp_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `selp_transactions`
--
ALTER TABLE `selp_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `selp_users`
--
ALTER TABLE `selp_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `selp_user_exams`
--
ALTER TABLE `selp_user_exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_exams_exam_user_package_id_foreign` (`exam_user_package_id`),
  ADD KEY `user_exams_user_id_foreign` (`user_id`);

--
-- Indexes for table `selp_user_exam_question`
--
ALTER TABLE `selp_user_exam_question`
  ADD KEY `user_exam_question_user_exam_id_foreign` (`user_exam_id`),
  ADD KEY `user_exam_question_question_id_foreign` (`question_id`);

--
-- Indexes for table `selp_user_metas`
--
ALTER TABLE `selp_user_metas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_metas_user_id_foreign` (`user_id`);

--
-- Indexes for table `selp_user_tokens`
--
ALTER TABLE `selp_user_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_tokens_user_id_foreign` (`user_id`);

--
-- Indexes for table `selp_verify_codes`
--
ALTER TABLE `selp_verify_codes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `selp_blogs`
--
ALTER TABLE `selp_blogs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `selp_blog_categories`
--
ALTER TABLE `selp_blog_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_blog_comments`
--
ALTER TABLE `selp_blog_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_blog_meta`
--
ALTER TABLE `selp_blog_meta`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `selp_educational`
--
ALTER TABLE `selp_educational`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_educational_packages`
--
ALTER TABLE `selp_educational_packages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_educational_package_metas`
--
ALTER TABLE `selp_educational_package_metas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_exam_packages`
--
ALTER TABLE `selp_exam_packages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_exam_user_packages`
--
ALTER TABLE `selp_exam_user_packages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_files`
--
ALTER TABLE `selp_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_media`
--
ALTER TABLE `selp_media`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `selp_menu`
--
ALTER TABLE `selp_menu`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `selp_menu_items`
--
ALTER TABLE `selp_menu_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `selp_migrations`
--
ALTER TABLE `selp_migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `selp_options`
--
ALTER TABLE `selp_options`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `selp_pages`
--
ALTER TABLE `selp_pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_parts`
--
ALTER TABLE `selp_parts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_payment_logs`
--
ALTER TABLE `selp_payment_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_permissions`
--
ALTER TABLE `selp_permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `selp_questions`
--
ALTER TABLE `selp_questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_question_metas`
--
ALTER TABLE `selp_question_metas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_roles`
--
ALTER TABLE `selp_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `selp_tickets`
--
ALTER TABLE `selp_tickets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_transactions`
--
ALTER TABLE `selp_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_users`
--
ALTER TABLE `selp_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `selp_user_exams`
--
ALTER TABLE `selp_user_exams`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_user_metas`
--
ALTER TABLE `selp_user_metas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `selp_user_tokens`
--
ALTER TABLE `selp_user_tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selp_verify_codes`
--
ALTER TABLE `selp_verify_codes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `selp_blogs`
--
ALTER TABLE `selp_blogs`
  ADD CONSTRAINT `blogs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `selp_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `selp_blog_category`
--
ALTER TABLE `selp_blog_category`
  ADD CONSTRAINT `blog_category_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `selp_blogs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blog_category_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `selp_blog_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `selp_blog_comments`
--
ALTER TABLE `selp_blog_comments`
  ADD CONSTRAINT `blog_comments_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `selp_blogs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `selp_blog_meta`
--
ALTER TABLE `selp_blog_meta`
  ADD CONSTRAINT `blog_meta_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `selp_blogs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `selp_educationalable`
--
ALTER TABLE `selp_educationalable`
  ADD CONSTRAINT `educationalable_educationable_id_foreign` FOREIGN KEY (`educationable_id`) REFERENCES `selp_educational` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `educationalable_educational_id_foreign` FOREIGN KEY (`educational_id`) REFERENCES `selp_educational` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `selp_educational_packages`
--
ALTER TABLE `selp_educational_packages`
  ADD CONSTRAINT `educational_packages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `selp_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `selp_educational_package_metas`
--
ALTER TABLE `selp_educational_package_metas`
  ADD CONSTRAINT `educational_package_metas_educational_package_id_foreign` FOREIGN KEY (`educational_package_id`) REFERENCES `selp_educational_packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `selp_exam_user_packages`
--
ALTER TABLE `selp_exam_user_packages`
  ADD CONSTRAINT `exam_user_packages_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `selp_exam_packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_user_packages_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `selp_transactions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `exam_user_packages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `selp_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `selp_files`
--
ALTER TABLE `selp_files`
  ADD CONSTRAINT `files_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `selp_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `selp_menu_items`
--
ALTER TABLE `selp_menu_items`
  ADD CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `selp_menu` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `selp_model_has_permissions`
--
ALTER TABLE `selp_model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `selp_permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `selp_model_has_roles`
--
ALTER TABLE `selp_model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `selp_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `selp_pages`
--
ALTER TABLE `selp_pages`
  ADD CONSTRAINT `pages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `selp_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `selp_part_user`
--
ALTER TABLE `selp_part_user`
  ADD CONSTRAINT `part_user_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `selp_parts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `part_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `selp_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `selp_payment_logs`
--
ALTER TABLE `selp_payment_logs`
  ADD CONSTRAINT `payment_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `selp_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `selp_questions`
--
ALTER TABLE `selp_questions`
  ADD CONSTRAINT `questions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `selp_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `selp_question_metas`
--
ALTER TABLE `selp_question_metas`
  ADD CONSTRAINT `question_metas_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `selp_questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `selp_role_has_permissions`
--
ALTER TABLE `selp_role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `selp_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `selp_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `selp_transactions`
--
ALTER TABLE `selp_transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `selp_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `selp_user_exams`
--
ALTER TABLE `selp_user_exams`
  ADD CONSTRAINT `user_exams_exam_user_package_id_foreign` FOREIGN KEY (`exam_user_package_id`) REFERENCES `selp_exam_user_packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_exams_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `selp_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `selp_user_exam_question`
--
ALTER TABLE `selp_user_exam_question`
  ADD CONSTRAINT `user_exam_question_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `selp_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_exam_question_user_exam_id_foreign` FOREIGN KEY (`user_exam_id`) REFERENCES `selp_user_exams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `selp_user_metas`
--
ALTER TABLE `selp_user_metas`
  ADD CONSTRAINT `user_metas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `selp_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `selp_user_tokens`
--
ALTER TABLE `selp_user_tokens`
  ADD CONSTRAINT `user_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `selp_users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
