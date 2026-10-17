-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2026 at 03:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_portfolio`
--

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_items`
--

CREATE TABLE `portfolio_items` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT '',
  `project_url` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolio_items`
--

INSERT INTO `portfolio_items` (`id`, `title`, `description`, `image`, `project_url`) VALUES
(1, 'عمل اسود', 'عمل مشاكل وضرب 3 انفار', 'img_6ad0cb5a6be25.jpeg', 'google.com');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section_key` varchar(100) DEFAULT NULL,
  `section_name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT 1,
  `display_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section_key`, `section_name`, `is_active`, `display_order`) VALUES
(1, 'hero', 'الهيرو سكشن', 1, 1),
(2, 'services', 'خدماتنا', 1, 3),
(3, 'portfolio', 'معرض الأعمال', 1, 2),
(4, 'testimonials', 'آراء العملاء', 1, 4),
(5, 'contact', 'تواصل معنا', 1, 5),
(6, 'about', 'نبذة عني', 1, 2),
(7, 'skills', 'المهارات والإحصائيات', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `image`) VALUES
(1, 'خدمة الغسيل', 'بغسل هدوم وستاير وملايات', 'img_6ad0cb948b6d6.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_title` varchar(255) DEFAULT 'CodeExport',
  `logo_type` varchar(20) DEFAULT 'text',
  `logo_image` varchar(255) DEFAULT '',
  `hero_title` varchar(255) DEFAULT '',
  `hero_desc` text DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT '',
  `primary_color` varchar(50) DEFAULT '#4f46e5',
  `whatsapp` varchar(50) DEFAULT '',
  `linkedin` varchar(255) DEFAULT '',
  `telegram` varchar(255) DEFAULT '',
  `footer_text` varchar(255) DEFAULT 'جميع الحقوق محفوظة',
  `about_title` varchar(255) DEFAULT 'نبذة عني',
  `about_subtitle` varchar(255) DEFAULT 'مطور ويب وشغوف ببناء الحلول الرقمية',
  `about_text` text DEFAULT NULL,
  `btn_style` varchar(50) DEFAULT 'jelly',
  `btn_text_color` varchar(50) DEFAULT '#ffffff',
  `font_family` varchar(100) DEFAULT 'Tajawal',
  `card_theme` varchar(50) DEFAULT 'soft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_title`, `logo_type`, `logo_image`, `hero_title`, `hero_desc`, `hero_image`, `primary_color`, `whatsapp`, `linkedin`, `telegram`, `footer_text`, `about_title`, `about_subtitle`, `about_text`, `btn_style`, `btn_text_color`, `font_family`, `card_theme`) VALUES
(1, 'بروفايل', 'image', 'img_6ad0cb00a9ae3.jpeg', 'عباس محمد ابراهيم محمد احمد النور علي ادم ابراهيم', 'نحول أفكارك إلى مواقع وتطبيقات', 'img_6ad0cb00a9dec.jpeg', '#ffffff', '249924300246', 'google.com', 'chatgpt.com', 'جميع الحقوق محفوظة', 'نبذة عني', '', 'بهذه الطريقة ستكون قاعدة البيانات نظيفة ومتوافقة 100% مع الملفات البرمجية الحالية (بدون حقل الصور الخاص بالنبذة)، وسيعمل قسم \"نبذة عني\" بالنصوص والسيرة الذاتية والشغف بشكل سلس ومريح تماماً!', 'capsule', '#000000', 'Tajawal', 'flat');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `skill_name` varchar(255) NOT NULL,
  `percentage` int(11) DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `skill_name`, `percentage`) VALUES
(1, 'php', 100),
(2, 'css', 88);

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE `stats` (
  `id` int(11) NOT NULL,
  `stat_number` varchar(50) NOT NULL,
  `stat_title` varchar(255) NOT NULL,
  `icon_class` varchar(100) DEFAULT 'fa-solid fa-star'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stats`
--

INSERT INTO `stats` (`id`, `stat_number`, `stat_title`, `icon_class`) VALUES
(5, '7', 'سنوات الخبرة', 'fa-solid fa-briefcase'),
(6, '80', 'مشروع', 'fa-solid fa-folder-open'),
(8, '50', 'عملاء سعداء', 'fa-solid fa-users'),
(9, '80', 'تقييمات مميزة', 'fa-solid fa-star'),
(10, '35', 'جائزة', 'fa-solid fa-award'),
(12, '35', 'جائزة', 'fa-solid fa-award');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `client_role` varchar(100) DEFAULT '',
  `client_image` varchar(255) DEFAULT '',
  `feedback` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `client_name`, `client_role`, `client_image`, `feedback`) VALUES
(1, 'عباس هيثم', 'ترزي', 'img_6ad0cb7792ea6.jpeg', 'ماعندي اي راي المهم مشروع شغال');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `portfolio_items`
--
ALTER TABLE `portfolio_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `section_key` (`section_key`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stats`
--
ALTER TABLE `stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `portfolio_items`
--
ALTER TABLE `portfolio_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stats`
--
ALTER TABLE `stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
