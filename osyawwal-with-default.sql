-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2014 at 02:45 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `osyawwal`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `satker_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `hostel` tinyint(1) DEFAULT '0',
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_history`
--

CREATE TABLE IF NOT EXISTS `activity_history` (
  `id` int(11) NOT NULL,
  `revision` int(11) NOT NULL,
  `satker_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `hostel` tinyint(1) DEFAULT '0',
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`revision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `activity_room`
--

CREATE TABLE IF NOT EXISTS `activity_room` (
  `activity_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `status` int(3) NOT NULL COMMENT '0=Waiting, 1=Process, 2=Approved, 3=Rejected',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`activity_id`,`room_id`),
  KEY `tb_room_id` (`room_id`),
  KEY `tb_training_id` (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('BPPK', '1', 1413761860);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('/admin/*', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/default/*', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/default/index', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/employee/*', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/employee/create', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/employee/delete', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/employee/index', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/employee/organisation', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/employee/update', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/employee/view', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/person/*', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/person/create', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/person/delete', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/person/index', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/person/update', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/person/view', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/user/*', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/user/block', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/user/create', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/user/delete', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/user/index', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/user/unblock', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/user/update', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/admin/user/view', 2, NULL, NULL, NULL, 1413763393, 1413763393),
('/gii/*', 2, NULL, NULL, NULL, 1413764009, 1413764009),
('/privilege/*', 2, NULL, NULL, NULL, 1413763971, 1413763971),
('Bagian Kepegawaian', 1, '3', NULL, NULL, NULL, NULL),
('Bagian Keuangan', 1, '3', NULL, NULL, NULL, NULL),
('Bagian Otl', 1, '3', NULL, NULL, NULL, NULL),
('Bagian Tata Usaha', 1, '3', NULL, NULL, NULL, NULL),
('Bagian Tata Usaha [PSDM]', 1, '3', NULL, NULL, NULL, NULL),
('Bagian Tik', 1, '3', NULL, NULL, NULL, NULL),
('Bagian Umum', 1, '3', NULL, NULL, NULL, NULL),
('BDK', 1, '3', NULL, NULL, NULL, NULL),
('Bidang Evaluasi Dan Pelaporan Kinerja', 1, '3', NULL, NULL, NULL, NULL),
('Bidang Penjenjangan Dan Peningkatan Kompetensi', 1, '3', NULL, NULL, NULL, NULL),
('Bidang Penyelenggaraan', 1, '3', NULL, NULL, NULL, NULL),
('Bidang Perencanaan Dan Pengembangan Diklat', 1, '3', NULL, NULL, NULL, NULL),
('BPPK', 1, '1', NULL, NULL, NULL, NULL),
('Pelaksana Bagian Kepegawaian', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Bagian Keuangan', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Bagian Otl', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Bagian Tik', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Bagian Umum', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Seksi Evaluasi Dan Informasi', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Seksi Penyelenggaraan', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbag Tata Usaha', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbagian Perencanaan Dan Keuangan [PSDM]', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbagian Perencanaan Keuangan', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbagian Rumah Tangga Dan Pengelolaan Aset', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbagian Rumah Tangga Dan Pengelolaan Aset [PSDM]', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbagian Tata Usaha, Kepegawaian, Dan Humas', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbagian Tata Usaha, Kepegawaian, Dan Humas [PSDM]', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbidang Evaluasi Dan Pelaporan Kinerja', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbidang Evaluasi Diklat', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbidang Informasi Dan Pelaporan Kinerja', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbidang Kurikulum', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbidang Pengolahan Hasil Diklat', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbidang Penyelenggaraan', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbidang Penyelenggaraan I', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbidang Penyelenggaraan II', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbidang Perencanaan Dan Pengembangan', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbidang Program', 1, '5', NULL, NULL, NULL, NULL),
('Pelaksana Subbidang Tenaga Pengajar', 1, '5', NULL, NULL, NULL, NULL),
('Pusdiklat', 1, '2', NULL, NULL, NULL, NULL),
('Pusdiklat PSDM', 1, '2', NULL, NULL, NULL, NULL),
('Sekretariat Badan', 1, '2', NULL, NULL, NULL, NULL),
('Seksi Evaluasi Dan Informasi', 1, '4', NULL, NULL, NULL, NULL),
('Seksi Penyelenggaraan', 1, '4', NULL, NULL, NULL, NULL),
('Subbag Tata Usaha', 1, '4', NULL, NULL, NULL, NULL),
('Subbagian Perencanaan Dan Keuangan', 1, '4', NULL, NULL, NULL, NULL),
('Subbagian Perencanaan Dan Keuangan  [PSDM]', 1, '4', NULL, NULL, NULL, NULL),
('Subbagian Rumah Tangga Dan Pengelolaan Aset ', 1, '4', NULL, NULL, NULL, NULL),
('Subbagian Rumah Tangga Dan Pengelolaan Aset  [PSDM]', 1, '4', NULL, NULL, NULL, NULL),
('Subbagian Tata Usaha, Kepegawaian , Dan Humas ', 1, '4', NULL, NULL, NULL, NULL),
('Subbagian Tata Usaha, Kepegawaian, Dan Humas  [PSDM]', 1, '4', NULL, NULL, NULL, NULL),
('Subbidang Evaluasi Dan Pelaporan Kinerja', 1, '4', NULL, NULL, NULL, NULL),
('Subbidang Evaluasi Diklat ', 1, '4', NULL, NULL, NULL, NULL),
('Subbidang Informasi Dan Pelaporan Kinerja ', 1, '4', NULL, NULL, NULL, NULL),
('Subbidang Kurikulum', 1, '4', NULL, NULL, NULL, NULL),
('Subbidang Pengolahan Hasil Diklat ', 1, '4', NULL, NULL, NULL, NULL),
('Subbidang Penyelenggaraan', 1, '4', NULL, NULL, NULL, NULL),
('Subbidang Penyelenggaraan I  ', 1, '4', NULL, NULL, NULL, NULL),
('Subbidang Penyelenggaraan II', 1, '4', NULL, NULL, NULL, NULL),
('Subbidang Perencanaan Dan Pengembangan', 1, '4', NULL, NULL, NULL, NULL),
('Subbidang Program ', 1, '4', NULL, NULL, NULL, NULL),
('Subbidang Tenaga Pengajar ', 1, '4', NULL, NULL, NULL, NULL),
('Super Administrator', 2, NULL, NULL, NULL, 1413763432, 1413763432);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('Super Administrator', '/admin/*'),
('Super Administrator', '/admin/default/*'),
('Super Administrator', '/admin/default/index'),
('Super Administrator', '/admin/employee/*'),
('Super Administrator', '/admin/employee/create'),
('Super Administrator', '/admin/employee/delete'),
('Super Administrator', '/admin/employee/index'),
('Super Administrator', '/admin/employee/organisation'),
('Super Administrator', '/admin/employee/update'),
('Super Administrator', '/admin/employee/view'),
('Super Administrator', '/admin/person/*'),
('Super Administrator', '/admin/person/create'),
('Super Administrator', '/admin/person/delete'),
('Super Administrator', '/admin/person/index'),
('Super Administrator', '/admin/person/update'),
('Super Administrator', '/admin/person/view'),
('Super Administrator', '/admin/user/*'),
('Super Administrator', '/admin/user/block'),
('Super Administrator', '/admin/user/create'),
('Super Administrator', '/admin/user/delete'),
('Super Administrator', '/admin/user/index'),
('Super Administrator', '/admin/user/unblock'),
('Super Administrator', '/admin/user/update'),
('Super Administrator', '/admin/user/view'),
('Super Administrator', '/gii/*'),
('Super Administrator', '/privilege/*'),
('Sekretariat Badan', 'Bagian Kepegawaian'),
('Sekretariat Badan', 'Bagian Keuangan'),
('Sekretariat Badan', 'Bagian Otl'),
('Pusdiklat', 'Bagian Tata Usaha'),
('Pusdiklat PSDM', 'Bagian Tata Usaha [PSDM]'),
('Sekretariat Badan', 'Bagian Tik'),
('Sekretariat Badan', 'Bagian Umum'),
('BPPK', 'BDK'),
('Pusdiklat', 'Bidang Evaluasi Dan Pelaporan Kinerja'),
('Pusdiklat PSDM', 'Bidang Penjenjangan Dan Peningkatan Kompetensi'),
('Pusdiklat', 'Bidang Penyelenggaraan'),
('Pusdiklat', 'Bidang Perencanaan Dan Pengembangan Diklat'),
('Bagian Kepegawaian', 'Pelaksana Bagian Kepegawaian'),
('Bagian Keuangan', 'Pelaksana Bagian Keuangan'),
('Bagian Otl', 'Pelaksana Bagian Otl'),
('Bagian Tik', 'Pelaksana Bagian Tik'),
('Bagian Umum', 'Pelaksana Bagian Umum'),
('Seksi Evaluasi Dan Informasi', 'Pelaksana Seksi Evaluasi Dan Informasi'),
('Seksi Penyelenggaraan', 'Pelaksana Seksi Penyelenggaraan'),
('Subbag Tata Usaha', 'Pelaksana Subbag Tata Usaha'),
('Subbagian Perencanaan Dan Keuangan  [PSDM]', 'Pelaksana Subbagian Perencanaan Dan Keuangan [PSDM]'),
('Subbagian Perencanaan Dan Keuangan', 'Pelaksana Subbagian Perencanaan Keuangan'),
('Subbagian Rumah Tangga Dan Pengelolaan Aset ', 'Pelaksana Subbagian Rumah Tangga Dan Pengelolaan Aset'),
('Subbagian Rumah Tangga Dan Pengelolaan Aset  [PSDM]', 'Pelaksana Subbagian Rumah Tangga Dan Pengelolaan Aset [PSDM]'),
('Subbagian Tata Usaha, Kepegawaian , Dan Humas ', 'Pelaksana Subbagian Tata Usaha, Kepegawaian, Dan Humas'),
('Subbagian Tata Usaha, Kepegawaian, Dan Humas  [PSDM]', 'Pelaksana Subbagian Tata Usaha, Kepegawaian, Dan Humas [PSDM]'),
('Subbidang Evaluasi Dan Pelaporan Kinerja', 'Pelaksana Subbidang Evaluasi Dan Pelaporan Kinerja'),
('Subbidang Kurikulum', 'Pelaksana Subbidang Kurikulum'),
('Subbidang Penyelenggaraan', 'Pelaksana Subbidang Penyelenggaraan'),
('Bidang Penyelenggaraan', 'Pelaksana Subbidang Penyelenggaraan I'),
('Bidang Penyelenggaraan', 'Pelaksana Subbidang Penyelenggaraan II'),
('Subbidang Perencanaan Dan Pengembangan', 'Pelaksana Subbidang Perencanaan Dan Pengembangan'),
('Subbidang Program ', 'Pelaksana Subbidang Program'),
('Subbidang Tenaga Pengajar ', 'Pelaksana Subbidang Tenaga Pengajar'),
('BPPK', 'Pusdiklat'),
('BPPK', 'Pusdiklat PSDM'),
('BPPK', 'Sekretariat Badan'),
('BDK', 'Seksi Evaluasi Dan Informasi'),
('BDK', 'Seksi Penyelenggaraan'),
('BDK', 'Subbag Tata Usaha'),
('Bagian Tata Usaha', 'Subbagian Perencanaan Dan Keuangan'),
('Bagian Tata Usaha [PSDM]', 'Subbagian Perencanaan Dan Keuangan  [PSDM]'),
('Bagian Tata Usaha', 'Subbagian Rumah Tangga Dan Pengelolaan Aset '),
('Bagian Tata Usaha [PSDM]', 'Subbagian Rumah Tangga Dan Pengelolaan Aset  [PSDM]'),
('Bagian Tata Usaha', 'Subbagian Tata Usaha, Kepegawaian , Dan Humas '),
('Bagian Tata Usaha [PSDM]', 'Subbagian Tata Usaha, Kepegawaian, Dan Humas  [PSDM]'),
('Bidang Evaluasi Dan Pelaporan Kinerja', 'Subbidang Evaluasi Diklat '),
('Bidang Evaluasi Dan Pelaporan Kinerja', 'Subbidang Informasi Dan Pelaporan Kinerja '),
('Bidang Perencanaan Dan Pengembangan Diklat', 'Subbidang Kurikulum'),
('Bidang Evaluasi Dan Pelaporan Kinerja', 'Subbidang Pengolahan Hasil Diklat '),
('Bidang Penyelenggaraan', 'Subbidang Penyelenggaraan I  '),
('Bidang Penyelenggaraan', 'Subbidang Penyelenggaraan II'),
('Bidang Penjenjangan Dan Peningkatan Kompetensi', 'Subbidang Perencanaan Dan Pengembangan'),
('Bidang Penjenjangan Dan Peningkatan Kompetensi', 'Subbidang Program '),
('Bidang Perencanaan Dan Pengembangan Diklat', 'Subbidang Program '),
('Bidang Penjenjangan Dan Peningkatan Kompetensi', 'Subbidang Tenaga Pengajar '),
('Bidang Perencanaan Dan Pengembangan Diklat', 'Subbidang Tenaga Pengajar '),
('BPPK', 'Super Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `person_id` int(11) NOT NULL,
  `satker_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `organisation_id` int(11) DEFAULT NULL,
  `chairman` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`person_id`),
  KEY `user_id` (`user_id`),
  KEY `organisation_id` (`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`person_id`, `satker_id`, `user_id`, `organisation_id`, `chairman`) VALUES
(1, 17, 1, NULL, 0),
(2, 17, 2, NULL, 0),
(3, 18, 3, NULL, 0),
(4, 19, 4, NULL, 0),
(5, 20, 5, NULL, 0),
(6, 21, 6, NULL, 0),
(7, 22, 7, NULL, 0),
(8, 23, 8, NULL, 0),
(9, 24, 9, NULL, 0),
(10, 25, 10, NULL, 0),
(11, 29, 11, NULL, 0),
(12, 28, 12, NULL, 0),
(13, 27, 13, NULL, 0),
(14, 26, 14, NULL, 0),
(15, 30, 15, NULL, 0),
(16, 31, 16, NULL, 0),
(17, 32, 17, NULL, 0),
(18, 33, 18, NULL, 0),
(19, 34, 19, NULL, 0),
(20, 35, 20, NULL, 0),
(21, 36, 21, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `meeting`
--

CREATE TABLE IF NOT EXISTS `meeting` (
  `activity_id` int(11) NOT NULL,
  `attendance_count_plan` int(11) DEFAULT NULL,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`activity_id`),
  KEY `organisation_id` (`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(256) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(3) NOT NULL,
  `category` int(3) NOT NULL,
  `author` int(11) NOT NULL,
  `recipient` int(11) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1411603868),
('m130524_201442_init', 1411603902);

-- --------------------------------------------------------

--
-- Table structure for table `object_file`
--

CREATE TABLE IF NOT EXISTS `object_file` (
  `object` varchar(100) NOT NULL,
  `object_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT '',
  `file_id` int(11) NOT NULL,
  PRIMARY KEY (`object`,`object_id`,`type`,`file_id`),
  KEY `file_id` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `object_person`
--

CREATE TABLE IF NOT EXISTS `object_person` (
  `object` varchar(100) NOT NULL,
  `object_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT '',
  `person_id` int(11) NOT NULL,
  PRIMARY KEY (`object`,`object_id`,`type`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `object_reference`
--

CREATE TABLE IF NOT EXISTS `object_reference` (
  `object` varchar(100) NOT NULL,
  `object_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT 'graduate,rank_class,religion,satker,finance_position',
  `reference_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`object`,`object_id`,`type`),
  KEY `reference_id` (`reference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `object_reference`
--

INSERT INTO `object_reference` (`object`, `object_id`, `type`, `reference_id`) VALUES
('employee', 1, 'finance_position', NULL),
('employee', 1, 'fungsional', NULL),
('employee', 1, 'pranata_komputer', NULL),
('employee', 1, 'pranata_komputer_ahli', NULL),
('employee', 1, 'pranata_komputer_terampil', NULL),
('employee', 1, 'unit', NULL),
('employee', 1, 'widyaiswara', NULL),
('employee', 2, 'finance_position', NULL),
('employee', 2, 'fungsional', NULL),
('employee', 2, 'pranata_komputer', NULL),
('employee', 2, 'pranata_komputer_ahli', NULL),
('employee', 2, 'pranata_komputer_terampil', NULL),
('employee', 2, 'unit', NULL),
('employee', 2, 'widyaiswara', NULL),
('employee', 3, 'finance_position', NULL),
('employee', 3, 'fungsional', NULL),
('employee', 3, 'pranata_komputer', NULL),
('employee', 3, 'pranata_komputer_ahli', NULL),
('employee', 3, 'pranata_komputer_terampil', NULL),
('employee', 3, 'unit', NULL),
('employee', 3, 'widyaiswara', NULL),
('employee', 4, 'finance_position', NULL),
('employee', 4, 'fungsional', NULL),
('employee', 4, 'pranata_komputer', NULL),
('employee', 4, 'pranata_komputer_ahli', NULL),
('employee', 4, 'pranata_komputer_terampil', NULL),
('employee', 4, 'unit', NULL),
('employee', 4, 'widyaiswara', NULL),
('employee', 5, 'finance_position', NULL),
('employee', 5, 'fungsional', NULL),
('employee', 5, 'pranata_komputer', NULL),
('employee', 5, 'pranata_komputer_ahli', NULL),
('employee', 5, 'pranata_komputer_terampil', NULL),
('employee', 5, 'unit', NULL),
('employee', 5, 'widyaiswara', NULL),
('employee', 6, 'finance_position', NULL),
('employee', 6, 'fungsional', NULL),
('employee', 6, 'pranata_komputer', NULL),
('employee', 6, 'pranata_komputer_ahli', NULL),
('employee', 6, 'pranata_komputer_terampil', NULL),
('employee', 6, 'unit', NULL),
('employee', 6, 'widyaiswara', NULL),
('employee', 7, 'finance_position', NULL),
('employee', 7, 'fungsional', NULL),
('employee', 7, 'pranata_komputer', NULL),
('employee', 7, 'pranata_komputer_ahli', NULL),
('employee', 7, 'pranata_komputer_terampil', NULL),
('employee', 7, 'unit', NULL),
('employee', 7, 'widyaiswara', NULL),
('employee', 8, 'finance_position', NULL),
('employee', 8, 'fungsional', NULL),
('employee', 8, 'pranata_komputer', NULL),
('employee', 8, 'pranata_komputer_ahli', NULL),
('employee', 8, 'pranata_komputer_terampil', NULL),
('employee', 8, 'unit', NULL),
('employee', 8, 'widyaiswara', NULL),
('employee', 9, 'finance_position', NULL),
('employee', 9, 'fungsional', NULL),
('employee', 9, 'pranata_komputer', NULL),
('employee', 9, 'pranata_komputer_ahli', NULL),
('employee', 9, 'pranata_komputer_terampil', NULL),
('employee', 9, 'unit', NULL),
('employee', 9, 'widyaiswara', NULL),
('employee', 10, 'finance_position', NULL),
('employee', 10, 'fungsional', NULL),
('employee', 10, 'pranata_komputer', NULL),
('employee', 10, 'pranata_komputer_ahli', NULL),
('employee', 10, 'pranata_komputer_terampil', NULL),
('employee', 10, 'unit', NULL),
('employee', 10, 'widyaiswara', NULL),
('employee', 11, 'finance_position', NULL),
('employee', 11, 'fungsional', NULL),
('employee', 11, 'pranata_komputer', NULL),
('employee', 11, 'pranata_komputer_ahli', NULL),
('employee', 11, 'pranata_komputer_terampil', NULL),
('employee', 11, 'unit', NULL),
('employee', 11, 'widyaiswara', NULL),
('employee', 12, 'finance_position', NULL),
('employee', 12, 'fungsional', NULL),
('employee', 12, 'pranata_komputer', NULL),
('employee', 12, 'pranata_komputer_ahli', NULL),
('employee', 12, 'pranata_komputer_terampil', NULL),
('employee', 12, 'unit', NULL),
('employee', 12, 'widyaiswara', NULL),
('employee', 13, 'finance_position', NULL),
('employee', 13, 'fungsional', NULL),
('employee', 13, 'pranata_komputer', NULL),
('employee', 13, 'pranata_komputer_ahli', NULL),
('employee', 13, 'pranata_komputer_terampil', NULL),
('employee', 13, 'unit', NULL),
('employee', 13, 'widyaiswara', NULL),
('employee', 14, 'finance_position', NULL),
('employee', 14, 'fungsional', NULL),
('employee', 14, 'pranata_komputer', NULL),
('employee', 14, 'pranata_komputer_ahli', NULL),
('employee', 14, 'pranata_komputer_terampil', NULL),
('employee', 14, 'unit', NULL),
('employee', 14, 'widyaiswara', NULL),
('employee', 15, 'finance_position', NULL),
('employee', 15, 'fungsional', NULL),
('employee', 15, 'pranata_komputer', NULL),
('employee', 15, 'pranata_komputer_ahli', NULL),
('employee', 15, 'pranata_komputer_terampil', NULL),
('employee', 15, 'unit', NULL),
('employee', 15, 'widyaiswara', NULL),
('employee', 16, 'finance_position', NULL),
('employee', 16, 'fungsional', NULL),
('employee', 16, 'pranata_komputer', NULL),
('employee', 16, 'pranata_komputer_ahli', NULL),
('employee', 16, 'pranata_komputer_terampil', NULL),
('employee', 16, 'unit', NULL),
('employee', 16, 'widyaiswara', NULL),
('employee', 17, 'finance_position', NULL),
('employee', 17, 'fungsional', NULL),
('employee', 17, 'pranata_komputer', NULL),
('employee', 17, 'pranata_komputer_ahli', NULL),
('employee', 17, 'pranata_komputer_terampil', NULL),
('employee', 17, 'unit', NULL),
('employee', 17, 'widyaiswara', NULL),
('employee', 18, 'finance_position', NULL),
('employee', 18, 'fungsional', NULL),
('employee', 18, 'pranata_komputer', NULL),
('employee', 18, 'pranata_komputer_ahli', NULL),
('employee', 18, 'pranata_komputer_terampil', NULL),
('employee', 18, 'unit', NULL),
('employee', 18, 'widyaiswara', NULL),
('employee', 19, 'finance_position', NULL),
('employee', 19, 'fungsional', NULL),
('employee', 19, 'pranata_komputer', NULL),
('employee', 19, 'pranata_komputer_ahli', NULL),
('employee', 19, 'pranata_komputer_terampil', NULL),
('employee', 19, 'unit', NULL),
('employee', 19, 'widyaiswara', NULL),
('employee', 20, 'finance_position', NULL),
('employee', 20, 'fungsional', NULL),
('employee', 20, 'pranata_komputer', NULL),
('employee', 20, 'pranata_komputer_ahli', NULL),
('employee', 20, 'pranata_komputer_terampil', NULL),
('employee', 20, 'unit', NULL),
('employee', 20, 'widyaiswara', NULL),
('employee', 21, 'finance_position', NULL),
('employee', 21, 'fungsional', NULL),
('employee', 21, 'pranata_komputer', NULL),
('employee', 21, 'pranata_komputer_ahli', NULL),
('employee', 21, 'pranata_komputer_terampil', NULL),
('employee', 21, 'unit', NULL),
('employee', 21, 'widyaiswara', NULL),
('person', 1, 'graduate', NULL),
('person', 1, 'rank_class', NULL),
('person', 1, 'religion', NULL),
('person', 2, 'graduate', NULL),
('person', 2, 'rank_class', NULL),
('person', 2, 'religion', NULL),
('person', 3, 'graduate', NULL),
('person', 3, 'rank_class', NULL),
('person', 3, 'religion', NULL),
('person', 4, 'graduate', NULL),
('person', 4, 'rank_class', NULL),
('person', 4, 'religion', NULL),
('person', 5, 'graduate', NULL),
('person', 5, 'rank_class', NULL),
('person', 5, 'religion', NULL),
('person', 6, 'graduate', NULL),
('person', 6, 'rank_class', NULL),
('person', 6, 'religion', NULL),
('person', 7, 'graduate', NULL),
('person', 7, 'rank_class', NULL),
('person', 7, 'religion', NULL),
('person', 8, 'graduate', NULL),
('person', 8, 'rank_class', NULL),
('person', 8, 'religion', NULL),
('person', 9, 'graduate', NULL),
('person', 9, 'rank_class', NULL),
('person', 9, 'religion', NULL),
('person', 10, 'graduate', NULL),
('person', 10, 'rank_class', NULL),
('person', 10, 'religion', NULL),
('person', 11, 'graduate', NULL),
('person', 11, 'rank_class', NULL),
('person', 11, 'religion', NULL),
('person', 12, 'graduate', NULL),
('person', 12, 'rank_class', NULL),
('person', 12, 'religion', NULL),
('person', 13, 'graduate', NULL),
('person', 13, 'rank_class', NULL),
('person', 13, 'religion', NULL),
('person', 14, 'graduate', NULL),
('person', 14, 'rank_class', NULL),
('person', 14, 'religion', NULL),
('person', 15, 'graduate', NULL),
('person', 15, 'rank_class', NULL),
('person', 15, 'religion', NULL),
('person', 16, 'graduate', NULL),
('person', 16, 'rank_class', NULL),
('person', 16, 'religion', NULL),
('person', 17, 'graduate', NULL),
('person', 17, 'rank_class', NULL),
('person', 17, 'religion', NULL),
('person', 18, 'graduate', NULL),
('person', 18, 'rank_class', NULL),
('person', 18, 'religion', NULL),
('person', 19, 'graduate', NULL),
('person', 19, 'rank_class', NULL),
('person', 19, 'religion', NULL),
('person', 20, 'graduate', NULL),
('person', 20, 'rank_class', NULL),
('person', 20, 'religion', NULL),
('person', 21, 'graduate', NULL),
('person', 21, 'rank_class', NULL),
('person', 21, 'religion', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `organisation`
--

CREATE TABLE IF NOT EXISTS `organisation` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `KD_UNIT_ORG` char(10) NOT NULL,
  `KD_UNIT_ES1` char(2) NOT NULL,
  `KD_UNIT_ES2` char(2) NOT NULL,
  `KD_UNIT_ES3` char(2) NOT NULL,
  `KD_UNIT_ES4` char(2) NOT NULL,
  `KD_UNIT_ES5` char(2) NOT NULL,
  `JNS_KANTOR` int(11) NOT NULL,
  `NM_UNIT_ORG` varchar(100) NOT NULL,
  `KD_ESELON` char(2) NOT NULL,
  `KD_SURAT_ORG` varchar(100) NOT NULL,
  `TKT_ESELON` char(2) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=403 ;

--
-- Dumping data for table `organisation`
--

INSERT INTO `organisation` (`ID`, `KD_UNIT_ORG`, `KD_UNIT_ES1`, `KD_UNIT_ES2`, `KD_UNIT_ES3`, `KD_UNIT_ES4`, `KD_UNIT_ES5`, `JNS_KANTOR`, `NM_UNIT_ORG`, `KD_ESELON`, `KD_SURAT_ORG`, `TKT_ESELON`) VALUES
(1, '1200000001', '12', '00', '00', '00', '01', 1, 'BADAN PENDIDIKAN DAN PELATIHAN KEUANGAN', '1', '', '11'),
(3, '1201000000', '12', '01', '00', '00', '00', 2, 'Sekretariat Badan\n', '2', '', '21'),
(4, '1201010000', '12', '01', '01', '00', '00', 2, 'Bagian Kepegawaian\n', '3', '3', '31'),
(5, '1201010100', '12', '01', '01', '01', '00', 2, 'SUBBAGIAN UMUM KEPEGAWAIAN', '4', '34', '41'),
(6, '1201010201', '12', '01', '01', '02', '01', 2, 'SUBBAGIAN PENGEMBANGAN PEGAWAI', '4', '31', '41'),
(8, '1201010300', '12', '01', '01', '03', '00', 2, 'SUBBAGIAN ADMINISTRASI JABATAN FUNGSIONAL', '4', '32', '41'),
(9, '1201010400', '12', '01', '01', '04', '00', 2, 'SUBBAGIAN KEPATUHAN INTERNAL', '4', '33', '41'),
(10, '1201020002', '12', '01', '02', '00', '02', 2, 'BAGIAN ORGANISASI DAN TATALAKSANA', '3', '2', '31'),
(13, '1201020102', '12', '01', '02', '01', '02', 2, 'SUBBAGIAN ORGANISASI', '4', '', '41'),
(16, '1201020202', '12', '01', '02', '02', '02', 2, 'SUBBAGIAN TATALAKSANA', '4', '', '41'),
(19, '1201020302', '12', '01', '02', '03', '02', 2, 'SUBBAGIAN HUKUM DAN KERJASAMA', '4', '', '41'),
(22, '1201030000', '12', '01', '03', '00', '00', 2, 'BAGIAN KEUANGAN', '3', '', '31'),
(23, '1201030100', '12', '01', '03', '01', '00', 2, 'SUBBAGIAN PENYUSUNAN ANGGARAN', '4', '', '41'),
(24, '1201030201', '12', '01', '03', '02', '01', 2, 'SUBBAGIAN PERBENDAHARAAN', '4', '', '41'),
(26, '1201030302', '12', '01', '03', '03', '02', 2, 'SUBBAGIAN AKUNTANSI DAN PELAPORAN', '4', '', '41'),
(29, '1201040003', '12', '01', '04', '00', '03', 2, 'BAGIAN TEKNOLOGI DAN INFORMASI KOMUNIKASI', '3', '', '31'),
(33, '1201040103', '12', '01', '04', '01', '03', 2, 'SUBBAGIAN SISTEM INFORMASI', '4', '', '41'),
(37, '1201040203', '12', '01', '04', '02', '03', 2, 'SUBBAGIAN KOMUNIKASI PUBLIK', '4', '', '41'),
(41, '1201040302', '12', '01', '04', '03', '02', 2, 'SUBBAGIAN DUKUNGAN TEKNIS', '4', '', '41'),
(44, '1201050000', '12', '01', '05', '00', '00', 2, 'BAGIAN UMUM', '3', '', '31'),
(45, '1201050101', '12', '01', '05', '01', '01', 2, 'SUBBAGIAN TATA USAHA', '4', '', '41'),
(47, '1201050202', '12', '01', '05', '02', '02', 2, 'SUBBAGIAN PENGELOLAAN ASET', '4', '', '41'),
(50, '1201050302', '12', '01', '05', '03', '02', 2, 'SUBBAGIAN RUMAH TANGGA', '4', '', '41'),
(56, '1202000002', '12', '02', '00', '00', '02', 3, 'Pusdiklat Pengembangan Sumber Daya Manusia', '2', '', '21'),
(59, '1202010001', '12', '02', '01', '00', '01', 3, 'Bagian Tata Usaha', '3', '', '31'),
(61, '1202010101', '12', '02', '01', '01', '01', 3, 'Subbagian Tata Usaha, Kepegawaian Dan Hubungan Masyarakat', '4', '', '41'),
(63, '1202010201', '12', '02', '01', '02', '01', 3, 'Subbagian Perencanaan Dan Keuangan', '4', '', '41'),
(65, '1202010301', '12', '02', '01', '03', '01', 3, 'Subbagian Rumah Tangga Dan Pengelolaan Aset', '4', '', '41'),
(66, '1202020000', '12', '02', '02', '00', '00', 3, 'Bidang Penjenjangan Pangkat Dan Peningkatan Kompetensi', '3', '', '31'),
(67, '1202020100', '12', '02', '02', '01', '00', 3, 'Subbidang Perencanaan Dan Pengembangan', '4', '', '41'),
(68, '1202020200', '12', '02', '02', '02', '00', 3, 'Subbidang Penyelenggaraan', '4', '', '41'),
(69, '1202020300', '12', '02', '02', '03', '00', 3, 'Subbidang Evaluasi Dan Pelaporan Kinerja', '4', '', '41'),
(70, '1202030000', '12', '02', '03', '00', '00', 3, 'Bidang Pengelolaan Tes Terpadu', '3', '', '31'),
(71, '1202030100', '12', '02', '03', '01', '00', 3, 'Subbidang Perencanaan Tes', '4', '', '41'),
(72, '1202030200', '12', '02', '03', '02', '00', 3, 'Subbidang Penyelenggaraan Tes', '4', '', '41'),
(73, '1202030300', '12', '02', '03', '03', '00', 3, 'Subbidang Evaluasi Hasil Tes', '4', '', '41'),
(76, '1202040002', '12', '02', '04', '00', '02', 3, 'Bidang Pengelolaan Beasiswa', '3', '', '31'),
(79, '1202040102', '12', '02', '04', '01', '02', 3, 'Subbidang Perencanaan Beasiswa', '4', '', '41'),
(81, '1202040201', '12', '02', '04', '02', '01', 3, 'Subbidang Seleksi Dan Penempatan', '4', '', '41'),
(82, '1202040300', '12', '02', '04', '03', '00', 3, 'Subbidang Pemantauan', '4', '', '41'),
(83, '1202050000', '12', '02', '05', '00', '00', 3, 'Balai Diklat Kepemimpinan', '3', '', '31'),
(84, '1202050101', '12', '02', '05', '01', '01', 3, 'Subbagian Tata Usaha Dan Kepatuhan Internal', '4', '', '41'),
(86, '1202050200', '12', '02', '05', '02', '00', 3, 'Seksi Penyelenggaraan', '4', '', '41'),
(87, '1202050300', '12', '02', '05', '03', '00', 3, 'Seksi Evaluasi Dan Informasi', '4', '', '41'),
(343, '1200010002', '12', '00', '01', '00', '02', 10, 'Balai Pendidikan Dan Pelatihan Keuangan', '3', '', '31'),
(345, '1200010101', '12', '00', '01', '01', '01', 10, 'Subbagian Tata Usaha Dan Kepatuhan Internal', '4', '', '41'),
(346, '1200010200', '12', '00', '01', '02', '00', 10, 'Seksi Penyelenggaraan', '4', '', '41'),
(347, '1200010300', '12', '00', '01', '03', '00', 10, 'Seksi Evaluasi Dan Informasi', '4', '', '41'),
(348, '1208000000', '12', '08', '00', '00', '00', 9, 'SEKOLAH TINGGI AKUNTANSI NEGARA', '2', '', '21'),
(349, '1208010000', '12', '08', '01', '00', '00', 9, 'SEKRETARIAT SEKOLAH TINGGI AKUNTANSI NEGARA', '3', '', '31'),
(350, '1208010200', '12', '08', '01', '02', '00', 9, 'SUBBAGIAN TATA USAHA DAN KEUANGAN', '4', '', '41'),
(351, '1208010100', '12', '08', '01', '01', '00', 9, 'SUBBAGIAN KEPEGAWAIAN DAN PERALATAN', '4', '', '41'),
(352, '1208010300', '12', '08', '01', '03', '00', 9, 'SUBBAGIAN PERALATAN', '4', '', '41'),
(353, '1208020000', '12', '08', '02', '00', '00', 9, 'BIDANG AKADEMIS PENDIDIKAN PEMBANTU AKUNTAN', '3', '', '31'),
(354, '1208020100', '12', '08', '02', '01', '00', 9, 'SUBBIDANG TATA LAKSANA PEMBANTU AKUNTAN', '4', '', '41'),
(355, '1208020200', '12', '08', '02', '02', '00', 9, 'SUBBIDANG PENGEMBANGAN PEMBANTU AKUNTAN', '4', '', '41'),
(356, '1208030000', '12', '08', '03', '00', '00', 9, 'BIDANG AKADEMIS PENDIDIKAN AJUN AKUNTAN', '3', '', '31'),
(357, '1208030100', '12', '08', '03', '01', '00', 9, 'SUBBIDANG TATA LAKSANA AJUN AKUNTAN', '4', '', '41'),
(358, '1208030200', '12', '08', '03', '02', '00', 9, 'SUBBIDANG PENGEMBANGAN AJUN AKUNTAN', '4', '', '41'),
(359, '1208040000', '12', '08', '04', '00', '00', 9, 'BIDANG AKADEMIS PENDIDIKAN AKUNTAN', '3', '', '31'),
(360, '1208040100', '12', '08', '04', '01', '00', 9, 'SUBBIDANG TATA LAKSANA AKUNTAN', '4', '', '41'),
(361, '1208040200', '12', '08', '04', '02', '00', 9, 'SUBBIDANG PENGEMBANGAN AKUNTAN', '4', '', '41'),
(387, '1213000000', '12', '13', '00', '00', '00', 13, 'Pusat Pendidikan dan Pelatihan', '2', '', '21'),
(388, '1213010000', '12', '13', '01', '00', '00', 13, 'Bagian Tata Usaha', '3', '', '31'),
(389, '1213010100', '12', '13', '01', '01', '00', 13, 'Subbagian Tata Usaha, Kepegawaian Dan Hubungan Masyarakat', '4', '', '41'),
(390, '1213010200', '12', '13', '01', '02', '00', 13, 'Subbagian Perencanaan Dan Keuangan', '4', '', '41'),
(391, '1213010300', '12', '13', '01', '03', '00', 13, 'Subbagian Rumah Tangga Dan Pengelolaan Aset', '4', '', '41'),
(392, '1213020000', '12', '13', '02', '00', '00', 13, 'Bidang Perencanaan dan Pengembangan Diklat', '3', '', '31'),
(393, '1213020100', '12', '13', '02', '01', '00', 13, 'Subbidang Program', '4', '', '41'),
(394, '1213020200', '12', '13', '02', '02', '00', 13, 'Subbidang Kurikulum', '4', '', '41'),
(395, '1213020300', '12', '13', '02', '03', '00', 13, 'Subbidang Tenaga Pengajar', '4', '', '41'),
(396, '1213030000', '12', '13', '03', '00', '00', 13, 'Bidang Penyelenggaraan', '3', '', '31'),
(397, '1213030100', '12', '13', '03', '01', '00', 13, 'Subbidang Penyelenggaraan I', '4', '', '41'),
(398, '1213030200', '12', '13', '03', '02', '00', 13, 'Subbidang Penyelenggaraan II', '4', '', '41'),
(399, '1213040000', '12', '13', '04', '00', '00', 13, 'Bidang Evaluasi dan Pelaporan Kinerja', '3', '', '31'),
(400, '1213040100', '12', '13', '04', '01', '00', 13, 'Subbidang Evaluasi Diklat', '4', '', '41'),
(401, '1213040200', '12', '13', '04', '02', '00', 13, 'Subbidang Pengolahan Hasil Diklat', '4', '', '41'),
(402, '1213040300', '12', '13', '04', '03', '00', 13, 'Subbidang Informasi Dan Pelaporan Kinerja', '4', '', '41');

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE IF NOT EXISTS `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nip` varchar(25) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `nickname` varchar(25) DEFAULT NULL,
  `front_title` varchar(25) DEFAULT NULL,
  `back_title` varchar(25) DEFAULT NULL,
  `nid` varchar(100) NOT NULL COMMENT 'No KTP  Passport',
  `npwp` varchar(100) DEFAULT NULL,
  `born` varchar(50) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` tinyint(1) DEFAULT '1',
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `homepage` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `office_phone` varchar(50) DEFAULT NULL,
  `office_fax` varchar(50) DEFAULT NULL,
  `office_email` varchar(100) DEFAULT NULL,
  `office_address` varchar(255) DEFAULT NULL,
  `bank_account` varchar(255) DEFAULT NULL,
  `married` int(3) DEFAULT NULL,
  `blood` varchar(25) DEFAULT '-',
  `graduate_desc` varchar(255) DEFAULT NULL,
  `position` int(3) DEFAULT NULL,
  `position_desc` varchar(255) DEFAULT NULL,
  `organisation` varchar(255) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nid` (`nid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`id`, `nip`, `name`, `nickname`, `front_title`, `back_title`, `nid`, `npwp`, `born`, `birthday`, `gender`, `phone`, `email`, `homepage`, `address`, `office_phone`, `office_fax`, `office_email`, `office_address`, `bank_account`, `married`, `blood`, `graduate_desc`, `position`, `position_desc`, `organisation`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, '', 'Super Administrator', 'superadmin', '', '', 'superadmin', '', 'Jember', NULL, 1, '', '', '', '', '', '', '', '', '', 0, '-', '', NULL, 'Pranata Komputer Ahli', '', 1, NULL, NULL, NULL, NULL),
(2, '', 'Admin Sekretariat Badan', '', '', '', 'admin_sekretariat_badan', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(3, '', 'Admin Pusdiklat PSDM', '', '', '', 'admin_pusdiklat_psdm', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(4, '', 'Admin Pusdiklat AP', '', '', '', 'admin_pusdiklat_ap', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(5, '', 'Admin Pusdiklat Pajak', '', '', '', 'admin_pusdiklat_pajak', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(6, '', 'Admin Pusdiklat BC', '', '', '', 'admin_pusdiklat_bc', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(7, '', 'Admin Pusdiklat KNPK', '', '', '', 'admin_pusdiklat_knpk', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(8, '', 'Admin Pusdiklat KU', '', '', '', 'admin_pusdiklat_ku', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(9, '', 'Admin STAN', '', '', '', 'admin_stan', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(10, '', 'Admin BDK Medan', '', '', '', 'admin_bdk_medan', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(11, '', 'Admin BDK Balikpapan', '', '', '', 'admin_bdk_balikpapan', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(12, '', 'Admin BDK Malang', '', '', '', 'admin_bdk_malang', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(13, '', 'Admin BDK Yogyakarta', '', '', '', 'admin_bdk_yogyakarta', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(14, '', 'Admin BDK Palembang', '', '', '', 'admin_bdk_palembang', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(15, '', 'Admin BDK Makassar', '', '', '', 'admin_bdk_makassar', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(16, '', 'Admin BDK Cimahi', '', '', '', 'admin_bdk_cimahi', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(17, '', 'Admin BDK Manado', '', '', '', 'admin_bdk_manado', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(18, '', 'Admin BDK Pekanbaru', '', '', '', 'admin_bdk_pekanbaru', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(19, '', 'Admin BDK Pontianak', '', '', '', 'admin_bdk_pontianak', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(20, '', 'Admin BDK Denpasar', '', '', '', 'admin_bdk_denpasar', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(21, '', 'Admin BDK Magelang', '', '', '', 'admin_bdk_magelang', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `person_activity`
--

CREATE TABLE IF NOT EXISTS `person_activity` (
  `person_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  UNIQUE KEY `name_UNIQUE` (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE IF NOT EXISTS `program` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `satker_id` int(11) NOT NULL,
  `number` varchar(15) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `hours` decimal(5,2) DEFAULT NULL,
  `days` int(3) DEFAULT NULL,
  `test` tinyint(1) DEFAULT '0',
  `note` varchar(255) DEFAULT NULL,
  `stage` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `validation_status` tinyint(1) DEFAULT '0',
  `validation_note` varchar(255) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `program_history`
--

CREATE TABLE IF NOT EXISTS `program_history` (
  `id` int(11) NOT NULL,
  `revision` int(11) NOT NULL,
  `satker_id` int(11) NOT NULL,
  `number` varchar(15) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `hours` decimal(5,2) DEFAULT NULL,
  `days` int(3) DEFAULT NULL,
  `test` tinyint(1) DEFAULT '0',
  `note` varchar(255) DEFAULT NULL,
  `stage` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `validation_status` tinyint(1) DEFAULT '0',
  `validation_note` varchar(255) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`revision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `program_subject`
--

CREATE TABLE IF NOT EXISTS `program_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) NOT NULL,
  `program_revision` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `hours` decimal(5,2) NOT NULL,
  `sort` int(3) DEFAULT NULL,
  `test` tinyint(1) DEFAULT '0',
  `stage` text,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_program_subject_tb_program1` (`program_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `program_subject_history`
--

CREATE TABLE IF NOT EXISTS `program_subject_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `revision` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `program_revision` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `hours` decimal(5,2) NOT NULL,
  `sort` int(3) DEFAULT NULL,
  `test` tinyint(1) DEFAULT '0',
  `stage` text,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_program_subject_tb_program1` (`program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reference`
--

CREATE TABLE IF NOT EXISTS `reference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=204 ;

--
-- Dumping data for table `reference`
--

INSERT INTO `reference` (`id`, `parent_id`, `type`, `name`, `value`, `sort`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 0, 'program_code', 'DIKLAT PRAJABATAN', '1.0.0.0', 0, 1, NULL, NULL, NULL, NULL),
(2, 0, 'program_code', 'DIKLAT DALAM JABATAN', '2.0.0.0', 0, 1, NULL, NULL, NULL, NULL),
(3, 2, 'program_code', 'DIKLAT KEPEMIMPINAN', '2.1.0.0', 0, 1, NULL, NULL, NULL, NULL),
(4, 2, 'program_code', 'DIKLAT FUNGSIONAL', '2.2.0.0', 0, 1, NULL, NULL, NULL, NULL),
(5, 4, 'program_code', 'DIKLAT FUNGSIONAL KEAHLIAN', '2.2.1.0', 0, 1, NULL, NULL, NULL, NULL),
(6, 4, 'program_code', 'DIKLAT FUNGSIONAL KETERAMPILAN', '2.2.2.0', 0, 1, NULL, NULL, NULL, NULL),
(7, 4, 'program_code', 'DIKLAT FUNGSIONAL PEMBENTUKAN', '2.2.3.0', 0, 1, NULL, NULL, NULL, NULL),
(8, 2, 'program_code', 'DIKLAT TEKNIS', '2.3.0.0', 2, 1, NULL, NULL, NULL, NULL),
(9, 8, 'program_code', 'DIKLAT TEKNIS SUBSTANTIF', '2.3.1.0', 0, 1, NULL, NULL, NULL, NULL),
(10, 9, 'program_code', 'DIKLAT TEKNIS SUBSTANTIF DASAR', '2.3.1.1', 0, 1, NULL, NULL, NULL, NULL),
(11, 9, 'program_code', 'DIKLAT TEKNIS SUBSTANTIF SPESIALISASI', '2.3.1.2', 0, 1, NULL, NULL, NULL, NULL),
(12, 8, 'program_code', 'DIKLAT TEKNIS UMUM', '2.3.2.0', 0, 1, NULL, NULL, NULL, NULL),
(13, 2, 'program_code', 'UJIAN DINAS', '2.4.0.0', 0, 1, NULL, NULL, NULL, NULL),
(14, 2, 'program_code', 'UJIAN PENYESUAIN KENAIKAN PANGKAT', '2.5.0.0', 0, 1, NULL, NULL, NULL, NULL),
(15, 2, 'program_code', 'DIKLAT PENYEGARAN', '2.6.0.0', 0, 1, NULL, NULL, NULL, NULL),
(16, 0, 'program_code', 'SERTIFIKASI', '3.0.0.0', 0, 1, NULL, NULL, NULL, NULL),
(17, 2, 'satker', 'Sekretariat Badan', '2', 1, 1, NULL, NULL, NULL, NULL),
(18, 3, 'satker', 'Pusdiklat PSDM', '2', 2, 1, NULL, NULL, NULL, NULL),
(19, 13, 'satker', 'Pusdiklat AP', '2', 3, 1, NULL, NULL, NULL, NULL),
(20, 13, 'satker', 'Pusdiklat Pajak', '2', 4, 1, NULL, NULL, NULL, NULL),
(21, 13, 'satker', 'Pusdiklat BC', '2', 5, 1, NULL, NULL, NULL, NULL),
(22, 13, 'satker', 'Pusdiklat KNPK', '2', 6, 1, NULL, NULL, NULL, NULL),
(23, 13, 'satker', 'Pusdiklat Keuangan Umum', '2', 7, 1, NULL, NULL, NULL, NULL),
(24, 9, 'satker', 'Sekolah Tinggi Akuntansi Negara', '2', 8, 1, NULL, NULL, NULL, NULL),
(25, 10, 'satker', 'Balai Diklat Keuangan Medan', '3', 9, 1, NULL, NULL, NULL, NULL),
(26, 10, 'satker', 'Balai Diklat Keuangan Palembang', '3', 10, 1, NULL, NULL, NULL, NULL),
(27, 10, 'satker', 'Balai Diklat Keuangan Yogyakarta', '3', 11, 1, NULL, NULL, NULL, NULL),
(28, 10, 'satker', 'Balai Diklat Keuangan Malang', '3', 12, 1, NULL, NULL, NULL, NULL),
(29, 10, 'satker', 'BDK Balikpapan', '3', 13, 1, NULL, NULL, NULL, NULL),
(30, 10, 'satker', 'BDK Makassar', '3', 14, 1, NULL, NULL, NULL, NULL),
(31, 10, 'satker', 'Balai Diklat Keuangan Cimahi', '3', 15, 1, NULL, NULL, NULL, NULL),
(32, 10, 'satker', 'Balai Diklat Keuangan Manado', '3', 16, 1, NULL, NULL, NULL, NULL),
(33, 10, 'satker', 'Balai Diklat Keuangan Pekanbaru', '3', 17, 1, NULL, NULL, NULL, NULL),
(34, 10, 'satker', 'Balai Diklat Keuangan Pontianak', '3', 18, 1, NULL, NULL, NULL, NULL),
(35, 10, 'satker', 'Balai Diklat Keuangan Denpasar', '3', 19, 1, NULL, NULL, NULL, NULL),
(36, 10, 'satker', 'Balai Diklat Keuangan Magelang', '3', 20, 1, NULL, NULL, NULL, NULL),
(37, 0, 'graduate', '-', '', 0, 1, NULL, NULL, NULL, NULL),
(38, 0, 'graduate', 'SD', '', 0, 1, NULL, NULL, NULL, NULL),
(39, 0, 'graduate', 'SMP', '', 0, 1, NULL, NULL, NULL, NULL),
(40, 0, 'graduate', 'SMA', '', 0, 1, NULL, NULL, NULL, NULL),
(41, 0, 'graduate', 'D I', '', 0, 1, NULL, NULL, NULL, NULL),
(42, 0, 'graduate', 'D II', '', 0, 1, NULL, NULL, NULL, NULL),
(43, 0, 'graduate', 'D III', '', 0, 1, NULL, NULL, NULL, NULL),
(44, 0, 'graduate', 'D IV', '', 0, 1, NULL, NULL, NULL, NULL),
(45, 0, 'graduate', 'S 1', '', 0, 1, NULL, NULL, NULL, NULL),
(46, 0, 'graduate', 'S 2', '', 0, 1, NULL, NULL, NULL, NULL),
(47, 0, 'graduate', 'S 3', '', 0, 1, NULL, NULL, NULL, NULL),
(66, 0, 'rank_class', 'Juru Muda / I.a', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(67, 0, 'rank_class', 'Juru Muda Tingkat I / I.b', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(68, 0, 'rank_class', 'Juru / I.c', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(69, 0, 'rank_class', 'Juru Tingkat I / I.d', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(70, 0, 'rank_class', 'Pengatur Muda / II.a', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(71, 0, 'rank_class', 'Pengatur Muda Tingkat I / II.b', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(72, 0, 'rank_class', 'Pengatur / II.c', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(73, 0, 'rank_class', 'Pengatur Tingkat I / II.d', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(74, 9, 'rank_class', 'Penata Muda / III.a', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(75, 0, 'rank_class', 'Penata Muda Tingkat I / III.b', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(76, 0, 'rank_class', 'Penata / III.c', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(77, 0, 'rank_class', 'Penata Tingkat I / III.d', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(78, 0, 'rank_class', 'Pembina / IV.a', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(79, 0, 'rank_class', 'Pembina Tingkat I / IV.b', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(80, 0, 'rank_class', 'Pembina Utama Muda / IV.c', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(81, 0, 'rank_class', 'Pembina Utama Madya / IV.d', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(82, 0, 'rank_class', 'Pembina Utama / IV.e', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(90, 0, 'religion', '-', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(91, 0, 'religion', 'Islam', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(92, 0, 'religion', 'Kristen Katolik', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(93, 0, 'religion', 'Kristen Protestan', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(94, 0, 'religion', 'Hindu', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(95, 0, 'religion', 'Budha', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(96, 0, 'religion', 'Konghucu', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(97, NULL, 'sbu', 'honor_persiapan_mengajar', '130000', NULL, 1, NULL, NULL, NULL, NULL),
(98, NULL, 'sbu', 'honor_pengajar_pns_internal', '100000', NULL, 1, NULL, NULL, NULL, NULL),
(99, NULL, 'sbu', 'honor_asisten_pns_internal', '50000', NULL, 1, NULL, NULL, NULL, NULL),
(100, NULL, 'sbu', 'honor_pengajar_pns_eksternal', '250000', NULL, 1, NULL, NULL, NULL, NULL),
(101, NULL, 'sbu', 'honor_asisten_pns_eksternal', '100000', NULL, 1, NULL, NULL, NULL, NULL),
(102, NULL, 'sbu', 'honor_penceramah_pns_internal_1', '550000', NULL, 1, NULL, NULL, NULL, NULL),
(103, NULL, 'sbu', 'honor_penceramah_pns_internal_2', '450000', NULL, 1, NULL, NULL, NULL, NULL),
(104, NULL, 'sbu', 'honor_penceramah_pns_internal_3', '350000', NULL, 1, NULL, NULL, NULL, NULL),
(105, NULL, 'sbu', 'honor_penceramah_pns_eksternal_1', '1100000', NULL, 1, NULL, NULL, NULL, NULL),
(106, NULL, 'sbu', 'honor_penceramah_pns_eksternal_2', '850000', NULL, 1, NULL, NULL, NULL, NULL),
(107, NULL, 'sbu', 'honor_penceramah_pns_eksternal_3', '700000', NULL, 1, NULL, NULL, NULL, NULL),
(108, NULL, 'sbu', 'honor_transport_dalam_kota', '110000', NULL, 1, NULL, NULL, NULL, NULL),
(109, NULL, 'subject_type', 'MP', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(110, NULL, 'subject_type', 'CERAMAH', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(111, NULL, 'subject_type', 'MFD (MENTAL FISIK DISIPLIN)', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(112, NULL, 'subject_type', 'OJT (ON THE JOB TRAINING)', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(113, NULL, 'trainer_type', 'PENGAJAR', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(114, NULL, 'trainer_type', 'PENCERAMAH', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(115, NULL, 'trainer_type', 'ASISTEN PENGAJAR', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(116, NULL, 'trainer_type', 'TEAM TEACHING', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(117, NULL, 'trainer_type', 'INSTRUKTUR MFD', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(118, NULL, 'trainer_type', 'ASISTEN INSTRUKTUR MFD', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(119, NULL, 'trainer_type', 'PEMBIMBING PKL', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(120, NULL, 'unit', '-', '-', NULL, 1, NULL, NULL, NULL, NULL),
(121, NULL, 'unit', 'Sekretariat Jenderal', 'Setjen', NULL, 1, NULL, NULL, NULL, NULL),
(122, NULL, 'unit', 'Inspektorat Jenderal', 'Itjen', NULL, 1, NULL, NULL, NULL, NULL),
(123, NULL, 'unit', 'Direktorat Jenderal Anggaran', 'DJA', NULL, 1, NULL, NULL, NULL, NULL),
(124, NULL, 'unit', 'Direktorat Jenderal Pajak', 'DJP', NULL, 1, NULL, NULL, NULL, NULL),
(125, NULL, 'unit', 'Direktorat Jenderal Bea dan Cukai', 'DJBC', NULL, 1, NULL, NULL, NULL, NULL),
(126, NULL, 'unit', 'Direktorat Jenderal Perbendaharaan', 'DJPBn', NULL, 1, NULL, NULL, NULL, NULL),
(127, NULL, 'unit', 'Direktorat Jenderal Kekayaan Negara', 'DJKN', NULL, 1, NULL, NULL, NULL, NULL),
(128, NULL, 'unit', 'Direktorat Jenderal Perimbangan Keuangan', 'DJPK', NULL, 1, NULL, NULL, NULL, NULL),
(129, NULL, 'unit', 'Direktorat Jenderal Pengelolaan Utang', 'DJPU', NULL, 1, NULL, NULL, NULL, NULL),
(130, NULL, 'unit', 'Badan Kebijakan Fiskal', 'BKF', NULL, 1, NULL, NULL, NULL, NULL),
(131, NULL, 'unit', 'Badan Pengawas Pasar Modal dan Lembaga Keuangan', 'Bapepam-LK', NULL, 1, NULL, NULL, NULL, NULL),
(132, NULL, 'unit', 'Badan Pendidikan dan Pelatihan Keuangan', 'BPPK', NULL, 1, NULL, NULL, NULL, NULL),
(133, NULL, 'unit', 'Non Kemenkeu', 'Lainnya', NULL, 1, NULL, NULL, NULL, NULL),
(134, NULL, 'finance_position', 'KPA', 'Kuasa Pengguna Anggaran', NULL, 1, NULL, NULL, NULL, NULL),
(135, NULL, 'finance_position', 'PPK', 'Pejabat Pembuat Komitmen', NULL, 1, NULL, NULL, NULL, NULL),
(136, NULL, 'finance_position', 'PPSPM', 'Pejabat Penandatanganan SPM', NULL, 1, NULL, NULL, NULL, NULL),
(137, NULL, 'finance_position', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran', NULL, 1, NULL, NULL, NULL, NULL),
(138, NULL, 'finance_position', 'Bendahara Penerimaan', 'Bendahara Penerimaan', NULL, 1, NULL, NULL, NULL, NULL),
(139, NULL, 'fungsional', 'Widyaiswara', 'Widyaiswara', NULL, 1, NULL, NULL, NULL, NULL),
(140, NULL, 'fungsional', 'Pranata Komputer', 'Pranta Komputer', NULL, 1, NULL, NULL, NULL, NULL),
(141, NULL, 'widyaiswara', 'Widyaiswara Pertama', 'Widyaiswara Pertama', NULL, 1, NULL, NULL, NULL, NULL),
(142, NULL, 'widyaiswara', 'Widyaiswara Muda', 'Widyaiswara Muda', NULL, 1, NULL, NULL, NULL, NULL),
(143, NULL, 'widyaiswara', 'Widyaiswara Madya', 'Widyaiswara Madya', NULL, 1, NULL, NULL, NULL, NULL),
(144, NULL, 'widyaiswara', 'Widyaiswara Utama', 'Widyaiswara Utama', NULL, 1, NULL, NULL, NULL, NULL),
(145, NULL, 'pranata_komputer', 'Pranata Komputer Terampil', 'Pranata Komputer Terampil', NULL, 1, NULL, NULL, NULL, NULL),
(146, NULL, 'pranata_komputer', 'Pranata Komputer Ahli', 'Pranata Komputer Ahli', NULL, 1, NULL, NULL, NULL, NULL),
(147, NULL, 'pranata_komputer_terampil', 'Pranata Komputer Pelaksana Pemula', 'Pranata Komputer Pelaksana Pemula', NULL, 1, NULL, NULL, NULL, NULL),
(148, NULL, 'pranata_komputer_terampil', 'Pranata Komputer Pelaksana', 'Pranata Komputer Pelaksana', NULL, 1, NULL, NULL, NULL, NULL),
(149, NULL, 'pranata_komputer_terampil', 'Pranata Komputer Pelaksana Lanjutan', 'Pranata Komputer Pelaksana Lanjutan', NULL, 1, NULL, NULL, NULL, NULL),
(150, NULL, 'pranata_komputer_terampil', 'Pranata Komputer Penyelia', 'Pranata Komputer Penyelia', NULL, 1, NULL, NULL, NULL, NULL),
(151, NULL, 'pranata_komputer_ahli', 'Pranata Komputer Pertama', 'Pranata Komputer Pertama', NULL, 1, NULL, NULL, NULL, NULL),
(152, NULL, 'pranata_komputer_ahli', 'Pranata Komputer Muda', 'Pranata Komputer Muda', NULL, 1, NULL, NULL, NULL, NULL),
(153, NULL, 'pranata_komputer_ahli', 'Pranata Komputer Madya', 'Pranata Komputer Madya Lanjutan', NULL, 1, NULL, NULL, NULL, NULL),
(154, NULL, 'pranata_komputer_ahli', 'Pranata Komputer Utama', 'Pranata Komputer Utama', NULL, 1, NULL, NULL, NULL, NULL),
(155, NULL, 'stage', 'Ilmu Ekonomi dan Keuangan Negara', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(156, NULL, 'stage', 'Kebijakan Fiskal', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(157, NULL, 'stage', 'Lembaga keuangan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(158, NULL, 'stage', 'Pasar Modal', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(159, NULL, 'stage', 'Penganggaran', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(160, NULL, 'stage', 'KUP', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(161, NULL, 'stage', 'PPN', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(162, NULL, 'stage', 'PPh', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(163, NULL, 'stage', 'PBB', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(164, NULL, 'stage', 'TIK Perpajakan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(165, NULL, 'stage', 'Administrasi Perpajakan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(166, NULL, 'stage', 'Kepabeanan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(167, NULL, 'stage', 'Cukai', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(168, NULL, 'stage', 'Pengelolaan PNBP', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(169, NULL, 'stage', 'Aplikasi Anggaran dan Perbendaharaan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(170, NULL, 'stage', 'Pinjaman Hibah Luar Negeri ', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(171, NULL, 'stage', 'Belanja Pengguna Anggaran', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(172, NULL, 'stage', 'Pencairan Dana BUN', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(173, NULL, 'stage', 'Perimbangan Keuangan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(174, NULL, 'stage', 'Perolehan Barang/Jasa', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(175, NULL, 'stage', 'Barang Milik Negara/Daerah', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(176, NULL, 'stage', 'Kekayaan Negara yang Dipisahkan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(177, NULL, 'stage', 'Piutang dan Lelang', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(178, NULL, 'stage', 'Penilaian', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(179, NULL, 'stage', 'Investasi', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(180, NULL, 'stage', 'Pembiayaan Utang', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(181, NULL, 'stage', 'Pengelolaan Kas', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(182, NULL, 'stage', 'Akuntansi Pemerintahan', 'Rumpun Teknis', NULL, 1, NULL, NULL, NULL, NULL),
(183, NULL, 'stage', 'Hukum', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(184, NULL, 'stage', 'Bahasa Indonesia', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(185, NULL, 'stage', 'Bahasa Inggris', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(186, NULL, 'stage', 'Teknologi, Informasi, dan Komunikasi Data Keuangan ', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(187, NULL, 'stage', 'Manajemen Teknologi', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(188, NULL, 'stage', 'Pranata Komputer', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(189, NULL, 'stage', 'Softskill Kementerian Keuangan', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(190, NULL, 'stage', 'Kediklatan', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(191, NULL, 'stage', 'Manajemen', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(192, NULL, 'stage', 'Organisasi dan TL Kementerian Keuangan', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(193, NULL, 'stage', 'Etika Profesi Kementerian Keuangan', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(194, NULL, 'stage', 'Statistik dan Riset', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(195, NULL, 'stage', 'Kesemaptaan ', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(196, NULL, 'stage', 'Audit', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(197, NULL, 'stage', 'Audit SI-TIK', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(198, NULL, 'stage', 'Kenegaraan', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(199, NULL, 'stage', 'Akuntansi Konvensional ', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(200, NULL, 'stage', 'Akuntansi Syariah', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(201, NULL, 'stage', 'Investigasi, Penyidikan, dan Forensik', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(202, NULL, 'stage', 'Keahlian Senjata', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL),
(203, NULL, 'stage', 'Kepatuhan  Internal', 'Rumpun Umum', NULL, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE IF NOT EXISTS `room` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `satker_id` int(11) NOT NULL,
  `code` varchar(25) NOT NULL,
  `name` varchar(255) NOT NULL,
  `capacity` int(5) DEFAULT '30' COMMENT 'SISWA',
  `owner` tinyint(1) DEFAULT '1' COMMENT '1:SATKER ; 0: BUKAN SATKER',
  `computer` tinyint(1) DEFAULT '0',
  `hostel` tinyint(1) DEFAULT '0',
  `address` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `satker_id`, `code`, `name`, `capacity`, `owner`, `computer`, `hostel`, `address`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 23, 'A01', 'Gedung A Ruang 1', 30, 1, 0, 0, '', 1, NULL, NULL, '2014-10-09 12:01:25', 1);

-- --------------------------------------------------------

--
-- Table structure for table `satker`
--

CREATE TABLE IF NOT EXISTS `satker` (
  `reference_id` int(11) NOT NULL,
  `letter_number` varchar(10) DEFAULT NULL,
  `eselon` int(1) DEFAULT NULL,
  `address` text,
  `city` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`reference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `satker`
--

INSERT INTO `satker` (`reference_id`, `letter_number`, `eselon`, `address`, `city`, `phone`, `fax`, `email`, `website`) VALUES
(23, '', 0, '', 'Jakarta', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `person_id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password_hash` varchar(60) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `eselon2` varchar(255) DEFAULT NULL,
  `eselon3` varchar(255) DEFAULT NULL,
  `eselon4` varchar(255) DEFAULT NULL,
  `satker` int(3) DEFAULT NULL,
  `no_sk` int(11) DEFAULT NULL COMMENT 'SK Pangkat',
  `tmt_sk` int(11) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`person_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `trainer`
--

CREATE TABLE IF NOT EXISTS `trainer` (
  `person_id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `education_history` text,
  `training_history` text,
  `experience_history` text,
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `training`
--

CREATE TABLE IF NOT EXISTS `training` (
  `activity_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `program_revision` int(11) DEFAULT NULL,
  `number` varchar(100) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `regular` tinyint(1) DEFAULT '0',
  `stakeholder` varchar(255) DEFAULT NULL,
  `student_count_plan` int(11) DEFAULT NULL,
  `class_count_plan` int(11) DEFAULT NULL,
  `execution_sk` varchar(255) DEFAULT NULL,
  `result_sk` varchar(255) DEFAULT NULL,
  `cost_source` varchar(255) DEFAULT NULL,
  `cost_plan` decimal(15,2) DEFAULT NULL,
  `cost_real` decimal(15,2) DEFAULT NULL,
  `approved_status` int(3) DEFAULT NULL,
  `approved_note` varchar(255) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`activity_id`),
  KEY `activity_id` (`activity_id`,`program_id`),
  KEY `program_id` (`program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `training_class`
--

CREATE TABLE IF NOT EXISTS `training_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_id` int(11) NOT NULL,
  `class` varchar(5) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_training_assignment_tb_training_subject1` (`training_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_class_student`
--

CREATE TABLE IF NOT EXISTS `training_class_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_id` int(11) NOT NULL,
  `training_class_id` int(11) NOT NULL,
  `training_student_id` int(11) NOT NULL,
  `number` varchar(255) DEFAULT NULL,
  `head_class` tinyint(1) DEFAULT '0',
  `activity` decimal(5,2) DEFAULT '1.00' COMMENT 'NILAI AKTIFITAS',
  `presence` decimal(5,2) DEFAULT NULL,
  `pre_test` decimal(5,2) DEFAULT NULL,
  `post_test` decimal(5,2) DEFAULT NULL,
  `test` decimal(5,2) DEFAULT NULL COMMENT 'Nilai Ujian',
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tb_training_id_2` (`training_id`,`training_student_id`),
  KEY `fk_tb_training_subject_student_tb_training_assignment1` (`training_class_id`),
  KEY `fk_tb_training_subject_student_tb_student1` (`training_student_id`),
  KEY `tb_training_id` (`training_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_class_student_attendance`
--

CREATE TABLE IF NOT EXISTS `training_class_student_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_schedule_id` int(11) NOT NULL,
  `training_class_student_id` int(11) NOT NULL,
  `hours` decimal(5,2) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_training_schedule_id` (`training_schedule_id`),
  KEY `tb_training_class_student_id` (`training_class_student_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=81 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_class_student_certificate`
--

CREATE TABLE IF NOT EXISTS `training_class_student_certificate` (
  `training_class_student_id` int(11) NOT NULL,
  `number` varchar(50) DEFAULT NULL,
  `seri` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `position` int(1) DEFAULT NULL COMMENT '[1:Es1;2:Es2;3:Es3;4:Es4;5:Pelaksana]',
  `position_desc` varchar(255) DEFAULT NULL,
  `graduate` int(11) DEFAULT NULL,
  `graduate_desc` varchar(255) DEFAULT NULL,
  `eselon2` varchar(255) DEFAULT NULL,
  `eselon3` varchar(255) DEFAULT NULL,
  `eselon4` varchar(255) DEFAULT NULL,
  `satker` enum('2','3','4') DEFAULT '2',
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`training_class_student_id`),
  KEY `fk_tb_training_certificate_tb_training1` (`training_class_student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `training_class_subject`
--

CREATE TABLE IF NOT EXISTS `training_class_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_class_id` int(11) NOT NULL,
  `program_subject_id` int(11) NOT NULL,
  `status` int(3) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_training_assignment_tb_training_subject1` (`training_class_id`),
  KEY `fk_tb_training_assignment_tb_trainer1` (`program_subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_class_subject_trainer_evaluation`
--

CREATE TABLE IF NOT EXISTS `training_class_subject_trainer_evaluation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_class_subject_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `comment` varchar(3000) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_training_trainer_evaluation_tb_student1` (`student_id`),
  KEY `tb_training_class_subject_id` (`training_class_subject_id`),
  KEY `trainer_id` (`trainer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_execution_evaluation`
--

CREATE TABLE IF NOT EXISTS `training_execution_evaluation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_class_student_id` int(11) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `text1` varchar(500) DEFAULT NULL,
  `text2` varchar(500) DEFAULT NULL,
  `text3` varchar(500) DEFAULT NULL,
  `text4` varchar(500) DEFAULT NULL,
  `text5` varchar(500) DEFAULT NULL,
  `overall` int(3) DEFAULT NULL,
  `comment` varchar(3000) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_training_execution_evaluation_tb_training_student1` (`training_class_student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_history`
--

CREATE TABLE IF NOT EXISTS `training_history` (
  `activity_id` int(11) NOT NULL,
  `revision` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `program_revision` int(11) DEFAULT NULL,
  `number` varchar(100) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `regular` tinyint(1) DEFAULT '0',
  `stakeholder` varchar(255) DEFAULT NULL,
  `student_count_plan` int(11) DEFAULT NULL,
  `class_count_plan` int(11) DEFAULT NULL,
  `execution_sk` varchar(255) DEFAULT NULL,
  `result_sk` varchar(255) DEFAULT NULL,
  `cost_source` varchar(255) DEFAULT NULL,
  `cost_plan` decimal(15,2) DEFAULT NULL,
  `cost_real` decimal(15,2) DEFAULT NULL,
  `approved_status` int(3) DEFAULT NULL,
  `approved_note` varchar(255) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`activity_id`,`revision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `training_schedule`
--

CREATE TABLE IF NOT EXISTS `training_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_class_id` int(11) NOT NULL,
  `training_class_subject_id` int(11) NOT NULL,
  `activity_room_id` int(11) NOT NULL,
  `activity` varchar(255) DEFAULT NULL COMMENT 'Honor untuk PIC/JP',
  `pic` varchar(100) DEFAULT NULL COMMENT '0-25',
  `hours` decimal(5,2) DEFAULT NULL COMMENT '1JP = 45menit',
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_training_schedule_tb_room1` (`activity_room_id`),
  KEY `tb_activity_room_id` (`activity_room_id`),
  KEY `tb_training_class_subject_assignment_id` (`training_class_subject_id`),
  KEY `tb_training_class_id` (`training_class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_schedule_trainer`
--

CREATE TABLE IF NOT EXISTS `training_schedule_trainer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_schedule_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `hours` decimal(5,2) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `cost` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tb_training_schedule_id` (`training_schedule_id`,`trainer_id`),
  KEY `tb_training_class_subject_assignment_id` (`trainer_id`),
  KEY `tb_training_class_id` (`training_schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_student`
--

CREATE TABLE IF NOT EXISTS `training_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_training_assignment_tb_training_subject1` (`training_id`),
  KEY `tb_student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training_student_plan`
--

CREATE TABLE IF NOT EXISTS `training_student_plan` (
  `training_id` int(11) NOT NULL,
  `spread` varchar(500) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`training_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `training_subject_trainer_recommendation`
--

CREATE TABLE IF NOT EXISTS `training_subject_trainer_recommendation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `training_id` int(11) NOT NULL,
  `program_subject_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `sort` int(5) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_training_subject_trainer_recommendation_tb_training_sub1` (`program_subject_id`),
  KEY `fk_tb_training_subject_trainer_recommendation_tb_trainer1` (`trainer_id`),
  KEY `tb_training_id` (`training_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` smallint(6) NOT NULL DEFAULT '10',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', '5_082mYBEKJPI2VsPKBUwh6EEZFhpuv0', '$2y$13$8W7z/7NCJ1BaLigR9dCWH.C0VdYrCB2.yw68xpoCwn3Hq82Zbi2ye', '', 'milisstudio@gmail.com', 1, 1, 1411603965, 1412108460),
(2, 'admin_sekretariat_badan', '', '$2y$13$srEdKVFpamSq7lRDhNn1Ye3bgVSQcTwPYUi210z/THk1iIIUURfCO', NULL, 'admin_sekretariat_badan@kemenkeu.go.id', 1, 1, 1413765096, 1413765096),
(3, 'admin_pusdiklat_psdm', '', '$2y$13$OQfNDXoGCLOgQ31y24OTwuiZqH/ZrYrDwcz5lW9sHd.Er5e3rtJDq', NULL, 'admin_pusdiklat_psdm@kemenkeu.go.id', 1, 1, 1413765144, 1413765144),
(4, 'admin_pusdiklat_ap', '', '$2y$13$Xh0UOiUz5983S/nou7VUe./GCLgEEh1bDCTiQ5afeQUZb5VENX4hG', NULL, 'admin_pusdiklat_ap@kemenkeu.go.id', 1, 1, 1413765179, 1413765179),
(5, 'admin_pusdiklat_pajak', '', '$2y$13$DpWvMQr7owkwySybUQTCfuUOzwOCipRTjrmf2xtfH6/7wtRXBC8ni', NULL, 'admin_pusdiklat_pajak @kemenkeu.go.id', 1, 1, 1413765209, 1413765209),
(6, 'admin_pusdiklat_bc', '', '$2y$13$qanbodlWc4w1nNwUqUdPZ.9zDDQdmZqWN.jHcLleUuP7DG7TmlltK', NULL, 'admin_pusdiklat_bc @kemenkeu.go.id', 1, 1, 1413765225, 1413765225),
(7, 'admin_pusdiklat_knpk', '', '$2y$13$n4IxTJZEJJO2oNXl2Lx3o.iVf8cENmhcwvWchXrkyeDBgBXgXPC3e', NULL, 'admin_pusdiklat_knpk @kemenkeu.go.id', 1, 1, 1413765244, 1413765244),
(8, 'admin_pusdiklat_ku', '', '$2y$13$zac9E550..fNNMYKMEnxFupl.Tk/4fnn.kR0ChDRwiDqcrCuDE2/a', NULL, 'admin_pusdiklat_ku @kemenkeu.go.id', 1, 1, 1413765275, 1413765275),
(9, 'admin_stan', '', '$2y$13$uoqG0HtjBtFgbINR4iJ9e.eQCBxdSkoMiKXgRsoSJPa3W2hm/wGKq', NULL, 'admin_stan @kemenkeu.go.id', 1, 1, 1413765292, 1413765292),
(10, 'admin_bdk_medan', '', '$2y$13$vNmJaeiDV/5PwjTxR4K1mO1hdyK/5bVxv1qCNPak1iCiNI9yddxka', NULL, 'admin_bdk_medan @kemenkeu.go.id', 1, 1, 1413765313, 1413765313),
(11, 'admin_bdk_balikpapan', '', '$2y$13$H0SEl6BOvql8Y6oAISvvgurjkO1BTTQDgJbN5d9G7NO14GKBw7AfC', NULL, 'admin_bdk_balikpapan @kemenkeu.go.id', 1, 1, 1413765335, 1413765335),
(12, 'admin_bdk_malang', '', '$2y$13$Og69VJTV77YXVXaXYarN3urNkO1xMqh/QN939eVuTrKGbbx4WeFuG', NULL, 'admin_bdk_malang @kemenkeu.go.id', 1, 1, 1413765400, 1413765400),
(13, 'admin_bdk_yogyakarta', '', '$2y$13$E5Nbcd6OMFfIxMMbWeSU4Ovomicu0L3omATlMStMZObMyLCMIgtGu', NULL, 'admin_bdk_yogyakarta @kemenkeu.go.id', 1, 1, 1413765411, 1413765411),
(14, 'admin_bdk_palembang', '', '$2y$13$dDzdv0bQSL8G9q0aZpCT..89eWSQlU2u2MdSmT2Bha1.cpTjOh3Ra', NULL, 'admin_bdk_palembang @kemenkeu.go.id', 1, 1, 1413765510, 1413765510),
(15, 'admin_bdk_makassar', '', '$2y$13$2f10PseJIGtL5CyXt1n3aeP/1QNS1rePa7XxYlr4YFPWrxZ.S2H7W', NULL, 'admin_bdk_makassar @kemenkeu.go.id', 1, 1, 1413765513, 1413765513),
(16, 'admin_bdk_cimahi', '', '$2y$13$h2lPGZMQs4NHx264HmyGreoIsABfp59EXAsxjO.smKUBx/mCvcXlO', NULL, 'admin_bdk_cimahi @kemenkeu.go.id', 1, 1, 1413765517, 1413765517),
(17, 'admin_bdk_manado', '', '$2y$13$8YscgSJcQV0EeNoxudgnCexybK0ktwXayng0Iz5.uH/LxA.HBH502', NULL, 'admin_bdk_manado @kemenkeu.go.id', 1, 1, 1413765520, 1413765520),
(18, 'admin_bdk_pekanbaru', '', '$2y$13$/1AeyBp5ytY563JPgrutm.F0Ff46.IJ/tXLdCo15EmK3CjsMp7era', NULL, 'admin_bdk_pekanbaru @kemenkeu.go.id', 1, 1, 1413765523, 1413765523),
(19, 'admin_bdk_pontianak', '', '$2y$13$sXTOuIaNHf1KnV6LKei17e1jwUqf/0FCyX0WIF92nysGaKtR9Luse', NULL, 'admin_bdk_pontianak @kemenkeu.go.id', 1, 1, 1413765527, 1413765527),
(20, 'admin_bdk_denpasar', '', '$2y$13$Mu8BZntJePVlOxZMW99ow.emjFp3noc21KX4ynYcq1aSaftDz/wOe', NULL, 'admin_bdk_denpasar @kemenkeu.go.id', 1, 1, 1413765562, 1413765562),
(21, 'admin_bdk_magelang', '', '$2y$13$yj6o0HbpWwEibRI0XL/bsuINe1DVXa08TXi0KahLdEI4BI5sbKGxa', NULL, 'admin_bdk_magelang @kemenkeu.go.id', 1, 1, 1413765583, 1413765583);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_room`
--
ALTER TABLE `activity_room`
  ADD CONSTRAINT `activity_room_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `activity_room_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `meeting`
--
ALTER TABLE `meeting`
  ADD CONSTRAINT `meeting_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `meeting_ibfk_2` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`ID`) ON UPDATE CASCADE;

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `object_file`
--
ALTER TABLE `object_file`
  ADD CONSTRAINT `object_file_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `object_person`
--
ALTER TABLE `object_person`
  ADD CONSTRAINT `object_person_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `object_reference`
--
ALTER TABLE `object_reference`
  ADD CONSTRAINT `object_reference_ibfk_1` FOREIGN KEY (`reference_id`) REFERENCES `reference` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `program_subject`
--
ALTER TABLE `program_subject`
  ADD CONSTRAINT `program_subject_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `program` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `satker`
--
ALTER TABLE `satker`
  ADD CONSTRAINT `satker_ibfk_1` FOREIGN KEY (`reference_id`) REFERENCES `reference` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trainer`
--
ALTER TABLE `trainer`
  ADD CONSTRAINT `trainer_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `training`
--
ALTER TABLE `training`
  ADD CONSTRAINT `training_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `program` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_ibfk_3` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_class`
--
ALTER TABLE `training_class`
  ADD CONSTRAINT `training_class_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`activity_id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_class_student`
--
ALTER TABLE `training_class_student`
  ADD CONSTRAINT `training_class_student_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`activity_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_class_student_ibfk_2` FOREIGN KEY (`training_class_id`) REFERENCES `training_class` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_class_student_ibfk_3` FOREIGN KEY (`training_student_id`) REFERENCES `training_student` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_class_student_attendance`
--
ALTER TABLE `training_class_student_attendance`
  ADD CONSTRAINT `training_class_student_attendance_ibfk_1` FOREIGN KEY (`training_class_student_id`) REFERENCES `training_class_student` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_class_student_certificate`
--
ALTER TABLE `training_class_student_certificate`
  ADD CONSTRAINT `training_class_student_certificate_ibfk_1` FOREIGN KEY (`training_class_student_id`) REFERENCES `training_class_student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `training_class_subject`
--
ALTER TABLE `training_class_subject`
  ADD CONSTRAINT `training_class_subject_ibfk_1` FOREIGN KEY (`training_class_id`) REFERENCES `training_class` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_class_subject_trainer_evaluation`
--
ALTER TABLE `training_class_subject_trainer_evaluation`
  ADD CONSTRAINT `training_class_subject_trainer_evaluation_ibfk_1` FOREIGN KEY (`training_class_subject_id`) REFERENCES `training_class_subject` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_class_subject_trainer_evaluation_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`person_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_class_subject_trainer_evaluation_ibfk_3` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`person_id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_execution_evaluation`
--
ALTER TABLE `training_execution_evaluation`
  ADD CONSTRAINT `training_execution_evaluation_ibfk_1` FOREIGN KEY (`training_class_student_id`) REFERENCES `training_class_student` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_schedule`
--
ALTER TABLE `training_schedule`
  ADD CONSTRAINT `training_schedule_ibfk_1` FOREIGN KEY (`training_class_id`) REFERENCES `training_class` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_schedule_ibfk_2` FOREIGN KEY (`training_class_subject_id`) REFERENCES `training_class_subject` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_schedule_trainer`
--
ALTER TABLE `training_schedule_trainer`
  ADD CONSTRAINT `training_schedule_trainer_ibfk_1` FOREIGN KEY (`training_schedule_id`) REFERENCES `training_schedule` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_schedule_trainer_ibfk_2` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`person_id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_student`
--
ALTER TABLE `training_student`
  ADD CONSTRAINT `training_student_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`activity_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_student_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`person_id`) ON UPDATE CASCADE;

--
-- Constraints for table `training_student_plan`
--
ALTER TABLE `training_student_plan`
  ADD CONSTRAINT `training_student_plan_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`activity_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `training_subject_trainer_recommendation`
--
ALTER TABLE `training_subject_trainer_recommendation`
  ADD CONSTRAINT `training_subject_trainer_recommendation_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `training` (`activity_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `training_subject_trainer_recommendation_ibfk_2` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`person_id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
