-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2014 at 05:44 AM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `satker_id`, `name`, `description`, `start`, `end`, `location`, `hostel`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 17, 'Pranata Komputer Terampil ', '', '2014-02-11 00:00:00', '2014-03-17 00:00:00', '23|Lab Lantai 8', 0, 1, '2014-10-22 06:11:40', 1, '2014-10-22 17:00:22', 1),
(2, 17, 'Pemantapan Kurikulum Pranata Komputer Terampil', '', '2014-10-01 00:00:00', '2014-10-01 00:00:00', '23|-', 0, 1, '2014-10-22 07:50:11', 1, '2014-10-22 07:52:47', 1);

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

--
-- Dumping data for table `activity_history`
--

INSERT INTO `activity_history` (`id`, `revision`, `satker_id`, `name`, `description`, `start`, `end`, `location`, `hostel`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 0, 17, 'Pranata Komputer Terampil ', '', '2014-02-11 00:00:00', '2014-03-17 00:00:00', '23|Lab Lantai 8', 0, 1, '2014-10-22 06:11:40', 1, '2014-10-22 17:00:23', 1),
(2, 0, 17, 'Pemantapan Kurikulum Pranata Komputer Terampil', '', '2014-10-01 00:00:00', '2014-10-01 00:00:00', '23|-', 0, 1, '2014-10-22 07:50:11', 1, '2014-10-22 07:52:47', 1);

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

--
-- Dumping data for table `activity_room`
--

INSERT INTO `activity_room` (`activity_id`, `room_id`, `start`, `end`, `note`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 1, '2014-02-11 08:00:00', '2014-03-17 17:00:00', NULL, 2, NULL, NULL, NULL, NULL);

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
('Bagian Kepegawaian', '2', 1413841857),
('Bagian Keuangan', '2', 1413841857),
('Bagian Keuangan', '24', 1413844311),
('Bagian Otl', '2', 1413841857),
('Bagian Otl', '22', 1413844305),
('Bagian Tik', '2', 1413841857),
('Bagian Tik', '25', 1413844318),
('Bagian Umum', '2', 1413841857),
('Bagian Umum', '26', 1413844349),
('BPPK', '1', 1413761860),
('Pusdiklat', '4', 1413841938),
('Pusdiklat', '5', 1413841943),
('Pusdiklat', '6', 1413841954),
('Pusdiklat', '7', 1413841960),
('Pusdiklat', '8', 1413841928),
('Subbagian Tata Usaha, Kepegawaian , Dan Humas ', '101', NULL),
('Subbidang Program ', '101', NULL);

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
('/pusdiklat-evaluation/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/add-activity-class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/available-room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/change-class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/choose-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/class', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/class-schedule-max-time', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/class-subject', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/create-class', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/create-class-subject', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/dashboard', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/delete-activity-class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/delete-class', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/delete-class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/delete-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/honorarium', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/import-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/index-student-plan', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/pic', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/prepare-honorarium', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/program-name', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/property', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/room-class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/set-room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/set-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/trainer-class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/unset-room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/update-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity/view-student-plan', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/class', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/class-schedule-max-time', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/class-subject', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/create-certificate-class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/dashboard', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/delete-certificate-class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/index-student-plan', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/pic', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/print-backend-certificate', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/print-certificate-receipt', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/print-frontend-certificate', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/print-student-checklist', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/print-value-certificate', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/program-name', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/property', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/set-certificate-class', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/update-certificate-class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/update-class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity2/view-student-plan', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/add-activity-class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/available-room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/change-class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/choose-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/class', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/class-schedule-max-time', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/class-subject', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/create-class', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/create-class-subject', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/dashboard', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/delete-activity-class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/delete-class', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/delete-class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/delete-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/honorarium', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/import-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/index-student-plan', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/pic', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/prepare-honorarium', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/program-name', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/property', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/room-class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/set-room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/set-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/trainer-class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/unset-room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/update-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/activity3/view-student-plan', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/default/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/default/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/default/view-employee', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity/create', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity/delete', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity/pic', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity/program-name', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity/room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity2/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity2/create', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity2/delete', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity2/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity2/pic', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity2/program-name', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity2/room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity2/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity2/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity3/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity3/create', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity3/delete', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity3/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity3/pic', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity3/program-name', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity3/room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity3/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-evaluation/meeting-activity3/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity-room/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity-room/create', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity-room/delete', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity-room/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity-room/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity-room/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/add-activity-class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/available-room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/change-class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/choose-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/class', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/class-schedule-max-time', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/class-subject', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/create-class', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/create-class-subject', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/dashboard', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/delete-activity-class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/delete-class', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/delete-class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/delete-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/honorarium', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/import-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/index-student-plan', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/pic', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/prepare-honorarium', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/program-name', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/property', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/room-class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/set-room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/set-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/trainer-class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/unset-room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/update-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity/view-student-plan', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/add-activity', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/attendance', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/choose-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/class', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/class-subject', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/create-class', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/create-class-subject', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/dashboard', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/delete-activity', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/delete-class', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/delete-class-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/delete-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/get-max-time', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/import-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/index-student-plan', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/pic', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/program-name', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/property', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/set-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/trainer-class-schedule', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/update-student', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/activity2/view-student-plan', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/default/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/default/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/default/view-employee', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity/create', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity/delete', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity/pic', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity/program-name', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity/room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity2/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity2/create', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity2/delete', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity2/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity2/pic', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity2/program-name', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity2/room', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity2/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/meeting-activity2/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/room/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/room/create', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/room/delete', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/room/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/room/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/room/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/student/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/student/create', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/student/delete', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/student/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/student/person', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/student/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/student/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/student2/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/student2/create', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/student2/delete', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/student2/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/student2/person', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/student2/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/student2/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-class-student-attendance/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-class-student-attendance/editable', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-class-student-attendance/import', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-class-student-attendance/open-tbs', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-class-student-attendance/php-excel', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-class-student-attendance/print', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-class-student-attendance/recap', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-class-student-attendance/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-schedule-trainer-attendance/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-schedule-trainer-attendance/editab', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-schedule-trainer-attendance/import', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-schedule-trainer-attendance/open-t', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-schedule-trainer-attendance/php-ex', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-schedule-trainer-attendance/print', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-execution/training-schedule-trainer-attendance/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-general/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity/index-student-plan', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity/program-name', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity/view-student-plan', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity2/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity2/index', 2, NULL, NULL, NULL, 1413838824, 1413838824),
('/pusdiklat-general/activity2/index-student-plan', 2, NULL, NULL, NULL, 1413838824, 1413838824),
('/pusdiklat-general/activity2/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity2/program-name', 2, NULL, NULL, NULL, 1413838824, 1413838824),
('/pusdiklat-general/activity2/update', 2, NULL, NULL, NULL, 1413838824, 1413838824),
('/pusdiklat-general/activity2/view', 2, NULL, NULL, NULL, 1413838824, 1413838824),
('/pusdiklat-general/activity2/view-student-plan', 2, NULL, NULL, NULL, 1413838824, 1413838824),
('/pusdiklat-general/activity3/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity3/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity3/index-student-plan', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity3/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity3/program-name', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity3/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity3/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/activity3/view-student-plan', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/assignment0/*', 2, NULL, NULL, NULL, 1413864754, 1413864754),
('/pusdiklat-general/assignment0/block', 2, NULL, NULL, NULL, 1413864754, 1413864754),
('/pusdiklat-general/assignment0/delete', 2, NULL, NULL, NULL, 1413864754, 1413864754),
('/pusdiklat-general/assignment0/index', 2, NULL, NULL, NULL, 1413864754, 1413864754),
('/pusdiklat-general/assignment0/unblock', 2, NULL, NULL, NULL, 1413864754, 1413864754),
('/pusdiklat-general/assignment0/update', 2, NULL, NULL, NULL, 1413864754, 1413864754),
('/pusdiklat-general/assignment0/view', 2, NULL, NULL, NULL, 1413864754, 1413864754),
('/pusdiklat-general/default/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/default/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/default/view-employee', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/employee/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/employee/delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/employee/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/employee/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/employee/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity/create', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity/delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity/program-name', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity/room', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity2/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity2/create', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity2/delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity2/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity2/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity2/program-name', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity2/room', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity2/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity2/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity3/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity3/create', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity3/delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity3/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity3/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity3/program-name', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity3/room', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity3/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/meeting-activity3/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/person/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/person/create', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/person/delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/person/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/person/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/person/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room-request3/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room-request3/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room-request3/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room-request3/program-name', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room-request3/room', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room-request3/set-room', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room-request3/unset-room', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room-request3/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room3/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room3/activity-room', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room3/calendar-activity-room', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room3/create', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room3/delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room3/event-activity-room', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room3/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room3/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room3/update-activity-room', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/room3/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/user/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/user/block', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/user/delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/user/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/user/unblock', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/user/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-general/user/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/activity/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity/create', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity/delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity/index-student-plan', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity/program-name', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity/update-student-plan', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity/view-student-plan', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity2/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity2/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity2/index-student-plan', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity2/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity2/program-name', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity2/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity2/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity2/view-student-plan', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity3/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity3/choose-trainer', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity3/delete-subject-trainer', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity3/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity3/index-student-plan', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity3/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity3/program-name', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity3/set-trainer', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity3/subject', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity3/subject-trainer', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity3/update-subject-trainer', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity3/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity3/view-student-plan', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/activity3/view-subject-trainer', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/default/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/default/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/default/view-employee', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity/create', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity/delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity/program-name', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity/room', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity2/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity2/create', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity2/delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity2/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity2/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity2/program-name', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity2/room', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity2/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity2/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity3/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity3/create', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity3/delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity3/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity3/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity3/program-name', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity3/room', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity3/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/meeting-activity3/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program/create', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program/delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program/status', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program/validation', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/document', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/document-delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/document-history', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/document-status', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/history', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/subject', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/subject-delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/subject-history', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/subject-status', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program2/view-history', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program3/*', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program3/index', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program3/pic', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program3/subject', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program3/subject-delete', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program3/subject-status', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program3/update', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/program3/view', 2, NULL, NULL, NULL, 1413838825, 1413838825),
('/pusdiklat-planning/trainer3/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/trainer3/create', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/trainer3/create-person', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/trainer3/delete', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/trainer3/delete-person', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/trainer3/index', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/trainer3/person', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/trainer3/update', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/trainer3/update-person', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/trainer3/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/trainer3/view-person', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/training-subject-trainer-recommendation/*', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/training-subject-trainer-recommendation/crea', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/training-subject-trainer-recommendation/dele', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/training-subject-trainer-recommendation/inde', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/training-subject-trainer-recommendation/upda', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/pusdiklat-planning/training-subject-trainer-recommendation/view', 2, NULL, NULL, NULL, 1413838826, 1413838826),
('/sekretariat-finance/*', 2, NULL, NULL, NULL, 1413838622, 1413838622),
('/sekretariat-finance/default/*', 2, NULL, NULL, NULL, 1413838622, 1413838622),
('/sekretariat-finance/default/index', 2, NULL, NULL, NULL, 1413838622, 1413838622),
('/sekretariat-finance/reference-sbu/*', 2, NULL, NULL, NULL, 1413838622, 1413838622),
('/sekretariat-finance/reference-sbu/create', 2, NULL, NULL, NULL, 1413838622, 1413838622),
('/sekretariat-finance/reference-sbu/delete', 2, NULL, NULL, NULL, 1413838622, 1413838622),
('/sekretariat-finance/reference-sbu/index', 2, NULL, NULL, NULL, 1413838622, 1413838622),
('/sekretariat-finance/reference-sbu/update', 2, NULL, NULL, NULL, 1413838622, 1413838622),
('/sekretariat-finance/reference-sbu/view', 2, NULL, NULL, NULL, 1413838622, 1413838622),
('/sekretariat-general/*', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/activity-meeting-general/*', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/activity-meeting-general/create', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/activity-meeting-general/delete', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/activity-meeting-general/index', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/activity-meeting-general/update', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/activity-meeting-general/view', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/activity-room/*', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/activity-room/create', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/activity-room/delete', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/activity-room/index', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/activity-room/update', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/activity-room/view', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/default/*', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/default/index', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/room/*', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/room/activity-room', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/room/calendar-activity-room', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/room/create', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/room/delete', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/room/event-activity-room', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/room/index', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/room/update', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/room/update-activity-room', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-general/room/view', 2, NULL, NULL, NULL, 1413838698, 1413838698),
('/sekretariat-hrd/*', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-hrd/default/*', 2, NULL, NULL, NULL, 1413838429, 1413838429),
('/sekretariat-hrd/default/index', 2, NULL, NULL, NULL, 1413838429, 1413838429),
('/sekretariat-hrd/employee/*', 2, NULL, NULL, NULL, 1413838429, 1413838429),
('/sekretariat-hrd/employee/create', 2, NULL, NULL, NULL, 1413838429, 1413838429),
('/sekretariat-hrd/employee/delete', 2, NULL, NULL, NULL, 1413838429, 1413838429),
('/sekretariat-hrd/employee/index', 2, NULL, NULL, NULL, 1413838429, 1413838429),
('/sekretariat-hrd/employee/organisation', 2, NULL, NULL, NULL, 1413838429, 1413838429),
('/sekretariat-hrd/employee/update', 2, NULL, NULL, NULL, 1413838429, 1413838429),
('/sekretariat-hrd/employee/view', 2, NULL, NULL, NULL, 1413838429, 1413838429),
('/sekretariat-hrd/person/*', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-hrd/person/create', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-hrd/person/delete', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-hrd/person/index', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-hrd/person/update', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-hrd/person/view', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-hrd/user/*', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-hrd/user/block', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-hrd/user/create', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-hrd/user/delete', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-hrd/user/index', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-hrd/user/unblock', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-hrd/user/update', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-hrd/user/view', 2, NULL, NULL, NULL, 1413838430, 1413838430),
('/sekretariat-it/*', 2, NULL, NULL, NULL, 1413847543, 1413847543),
('/sekretariat-it/default/*', 2, NULL, NULL, NULL, 1413847590, 1413847590),
('/sekretariat-it/default/index', 2, NULL, NULL, NULL, 1413847601, 1413847601),
('/sekretariat-organisation/*', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/default/*', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/default/index', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-graduate/*', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-graduate/create', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-graduate/delete', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-graduate/index', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-graduate/update', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-graduate/view', 2, NULL, NULL, NULL, 1413838538, 1413838538);
INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('/sekretariat-organisation/reference-program-code/*', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-program-code/create', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-program-code/delete', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-program-code/index', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-program-code/update', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-program-code/view', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-rank-class/*', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-rank-class/create', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-rank-class/delete', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-rank-class/index', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-rank-class/update', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-rank-class/view', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-religion/*', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-religion/create', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-religion/delete', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-religion/index', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-religion/update', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-religion/view', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-satker/*', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-satker/create', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-satker/delete', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-satker/index', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-satker/update', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-satker/view', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-subject-type/*', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-subject-type/create', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-subject-type/delete', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-subject-type/index', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-subject-type/update', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-subject-type/view', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-trainer-type/*', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-trainer-type/create', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-trainer-type/delete', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-trainer-type/index', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-trainer-type/update', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-trainer-type/view', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-unit/*', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-unit/create', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-unit/delete', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-unit/index', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-unit/update', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('/sekretariat-organisation/reference-unit/view', 2, NULL, NULL, NULL, 1413838538, 1413838538),
('admin-pusdiklat', 2, NULL, NULL, NULL, 1413838886, 1413842949),
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
('Pelaksana Subbagian Perencanaan Dan Keuangan', 1, '5', NULL, NULL, NULL, 1413849278),
('Pelaksana Subbagian Perencanaan Dan Keuangan [PSDM]', 1, '5', NULL, NULL, NULL, NULL),
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
('pusdiklat-evaluation-1', 2, NULL, NULL, NULL, 1413840450, 1413840450),
('pusdiklat-evaluation-2', 2, NULL, NULL, NULL, 1413840508, 1413840508),
('pusdiklat-evaluation-3', 2, NULL, NULL, NULL, 1413840560, 1413840560),
('pusdiklat-execution-1', 2, NULL, NULL, NULL, 1413840325, 1413840325),
('pusdiklat-execution-2', 2, NULL, NULL, NULL, 1413840386, 1413840386),
('pusdiklat-general-1', 2, NULL, NULL, NULL, 1413839842, 1413839842),
('pusdiklat-general-2', 2, NULL, NULL, NULL, 1413839952, 1413839952),
('pusdiklat-general-3', 2, NULL, NULL, NULL, 1413840012, 1413840012),
('pusdiklat-planning-1', 2, NULL, NULL, NULL, 1413840127, 1413840127),
('pusdiklat-planning-2', 2, NULL, NULL, NULL, 1413840185, 1413840185),
('pusdiklat-planning-3', 2, NULL, NULL, NULL, 1413840251, 1413840251),
('Sekretariat Badan', 1, '2', NULL, NULL, NULL, NULL),
('sekretariat-badan-finance', 2, NULL, NULL, NULL, 1413838636, 1413842873),
('sekretariat-badan-general', 2, NULL, NULL, NULL, 1413838713, 1413842914),
('sekretariat-badan-hrd', 2, NULL, NULL, NULL, 1413838469, 1413842894),
('sekretariat-badan-it', 2, NULL, NULL, NULL, 1413847622, 1413847622),
('sekretariat-badan-organisation', 2, NULL, NULL, NULL, 1413838560, 1413845576),
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
('superadmin', 2, NULL, NULL, NULL, 1413763432, 1413842796);

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
('superadmin', '/admin/*'),
('superadmin', '/admin/default/*'),
('superadmin', '/admin/default/index'),
('superadmin', '/admin/employee/*'),
('superadmin', '/admin/employee/create'),
('superadmin', '/admin/employee/delete'),
('superadmin', '/admin/employee/index'),
('superadmin', '/admin/employee/organisation'),
('superadmin', '/admin/employee/update'),
('superadmin', '/admin/employee/view'),
('superadmin', '/admin/person/*'),
('superadmin', '/admin/person/create'),
('superadmin', '/admin/person/delete'),
('superadmin', '/admin/person/index'),
('superadmin', '/admin/person/update'),
('superadmin', '/admin/person/view'),
('superadmin', '/admin/user/*'),
('superadmin', '/admin/user/block'),
('superadmin', '/admin/user/create'),
('superadmin', '/admin/user/delete'),
('superadmin', '/admin/user/index'),
('superadmin', '/admin/user/unblock'),
('superadmin', '/admin/user/update'),
('superadmin', '/admin/user/view'),
('superadmin', '/gii/*'),
('superadmin', '/privilege/*'),
('admin-pusdiklat', '/pusdiklat-evaluation/*'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/*'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/*'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/add-activity-class-schedule'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/add-activity-class-schedule'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/available-room'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/available-room'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/change-class-student'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/change-class-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/choose-student'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/choose-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/class'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/class'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/class-schedule'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/class-schedule'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/class-schedule-max-time'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/class-schedule-max-time'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/class-student'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/class-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/class-subject'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/class-subject'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/create-class'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/create-class'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/create-class-subject'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/create-class-subject'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/dashboard'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/dashboard'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/delete-activity-class-schedule'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/delete-activity-class-schedule'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/delete-class'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/delete-class'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/delete-class-student'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/delete-class-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/delete-student'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/delete-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/honorarium'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/honorarium'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/import-student'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/import-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/index'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/index'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/index-student-plan'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/index-student-plan'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/pic'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/pic'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/prepare-honorarium'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/prepare-honorarium'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/program-name'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/program-name'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/property'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/property'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/room'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/room'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/room-class-schedule'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/room-class-schedule'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/set-room'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/set-room'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/set-student'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/set-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/student'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/trainer-class-schedule'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/trainer-class-schedule'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/unset-room'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/unset-room'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/update'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/update'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/update-student'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/update-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/view'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/view'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity/view-student-plan'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/activity/view-student-plan'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/*'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/*'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/class'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/class'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/class-schedule'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/class-schedule'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/class-schedule-max-time'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/class-schedule-max-time'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/class-student'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/class-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/class-subject'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/class-subject'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/create-certificate-class-student'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/create-certificate-class-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/dashboard'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/dashboard'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/delete-certificate-class-student'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/delete-certificate-class-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/index'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/index'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/index-student-plan'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/index-student-plan'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/pic'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/pic'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/print-backend-certificate'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/print-backend-certificate'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/print-certificate-receipt'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/print-certificate-receipt'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/print-frontend-certificate'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/print-frontend-certificate'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/print-student-checklist'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/print-student-checklist'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/print-value-certificate'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/print-value-certificate'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/program-name'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/program-name'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/property'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/property'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/set-certificate-class'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/set-certificate-class'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/student'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/update-certificate-class-student'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/update-certificate-class-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/update-class-student'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/update-class-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/view'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/view'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity2/view-student-plan'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/activity2/view-student-plan'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/*'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/*'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/add-activity-class-schedule'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/add-activity-class-schedule'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/available-room'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/available-room'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/change-class-student'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/change-class-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/choose-student'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/choose-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/class'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/class'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/class-schedule'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/class-schedule'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/class-schedule-max-time'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/class-schedule-max-time'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/class-student'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/class-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/class-subject'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/class-subject'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/create-class'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/create-class'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/create-class-subject'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/create-class-subject'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/dashboard'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/dashboard'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/delete-activity-class-schedule'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/delete-activity-class-schedule'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/delete-class'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/delete-class'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/delete-class-student'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/delete-class-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/delete-student'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/delete-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/honorarium'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/honorarium'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/import-student'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/import-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/index'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/index'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/index-student-plan'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/index-student-plan'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/pic'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/pic'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/prepare-honorarium'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/prepare-honorarium'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/program-name'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/program-name'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/property'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/property'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/room'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/room'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/room-class-schedule'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/room-class-schedule'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/set-room'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/set-room'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/set-student'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/set-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/student'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/trainer-class-schedule'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/trainer-class-schedule'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/unset-room'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/unset-room'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/update'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/update'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/update-student'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/update-student'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/view'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/view'),
('admin-pusdiklat', '/pusdiklat-evaluation/activity3/view-student-plan'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/activity3/view-student-plan'),
('admin-pusdiklat', '/pusdiklat-evaluation/default/*'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/default/*'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/default/*'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/default/*'),
('admin-pusdiklat', '/pusdiklat-evaluation/default/index'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/default/index'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/default/index'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/default/index'),
('admin-pusdiklat', '/pusdiklat-evaluation/default/view-employee'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/default/view-employee'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/default/view-employee'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/default/view-employee'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity/*'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/meeting-activity/*'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity/create'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/meeting-activity/create'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity/delete'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/meeting-activity/delete'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity/index'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/meeting-activity/index'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity/pic'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/meeting-activity/pic'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity/program-name'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/meeting-activity/program-name'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity/room'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/meeting-activity/room'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity/update'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/meeting-activity/update'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity/view'),
('pusdiklat-evaluation-1', '/pusdiklat-evaluation/meeting-activity/view'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity2/*'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/meeting-activity2/*'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity2/create'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/meeting-activity2/create'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity2/delete'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/meeting-activity2/delete'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity2/index'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/meeting-activity2/index'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity2/pic'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/meeting-activity2/pic'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity2/program-name'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/meeting-activity2/program-name'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity2/room'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/meeting-activity2/room'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity2/update'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/meeting-activity2/update'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity2/view'),
('pusdiklat-evaluation-2', '/pusdiklat-evaluation/meeting-activity2/view'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity3/*'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/meeting-activity3/*'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity3/create'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/meeting-activity3/create'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity3/delete'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/meeting-activity3/delete'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity3/index'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/meeting-activity3/index'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity3/pic'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/meeting-activity3/pic'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity3/program-name'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/meeting-activity3/program-name'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity3/room'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/meeting-activity3/room'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity3/update'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/meeting-activity3/update'),
('admin-pusdiklat', '/pusdiklat-evaluation/meeting-activity3/view'),
('pusdiklat-evaluation-3', '/pusdiklat-evaluation/meeting-activity3/view'),
('admin-pusdiklat', '/pusdiklat-execution/*'),
('admin-pusdiklat', '/pusdiklat-execution/activity-room/*'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity-room/*'),
('admin-pusdiklat', '/pusdiklat-execution/activity-room/create'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity-room/create'),
('admin-pusdiklat', '/pusdiklat-execution/activity-room/delete'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity-room/delete'),
('admin-pusdiklat', '/pusdiklat-execution/activity-room/index'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity-room/index'),
('admin-pusdiklat', '/pusdiklat-execution/activity-room/update'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity-room/update'),
('admin-pusdiklat', '/pusdiklat-execution/activity-room/view'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity-room/view'),
('admin-pusdiklat', '/pusdiklat-execution/activity/*'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/*'),
('admin-pusdiklat', '/pusdiklat-execution/activity/add-activity-class-schedule'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/add-activity-class-schedule'),
('admin-pusdiklat', '/pusdiklat-execution/activity/available-room'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/available-room'),
('admin-pusdiklat', '/pusdiklat-execution/activity/change-class-student'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/change-class-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity/choose-student'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/choose-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity/class'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/class'),
('admin-pusdiklat', '/pusdiklat-execution/activity/class-schedule'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/class-schedule'),
('admin-pusdiklat', '/pusdiklat-execution/activity/class-schedule-max-time'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/class-schedule-max-time'),
('admin-pusdiklat', '/pusdiklat-execution/activity/class-student'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/class-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity/class-subject'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/class-subject'),
('admin-pusdiklat', '/pusdiklat-execution/activity/create-class'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/create-class'),
('admin-pusdiklat', '/pusdiklat-execution/activity/create-class-subject'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/create-class-subject'),
('admin-pusdiklat', '/pusdiklat-execution/activity/dashboard'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/dashboard'),
('admin-pusdiklat', '/pusdiklat-execution/activity/delete-activity-class-schedule'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/delete-activity-class-schedule'),
('admin-pusdiklat', '/pusdiklat-execution/activity/delete-class'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/delete-class'),
('admin-pusdiklat', '/pusdiklat-execution/activity/delete-class-student'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/delete-class-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity/delete-student'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/delete-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity/honorarium'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/honorarium'),
('admin-pusdiklat', '/pusdiklat-execution/activity/import-student'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/import-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity/index'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/index'),
('admin-pusdiklat', '/pusdiklat-execution/activity/index-student-plan'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/index-student-plan'),
('admin-pusdiklat', '/pusdiklat-execution/activity/pic'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/pic'),
('admin-pusdiklat', '/pusdiklat-execution/activity/prepare-honorarium'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/prepare-honorarium'),
('admin-pusdiklat', '/pusdiklat-execution/activity/program-name'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/program-name'),
('admin-pusdiklat', '/pusdiklat-execution/activity/property'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/property'),
('admin-pusdiklat', '/pusdiklat-execution/activity/room'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/room'),
('admin-pusdiklat', '/pusdiklat-execution/activity/room-class-schedule'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/room-class-schedule'),
('admin-pusdiklat', '/pusdiklat-execution/activity/set-room'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/set-room'),
('admin-pusdiklat', '/pusdiklat-execution/activity/set-student'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/set-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity/student'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/student'),
('admin-pusdiklat', '/pusdiklat-execution/activity/trainer-class-schedule'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/trainer-class-schedule'),
('admin-pusdiklat', '/pusdiklat-execution/activity/unset-room'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/unset-room'),
('admin-pusdiklat', '/pusdiklat-execution/activity/update'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/update'),
('admin-pusdiklat', '/pusdiklat-execution/activity/update-student'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/update-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity/view'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/view'),
('admin-pusdiklat', '/pusdiklat-execution/activity/view-student-plan'),
('pusdiklat-execution-1', '/pusdiklat-execution/activity/view-student-plan'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/*'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/*'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/add-activity'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/add-activity'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/attendance'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/attendance'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/choose-student'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/choose-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/class'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/class'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/class-student'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/class-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/class-subject'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/class-subject'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/create-class'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/create-class'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/create-class-subject'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/create-class-subject'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/dashboard'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/dashboard'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/delete-activity'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/delete-activity'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/delete-class'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/delete-class'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/delete-class-student'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/delete-class-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/delete-student'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/delete-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/get-max-time'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/get-max-time'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/import-student'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/import-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/index'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/index'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/index-student-plan'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/index-student-plan'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/pic'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/pic'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/program-name'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/program-name'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/property'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/property'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/schedule'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/schedule'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/set-student'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/set-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/student'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/student'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/trainer-class-schedule'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/trainer-class-schedule'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/update'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/update'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/update-student'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/update-student'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/view'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/view'),
('admin-pusdiklat', '/pusdiklat-execution/activity2/view-student-plan'),
('pusdiklat-execution-2', '/pusdiklat-execution/activity2/view-student-plan'),
('admin-pusdiklat', '/pusdiklat-execution/default/*'),
('pusdiklat-execution-1', '/pusdiklat-execution/default/*'),
('pusdiklat-execution-2', '/pusdiklat-execution/default/*'),
('admin-pusdiklat', '/pusdiklat-execution/default/index'),
('pusdiklat-execution-1', '/pusdiklat-execution/default/index'),
('pusdiklat-execution-2', '/pusdiklat-execution/default/index'),
('admin-pusdiklat', '/pusdiklat-execution/default/view-employee'),
('pusdiklat-execution-1', '/pusdiklat-execution/default/view-employee'),
('pusdiklat-execution-2', '/pusdiklat-execution/default/view-employee'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity/*'),
('pusdiklat-execution-1', '/pusdiklat-execution/meeting-activity/*'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity/create'),
('pusdiklat-execution-1', '/pusdiklat-execution/meeting-activity/create'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity/delete'),
('pusdiklat-execution-1', '/pusdiklat-execution/meeting-activity/delete'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity/index'),
('pusdiklat-execution-1', '/pusdiklat-execution/meeting-activity/index'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity/pic'),
('pusdiklat-execution-1', '/pusdiklat-execution/meeting-activity/pic'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity/program-name'),
('pusdiklat-execution-1', '/pusdiklat-execution/meeting-activity/program-name'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity/room'),
('pusdiklat-execution-1', '/pusdiklat-execution/meeting-activity/room'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity/update'),
('pusdiklat-execution-1', '/pusdiklat-execution/meeting-activity/update'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity/view'),
('pusdiklat-execution-1', '/pusdiklat-execution/meeting-activity/view'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity2/*'),
('pusdiklat-execution-2', '/pusdiklat-execution/meeting-activity2/*'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity2/create'),
('pusdiklat-execution-2', '/pusdiklat-execution/meeting-activity2/create'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity2/delete'),
('pusdiklat-execution-2', '/pusdiklat-execution/meeting-activity2/delete'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity2/index'),
('pusdiklat-execution-2', '/pusdiklat-execution/meeting-activity2/index'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity2/pic'),
('pusdiklat-execution-2', '/pusdiklat-execution/meeting-activity2/pic'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity2/program-name'),
('pusdiklat-execution-2', '/pusdiklat-execution/meeting-activity2/program-name'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity2/room'),
('pusdiklat-execution-2', '/pusdiklat-execution/meeting-activity2/room'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity2/update'),
('pusdiklat-execution-2', '/pusdiklat-execution/meeting-activity2/update'),
('admin-pusdiklat', '/pusdiklat-execution/meeting-activity2/view'),
('pusdiklat-execution-2', '/pusdiklat-execution/meeting-activity2/view'),
('admin-pusdiklat', '/pusdiklat-execution/room/*'),
('pusdiklat-execution-1', '/pusdiklat-execution/room/*'),
('admin-pusdiklat', '/pusdiklat-execution/room/create'),
('pusdiklat-execution-1', '/pusdiklat-execution/room/create'),
('admin-pusdiklat', '/pusdiklat-execution/room/delete'),
('pusdiklat-execution-1', '/pusdiklat-execution/room/delete'),
('admin-pusdiklat', '/pusdiklat-execution/room/index'),
('pusdiklat-execution-1', '/pusdiklat-execution/room/index'),
('admin-pusdiklat', '/pusdiklat-execution/room/update'),
('pusdiklat-execution-1', '/pusdiklat-execution/room/update'),
('admin-pusdiklat', '/pusdiklat-execution/room/view'),
('pusdiklat-execution-1', '/pusdiklat-execution/room/view'),
('admin-pusdiklat', '/pusdiklat-execution/student/*'),
('pusdiklat-execution-1', '/pusdiklat-execution/student/*'),
('admin-pusdiklat', '/pusdiklat-execution/student/create'),
('pusdiklat-execution-1', '/pusdiklat-execution/student/create'),
('admin-pusdiklat', '/pusdiklat-execution/student/delete'),
('pusdiklat-execution-1', '/pusdiklat-execution/student/delete'),
('admin-pusdiklat', '/pusdiklat-execution/student/index'),
('pusdiklat-execution-1', '/pusdiklat-execution/student/index'),
('admin-pusdiklat', '/pusdiklat-execution/student/person'),
('pusdiklat-execution-1', '/pusdiklat-execution/student/person'),
('admin-pusdiklat', '/pusdiklat-execution/student/update'),
('pusdiklat-execution-1', '/pusdiklat-execution/student/update'),
('admin-pusdiklat', '/pusdiklat-execution/student/view'),
('pusdiklat-execution-1', '/pusdiklat-execution/student/view'),
('admin-pusdiklat', '/pusdiklat-execution/student2/*'),
('pusdiklat-execution-2', '/pusdiklat-execution/student2/*'),
('admin-pusdiklat', '/pusdiklat-execution/student2/create'),
('pusdiklat-execution-2', '/pusdiklat-execution/student2/create'),
('admin-pusdiklat', '/pusdiklat-execution/student2/delete'),
('pusdiklat-execution-2', '/pusdiklat-execution/student2/delete'),
('admin-pusdiklat', '/pusdiklat-execution/student2/index'),
('pusdiklat-execution-2', '/pusdiklat-execution/student2/index'),
('admin-pusdiklat', '/pusdiklat-execution/student2/person'),
('pusdiklat-execution-2', '/pusdiklat-execution/student2/person'),
('admin-pusdiklat', '/pusdiklat-execution/student2/update'),
('pusdiklat-execution-2', '/pusdiklat-execution/student2/update'),
('admin-pusdiklat', '/pusdiklat-execution/student2/view'),
('pusdiklat-execution-2', '/pusdiklat-execution/student2/view'),
('admin-pusdiklat', '/pusdiklat-execution/training-class-student-attendance/*'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-class-student-attendance/*'),
('admin-pusdiklat', '/pusdiklat-execution/training-class-student-attendance/editable'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-class-student-attendance/editable'),
('admin-pusdiklat', '/pusdiklat-execution/training-class-student-attendance/import'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-class-student-attendance/import'),
('admin-pusdiklat', '/pusdiklat-execution/training-class-student-attendance/open-tbs'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-class-student-attendance/open-tbs'),
('admin-pusdiklat', '/pusdiklat-execution/training-class-student-attendance/php-excel'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-class-student-attendance/php-excel'),
('admin-pusdiklat', '/pusdiklat-execution/training-class-student-attendance/print'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-class-student-attendance/print'),
('admin-pusdiklat', '/pusdiklat-execution/training-class-student-attendance/recap'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-class-student-attendance/recap'),
('admin-pusdiklat', '/pusdiklat-execution/training-class-student-attendance/update'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-class-student-attendance/update'),
('admin-pusdiklat', '/pusdiklat-execution/training-schedule-trainer-attendance/*'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-schedule-trainer-attendance/*'),
('admin-pusdiklat', '/pusdiklat-execution/training-schedule-trainer-attendance/editab'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-schedule-trainer-attendance/editab'),
('admin-pusdiklat', '/pusdiklat-execution/training-schedule-trainer-attendance/import'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-schedule-trainer-attendance/import'),
('admin-pusdiklat', '/pusdiklat-execution/training-schedule-trainer-attendance/open-t'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-schedule-trainer-attendance/open-t'),
('admin-pusdiklat', '/pusdiklat-execution/training-schedule-trainer-attendance/php-ex'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-schedule-trainer-attendance/php-ex'),
('admin-pusdiklat', '/pusdiklat-execution/training-schedule-trainer-attendance/print'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-schedule-trainer-attendance/print'),
('admin-pusdiklat', '/pusdiklat-execution/training-schedule-trainer-attendance/update'),
('pusdiklat-execution-1', '/pusdiklat-execution/training-schedule-trainer-attendance/update'),
('admin-pusdiklat', '/pusdiklat-general/*'),
('admin-pusdiklat', '/pusdiklat-general/activity/*'),
('pusdiklat-general-1', '/pusdiklat-general/activity/*'),
('admin-pusdiklat', '/pusdiklat-general/activity/index'),
('pusdiklat-general-1', '/pusdiklat-general/activity/index'),
('admin-pusdiklat', '/pusdiklat-general/activity/index-student-plan'),
('pusdiklat-general-1', '/pusdiklat-general/activity/index-student-plan'),
('admin-pusdiklat', '/pusdiklat-general/activity/pic'),
('pusdiklat-general-1', '/pusdiklat-general/activity/pic'),
('admin-pusdiklat', '/pusdiklat-general/activity/program-name'),
('pusdiklat-general-1', '/pusdiklat-general/activity/program-name'),
('admin-pusdiklat', '/pusdiklat-general/activity/view'),
('pusdiklat-general-1', '/pusdiklat-general/activity/view'),
('admin-pusdiklat', '/pusdiklat-general/activity/view-student-plan'),
('pusdiklat-general-1', '/pusdiklat-general/activity/view-student-plan'),
('admin-pusdiklat', '/pusdiklat-general/activity2/*'),
('pusdiklat-general-2', '/pusdiklat-general/activity2/*'),
('admin-pusdiklat', '/pusdiklat-general/activity2/index'),
('pusdiklat-general-2', '/pusdiklat-general/activity2/index'),
('admin-pusdiklat', '/pusdiklat-general/activity2/index-student-plan'),
('pusdiklat-general-2', '/pusdiklat-general/activity2/index-student-plan'),
('admin-pusdiklat', '/pusdiklat-general/activity2/pic'),
('pusdiklat-general-2', '/pusdiklat-general/activity2/pic'),
('admin-pusdiklat', '/pusdiklat-general/activity2/program-name'),
('pusdiklat-general-2', '/pusdiklat-general/activity2/program-name'),
('admin-pusdiklat', '/pusdiklat-general/activity2/update'),
('pusdiklat-general-2', '/pusdiklat-general/activity2/update'),
('admin-pusdiklat', '/pusdiklat-general/activity2/view'),
('pusdiklat-general-2', '/pusdiklat-general/activity2/view'),
('admin-pusdiklat', '/pusdiklat-general/activity2/view-student-plan'),
('pusdiklat-general-2', '/pusdiklat-general/activity2/view-student-plan'),
('admin-pusdiklat', '/pusdiklat-general/activity3/*'),
('pusdiklat-general-3', '/pusdiklat-general/activity3/*'),
('admin-pusdiklat', '/pusdiklat-general/activity3/index'),
('pusdiklat-general-3', '/pusdiklat-general/activity3/index'),
('admin-pusdiklat', '/pusdiklat-general/activity3/index-student-plan'),
('pusdiklat-general-3', '/pusdiklat-general/activity3/index-student-plan'),
('admin-pusdiklat', '/pusdiklat-general/activity3/pic'),
('pusdiklat-general-3', '/pusdiklat-general/activity3/pic'),
('admin-pusdiklat', '/pusdiklat-general/activity3/program-name'),
('pusdiklat-general-3', '/pusdiklat-general/activity3/program-name'),
('admin-pusdiklat', '/pusdiklat-general/activity3/update'),
('pusdiklat-general-3', '/pusdiklat-general/activity3/update'),
('admin-pusdiklat', '/pusdiklat-general/activity3/view'),
('pusdiklat-general-3', '/pusdiklat-general/activity3/view'),
('admin-pusdiklat', '/pusdiklat-general/activity3/view-student-plan'),
('pusdiklat-general-3', '/pusdiklat-general/activity3/view-student-plan'),
('admin-pusdiklat', '/pusdiklat-general/assignment0/*'),
('admin-pusdiklat', '/pusdiklat-general/assignment0/block'),
('admin-pusdiklat', '/pusdiklat-general/assignment0/delete'),
('admin-pusdiklat', '/pusdiklat-general/assignment0/index'),
('admin-pusdiklat', '/pusdiklat-general/assignment0/unblock'),
('admin-pusdiklat', '/pusdiklat-general/assignment0/update'),
('admin-pusdiklat', '/pusdiklat-general/assignment0/view'),
('admin-pusdiklat', '/pusdiklat-general/default/*'),
('pusdiklat-general-1', '/pusdiklat-general/default/*'),
('pusdiklat-general-2', '/pusdiklat-general/default/*'),
('pusdiklat-general-3', '/pusdiklat-general/default/*'),
('admin-pusdiklat', '/pusdiklat-general/default/index'),
('pusdiklat-general-1', '/pusdiklat-general/default/index'),
('pusdiklat-general-2', '/pusdiklat-general/default/index'),
('pusdiklat-general-3', '/pusdiklat-general/default/index'),
('admin-pusdiklat', '/pusdiklat-general/default/view-employee'),
('pusdiklat-general-1', '/pusdiklat-general/default/view-employee'),
('pusdiklat-general-2', '/pusdiklat-general/default/view-employee'),
('pusdiklat-general-3', '/pusdiklat-general/default/view-employee'),
('admin-pusdiklat', '/pusdiklat-general/employee/*'),
('pusdiklat-general-1', '/pusdiklat-general/employee/*'),
('admin-pusdiklat', '/pusdiklat-general/employee/delete'),
('pusdiklat-general-1', '/pusdiklat-general/employee/delete'),
('admin-pusdiklat', '/pusdiklat-general/employee/index'),
('pusdiklat-general-1', '/pusdiklat-general/employee/index'),
('admin-pusdiklat', '/pusdiklat-general/employee/update'),
('pusdiklat-general-1', '/pusdiklat-general/employee/update'),
('admin-pusdiklat', '/pusdiklat-general/employee/view'),
('pusdiklat-general-1', '/pusdiklat-general/employee/view'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity/*'),
('pusdiklat-general-1', '/pusdiklat-general/meeting-activity/*'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity/create'),
('pusdiklat-general-1', '/pusdiklat-general/meeting-activity/create'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity/delete'),
('pusdiklat-general-1', '/pusdiklat-general/meeting-activity/delete'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity/index'),
('pusdiklat-general-1', '/pusdiklat-general/meeting-activity/index'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity/pic'),
('pusdiklat-general-1', '/pusdiklat-general/meeting-activity/pic'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity/program-name'),
('pusdiklat-general-1', '/pusdiklat-general/meeting-activity/program-name'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity/room'),
('pusdiklat-general-1', '/pusdiklat-general/meeting-activity/room'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity/update'),
('pusdiklat-general-1', '/pusdiklat-general/meeting-activity/update'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity/view'),
('pusdiklat-general-1', '/pusdiklat-general/meeting-activity/view'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity2/*'),
('pusdiklat-general-2', '/pusdiklat-general/meeting-activity2/*'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity2/create'),
('pusdiklat-general-2', '/pusdiklat-general/meeting-activity2/create'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity2/delete'),
('pusdiklat-general-2', '/pusdiklat-general/meeting-activity2/delete'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity2/index'),
('pusdiklat-general-2', '/pusdiklat-general/meeting-activity2/index'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity2/pic'),
('pusdiklat-general-2', '/pusdiklat-general/meeting-activity2/pic'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity2/program-name'),
('pusdiklat-general-2', '/pusdiklat-general/meeting-activity2/program-name'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity2/room'),
('pusdiklat-general-2', '/pusdiklat-general/meeting-activity2/room'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity2/update'),
('pusdiklat-general-2', '/pusdiklat-general/meeting-activity2/update'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity2/view'),
('pusdiklat-general-2', '/pusdiklat-general/meeting-activity2/view'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity3/*'),
('pusdiklat-general-3', '/pusdiklat-general/meeting-activity3/*'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity3/create'),
('pusdiklat-general-3', '/pusdiklat-general/meeting-activity3/create'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity3/delete'),
('pusdiklat-general-3', '/pusdiklat-general/meeting-activity3/delete'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity3/index'),
('pusdiklat-general-3', '/pusdiklat-general/meeting-activity3/index'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity3/pic'),
('pusdiklat-general-3', '/pusdiklat-general/meeting-activity3/pic'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity3/program-name'),
('pusdiklat-general-3', '/pusdiklat-general/meeting-activity3/program-name'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity3/room'),
('pusdiklat-general-3', '/pusdiklat-general/meeting-activity3/room'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity3/update'),
('pusdiklat-general-3', '/pusdiklat-general/meeting-activity3/update'),
('admin-pusdiklat', '/pusdiklat-general/meeting-activity3/view'),
('pusdiklat-general-3', '/pusdiklat-general/meeting-activity3/view'),
('admin-pusdiklat', '/pusdiklat-general/person/*'),
('pusdiklat-general-1', '/pusdiklat-general/person/*'),
('admin-pusdiklat', '/pusdiklat-general/person/create'),
('pusdiklat-general-1', '/pusdiklat-general/person/create'),
('admin-pusdiklat', '/pusdiklat-general/person/delete'),
('pusdiklat-general-1', '/pusdiklat-general/person/delete'),
('admin-pusdiklat', '/pusdiklat-general/person/index'),
('pusdiklat-general-1', '/pusdiklat-general/person/index'),
('admin-pusdiklat', '/pusdiklat-general/person/update'),
('pusdiklat-general-1', '/pusdiklat-general/person/update'),
('admin-pusdiklat', '/pusdiklat-general/person/view'),
('pusdiklat-general-1', '/pusdiklat-general/person/view'),
('admin-pusdiklat', '/pusdiklat-general/room-request3/*'),
('pusdiklat-general-3', '/pusdiklat-general/room-request3/*'),
('admin-pusdiklat', '/pusdiklat-general/room-request3/index'),
('pusdiklat-general-3', '/pusdiklat-general/room-request3/index'),
('admin-pusdiklat', '/pusdiklat-general/room-request3/pic'),
('pusdiklat-general-3', '/pusdiklat-general/room-request3/pic'),
('admin-pusdiklat', '/pusdiklat-general/room-request3/program-name'),
('pusdiklat-general-3', '/pusdiklat-general/room-request3/program-name'),
('admin-pusdiklat', '/pusdiklat-general/room-request3/room'),
('pusdiklat-general-3', '/pusdiklat-general/room-request3/room'),
('admin-pusdiklat', '/pusdiklat-general/room-request3/set-room'),
('pusdiklat-general-3', '/pusdiklat-general/room-request3/set-room'),
('admin-pusdiklat', '/pusdiklat-general/room-request3/unset-room'),
('pusdiklat-general-3', '/pusdiklat-general/room-request3/unset-room'),
('admin-pusdiklat', '/pusdiklat-general/room-request3/view'),
('pusdiklat-general-3', '/pusdiklat-general/room-request3/view'),
('admin-pusdiklat', '/pusdiklat-general/room3/*'),
('pusdiklat-general-3', '/pusdiklat-general/room3/*'),
('admin-pusdiklat', '/pusdiklat-general/room3/activity-room'),
('pusdiklat-general-3', '/pusdiklat-general/room3/activity-room'),
('admin-pusdiklat', '/pusdiklat-general/room3/calendar-activity-room'),
('pusdiklat-general-3', '/pusdiklat-general/room3/calendar-activity-room'),
('admin-pusdiklat', '/pusdiklat-general/room3/create'),
('pusdiklat-general-3', '/pusdiklat-general/room3/create'),
('admin-pusdiklat', '/pusdiklat-general/room3/delete'),
('pusdiklat-general-3', '/pusdiklat-general/room3/delete'),
('admin-pusdiklat', '/pusdiklat-general/room3/event-activity-room'),
('pusdiklat-general-3', '/pusdiklat-general/room3/event-activity-room'),
('admin-pusdiklat', '/pusdiklat-general/room3/index'),
('pusdiklat-general-3', '/pusdiklat-general/room3/index'),
('admin-pusdiklat', '/pusdiklat-general/room3/update'),
('pusdiklat-general-3', '/pusdiklat-general/room3/update'),
('admin-pusdiklat', '/pusdiklat-general/room3/update-activity-room'),
('pusdiklat-general-3', '/pusdiklat-general/room3/update-activity-room'),
('admin-pusdiklat', '/pusdiklat-general/room3/view'),
('pusdiklat-general-3', '/pusdiklat-general/room3/view'),
('admin-pusdiklat', '/pusdiklat-general/user/*'),
('pusdiklat-general-1', '/pusdiklat-general/user/*'),
('admin-pusdiklat', '/pusdiklat-general/user/block'),
('pusdiklat-general-1', '/pusdiklat-general/user/block'),
('admin-pusdiklat', '/pusdiklat-general/user/delete'),
('pusdiklat-general-1', '/pusdiklat-general/user/delete'),
('admin-pusdiklat', '/pusdiklat-general/user/index'),
('pusdiklat-general-1', '/pusdiklat-general/user/index'),
('admin-pusdiklat', '/pusdiklat-general/user/unblock'),
('pusdiklat-general-1', '/pusdiklat-general/user/unblock'),
('admin-pusdiklat', '/pusdiklat-general/user/update'),
('pusdiklat-general-1', '/pusdiklat-general/user/update'),
('admin-pusdiklat', '/pusdiklat-general/user/view'),
('pusdiklat-general-1', '/pusdiklat-general/user/view'),
('admin-pusdiklat', '/pusdiklat-planning/*'),
('admin-pusdiklat', '/pusdiklat-planning/activity/*'),
('pusdiklat-planning-1', '/pusdiklat-planning/activity/*'),
('admin-pusdiklat', '/pusdiklat-planning/activity/create'),
('pusdiklat-planning-1', '/pusdiklat-planning/activity/create'),
('admin-pusdiklat', '/pusdiklat-planning/activity/delete'),
('pusdiklat-planning-1', '/pusdiklat-planning/activity/delete'),
('admin-pusdiklat', '/pusdiklat-planning/activity/index'),
('pusdiklat-planning-1', '/pusdiklat-planning/activity/index'),
('admin-pusdiklat', '/pusdiklat-planning/activity/index-student-plan');
INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('pusdiklat-planning-1', '/pusdiklat-planning/activity/index-student-plan'),
('admin-pusdiklat', '/pusdiklat-planning/activity/pic'),
('pusdiklat-planning-1', '/pusdiklat-planning/activity/pic'),
('admin-pusdiklat', '/pusdiklat-planning/activity/program-name'),
('pusdiklat-planning-1', '/pusdiklat-planning/activity/program-name'),
('admin-pusdiklat', '/pusdiklat-planning/activity/update'),
('pusdiklat-planning-1', '/pusdiklat-planning/activity/update'),
('admin-pusdiklat', '/pusdiklat-planning/activity/update-student-plan'),
('pusdiklat-planning-1', '/pusdiklat-planning/activity/update-student-plan'),
('admin-pusdiklat', '/pusdiklat-planning/activity/view'),
('pusdiklat-planning-1', '/pusdiklat-planning/activity/view'),
('admin-pusdiklat', '/pusdiklat-planning/activity/view-student-plan'),
('pusdiklat-planning-1', '/pusdiklat-planning/activity/view-student-plan'),
('admin-pusdiklat', '/pusdiklat-planning/activity2/*'),
('pusdiklat-planning-2', '/pusdiklat-planning/activity2/*'),
('admin-pusdiklat', '/pusdiklat-planning/activity2/index'),
('pusdiklat-planning-2', '/pusdiklat-planning/activity2/index'),
('admin-pusdiklat', '/pusdiklat-planning/activity2/index-student-plan'),
('pusdiklat-planning-2', '/pusdiklat-planning/activity2/index-student-plan'),
('admin-pusdiklat', '/pusdiklat-planning/activity2/pic'),
('pusdiklat-planning-2', '/pusdiklat-planning/activity2/pic'),
('admin-pusdiklat', '/pusdiklat-planning/activity2/program-name'),
('pusdiklat-planning-2', '/pusdiklat-planning/activity2/program-name'),
('admin-pusdiklat', '/pusdiklat-planning/activity2/update'),
('pusdiklat-planning-2', '/pusdiklat-planning/activity2/update'),
('admin-pusdiklat', '/pusdiklat-planning/activity2/view'),
('pusdiklat-planning-2', '/pusdiklat-planning/activity2/view'),
('admin-pusdiklat', '/pusdiklat-planning/activity2/view-student-plan'),
('pusdiklat-planning-2', '/pusdiklat-planning/activity2/view-student-plan'),
('admin-pusdiklat', '/pusdiklat-planning/activity3/*'),
('pusdiklat-planning-3', '/pusdiklat-planning/activity3/*'),
('admin-pusdiklat', '/pusdiklat-planning/activity3/choose-trainer'),
('pusdiklat-planning-3', '/pusdiklat-planning/activity3/choose-trainer'),
('admin-pusdiklat', '/pusdiklat-planning/activity3/delete-subject-trainer'),
('pusdiklat-planning-3', '/pusdiklat-planning/activity3/delete-subject-trainer'),
('admin-pusdiklat', '/pusdiklat-planning/activity3/index'),
('pusdiklat-planning-3', '/pusdiklat-planning/activity3/index'),
('admin-pusdiklat', '/pusdiklat-planning/activity3/index-student-plan'),
('pusdiklat-planning-3', '/pusdiklat-planning/activity3/index-student-plan'),
('admin-pusdiklat', '/pusdiklat-planning/activity3/pic'),
('pusdiklat-planning-3', '/pusdiklat-planning/activity3/pic'),
('admin-pusdiklat', '/pusdiklat-planning/activity3/program-name'),
('pusdiklat-planning-3', '/pusdiklat-planning/activity3/program-name'),
('admin-pusdiklat', '/pusdiklat-planning/activity3/set-trainer'),
('pusdiklat-planning-3', '/pusdiklat-planning/activity3/set-trainer'),
('admin-pusdiklat', '/pusdiklat-planning/activity3/subject'),
('pusdiklat-planning-3', '/pusdiklat-planning/activity3/subject'),
('admin-pusdiklat', '/pusdiklat-planning/activity3/subject-trainer'),
('pusdiklat-planning-3', '/pusdiklat-planning/activity3/subject-trainer'),
('admin-pusdiklat', '/pusdiklat-planning/activity3/update-subject-trainer'),
('pusdiklat-planning-3', '/pusdiklat-planning/activity3/update-subject-trainer'),
('admin-pusdiklat', '/pusdiklat-planning/activity3/view'),
('pusdiklat-planning-3', '/pusdiklat-planning/activity3/view'),
('admin-pusdiklat', '/pusdiklat-planning/activity3/view-student-plan'),
('pusdiklat-planning-3', '/pusdiklat-planning/activity3/view-student-plan'),
('admin-pusdiklat', '/pusdiklat-planning/activity3/view-subject-trainer'),
('pusdiklat-planning-3', '/pusdiklat-planning/activity3/view-subject-trainer'),
('admin-pusdiklat', '/pusdiklat-planning/default/*'),
('pusdiklat-planning-1', '/pusdiklat-planning/default/*'),
('pusdiklat-planning-2', '/pusdiklat-planning/default/*'),
('pusdiklat-planning-3', '/pusdiklat-planning/default/*'),
('admin-pusdiklat', '/pusdiklat-planning/default/index'),
('pusdiklat-planning-1', '/pusdiklat-planning/default/index'),
('pusdiklat-planning-2', '/pusdiklat-planning/default/index'),
('pusdiklat-planning-3', '/pusdiklat-planning/default/index'),
('admin-pusdiklat', '/pusdiklat-planning/default/view-employee'),
('pusdiklat-planning-1', '/pusdiklat-planning/default/view-employee'),
('pusdiklat-planning-2', '/pusdiklat-planning/default/view-employee'),
('pusdiklat-planning-3', '/pusdiklat-planning/default/view-employee'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity/*'),
('pusdiklat-planning-1', '/pusdiklat-planning/meeting-activity/*'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity/create'),
('pusdiklat-planning-1', '/pusdiklat-planning/meeting-activity/create'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity/delete'),
('pusdiklat-planning-1', '/pusdiklat-planning/meeting-activity/delete'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity/index'),
('pusdiklat-planning-1', '/pusdiklat-planning/meeting-activity/index'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity/pic'),
('pusdiklat-planning-1', '/pusdiklat-planning/meeting-activity/pic'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity/program-name'),
('pusdiklat-planning-1', '/pusdiklat-planning/meeting-activity/program-name'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity/room'),
('pusdiklat-planning-1', '/pusdiklat-planning/meeting-activity/room'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity/update'),
('pusdiklat-planning-1', '/pusdiklat-planning/meeting-activity/update'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity/view'),
('pusdiklat-planning-1', '/pusdiklat-planning/meeting-activity/view'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity2/*'),
('pusdiklat-planning-2', '/pusdiklat-planning/meeting-activity2/*'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity2/create'),
('pusdiklat-planning-2', '/pusdiklat-planning/meeting-activity2/create'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity2/delete'),
('pusdiklat-planning-2', '/pusdiklat-planning/meeting-activity2/delete'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity2/index'),
('pusdiklat-planning-2', '/pusdiklat-planning/meeting-activity2/index'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity2/pic'),
('pusdiklat-planning-2', '/pusdiklat-planning/meeting-activity2/pic'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity2/program-name'),
('pusdiklat-planning-2', '/pusdiklat-planning/meeting-activity2/program-name'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity2/room'),
('pusdiklat-planning-2', '/pusdiklat-planning/meeting-activity2/room'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity2/update'),
('pusdiklat-planning-2', '/pusdiklat-planning/meeting-activity2/update'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity2/view'),
('pusdiklat-planning-2', '/pusdiklat-planning/meeting-activity2/view'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity3/*'),
('pusdiklat-planning-3', '/pusdiklat-planning/meeting-activity3/*'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity3/create'),
('pusdiklat-planning-3', '/pusdiklat-planning/meeting-activity3/create'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity3/delete'),
('pusdiklat-planning-3', '/pusdiklat-planning/meeting-activity3/delete'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity3/index'),
('pusdiklat-planning-3', '/pusdiklat-planning/meeting-activity3/index'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity3/pic'),
('pusdiklat-planning-3', '/pusdiklat-planning/meeting-activity3/pic'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity3/program-name'),
('pusdiklat-planning-3', '/pusdiklat-planning/meeting-activity3/program-name'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity3/room'),
('pusdiklat-planning-3', '/pusdiklat-planning/meeting-activity3/room'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity3/update'),
('pusdiklat-planning-3', '/pusdiklat-planning/meeting-activity3/update'),
('admin-pusdiklat', '/pusdiklat-planning/meeting-activity3/view'),
('pusdiklat-planning-3', '/pusdiklat-planning/meeting-activity3/view'),
('admin-pusdiklat', '/pusdiklat-planning/program/*'),
('pusdiklat-planning-1', '/pusdiklat-planning/program/*'),
('admin-pusdiklat', '/pusdiklat-planning/program/create'),
('pusdiklat-planning-1', '/pusdiklat-planning/program/create'),
('admin-pusdiklat', '/pusdiklat-planning/program/delete'),
('pusdiklat-planning-1', '/pusdiklat-planning/program/delete'),
('admin-pusdiklat', '/pusdiklat-planning/program/index'),
('pusdiklat-planning-1', '/pusdiklat-planning/program/index'),
('admin-pusdiklat', '/pusdiklat-planning/program/pic'),
('pusdiklat-planning-1', '/pusdiklat-planning/program/pic'),
('admin-pusdiklat', '/pusdiklat-planning/program/status'),
('pusdiklat-planning-1', '/pusdiklat-planning/program/status'),
('admin-pusdiklat', '/pusdiklat-planning/program/update'),
('pusdiklat-planning-1', '/pusdiklat-planning/program/update'),
('admin-pusdiklat', '/pusdiklat-planning/program/validation'),
('pusdiklat-planning-1', '/pusdiklat-planning/program/validation'),
('admin-pusdiklat', '/pusdiklat-planning/program/view'),
('pusdiklat-planning-1', '/pusdiklat-planning/program/view'),
('admin-pusdiklat', '/pusdiklat-planning/program2/*'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/*'),
('admin-pusdiklat', '/pusdiklat-planning/program2/document'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/document'),
('admin-pusdiklat', '/pusdiklat-planning/program2/document-delete'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/document-delete'),
('admin-pusdiklat', '/pusdiklat-planning/program2/document-history'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/document-history'),
('admin-pusdiklat', '/pusdiklat-planning/program2/document-status'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/document-status'),
('admin-pusdiklat', '/pusdiklat-planning/program2/history'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/history'),
('admin-pusdiklat', '/pusdiklat-planning/program2/index'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/index'),
('admin-pusdiklat', '/pusdiklat-planning/program2/pic'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/pic'),
('admin-pusdiklat', '/pusdiklat-planning/program2/subject'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/subject'),
('admin-pusdiklat', '/pusdiklat-planning/program2/subject-delete'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/subject-delete'),
('admin-pusdiklat', '/pusdiklat-planning/program2/subject-history'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/subject-history'),
('admin-pusdiklat', '/pusdiklat-planning/program2/subject-status'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/subject-status'),
('admin-pusdiklat', '/pusdiklat-planning/program2/update'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/update'),
('admin-pusdiklat', '/pusdiklat-planning/program2/view'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/view'),
('admin-pusdiklat', '/pusdiklat-planning/program2/view-history'),
('pusdiklat-planning-2', '/pusdiklat-planning/program2/view-history'),
('admin-pusdiklat', '/pusdiklat-planning/program3/*'),
('pusdiklat-planning-3', '/pusdiklat-planning/program3/*'),
('admin-pusdiklat', '/pusdiklat-planning/program3/index'),
('pusdiklat-planning-3', '/pusdiklat-planning/program3/index'),
('admin-pusdiklat', '/pusdiklat-planning/program3/pic'),
('pusdiklat-planning-3', '/pusdiklat-planning/program3/pic'),
('admin-pusdiklat', '/pusdiklat-planning/program3/subject'),
('pusdiklat-planning-3', '/pusdiklat-planning/program3/subject'),
('admin-pusdiklat', '/pusdiklat-planning/program3/subject-delete'),
('pusdiklat-planning-3', '/pusdiklat-planning/program3/subject-delete'),
('admin-pusdiklat', '/pusdiklat-planning/program3/subject-status'),
('pusdiklat-planning-3', '/pusdiklat-planning/program3/subject-status'),
('admin-pusdiklat', '/pusdiklat-planning/program3/update'),
('pusdiklat-planning-3', '/pusdiklat-planning/program3/update'),
('admin-pusdiklat', '/pusdiklat-planning/program3/view'),
('pusdiklat-planning-3', '/pusdiklat-planning/program3/view'),
('admin-pusdiklat', '/pusdiklat-planning/trainer3/*'),
('pusdiklat-planning-3', '/pusdiklat-planning/trainer3/*'),
('admin-pusdiklat', '/pusdiklat-planning/trainer3/create'),
('pusdiklat-planning-3', '/pusdiklat-planning/trainer3/create'),
('admin-pusdiklat', '/pusdiklat-planning/trainer3/create-person'),
('pusdiklat-planning-3', '/pusdiklat-planning/trainer3/create-person'),
('admin-pusdiklat', '/pusdiklat-planning/trainer3/delete'),
('pusdiklat-planning-3', '/pusdiklat-planning/trainer3/delete'),
('admin-pusdiklat', '/pusdiklat-planning/trainer3/delete-person'),
('pusdiklat-planning-3', '/pusdiklat-planning/trainer3/delete-person'),
('admin-pusdiklat', '/pusdiklat-planning/trainer3/index'),
('pusdiklat-planning-3', '/pusdiklat-planning/trainer3/index'),
('admin-pusdiklat', '/pusdiklat-planning/trainer3/person'),
('pusdiklat-planning-3', '/pusdiklat-planning/trainer3/person'),
('admin-pusdiklat', '/pusdiklat-planning/trainer3/update'),
('pusdiklat-planning-3', '/pusdiklat-planning/trainer3/update'),
('admin-pusdiklat', '/pusdiklat-planning/trainer3/update-person'),
('pusdiklat-planning-3', '/pusdiklat-planning/trainer3/update-person'),
('admin-pusdiklat', '/pusdiklat-planning/trainer3/view'),
('pusdiklat-planning-3', '/pusdiklat-planning/trainer3/view'),
('admin-pusdiklat', '/pusdiklat-planning/trainer3/view-person'),
('pusdiklat-planning-3', '/pusdiklat-planning/trainer3/view-person'),
('admin-pusdiklat', '/pusdiklat-planning/training-subject-trainer-recommendation/*'),
('pusdiklat-planning-1', '/pusdiklat-planning/training-subject-trainer-recommendation/*'),
('admin-pusdiklat', '/pusdiklat-planning/training-subject-trainer-recommendation/crea'),
('pusdiklat-planning-1', '/pusdiklat-planning/training-subject-trainer-recommendation/crea'),
('admin-pusdiklat', '/pusdiklat-planning/training-subject-trainer-recommendation/dele'),
('pusdiklat-planning-1', '/pusdiklat-planning/training-subject-trainer-recommendation/dele'),
('admin-pusdiklat', '/pusdiklat-planning/training-subject-trainer-recommendation/inde'),
('pusdiklat-planning-1', '/pusdiklat-planning/training-subject-trainer-recommendation/inde'),
('admin-pusdiklat', '/pusdiklat-planning/training-subject-trainer-recommendation/upda'),
('pusdiklat-planning-1', '/pusdiklat-planning/training-subject-trainer-recommendation/upda'),
('admin-pusdiklat', '/pusdiklat-planning/training-subject-trainer-recommendation/view'),
('pusdiklat-planning-1', '/pusdiklat-planning/training-subject-trainer-recommendation/view'),
('sekretariat-badan-finance', '/sekretariat-finance/*'),
('sekretariat-badan-finance', '/sekretariat-finance/default/*'),
('sekretariat-badan-finance', '/sekretariat-finance/default/index'),
('sekretariat-badan-finance', '/sekretariat-finance/reference-sbu/*'),
('sekretariat-badan-finance', '/sekretariat-finance/reference-sbu/create'),
('sekretariat-badan-finance', '/sekretariat-finance/reference-sbu/delete'),
('sekretariat-badan-finance', '/sekretariat-finance/reference-sbu/index'),
('sekretariat-badan-finance', '/sekretariat-finance/reference-sbu/update'),
('sekretariat-badan-finance', '/sekretariat-finance/reference-sbu/view'),
('sekretariat-badan-general', '/sekretariat-general/*'),
('sekretariat-badan-general', '/sekretariat-general/activity-meeting-general/*'),
('sekretariat-badan-general', '/sekretariat-general/activity-meeting-general/create'),
('sekretariat-badan-general', '/sekretariat-general/activity-meeting-general/delete'),
('sekretariat-badan-general', '/sekretariat-general/activity-meeting-general/index'),
('sekretariat-badan-general', '/sekretariat-general/activity-meeting-general/update'),
('sekretariat-badan-general', '/sekretariat-general/activity-meeting-general/view'),
('sekretariat-badan-general', '/sekretariat-general/activity-room/*'),
('sekretariat-badan-general', '/sekretariat-general/activity-room/create'),
('sekretariat-badan-general', '/sekretariat-general/activity-room/delete'),
('sekretariat-badan-general', '/sekretariat-general/activity-room/index'),
('sekretariat-badan-general', '/sekretariat-general/activity-room/update'),
('sekretariat-badan-general', '/sekretariat-general/activity-room/view'),
('sekretariat-badan-general', '/sekretariat-general/default/*'),
('sekretariat-badan-general', '/sekretariat-general/default/index'),
('sekretariat-badan-general', '/sekretariat-general/room/*'),
('sekretariat-badan-general', '/sekretariat-general/room/activity-room'),
('sekretariat-badan-general', '/sekretariat-general/room/calendar-activity-room'),
('sekretariat-badan-general', '/sekretariat-general/room/create'),
('sekretariat-badan-general', '/sekretariat-general/room/delete'),
('sekretariat-badan-general', '/sekretariat-general/room/event-activity-room'),
('sekretariat-badan-general', '/sekretariat-general/room/index'),
('sekretariat-badan-general', '/sekretariat-general/room/update'),
('sekretariat-badan-general', '/sekretariat-general/room/update-activity-room'),
('sekretariat-badan-general', '/sekretariat-general/room/view'),
('sekretariat-badan-hrd', '/sekretariat-hrd/*'),
('sekretariat-badan-hrd', '/sekretariat-hrd/default/*'),
('sekretariat-badan-hrd', '/sekretariat-hrd/default/index'),
('sekretariat-badan-hrd', '/sekretariat-hrd/employee/*'),
('sekretariat-badan-hrd', '/sekretariat-hrd/employee/create'),
('sekretariat-badan-hrd', '/sekretariat-hrd/employee/delete'),
('sekretariat-badan-hrd', '/sekretariat-hrd/employee/index'),
('sekretariat-badan-hrd', '/sekretariat-hrd/employee/organisation'),
('sekretariat-badan-hrd', '/sekretariat-hrd/employee/update'),
('sekretariat-badan-hrd', '/sekretariat-hrd/employee/view'),
('sekretariat-badan-hrd', '/sekretariat-hrd/person/*'),
('sekretariat-badan-hrd', '/sekretariat-hrd/person/create'),
('sekretariat-badan-hrd', '/sekretariat-hrd/person/delete'),
('sekretariat-badan-hrd', '/sekretariat-hrd/person/index'),
('sekretariat-badan-hrd', '/sekretariat-hrd/person/update'),
('sekretariat-badan-hrd', '/sekretariat-hrd/person/view'),
('sekretariat-badan-hrd', '/sekretariat-hrd/user/*'),
('sekretariat-badan-hrd', '/sekretariat-hrd/user/block'),
('sekretariat-badan-hrd', '/sekretariat-hrd/user/create'),
('sekretariat-badan-hrd', '/sekretariat-hrd/user/delete'),
('sekretariat-badan-hrd', '/sekretariat-hrd/user/index'),
('sekretariat-badan-hrd', '/sekretariat-hrd/user/unblock'),
('sekretariat-badan-hrd', '/sekretariat-hrd/user/update'),
('sekretariat-badan-hrd', '/sekretariat-hrd/user/view'),
('sekretariat-badan-it', '/sekretariat-it/*'),
('sekretariat-badan-it', '/sekretariat-it/default/*'),
('sekretariat-badan-it', '/sekretariat-it/default/index'),
('sekretariat-badan-organisation', '/sekretariat-organisation/*'),
('sekretariat-badan-organisation', '/sekretariat-organisation/default/*'),
('sekretariat-badan-organisation', '/sekretariat-organisation/default/index'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-graduate/*'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-graduate/create'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-graduate/delete'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-graduate/index'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-graduate/update'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-graduate/view'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-program-code/*'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-program-code/create'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-program-code/delete'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-program-code/index'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-program-code/update'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-program-code/view'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-rank-class/*'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-rank-class/create'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-rank-class/delete'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-rank-class/index'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-rank-class/update'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-rank-class/view'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-religion/*'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-religion/create'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-religion/delete'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-religion/index'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-religion/update'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-religion/view'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-satker/*'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-satker/create'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-satker/delete'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-satker/index'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-satker/update'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-satker/view'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-subject-type/*'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-subject-type/create'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-subject-type/delete'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-subject-type/index'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-subject-type/update'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-subject-type/view'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-trainer-type/*'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-trainer-type/create'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-trainer-type/delete'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-trainer-type/index'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-trainer-type/update'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-trainer-type/view'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-unit/*'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-unit/create'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-unit/delete'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-unit/index'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-unit/update'),
('sekretariat-badan-organisation', '/sekretariat-organisation/reference-unit/view'),
('Pusdiklat', 'admin-pusdiklat'),
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
('Subbagian Perencanaan Dan Keuangan', 'Pelaksana Subbagian Perencanaan Dan Keuangan'),
('Subbagian Perencanaan Dan Keuangan  [PSDM]', 'Pelaksana Subbagian Perencanaan Dan Keuangan [PSDM]'),
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
('admin-pusdiklat', 'pusdiklat-evaluation-1'),
('Pelaksana Subbidang Evaluasi Diklat', 'pusdiklat-evaluation-1'),
('admin-pusdiklat', 'pusdiklat-evaluation-2'),
('Pelaksana Subbidang Pengolahan Hasil Diklat', 'pusdiklat-evaluation-2'),
('admin-pusdiklat', 'pusdiklat-evaluation-3'),
('Pelaksana Subbidang Informasi Dan Pelaporan Kinerja', 'pusdiklat-evaluation-3'),
('admin-pusdiklat', 'pusdiklat-execution-1'),
('Pelaksana Subbidang Penyelenggaraan I', 'pusdiklat-execution-1'),
('admin-pusdiklat', 'pusdiklat-execution-2'),
('Pelaksana Subbidang Penyelenggaraan II', 'pusdiklat-execution-2'),
('admin-pusdiklat', 'pusdiklat-general-1'),
('Pelaksana Subbagian Tata Usaha, Kepegawaian, Dan Humas', 'pusdiklat-general-1'),
('admin-pusdiklat', 'pusdiklat-general-2'),
('Pelaksana Subbagian Perencanaan Dan Keuangan', 'pusdiklat-general-2'),
('admin-pusdiklat', 'pusdiklat-general-3'),
('Pelaksana Subbagian Rumah Tangga Dan Pengelolaan Aset', 'pusdiklat-general-3'),
('admin-pusdiklat', 'pusdiklat-planning-1'),
('Pelaksana Subbidang Program', 'pusdiklat-planning-1'),
('admin-pusdiklat', 'pusdiklat-planning-2'),
('Pelaksana Subbidang Kurikulum', 'pusdiklat-planning-2'),
('admin-pusdiklat', 'pusdiklat-planning-3'),
('Pelaksana Subbidang Tenaga Pengajar', 'pusdiklat-planning-3'),
('BPPK', 'Sekretariat Badan'),
('Pelaksana Bagian Keuangan', 'sekretariat-badan-finance'),
('Pelaksana Bagian Umum', 'sekretariat-badan-general'),
('Pelaksana Bagian Kepegawaian', 'sekretariat-badan-hrd'),
('Pelaksana Bagian Tik', 'sekretariat-badan-it'),
('Pelaksana Bagian Otl', 'sekretariat-badan-organisation'),
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
('BPPK', 'superadmin');

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
(21, 36, 21, NULL, 0),
(22, 17, 22, NULL, NULL),
(23, 17, 23, NULL, NULL),
(24, 17, 24, NULL, NULL),
(25, 17, 25, NULL, NULL),
(26, 17, 26, NULL, NULL),
(101, 17, 101, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`id`, `name`, `file_name`, `file_type`, `file_size`, `description`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, '5445ad5231266.jpg', '5445ad5231266.jpg', NULL, NULL, NULL, 1, '2014-10-21 07:48:18', 1, '2014-10-21 07:48:18', 1);

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

--
-- Dumping data for table `meeting`
--

INSERT INTO `meeting` (`activity_id`, `attendance_count_plan`, `organisation_id`) VALUES
(2, 15, 392);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES
(1, 'Sekretariat', NULL, NULL, NULL, NULL),
(2, 'Bagian OTL', 1, '/sekretariat-organisation/default/index', 1, 'return [''icon''=>''fa fa-sitemap fa-fw'',''path''=>''/sekretariat-organisation/''];'),
(3, 'Bagian Kepegawaian', 1, '/sekretariat-hrd/default/index', 2, 'return [''icon''=>''fa fa-users fa-fw'',''path''=>''/sekretariat-hrd/''];'),
(4, 'Bagian Keuangan', 1, '/sekretariat-finance/default/index', 3, 'return [''icon''=>''fa fa-money fa-fw'',''path''=>''/sekretariat-finance/''];'),
(5, 'Bagian Umum', 1, '/sekretariat-general/default/index', 4, 'return [''icon''=>''fa fa-joomla fa-fw'',''path''=>''/sekretariat-general/''];'),
(6, 'Bagian TIK', 1, '/sekretariat-it/default/index', 5, 'return [''icon''=>''fa fa-desktop fa-fw'',''path''=>''/sekretariat-it/''];'),
(7, 'Pusdiklat', NULL, NULL, NULL, NULL),
(8, 'Bagian Tata Usaha', 7, '/pusdiklat-general/default/index', 1, 'return [''icon''=>''fa fa-joomla fa-fw'',''path''=>''/pusdiklat-general/''];'),
(9, 'Bidang Perencanaan dan Pengembangan Diklat', 7, '/pusdiklat-planning/default/index', 2, 'return [''icon''=>''fa fa-calendar fa-fw'',''path''=>''/pusdiklat-planning/''];'),
(10, 'Bidang Penyelenggaraan', 7, '/pusdiklat-execution/default/index', 3, 'return [''icon''=>''fa fa-paper-plane fa-fw'',''path''=>''/pusdiklat-execution/''];'),
(11, 'Bidang Evaluasi dan Pelaporan Kinerja', 7, '/pusdiklat-evaluation/default/index', 4, 'return [''icon''=>''fa fa-check-square-o fa-fw'',''path''=>''/pusdiklat-evaluation/''];'),
(12, 'Pusdiklat - General - Administrator', 8, '/pusdiklat-general/assignment0/index', NULL, NULL),
(13, 'Assignment', 12, '/pusdiklat-general/assignment0/index', 1, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/assignment0/''];'),
(14, 'Pusdiklat - General - I (Pegawai TU Humas)', 8, '/pusdiklat-general/activity/index', NULL, NULL),
(15, 'Diklat', 14, '/pusdiklat-general/activity/index', 1, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/activity/''];'),
(16, 'Rapat', 14, '/pusdiklat-general/meeting-activity/index', 2, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/meeting-activity/''];'),
(17, 'Manajemen Individu', 14, '/pusdiklat-general/person/index', 3, 'return [''icon''=>''fa fa-user fa-fw'',''path''=>''/person/''];'),
(18, 'Manajemen Pegawai', 14, '/pusdiklat-general/employee/index', 4, 'return [''icon''=>''fa fa-user-md fa-fw'',''path''=>''/employee/''];'),
(19, 'Manajemen User', 14, '/pusdiklat-general/user/index', 5, 'return [''icon''=>''fa fa-key fa-fw'',''path''=>''/user/''];'),
(20, 'Pusdiklat - General - II (Keuangan)', 8, '/pusdiklat-general/activity2/index', NULL, NULL),
(21, 'Diklat', 20, '/pusdiklat-general/activity2/index', 1, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/activity2/''];'),
(22, 'Rapat', 20, '/pusdiklat-general/meeting-activity2/index', 2, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/meeting-activity2/''];'),
(23, 'Pusdiklat - General - III (Pengelolaan Aset)', 8, '/pusdiklat-general/activity3/index', NULL, NULL),
(24, 'Diklat', 23, '/pusdiklat-general/activity3/index', 1, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/activity3/''];'),
(25, 'Rapat', 23, '/pusdiklat-general/meeting-activity3/index', 2, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/meeting-activity3/''];'),
(26, 'Permintaan Ruangan', 23, '/pusdiklat-general/room-request3/index', 3, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/room-request3/''];'),
(27, 'Manajemen Ruangan', 23, '/pusdiklat-general/room3/index', 4, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/room3/''];'),
(28, 'Pusdiklat - Planning - I (Program)', 9, '/pusdiklat-planning/program/index', NULL, NULL),
(29, 'Pusdiklat - Planning - II (Kurikulum)', 9, '/pusdiklat-planning/program2/index', NULL, NULL),
(30, 'Pusdiklat - Planning - III (Tenaga Pengajar)', 9, '/pusdiklat-planning/program3/index', NULL, NULL),
(31, 'Program', 28, '/pusdiklat-planning/program/index', 1, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/program/''];'),
(32, 'Diklat', 28, '/pusdiklat-planning/activity/index', 2, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/activity/''];'),
(33, 'Rapat', 28, '/pusdiklat-planning/meeting-activity/index', 3, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/meeting-activity/''];'),
(34, 'Program', 29, '/pusdiklat-planning/program2/index', 1, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/program2/''];'),
(35, 'Diklat', 29, '/pusdiklat-planning/activity2/index', 2, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/activity2/''];'),
(36, 'Rapat', 29, '/pusdiklat-planning/meeting-activity2/index', 3, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/meeting-activity2/''];'),
(37, 'Program', 30, '/pusdiklat-planning/program3/index', 1, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/program3/''];'),
(38, 'Diklat', 30, '/pusdiklat-planning/activity3/index', 2, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/activity3/''];'),
(39, 'Rapat', 30, '/pusdiklat-planning/meeting-activity3/index', 3, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/meeting-activity3/''];'),
(40, 'Manajemen Pengajar', 30, '/pusdiklat-planning/trainer3/index', 4, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/trainer3/''];'),
(41, 'Pusdiklat - Execution - I', 10, '/pusdiklat-execution/activity/index', NULL, NULL),
(42, 'Pusdiklat - Execution - II', 10, '/pusdiklat-execution/activity2/index', NULL, NULL),
(43, 'Pusdiklat - Evaluation - I (Evaluasi Diklat)', 11, '/pusdiklat-evaluation/activity/index', NULL, NULL),
(44, 'Pusdiklat - Evaluation - II (PHD)', 11, '/pusdiklat-evaluation/activity2/index', NULL, NULL),
(45, 'Pusdiklat - Evaluation - III (IPK)', 11, '/pusdiklat-evaluation/activity3/index', NULL, NULL),
(46, 'Diklat', 41, '/pusdiklat-execution/activity/index', 1, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/activity/''];'),
(47, 'Rapat', 41, '/pusdiklat-execution/meeting-activity/index', 1, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/meeting-activity/''];'),
(48, 'Manajemen Peserta', 41, '/pusdiklat-execution/student/index', 3, 'return [''icon''=>''fa fa-user-md fa-fw'',''path''=>''/student/''];'),
(49, 'Diklat', 42, '/pusdiklat-execution/activity2/index', 1, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/activity2/''];'),
(50, 'Rapat', 42, '/pusdiklat-execution/meeting-activity2/index', 2, 'return [''icon''=>''fa fa-stack-overflow fa-fw'',''path''=>''/meeting-activity2/''];'),
(51, 'Manajemen Peserta', 42, '/pusdiklat-execution/student2/index', 3, 'return [''icon''=>''fa fa-user-md fa-fw'',''path''=>''/student2/''];'),
(52, 'Diklat', 43, '/pusdiklat-evaluation/activity/index', 1, 'return [''icon''=>''fa fa-link fa-fw'',''path''=>''/activity/''];'),
(53, 'Diklat', 44, '/pusdiklat-evaluation/activity2/index', 1, 'return [''icon''=>''fa fa-link fa-fw'',''path''=>''/activity2/''];'),
(54, 'Diklat', 45, '/pusdiklat-evaluation/activity3/index', 1, 'return [''icon''=>''fa fa-link fa-fw'',''path''=>''/activity3/''];'),
(55, 'Rapat', 43, '/pusdiklat-evaluation/meeting-activity/index', 2, 'return [''icon''=>''fa fa-link fa-fw'',''path''=>''/meeting-activity/''];'),
(56, 'Rapat', 44, '/pusdiklat-evaluation/meeting-activity2/index', 2, 'return [''icon''=>''fa fa-link fa-fw'',''path''=>''/meeting-activity2/''];'),
(57, 'Rapat', 45, '/pusdiklat-evaluation/meeting-activity3/index', 2, 'return [''icon''=>''fa fa-link fa-fw'',''path''=>''/meeting-activity3/''];');

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

--
-- Dumping data for table `object_file`
--

INSERT INTO `object_file` (`object`, `object_id`, `type`, `file_id`) VALUES
('person', 101, 'photo', 1);

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
('person', 21, 'religion', NULL),
('person', 22, 'graduate', NULL),
('person', 22, 'rank_class', NULL),
('person', 22, 'religion', NULL),
('person', 22, 'unit', NULL),
('person', 23, 'graduate', NULL),
('person', 23, 'rank_class', NULL),
('person', 23, 'religion', NULL),
('person', 23, 'unit', NULL),
('person', 24, 'graduate', NULL),
('person', 24, 'rank_class', NULL),
('person', 24, 'religion', NULL),
('person', 24, 'unit', NULL),
('person', 25, 'graduate', NULL),
('person', 25, 'rank_class', NULL),
('person', 25, 'religion', NULL),
('person', 25, 'unit', NULL),
('person', 26, 'graduate', NULL),
('person', 26, 'rank_class', NULL),
('person', 26, 'religion', NULL),
('person', 26, 'unit', NULL),
('person', 101, 'graduate', 45),
('person', 101, 'rank_class', 75),
('person', 101, 'religion', 91),
('person', 102, 'unit', 121),
('person', 103, 'unit', 121),
('person', 104, 'unit', 121),
('person', 105, 'unit', 121),
('person', 106, 'unit', 121),
('person', 107, 'unit', 121),
('person', 108, 'unit', 121),
('person', 109, 'unit', 121),
('person', 110, 'unit', 121),
('person', 111, 'unit', 121),
('person', 112, 'unit', 121),
('person', 113, 'unit', 121),
('person', 114, 'unit', 121),
('person', 115, 'unit', 121),
('person', 116, 'unit', 121),
('person', 117, 'unit', 121),
('person', 118, 'unit', 121),
('person', 119, 'unit', 121),
('person', 120, 'unit', 121),
('person', 121, 'unit', 121),
('person', 122, 'unit', 121),
('person', 123, 'unit', 121),
('person', 124, 'unit', 121),
('person', 125, 'unit', 121),
('person', 126, 'unit', 121),
('person', 127, 'unit', 121),
('person', 128, 'unit', 121),
('person', 129, 'unit', 121),
('person', 130, 'unit', 121),
('person', 131, 'unit', 121),
('person', 132, 'unit', 121),
('person', 133, 'unit', 121),
('person', 134, 'unit', 121),
('person', 135, 'unit', 121),
('person', 136, 'unit', 121),
('person', 137, 'unit', 121),
('person', 138, 'unit', 121),
('person', 139, 'unit', 121),
('person', 140, 'unit', 121),
('person', 141, 'unit', 121),
('person', 142, 'unit', 121),
('person', 143, 'unit', 121),
('person', 144, 'unit', 121),
('person', 145, 'unit', 121),
('person', 146, 'unit', 121),
('person', 147, 'unit', 121),
('person', 148, 'unit', 121),
('person', 149, 'unit', 121),
('person', 150, 'unit', 121),
('person', 101, 'unit', 132);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=151 ;

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
(21, '', 'Admin BDK Magelang', '', '', '', 'admin_bdk_magelang', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(22, '', 'Admin Sekretariat Badan - OTL', '', '', '', 'admin_sekretariat_organisation', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(23, '', 'Admin Sekretariat Badan - Kepegawaian', '', '', '', 'admin_sekretariat_hrd', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(24, '', 'Admin Sekretariat Badan - Keuangan', '', '', '', 'admin_sekretariat_finance', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(25, '', 'Admin Sekretariat Badan - TIK', '', '', '', 'admin_sekretariat_it', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(26, '', 'Admin Sekretariat Badan - Umum', '', '', '', 'admin_sekretariat_general', '', '', NULL, 0, '', '', '', '', '', '', '', '', '', 0, '', '', NULL, '', '', 1, NULL, NULL, NULL, NULL),
(101, '198604302009011002', 'Hafid Mukhlasin', 'Hafid', '', 'S.Kom', '198604302009011002', '', 'Jember', '1986-04-30', 1, '081559915720', 'hafid.mukhlasin@depkeu.go.id', 'http://www.hafidmukhlasin.com', 'Perum. Nuansa Madinatul Quran Jl Cepit Raya No 12 Kav 19 Jatimulya - Cilodong - Depok - Jawa Barat', '', '', '', 'Jl Pancoran Timur 2 No 1 Pancoran Jakarta Selatan', 'Bank Mandiri 126 000 545 4839 an Hafid Mukhlasin', 1, '', 'Teknik Informatika - Universitas Teknologi Yogyakarta', 5, 'Pranata Komputer Pertama', 'BPPK', 1, NULL, NULL, NULL, NULL),
(102, '198606052007011002', 'Ageng Budi Widiarto', NULL, NULL, NULL, '198606052007011002', NULL, NULL, '1986-06-05', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(103, '197305071993011001', 'Agung Nana Permana', NULL, NULL, NULL, '197305071993011001', NULL, NULL, '1973-05-07', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(104, '198704302008121003', 'Andre Harahap', NULL, NULL, NULL, '198704302008121003', NULL, NULL, '1987-04-30', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(105, '196501011985032003', 'Anne Akbari N. H. C.', NULL, NULL, NULL, '196501011985032003', NULL, NULL, '1965-01-01', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(106, '196712111988021001', 'Armanzah', NULL, NULL, NULL, '196712111988021001', NULL, NULL, '1967-12-11', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(107, '197704291998031002', 'Bahrahmat Simamora', NULL, NULL, NULL, '197704291998031002', NULL, NULL, '1977-04-29', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(108, '196312281985031001', 'Dedyn Budi Prayoga', NULL, NULL, NULL, '196312281985031001', NULL, NULL, '1963-12-28', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(109, '197404131994031002', 'Didy Supriyadi', NULL, NULL, NULL, '197404131994031002', NULL, NULL, '1974-04-13', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(110, '198201062009011008', 'Dody Widayanto', NULL, NULL, NULL, '198201062009011008', NULL, NULL, '1982-01-06', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(111, '196704121992012001', 'Dwian Widyati Haristyani', NULL, NULL, NULL, '196704121992012001', NULL, NULL, '1967-04-12', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(112, '197802272000011003', 'Edy Setiawan', NULL, NULL, NULL, '197802272000011003', NULL, NULL, '1978-02-27', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(113, '197406011998031001', 'Edy Susanto', NULL, NULL, NULL, '197406011998031001', NULL, NULL, '1974-06-01', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(114, '197307021998032001', 'Fathonatan Dewi Nastiti', NULL, NULL, NULL, '197307021998032001', NULL, NULL, '1973-07-02', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(115, '196911291996031002', 'Ferdy Alfonsus Sihotang', NULL, NULL, NULL, '196911291996031002', NULL, NULL, '1969-11-29', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(116, '197903152000121006', 'Hadi Setiawan', NULL, NULL, NULL, '197903152000121006', NULL, NULL, '1979-03-15', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(117, '198105132002122001', 'Heny Setyawati', NULL, NULL, NULL, '198105132002122001', NULL, NULL, '1981-05-13', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(118, '196411051986031001', 'Indra Utama', NULL, NULL, NULL, '196411051986031001', NULL, NULL, '1964-11-05', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(119, '197111181992031001', 'Iqravid Hajat', NULL, NULL, NULL, '197111181992031001', NULL, NULL, '1971-11-18', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(120, '196209171983021002', 'Isnaidi', NULL, NULL, NULL, '196209171983021002', NULL, NULL, '1962-09-17', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(121, '196111051985031002', 'Jaitar Sirait', NULL, NULL, NULL, '196111051985031002', NULL, NULL, '1961-11-05', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(122, '198708302008121002', 'Jarvik Fuad Rizky', NULL, NULL, NULL, '198708302008121002', NULL, NULL, '1987-08-30', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(123, '197801102002121002', 'M. Ichsan Firmansyah', NULL, NULL, NULL, '197801102002121002', NULL, NULL, '1978-01-10', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(124, '196206151983021001', 'M. Kayani', NULL, NULL, NULL, '196206151983021001', NULL, NULL, '1962-06-15', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(125, '198411052009012003', 'Maria Yosephina Siregar', NULL, NULL, NULL, '198411052009012003', NULL, NULL, '1984-11-05', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(126, '198405182006021002', 'Meiseno Purnawan', NULL, NULL, NULL, '198405182006021002', NULL, NULL, '1984-05-18', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(127, '196205191988101001', 'Mohammad  Irwan', NULL, NULL, NULL, '196205191988101001', NULL, NULL, '1962-05-19', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(128, '197210231993021001', 'Muhaimin Zikri', NULL, NULL, NULL, '197210231993021001', NULL, NULL, '1972-10-23', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(129, '196705031992012001', 'Nany Nur Aini', NULL, NULL, NULL, '196705031992012001', NULL, NULL, '1967-05-03', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(130, '196707051998031001', 'Noor Cholis Yuana', NULL, NULL, NULL, '196707051998031001', NULL, NULL, '1967-07-05', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(131, '196604101987031002', 'Nyamat', NULL, NULL, NULL, '196604101987031002', NULL, NULL, '1966-04-10', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(132, '198606022010122004', 'Prita Anindya', NULL, NULL, NULL, '198606022010122004', NULL, NULL, '1986-06-02', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(133, '198302112009012009', 'Putri Sion', NULL, NULL, NULL, '198302112009012009', NULL, NULL, '1983-02-11', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(134, '198511142009011007', 'Randi Mesarino', NULL, NULL, NULL, '198511142009011007', NULL, NULL, '1985-11-14', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(135, '196711221988032001', 'Ratnasari', NULL, NULL, NULL, '196711221988032001', NULL, NULL, '1967-11-22', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(136, '198306222006022001', 'Retno Maruti', NULL, NULL, NULL, '198306222006022001', NULL, NULL, '1983-06-22', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(137, '198506032006021001', 'Ridwan Ramdhani', NULL, NULL, NULL, '198506032006021001', NULL, NULL, '1985-06-03', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(138, '198606052007012002', 'Ristiana Susanti', NULL, NULL, NULL, '198606052007012002', NULL, NULL, '1986-06-05', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(139, '197004271990121001', 'Rustiyono', NULL, NULL, NULL, '197004271990121001', NULL, NULL, '1970-04-27', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(140, '197307051993031010', 'Saifudin', NULL, NULL, NULL, '197307051993031010', NULL, NULL, '1973-07-05', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(141, '196506231986032003', 'Sri Sukesi, Ak.', NULL, NULL, NULL, '196506231986032003', NULL, NULL, '1965-06-23', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(142, '198711242010122004', 'Suci Lestari', NULL, NULL, NULL, '198711242010122004', NULL, NULL, '1987-11-24', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(143, '198006172001121002', 'Suhadiyan Syah Alam', NULL, NULL, NULL, '198006172001121002', NULL, NULL, '1980-06-17', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(144, '196904181990031001', 'Suparyadi', NULL, NULL, NULL, '196904181990031001', NULL, NULL, '1969-04-18', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(145, '196309191985032001', 'Tirawan Mahulae', NULL, NULL, NULL, '196309191985032001', NULL, NULL, '1963-09-19', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(146, '196608031992011001', 'Tripto Tri Agustono', NULL, NULL, NULL, '196608031992011001', NULL, NULL, '1966-08-03', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(147, '196307181984021003', 'Untung Dwiyono', NULL, NULL, NULL, '196307181984021003', NULL, NULL, '1963-07-18', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(148, '196602061992012001', 'Wijaya Wardhani', NULL, NULL, NULL, '196602061992012001', NULL, NULL, '1966-02-06', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(149, '199001122012122002', 'Yohana Intan Dias Sari', NULL, NULL, NULL, '199001122012122002', NULL, NULL, '1990-01-12', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(150, '198306162004121001', 'Yudhistira, S.AB.', NULL, NULL, NULL, '198306162004121001', NULL, NULL, '1983-06-16', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`id`, `satker_id`, `number`, `name`, `hours`, `days`, `test`, `note`, `stage`, `category`, `validation_status`, `validation_note`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 17, '2.2.2.0', 'Pranata Komputer Terampil', '300.00', 30, 1, '', 'komputer', '1', 0, NULL, 1, '2014-10-22 06:09:02', 1, '2014-10-22 07:45:59', 1);

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

--
-- Dumping data for table `program_history`
--

INSERT INTO `program_history` (`id`, `revision`, `satker_id`, `number`, `name`, `hours`, `days`, `test`, `note`, `stage`, `category`, `validation_status`, `validation_note`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 0, 17, '2.2.2.0', 'Pranata Komputer Terampil', '300.00', 30, 1, '', 'komputer', '1', 0, NULL, 1, '2014-10-22 06:09:02', 1, '2014-10-22 07:45:59', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `program_subject`
--

INSERT INTO `program_subject` (`id`, `program_id`, `program_revision`, `type`, `name`, `hours`, `sort`, `test`, `stage`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(5, 1, 0, 109, 'PHP', '72.00', 1, 1, 'Pranata Komputer', 1, '2014-10-23 03:58:38', 1, '2014-10-23 04:50:32', 1),
(6, 1, 0, 109, 'Aplikasi Pranata Komputer', '36.00', 1, 1, 'Pranata Komputer', 1, '2014-10-23 03:59:37', 1, '2014-10-23 04:51:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `program_subject_history`
--

CREATE TABLE IF NOT EXISTS `program_subject_history` (
  `id` int(11) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `program_subject_history`
--

INSERT INTO `program_subject_history` (`id`, `revision`, `program_id`, `program_revision`, `type`, `name`, `hours`, `sort`, `test`, `stage`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(5, 0, 1, 0, 109, 'PHP', '72.00', 1, 1, 'Pranata Komputer', 1, '2014-10-23 03:58:38', 1, '2014-10-23 04:50:32', 1),
(6, 0, 1, 0, 109, 'Aplikasi Pranata Komputer', '36.00', 1, 1, 'Pranata Komputer', 1, '2014-10-23 03:59:37', 1, '2014-10-23 04:51:08', 1);

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
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `eselon` int(11) NOT NULL,
  `organisation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `organisation_id` (`organisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `parent_id`, `name`, `eselon`, `organisation_id`) VALUES
(1, 0, 'BPPK', 1, 1),
(2, 1, 'Sekretariat Badan', 2, 3),
(3, 2, 'Bagian Kepegawaian', 3, 4),
(4, 3, 'Pelaksana Bagian Kepegawaian', 5, 4),
(5, 2, 'Bagian Otl', 3, 10),
(6, 5, 'Pelaksana Bagian Otl', 5, 10),
(7, 2, 'Bagian Keuangan', 3, 22),
(8, 7, 'Pelaksana Bagian Keuangan', 5, 22),
(9, 2, 'Bagian Tik', 3, 29),
(10, 9, 'Pelaksana Bagian Tik', 5, 29),
(11, 2, 'Bagian Umum', 3, 44),
(12, 11, 'Pelaksana Bagian Umum', 5, 44),
(13, 1, 'Pusdiklat', 2, 387),
(14, 13, 'Bagian Tata Usaha', 3, 388),
(15, 14, 'Subbagian Tata Usaha, Kepegawaian , Dan Humas ', 4, 389),
(16, 15, 'Pelaksana Subbagian Tata Usaha, Kepegawaian, Dan Humas', 5, 389),
(17, 14, 'Subbagian Perencanaan Dan Keuangan', 4, 390),
(18, 17, 'Pelaksana Subbagian Perencanaan Dan Keuangan', 5, 390),
(19, 14, 'Subbagian Rumah Tangga Dan Pengelolaan Aset ', 4, 391),
(20, 19, 'Pelaksana Subbagian Rumah Tangga Dan Pengelolaan Aset', 5, 391),
(21, 13, 'Bidang Perencanaan Dan Pengembangan Diklat', 3, 392),
(22, 21, 'Subbidang Program ', 4, 393),
(23, 22, 'Pelaksana Subbidang Program', 5, 393),
(24, 21, 'Subbidang Kurikulum', 4, 394),
(25, 24, 'Pelaksana Subbidang Kurikulum', 5, 394),
(26, 21, 'Subbidang Tenaga Pengajar ', 4, 395),
(27, 26, 'Pelaksana Subbidang Tenaga Pengajar', 5, 395),
(28, 13, 'Bidang Penyelenggaraan', 3, 396),
(29, 28, 'Subbidang Penyelenggaraan I  ', 4, 397),
(30, 29, 'Pelaksana Subbidang Penyelenggaraan I', 5, 397),
(31, 28, 'Subbidang Penyelenggaraan II', 4, 398),
(32, 31, 'Pelaksana Subbidang Penyelenggaraan II', 5, 398),
(33, 13, 'Bidang Evaluasi Dan Pelaporan Kinerja', 3, 399),
(34, 33, 'Subbidang Evaluasi Diklat ', 4, 400),
(35, 34, 'Pelaksana Subbidang Evaluasi Diklat', 5, 400),
(36, 33, 'Subbidang Pengolahan Hasil Diklat ', 4, 401),
(37, 36, 'Pelaksana Subbidang Pengolahan Hasil Diklat', 5, 401),
(38, 33, 'Subbidang Informasi Dan Pelaporan Kinerja ', 4, 402),
(39, 38, 'Pelaksana Subbidang Informasi Dan Pelaporan Kinerja', 5, 402),
(40, 1, 'Pusdiklat PSDM', 2, 56),
(41, 40, 'Bagian Tata Usaha [PSDM]', 3, 59),
(42, 41, 'Subbagian Tata Usaha, Kepegawaian, Dan Humas  [PSDM]', 4, 61),
(43, 42, 'Pelaksana Subbagian Tata Usaha, Kepegawaian, Dan Humas [PSDM]', 5, 61),
(44, 41, 'Subbagian Perencanaan Dan Keuangan  [PSDM]', 4, 63),
(45, 44, 'Pelaksana Subbagian Perencanaan Dan Keuangan [PSDM]', 5, 63),
(46, 41, 'Subbagian Rumah Tangga Dan Pengelolaan Aset  [PSDM]', 4, 65),
(47, 46, 'Pelaksana Subbagian Rumah Tangga Dan Pengelolaan Aset [PSDM]', 5, 65),
(48, 40, 'Bidang Penjenjangan Dan Peningkatan Kompetensi', 3, 66),
(49, 48, 'Subbidang Perencanaan Dan Pengembangan', 4, 67),
(50, 49, 'Pelaksana Subbidang Perencanaan Dan Pengembangan', 5, 67),
(51, 48, 'Subbidang Penyelenggaraan', 4, 68),
(52, 51, 'Pelaksana Subbidang Penyelenggaraan', 5, 68),
(53, 48, 'Subbidang Evaluasi Dan Pelaporan Kinerja', 4, 69),
(54, 53, 'Pelaksana Subbidang Evaluasi Dan Pelaporan Kinerja', 5, 69),
(55, 1, 'BDK', 3, 343),
(56, 55, 'Seksi Evaluasi Dan Informasi', 4, 345),
(57, 56, 'Pelaksana Seksi Evaluasi Dan Informasi', 5, 345),
(58, 55, 'Seksi Penyelenggaraan', 4, 346),
(59, 58, 'Pelaksana Seksi Penyelenggaraan', 5, 346),
(60, 55, 'Subbag Tata Usaha', 4, 347),
(61, 60, 'Pelaksana Subbag Tata Usaha', 5, 347);

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

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`person_id`, `username`, `password_hash`, `auth_key`, `password_reset_token`, `eselon2`, `eselon3`, `eselon4`, `satker`, `no_sk`, `tmt_sk`, `status`) VALUES
(102, '198606052007011002', '$2y$04$ef5jWuVTbdWOhkv1LwcCnOHV8HvZoCClbsOOwPlHys./NdS.kXd9m', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(103, '197305071993011001', '$2y$04$a39NTJWEMC0h.imuEIXmWOLErBZ8VDxhWwwbA7765lHzSfIlddpqS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(104, '198704302008121003', '$2y$04$g1hfdK5zCs0x3Gdj9A7SNOnJtbBJC5t0to4fcLQ4tIMAGAwhisEy2', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(105, '196501011985032003', '$2y$04$u6RccEww9/Sq.v23TDuxW.UBZV7NNyaCsG79wBXbO6DwMw8OW/7HW', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(106, '196712111988021001', '$2y$04$t/TYa2WKuUd5KSLSS5zkL.SPR/ydoVSlo7iRDRHxyc/LLHP.rJsSW', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(107, '197704291998031002', '$2y$04$FYrSjglcqD9f5xHiJMT3gua8TQ3sH8i1ai11hu2FXRWwOpfxvVJnm', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(108, '196312281985031001', '$2y$04$uhbzXhUIQ7ah/xsMFzKcXe/2YvhIpsM6d4.DMtYG3MuHCL.1lUZHq', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(109, '197404131994031002', '$2y$04$sO6Bs/aQbBCkEqgcfZfZGOUJnj4rIPhOK8R0Cuf0xwhZoHJhqN1n.', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(110, '198201062009011008', '$2y$04$GUeEslDWS1akPovOx3FfsuFm26VnTShSKA4E2zH82ZhR4fw5fLMvq', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(111, '196704121992012001', '$2y$04$SQDuZkoUHVvxRiJOosDz9eSWojzVoLEvrM7Mjz1NTigN1DxKDbw26', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(112, '197802272000011003', '$2y$04$Y25ISZNeZ/pLivhlrctkEuwBEZ7Y9IXXBKyle58X9nK.EW9ktq.Qe', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(113, '197406011998031001', '$2y$04$2vaRpES6ek3qnkYmkGLDR.s0.vy2xHB1KvHYG8/2Awr91YRoprxCq', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(114, '197307021998032001', '$2y$04$.IbBjv./EU6tlGAJvZzTieDfJUh7Hqyh.LHe8AzbdTdGreQEDAGE.', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(115, '196911291996031002', '$2y$04$73fr.bhkW0X9xSbNyZAAZ.In6RJFwbEpdUiQmf06gbiAO8q.GlvSq', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(116, '197903152000121006', '$2y$04$KF9Fp6OKJTUs4A69ne0DauzJ5/NskNSNs1YR6a18G7I7Cqu8j8GPW', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(117, '198105132002122001', '$2y$04$GSwqdTRJjjLSCdOm4fzQNuIegbtWYwdcol7weNSy4VbnaMuALnVyK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(118, '196411051986031001', '$2y$04$x6mNAF12Su0TEOsg1zZ1m.dOKbdVLX9pF3Y.atwbBnujXEj6wPaYG', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(119, '197111181992031001', '$2y$04$8SlxwcjxwUnY0jGgDfhDuuUi.klgHhWQ5TTiCxUOqG30QWNkVmCfK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(120, '196209171983021002', '$2y$04$LxVjOFKDoIuCy07VZ85pouNC2V9Etdi.YmbC3vNQzv3yI0XH24tw2', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(121, '196111051985031002', '$2y$04$6QZZfMcuS5SFzs3C9Z7.7erVge8FV/9vCJPdDdHOZ/cOgTpdIn4PK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(122, '198708302008121002', '$2y$04$Hn0CVaPm9PZKB5gDO6XJoOPorpltZZ.HY6yiFyAZz49KkoxEgPxMW', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(123, '197801102002121002', '$2y$04$42CdwtO3hrql6qh4UDxHguBadhnmrcZJBPOeMKPRuyrfddAlVbjTy', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(124, '196206151983021001', '$2y$04$tL/YTvh1cy61P6Z/Fj4x8eIOcR1YW.yKfDC2OxPmfECrd7.YdgRue', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(125, '198411052009012003', '$2y$04$0zkCZ/w2wOv.8F4HBFjn9OrRTNawA/Boz05V3Wi55ZB1wxGvuVPau', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(126, '198405182006021002', '$2y$04$AvcISpVOGt9deQGq.vvTI.XY8FY1JxoNxV2IILT09ch2G8l/SpVFe', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(127, '196205191988101001', '$2y$04$ys9srHewv2UgrHhLAl5r5Ot8HHDoxai2X8fiIvMq1MD2NWs3ZWbta', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(128, '197210231993021001', '$2y$04$C7FtZyuBHNuCkslkqsvxseSA7QPDyNVMkEDc9vEFN2DQHClmEoTly', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(129, '196705031992012001', '$2y$04$21wp35SiPYNdmW8CeUGbJud8vDzMOabK1Nb4fY6V9IFJzX5qsobCu', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(130, '196707051998031001', '$2y$04$fHBeNQrRMD5bcYtXN/zuGOn4mcWOwlAybXODKWxGhSz7jPNjGaTPK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(131, '196604101987031002', '$2y$04$9eZn5ufEcV7xzfWmY0co4OAbiDZ4GzPq8jvkRjo/oZ59VU2IJLzW6', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(132, '198606022010122004', '$2y$04$CCWsguD1QeLJiy2PFXxSiOPrglcsB38x9F1pl/M1RYcga.aNxadLe', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(133, '198302112009012009', '$2y$04$na50KVDXuHCPnWMmxQ7Gq.es1ni6lZgUvMi7KsCTO0f96Sz34tWcS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(134, '198511142009011007', '$2y$04$mp3jeqy9UN2GZxczpkRf7.JYMlA.ntia9TRTErUdo9emYeel.NrWm', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(135, '196711221988032001', '$2y$04$zp.ptglppm7.4D.hUNfkJedDlIieHIdGH9s0.Lyscwd9.1ocJI/Qu', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(136, '198306222006022001', '$2y$04$gWgUTADQg.yTuneznyJ0u.rp0vc/zrxrdHPoLnez7j3.9bQy5NUHi', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(137, '198506032006021001', '$2y$04$.KoT9ppAeB9t3lrt4aelfOeqiCE18rklsDBg2F4JF5nCHPSSbtsn2', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(138, '198606052007012002', '$2y$04$mJ0rIikVzZsTfbw2Yfbwhe3VaxBq3O22d3G9zjmqvr/sB5TmElk1G', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(139, '197004271990121001', '$2y$04$BQwMXSnPSZUP9VySGQT2Te4rNeG8nUsEQXYXwZIgAJ1Vk7didaF46', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(140, '197307051993031010', '$2y$04$orv9OZjeYWyCGQ7ru5kY5eoaSp8uqizH59l.YLhArOYf3VB3D8DDq', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(141, '196506231986032003', '$2y$04$svDJMxwzj8KuLNAupowA6uv8ej6q4R9ynjps7fd4zI1P9nruV/Ota', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(142, '198711242010122004', '$2y$04$8JKwieQzXVed1bgfHHK0FeHId8x9EehXIVIxtV6bVPWqTtI5.HbGS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(143, '198006172001121002', '$2y$04$x6mG3HxRtG/YkE/SubPaveQsc2ymM9urYopztj6ezR2uq4zUrO6l2', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(144, '196904181990031001', '$2y$04$zLFWCdf8CcxtxhrbJ5tGMeQjdWeJto3Ht827wTe5VIQ/V77F1z7ZK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(145, '196309191985032001', '$2y$04$iVhHY2WFErJXRQaZfjxe2Olx.p3knynUMkxPtlF4tx6sMQCuNXdaC', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(146, '196608031992011001', '$2y$04$ReA7SyhLMureUtmuoo9quOspnTqjie73OcUcM/62u4ycZtOCTLLFK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(147, '196307181984021003', '$2y$04$vgvTbuGTjup1wKz2WOM9GO8UnpFZ7ke2LJAmtVRBhPEcuOftP7c76', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(148, '196602061992012001', '$2y$04$uzlJgVYfV7pqY7oh1pVz9urrpFW4FNvNgbk.v3.Q1HN8laRLkdcP.', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(149, '199001122012122002', '$2y$04$ux307d1eo7510.WgACWDoOqpqcgjsolFOOzZSapp0UsR3ze7tvCpa', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(150, '198306162004121001', '$2y$04$acxm8YbMqJKlbzQ0BTlDcu876/2PCBQA7oHpaUoGQ0X/7eH3b61dC', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

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

--
-- Dumping data for table `trainer`
--

INSERT INTO `trainer` (`person_id`, `category`, `education_history`, `training_history`, `experience_history`) VALUES
(101, NULL, NULL, NULL, NULL);

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

--
-- Dumping data for table `training`
--

INSERT INTO `training` (`activity_id`, `program_id`, `program_revision`, `number`, `note`, `regular`, `stakeholder`, `student_count_plan`, `class_count_plan`, `execution_sk`, `result_sk`, `cost_source`, `cost_plan`, `cost_real`, `approved_status`, `approved_note`, `approved_date`, `approved_by`) VALUES
(1, 1, 0, '2014-01-00-2.2.2.0.2', '', 1, '', 25, 1, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `training_class`
--

INSERT INTO `training_class` (`id`, `training_id`, `class`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 1, 'A', 1, NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `training_class_student`
--

INSERT INTO `training_class_student` (`id`, `training_id`, `training_class_id`, `training_student_id`, `number`, `head_class`, `activity`, `presence`, `pre_test`, `post_test`, `test`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 1, 1, 89, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(2, 1, 1, 59, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(3, 1, 1, 94, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(4, 1, 1, 65, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(5, 1, 1, 70, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(6, 1, 1, 84, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(7, 1, 1, 51, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(8, 1, 1, 85, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(9, 1, 1, 83, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(10, 1, 1, 63, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(11, 1, 1, 87, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(12, 1, 1, 82, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(13, 1, 1, 75, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(14, 1, 1, 74, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(15, 1, 1, 53, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(16, 1, 1, 96, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(17, 1, 1, 68, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(18, 1, 1, 62, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(19, 1, 1, 86, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(20, 1, 1, 60, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(21, 1, 1, 78, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(22, 1, 1, 90, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(23, 1, 1, 98, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(24, 1, 1, 76, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(25, 1, 1, 61, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(26, 1, 1, 93, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(27, 1, 1, 52, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(28, 1, 1, 77, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(29, 1, 1, 66, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(30, 1, 1, 71, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(31, 1, 1, 81, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(32, 1, 1, 69, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(33, 1, 1, 67, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(34, 1, 1, 97, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(35, 1, 1, 73, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(36, 1, 1, 72, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(37, 1, 1, 54, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(38, 1, 1, 92, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(39, 1, 1, 91, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(40, 1, 1, 55, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(41, 1, 1, 56, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(42, 1, 1, 95, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(43, 1, 1, 80, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(44, 1, 1, 88, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(45, 1, 1, 57, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(46, 1, 1, 64, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(47, 1, 1, 58, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(48, 1, 1, 79, NULL, 0, '1.00', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `training_class_subject`
--

INSERT INTO `training_class_subject` (`id`, `training_class_id`, `program_subject_id`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 1, 5, 1, NULL, NULL, NULL, NULL),
(2, 1, 6, 1, NULL, NULL, NULL, NULL);

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

--
-- Dumping data for table `training_history`
--

INSERT INTO `training_history` (`activity_id`, `revision`, `program_id`, `program_revision`, `number`, `note`, `regular`, `stakeholder`, `student_count_plan`, `class_count_plan`, `execution_sk`, `result_sk`, `cost_source`, `cost_plan`, `cost_real`, `approved_status`, `approved_note`, `approved_date`, `approved_by`) VALUES
(1, 0, 1, 0, '2014-01-00-2.2.2.0.2', '', 1, '', 25, 1, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `training_schedule`
--

INSERT INTO `training_schedule` (`id`, `training_class_id`, `training_class_subject_id`, `activity_room_id`, `activity`, `pic`, `hours`, `start`, `end`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(2, 1, -3, 1, 'Pembukaan', 'Kapusdiklat', '0.00', '2014-02-11 08:00:00', '2014-02-11 08:30:00', 1, NULL, NULL, NULL, NULL),
(3, 1, 1, 1, '', '', '3.00', '2014-02-11 08:30:00', '2014-02-11 10:45:00', 1, NULL, NULL, NULL, NULL),
(4, 1, -1, 1, 'Coffe Break', '-', '0.00', '2014-02-11 10:45:00', '2014-02-11 11:00:00', 1, NULL, NULL, NULL, NULL),
(5, 1, 1, 1, '', '', '1.00', '2014-02-11 11:00:00', '2014-02-11 11:45:00', 1, NULL, NULL, NULL, NULL),
(6, 1, -2, 1, 'Ishoma', '-', '0.00', '2014-02-11 11:45:00', '2014-02-11 12:45:00', 1, NULL, NULL, NULL, NULL),
(7, 1, 2, 1, '', '', '4.00', '2014-02-11 12:45:00', '2014-02-11 15:45:00', 1, NULL, NULL, NULL, NULL),
(8, 1, -1, 1, 'Coffe Break', '-', '0.00', '2014-02-11 15:45:00', '2014-02-11 16:00:00', 1, NULL, NULL, NULL, NULL),
(9, 1, 1, 1, '', '', '2.00', '2014-02-11 16:00:00', '2014-02-11 17:30:00', 1, NULL, NULL, NULL, NULL),
(10, 1, 1, 1, '', '', '5.00', '2014-02-12 08:00:00', '2014-02-12 11:45:00', 1, NULL, NULL, NULL, NULL),
(11, 1, -2, 1, 'Ishoma', '-', '0.00', '2014-02-12 11:45:00', '2014-02-12 12:45:00', 1, NULL, NULL, NULL, NULL),
(12, 1, 2, 1, '', '', '5.00', '2014-02-12 12:45:00', '2014-02-12 16:30:00', 1, NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `training_schedule_trainer`
--

INSERT INTO `training_schedule_trainer` (`id`, `training_schedule_id`, `type`, `trainer_id`, `hours`, `reason`, `cost`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(4, 3, 113, 101, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=99 ;

--
-- Dumping data for table `training_student`
--

INSERT INTO `training_student` (`id`, `training_id`, `student_id`, `note`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(50, 1, 102, 'mengundurkan diri', 0, NULL, NULL, NULL, NULL),
(51, 1, 103, NULL, 1, NULL, NULL, NULL, NULL),
(52, 1, 104, NULL, 1, NULL, NULL, NULL, NULL),
(53, 1, 105, NULL, 1, NULL, NULL, NULL, NULL),
(54, 1, 106, NULL, 1, NULL, NULL, NULL, NULL),
(55, 1, 107, NULL, 1, NULL, NULL, NULL, NULL),
(56, 1, 108, NULL, 1, NULL, NULL, NULL, NULL),
(57, 1, 109, NULL, 1, NULL, NULL, NULL, NULL),
(58, 1, 110, NULL, 1, NULL, NULL, NULL, NULL),
(59, 1, 111, NULL, 1, NULL, NULL, NULL, NULL),
(60, 1, 112, NULL, 1, NULL, NULL, NULL, NULL),
(61, 1, 113, NULL, 1, NULL, NULL, NULL, NULL),
(62, 1, 114, NULL, 1, NULL, NULL, NULL, NULL),
(63, 1, 115, NULL, 1, NULL, NULL, NULL, NULL),
(64, 1, 116, NULL, 1, NULL, NULL, NULL, NULL),
(65, 1, 117, NULL, 1, NULL, NULL, NULL, NULL),
(66, 1, 118, NULL, 1, NULL, NULL, NULL, NULL),
(67, 1, 119, NULL, 1, NULL, NULL, NULL, NULL),
(68, 1, 120, NULL, 1, NULL, NULL, NULL, NULL),
(69, 1, 121, NULL, 1, NULL, NULL, NULL, NULL),
(70, 1, 122, NULL, 1, NULL, NULL, NULL, NULL),
(71, 1, 123, NULL, 1, NULL, NULL, NULL, NULL),
(72, 1, 124, NULL, 1, NULL, NULL, NULL, NULL),
(73, 1, 125, NULL, 1, NULL, NULL, NULL, NULL),
(74, 1, 126, NULL, 1, NULL, NULL, NULL, NULL),
(75, 1, 127, NULL, 1, NULL, NULL, NULL, NULL),
(76, 1, 128, NULL, 1, NULL, NULL, NULL, NULL),
(77, 1, 129, NULL, 1, NULL, NULL, NULL, NULL),
(78, 1, 130, NULL, 1, NULL, NULL, NULL, NULL),
(79, 1, 131, NULL, 1, NULL, NULL, NULL, NULL),
(80, 1, 132, NULL, 1, NULL, NULL, NULL, NULL),
(81, 1, 133, NULL, 1, NULL, NULL, NULL, NULL),
(82, 1, 134, NULL, 1, NULL, NULL, NULL, NULL),
(83, 1, 135, NULL, 1, NULL, NULL, NULL, NULL),
(84, 1, 136, NULL, 1, NULL, NULL, NULL, NULL),
(85, 1, 137, NULL, 1, NULL, NULL, NULL, NULL),
(86, 1, 138, NULL, 1, NULL, NULL, NULL, NULL),
(87, 1, 139, NULL, 1, NULL, NULL, NULL, NULL),
(88, 1, 140, NULL, 1, NULL, NULL, NULL, NULL),
(89, 1, 141, NULL, 1, NULL, NULL, NULL, NULL),
(90, 1, 142, NULL, 1, NULL, NULL, NULL, NULL),
(91, 1, 143, NULL, 1, NULL, NULL, NULL, NULL),
(92, 1, 144, NULL, 1, NULL, NULL, NULL, NULL),
(93, 1, 145, NULL, 1, NULL, NULL, NULL, NULL),
(94, 1, 146, NULL, 1, NULL, NULL, NULL, NULL),
(95, 1, 147, NULL, 1, NULL, NULL, NULL, NULL),
(96, 1, 148, NULL, 1, NULL, NULL, NULL, NULL),
(97, 1, 149, NULL, 1, NULL, NULL, NULL, NULL),
(98, 1, 150, NULL, 1, NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `training_subject_trainer_recommendation`
--

INSERT INTO `training_subject_trainer_recommendation` (`id`, `training_id`, `program_subject_id`, `type`, `trainer_id`, `note`, `sort`, `status`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(2, 1, 5, 113, 101, NULL, NULL, 1, NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=102 ;

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
(21, 'admin_bdk_magelang', '', '$2y$13$yj6o0HbpWwEibRI0XL/bsuINe1DVXa08TXi0KahLdEI4BI5sbKGxa', NULL, 'admin_bdk_magelang @kemenkeu.go.id', 1, 1, 1413765583, 1413765583),
(22, 'admin_sekretariat_organisation', '', '$2y$13$DpWalezkf3sFHTdcyvQIbeK55E7PuTG331iNxN5Tfk5qRGOf7WIKi', NULL, 'admin_sekretariat_organisation@gmail.com', 1, 1, 1413843604, 1413843604),
(23, 'admin_sekretariat_hrd', '', '$2y$13$I15w2LMAOslm3GJrIC3ss.8mdDE8.QiZZ1uCmh6mBhEWGPKfDq82W', NULL, 'admin_sekretariat_hrd@gmail.com', 1, 1, 1413843677, 1413843677),
(24, 'admin_sekretariat_finance', '', '$2y$13$bvGxy1TfI2HFwZs4vYVI2eqVxiOuS7TZEub/KvArQXmMriQA0FQnK', NULL, 'admin_sekretariat_finance@gmail.com', 1, 1, 1413843775, 1413843775),
(25, 'admin_sekretariat_it', '', '$2y$13$X8nh/TXpBMUhmRfB7Hg/uOPjh63mm8hgnlyBOWe8vRSjLHPIVBd2K', NULL, 'admin_sekretariat_it@gmail.com', 1, 1, 1413843832, 1413843832),
(26, 'admin_sekretariat_general', '', '$2y$13$0RWmtv7pTfpX0g88C8Tm6umWLsHAw8OiPTRCW8IvW0b7OikOfKyES', NULL, 'admin_sekretariat_general@gmail.com', 1, 1, 1413843871, 1413843871),
(101, '198604302009011002', '', '$2y$13$TBX6tJ9xfO7981OgRCRIxeiTzyyc5iSFiTh3Y2Uu8Kb1VOjuWKQci', NULL, '198604302009011002@gmail.com', 1, 1, 1413852500, 1413869879);

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
-- Constraints for table `role`
--
ALTER TABLE `role`
  ADD CONSTRAINT `role_ibfk_1` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `training_schedule_ibfk_1` FOREIGN KEY (`training_class_id`) REFERENCES `training_class` (`id`) ON UPDATE CASCADE;

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
