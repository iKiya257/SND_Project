-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2025 at 06:18 PM
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
-- Database: `snddb`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(1, 'กลุ่มการพยาบาลกุมารเวชศาสตร์'),
(2, 'การพยาบาลจิตเวช'),
(3, 'การพยาบาลพื้นฐาน'),
(4, 'การพยาบาลศัลยศาสตร์'),
(5, 'การพยาบาลสาธารณสุข'),
(6, 'การพยาบาลสูติศาสตร์และนรีเวชวิทยา'),
(7, 'การพยาบาลอายุรศาสตร์'),
(8, 'บริหารการพยาบาล'),
(19, 'การทดสอบ');

-- --------------------------------------------------------

--
-- Table structure for table `document_files`
--

CREATE TABLE `document_files` (
  `file_id` int(11) NOT NULL,
  `submission_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_files`
--

INSERT INTO `document_files` (`file_id`, `submission_id`, `file_path`, `created_at`) VALUES
(27, 65, '../../uploads/1741103827_W4.pdf', '2025-03-04 15:57:07'),
(28, 66, '../../uploads/1741103832_W4.pdf', '2025-03-04 15:57:12'),
(29, 67, '../../uploads/1741104027_หัวข้อ6 1 (1).docx', '2025-03-04 16:00:27'),
(30, 68, '../../uploads/1741106177_Image.png', '2025-03-04 16:36:17'),
(31, 69, '../../uploads/1741106664_file_67bf5b2a0dffd5.95140044.docx', '2025-03-04 16:44:24'),
(32, 69, '../../uploads/1741106664_file_67befe1b1e7389.88669371.docx', '2025-03-04 16:44:24'),
(33, 69, '../../uploads/1741106664_Image (1).png', '2025-03-04 16:44:24'),
(34, 69, '../../uploads/1741106664_Image.png', '2025-03-04 16:44:24'),
(35, 70, '../../uploads/1741110489_createfolder (2).png', '2025-03-04 17:48:09'),
(36, 71, '../../uploads/1741253371_สรุปการประชุมกับอาจารย์ที่ปรึกษา10.01.68.docx', '2025-03-06 09:29:31'),
(37, 71, '../../uploads/1741253371_SND_ERD.jpg', '2025-03-06 09:29:31'),
(38, 72, '../../uploads/1741280145_แบบฟอร์มรายงานการประชุม (1).doc', '2025-03-06 16:55:45'),
(39, 72, '../../uploads/1741280145_File-staff.png', '2025-03-06 16:55:45'),
(40, 73, '../../uploads/1741585033_แนวการเขียนรายงานวิชา วซ.492 สัมมนาวิศวกรรมซอฟต์แวร์.docx', '2025-03-10 05:37:13'),
(41, 73, '../../uploads/1741585033_Image.png', '2025-03-10 05:37:13'),
(42, 73, '../../uploads/1741585033_analyst.png', '2025-03-10 05:37:13'),
(43, 74, '../../uploads/1741696332_analyst-admin (2).png', '2025-03-11 12:32:12'),
(44, 74, '../../uploads/1741696332_Dashbroad-admin (1).png', '2025-03-11 12:32:12'),
(45, 75, '../../uploads/1741700066_draft.png', '2025-03-11 13:34:26'),
(46, 75, '../../uploads/1741700066_send2 (2).png', '2025-03-11 13:34:26'),
(47, 75, '../../uploads/1741700066_send (2).png', '2025-03-11 13:34:26'),
(48, 76, '../../uploads/แนวการเขียนรายงานวิชา วซ.492 สัมมนาวิศวกรรมซอฟต์แวร์.docx', '2025-03-11 18:48:09'),
(49, 77, '../../uploads/analyst-admin (2).png', '2025-03-11 20:07:24'),
(50, 78, '../../uploads/analyst-admin (2) (1).png', '2025-03-11 20:08:06'),
(51, 79, '../../uploads/Dashbroad-lecturer.png', '2025-03-12 15:24:12'),
(52, 79, '../../uploads/Dashbroad-staff.png', '2025-03-12 15:24:12'),
(53, 79, '../../uploads/word (1).png', '2025-03-12 15:24:12'),
(54, 80, '../../uploads/Image.png', '2025-03-12 15:26:02'),
(55, 81, '../../uploads/fastapi-vs-flask-summary-bluebird.png', '2025-03-13 10:47:51'),
(56, 81, '../../uploads/nongbot.jpg', '2025-03-13 10:47:51'),
(57, 82, '../../uploads/1.png', '2025-03-13 11:40:14'),
(58, 83, '../../uploads/fastapi-vs-flask-summary-bluebird (1).png', '2025-03-13 11:47:54'),
(59, 84, '../../uploads/fastapi-vs-flask-summary-bluebird (2).png', '2025-03-13 12:34:18'),
(60, 85, '../../uploads/สรุปการประชุมกับอาจารย์ที่ปรึกษา10.01.68.docx', '2025-03-13 12:49:57'),
(61, 86, '../../uploads/Frame 3.png', '2025-03-13 13:44:29'),
(62, 86, '../../uploads/loudspeaker.png', '2025-03-13 13:44:29'),
(63, 87, '../../uploads/img-bg.jpg', '2025-03-13 13:55:27'),
(64, 88, '../../uploads/แนวการเขียนรายงานวิชา วซ.492 สัมมนาวิศวกรรมซอฟต์แวร์ (1).docx', '2025-03-13 16:10:28'),
(65, 89, '../../uploads/img-bg (1).jpg', '2025-03-13 16:14:37');

-- --------------------------------------------------------

--
-- Table structure for table `document_history`
--

CREATE TABLE `document_history` (
  `history_id` int(11) NOT NULL,
  `submission_id` int(11) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `action_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_recipient`
--

CREATE TABLE `document_recipient` (
  `recipient_id` int(11) NOT NULL,
  `submission_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','read','removed','revision','completed') DEFAULT 'pending',
  `revision_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_recipient`
--

INSERT INTO `document_recipient` (`recipient_id`, `submission_id`, `receiver_id`, `department_id`, `created_at`, `status`, `revision_reason`) VALUES
(1, 4, 2, NULL, '2025-01-28 22:36:09', 'pending', NULL),
(2, 4, 1, NULL, '2025-01-28 22:36:26', 'pending', NULL),
(3, 6, 2, NULL, '2025-01-29 09:18:29', 'pending', NULL),
(4, 7, 2, NULL, '2025-02-04 10:55:58', 'pending', NULL),
(5, 11, 1, 1, '2025-03-01 12:10:00', 'pending', NULL),
(6, 13, 1, 1, '2025-03-01 12:11:26', 'pending', NULL),
(7, 15, 1, 1, '2025-03-01 12:11:41', 'pending', NULL),
(19, 39, 17, NULL, '2025-03-01 14:41:10', 'pending', NULL),
(20, 39, 13, NULL, '2025-03-01 14:41:10', 'pending', NULL),
(21, 40, 10, NULL, '2025-03-01 14:43:46', 'pending', NULL),
(22, 40, 11, NULL, '2025-03-01 14:43:46', 'pending', NULL),
(24, 43, 17, NULL, '2025-03-01 15:07:00', 'read', NULL),
(25, 44, 16, NULL, '2025-03-01 16:07:03', 'pending', NULL),
(26, 44, 20, NULL, '2025-03-01 16:07:03', 'pending', NULL),
(27, 45, 13, NULL, '2025-03-01 16:35:40', 'pending', NULL),
(28, 45, 11, NULL, '2025-03-01 16:35:40', 'revision', NULL),
(29, 48, 16, NULL, '2025-03-01 17:10:15', 'pending', NULL),
(30, 49, 11, NULL, '2025-03-01 17:13:52', 'revision', NULL),
(31, 49, 10, NULL, '2025-03-01 17:13:52', 'read', NULL),
(32, 49, NULL, 4, '2025-03-01 17:13:52', 'pending', NULL),
(33, 50, NULL, 8, '2025-03-01 18:08:24', 'pending', NULL),
(34, 51, 15, NULL, '2025-03-01 18:36:30', 'pending', NULL),
(35, 51, NULL, 4, '2025-03-01 18:36:30', 'pending', NULL),
(36, 52, 16, NULL, '2025-03-02 10:45:54', 'read', NULL),
(37, 52, NULL, 4, '2025-03-02 10:45:54', 'pending', NULL),
(38, 53, 13, NULL, '2025-03-02 18:22:48', 'read', NULL),
(39, 53, NULL, 8, '2025-03-02 18:22:48', 'pending', NULL),
(40, 54, 13, NULL, '2025-03-04 09:35:51', 'revision', 'ทดสอบ'),
(41, 54, 15, NULL, '2025-03-04 09:35:51', 'pending', NULL),
(42, 54, NULL, 3, '2025-03-04 09:35:51', 'pending', NULL),
(43, 55, NULL, 7, '2025-03-04 09:38:18', 'pending', NULL),
(44, 56, 21, NULL, '2025-03-04 15:44:01', 'pending', NULL),
(45, 57, 21, NULL, '2025-03-04 15:56:12', 'pending', NULL),
(46, 58, 21, NULL, '2025-03-04 15:56:18', 'pending', NULL),
(47, 59, 21, NULL, '2025-03-04 15:56:24', 'pending', NULL),
(48, 60, 21, NULL, '2025-03-04 15:56:29', 'pending', NULL),
(49, 61, 21, NULL, '2025-03-04 15:56:35', 'pending', NULL),
(50, 62, 21, NULL, '2025-03-04 15:56:43', 'pending', NULL),
(51, 63, 21, NULL, '2025-03-04 15:56:48', 'pending', NULL),
(52, 64, 21, NULL, '2025-03-04 15:56:55', 'pending', NULL),
(53, 65, 21, NULL, '2025-03-04 15:57:01', 'pending', NULL),
(54, 66, 21, NULL, '2025-03-04 15:57:07', 'pending', NULL),
(55, 67, 21, NULL, '2025-03-04 16:00:20', 'pending', NULL),
(56, 68, 21, NULL, '2025-03-04 16:36:13', 'pending', NULL),
(57, 69, 11, NULL, '2025-03-04 16:44:20', 'completed', NULL),
(58, 69, NULL, 6, '2025-03-04 16:44:24', 'pending', NULL),
(59, 70, 20, NULL, '2025-03-04 17:48:05', 'revision', 'ทดสอบการส่งคืนเอกสาร'),
(60, 71, 20, NULL, '2025-03-06 09:29:20', 'pending', NULL),
(61, 71, 13, NULL, '2025-03-06 09:29:26', 'revision', 'ทดสอบส่งกลับ'),
(62, 71, NULL, 4, '2025-03-06 09:29:31', 'pending', NULL),
(63, 71, NULL, 7, '2025-03-06 09:29:31', 'pending', NULL),
(64, 72, 13, NULL, '2025-03-06 16:55:37', 'revision', 'ต้องการเอกสารแนบ'),
(65, 72, 15, NULL, '2025-03-06 16:55:41', 'completed', NULL),
(66, 72, NULL, 1, '2025-03-06 16:55:45', 'pending', NULL),
(67, 73, 13, NULL, '2025-03-10 05:37:04', 'revision', 'เอกสารตีกลับ'),
(68, 73, 15, NULL, '2025-03-10 05:37:10', 'pending', NULL),
(69, 74, NULL, 1, '2025-03-11 12:32:12', 'pending', NULL),
(70, 75, 11, NULL, '2025-03-11 13:34:16', 'read', NULL),
(71, 75, 14, NULL, '2025-03-11 13:34:21', 'read', NULL),
(72, 75, NULL, 1, '2025-03-11 13:34:26', 'revision', 'ส่งกลับแบบกลุ่ม'),
(73, 76, 16, NULL, '2025-03-11 18:48:05', 'read', NULL),
(74, 77, 20, NULL, '2025-03-11 20:07:20', 'pending', NULL),
(75, 78, NULL, 3, '2025-03-11 20:08:06', 'pending', NULL),
(76, 79, 15, NULL, '2025-03-12 15:24:04', 'pending', NULL),
(77, 79, 20, NULL, '2025-03-12 15:24:08', 'pending', NULL),
(78, 80, 16, NULL, '2025-03-12 15:25:59', 'completed', NULL),
(79, 81, 15, NULL, '2025-03-13 10:47:47', 'completed', NULL),
(80, 81, NULL, 1, '2025-03-13 10:47:51', 'pending', NULL),
(81, 82, 14, 5, '2025-03-13 11:40:10', 'completed', 'ปุ่มต้องขึ้นทราบปุ่มเดียว'),
(82, 83, NULL, 1, '2025-03-13 11:47:54', 'completed', NULL),
(83, 84, NULL, 1, '2025-03-13 12:34:18', 'pending', NULL),
(84, 85, 15, NULL, '2025-03-13 12:49:53', 'read', NULL),
(85, 86, 22, NULL, '2025-03-13 13:44:21', 'pending', NULL),
(86, 86, 17, 8, '2025-03-13 13:44:25', 'revision', 'ทดสอบส่งกลับ'),
(87, 87, 12, NULL, '2025-03-13 13:55:19', 'revision', 'ส่งกลับ'),
(88, 87, 16, NULL, '2025-03-13 13:55:23', 'completed', NULL),
(89, 87, NULL, 1, '2025-03-13 13:55:27', 'completed', NULL),
(90, 88, 13, NULL, '2025-03-13 16:10:23', 'completed', NULL),
(91, 89, NULL, 1, '2025-03-13 16:14:37', 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `document_revisions`
--

CREATE TABLE `document_revisions` (
  `revision_id` int(11) NOT NULL,
  `submission_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `revised_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_revisions`
--

INSERT INTO `document_revisions` (`revision_id`, `submission_id`, `recipient_id`, `revised_at`) VALUES
(1, 70, 59, '2025-03-06 13:38:39'),
(2, 72, 64, '2025-03-06 17:29:01'),
(3, 54, 40, '2025-03-09 10:01:55'),
(4, 71, 61, '2025-03-10 05:05:18'),
(5, 73, 67, '2025-03-10 05:50:56'),
(6, 75, 72, '2025-03-11 13:37:55'),
(7, 82, 81, '2025-03-13 11:43:00'),
(8, 86, 86, '2025-03-13 13:45:15'),
(9, 87, 87, '2025-03-13 13:56:22');

-- --------------------------------------------------------

--
-- Table structure for table `document_submission`
--

CREATE TABLE `document_submission` (
  `submission_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `purpose_id` int(11) DEFAULT NULL,
  `document_code` varchar(50) NOT NULL,
  `status` enum('submitted','revision','cancel') DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `urgency` tinyint(1) NOT NULL DEFAULT 0,
  `previous_refcode` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_submission`
--

INSERT INTO `document_submission` (`submission_id`, `sender_id`, `purpose_id`, `document_code`, `status`, `name`, `remark`, `urgency`, `previous_refcode`, `created_at`, `updated_at`) VALUES
(4, 3, 2, '', 'submitted', 'ทดสอบ', '', 0, NULL, '2025-01-28 22:36:09', '2025-01-28 22:36:09'),
(5, 3, 1, '', 'submitted', 'ทดสอบ', '', 0, NULL, '2025-01-28 22:36:26', '2025-01-28 22:36:26'),
(6, 3, 1, '', 'submitted', 'test', '', 0, NULL, '2025-01-29 09:18:28', '2025-01-29 09:18:28'),
(7, 3, 2, '', 'submitted', 'ทดสอบบบบ', '', 0, NULL, '2025-02-04 10:55:57', '2025-02-04 10:55:57'),
(10, 3, NULL, '20250301-001', 'submitted', 'ทดสอบ', '', 0, NULL, '2025-03-01 12:10:00', '2025-03-01 12:10:00'),
(11, 3, NULL, '20250301-001', 'submitted', 'ทดสอบ', '', 0, NULL, '2025-03-01 12:10:00', '2025-03-01 12:10:00'),
(12, 3, NULL, '20250301-003', 'submitted', 'ทดสอบ', '', 0, NULL, '2025-03-01 12:11:26', '2025-03-01 12:11:26'),
(13, 3, NULL, '20250301-003', 'submitted', 'ทดสอบ', '', 0, NULL, '2025-03-01 12:11:26', '2025-03-01 12:11:26'),
(14, 3, NULL, '20250301-005', 'submitted', 'ทดสอบ', '', 0, NULL, '2025-03-01 12:11:41', '2025-03-01 12:11:41'),
(15, 3, NULL, '20250301-005', 'submitted', 'ทดสอบ', '', 0, NULL, '2025-03-01 12:11:41', '2025-03-01 12:11:41'),
(22, 3, NULL, '20250301-007', 'submitted', 'ทดสอบ', '', 0, NULL, '2025-03-01 12:21:04', '2025-03-01 12:21:04'),
(37, 3, 2, '20250301-008', NULL, 'ทดสอบ', '', 0, NULL, '2025-03-01 14:35:10', '2025-03-01 14:35:10'),
(38, 3, 2, '20250301-009', NULL, 'ทดสอบ', '', 0, NULL, '2025-03-01 14:40:25', '2025-03-01 14:40:25'),
(39, 3, 2, '20250301-010', NULL, 'ทดสอบ', '', 0, NULL, '2025-03-01 14:41:10', '2025-03-01 14:41:10'),
(40, 3, 1, '20250301-011', NULL, 'Chatbot-BMI', 'hellooo', 0, NULL, '2025-03-01 14:43:45', '2025-03-01 14:43:45'),
(43, 3, 2, '20250301-012', 'submitted', 'ทดสอบ', '', 0, NULL, '2025-03-01 15:06:59', '2025-03-01 15:06:59'),
(44, 3, 2, '20250301-013', 'submitted', '', '', 0, NULL, '2025-03-01 16:07:03', '2025-03-01 16:07:03'),
(45, 3, 1, '20250301-014', 'submitted', 'ทดสอบ', '', 0, NULL, '2025-03-01 16:35:40', '2025-03-01 16:35:40'),
(48, 3, 4, '20250301-015', 'submitted', 'ทดสอบ', '', 0, NULL, '2025-03-01 17:10:15', '2025-03-01 17:10:15'),
(49, 3, 2, '20250301-016', 'submitted', 'thames', 'hi', 0, NULL, '2025-03-01 17:13:52', '2025-03-01 17:13:52'),
(50, 3, 3, '20250301-017', 'submitted', '', '', 0, NULL, '2025-03-01 18:08:24', '2025-03-01 18:08:24'),
(51, 3, 1, '20250301-018', 'submitted', 'ทดสอบ', '', 0, NULL, '2025-03-01 18:36:30', '2025-03-01 18:36:30'),
(52, 3, 1, '20250302-001', 'submitted', '', 'hello', 0, NULL, '2025-03-02 10:45:54', '2025-03-02 10:45:54'),
(53, 3, 1, '20250302-002', 'submitted', 'ขอเบิกเงินค่าอาหารว่าง', '', 1, NULL, '2025-03-02 18:22:48', '2025-03-02 18:22:48'),
(54, 3, 1, '20250304-001', 'revision', 'ขออนุมัติเปิดระบบลงเวลาราชการ', 'ต้องการเอกสารอ้างอิง', 1, NULL, '2025-03-04 09:35:51', '2025-03-09 10:01:55'),
(55, 3, 3, '20250304-002', 'submitted', 'ขออนุมัติเปิดห้องการสอน', '', 0, NULL, '2025-03-04 09:38:18', '2025-03-04 09:38:18'),
(56, 3, 3, '20250304-003', 'submitted', 'eeeccccxxx', '', 0, NULL, '2025-03-04 15:44:01', '2025-03-04 15:44:01'),
(57, 3, 3, '20250304-004', 'submitted', 'Noyt', '', 0, NULL, '2025-03-04 15:56:12', '2025-03-04 15:56:12'),
(58, 3, 3, '20250304-005', 'submitted', 'Noyt', '', 0, NULL, '2025-03-04 15:56:18', '2025-03-04 15:56:18'),
(59, 3, 3, '20250304-006', 'submitted', 'Noyt', '', 0, NULL, '2025-03-04 15:56:24', '2025-03-04 15:56:24'),
(60, 3, 3, '20250304-007', 'submitted', 'Noyt', '', 0, NULL, '2025-03-04 15:56:29', '2025-03-04 15:56:29'),
(61, 3, 3, '20250304-008', 'submitted', 'Noyt', '', 0, NULL, '2025-03-04 15:56:35', '2025-03-04 15:56:35'),
(62, 3, 3, '20250304-009', 'submitted', 'Noyt', '', 0, NULL, '2025-03-04 15:56:43', '2025-03-04 15:56:43'),
(63, 3, 3, '20250304-010', 'submitted', 'Noyt', '', 0, NULL, '2025-03-04 15:56:48', '2025-03-04 15:56:48'),
(64, 3, 3, '20250304-011', 'submitted', 'Noyt', '', 0, NULL, '2025-03-04 15:56:55', '2025-03-04 15:56:55'),
(65, 3, 3, '20250304-012', 'submitted', 'Noyt', '', 0, NULL, '2025-03-04 15:57:01', '2025-03-04 15:57:01'),
(66, 3, 3, '20250304-013', 'submitted', 'Noyt', '', 0, NULL, '2025-03-04 15:57:07', '2025-03-04 15:57:07'),
(67, 3, 1, '20250304-014', 'submitted', 'Noyrer', '', 0, NULL, '2025-03-04 16:00:20', '2025-03-04 16:00:20'),
(68, 16, 2, '20250304-015', 'submitted', 'ทดสอบ', '', 1, NULL, '2025-03-04 16:36:13', '2025-03-04 16:36:13'),
(69, 16, 1, '20250304-016', 'submitted', '', '', 0, NULL, '2025-03-04 16:44:20', '2025-03-04 16:44:20'),
(70, 11, 2, '20250304-017', 'revision', 'thames', '', 1, NULL, '2025-03-04 17:48:05', '2025-03-06 13:38:39'),
(71, 11, 1, '20250306-001', 'submitted', 'การทดสอบการส่ง', '', 1, NULL, '2025-03-06 09:29:20', '2025-03-13 10:43:23'),
(72, 12, 4, '20250306-002', 'revision', 'ขออนุมัติเปิดห้องการสอน ', '', 1, NULL, '2025-03-06 16:55:37', '2025-03-06 17:29:01'),
(73, 11, 2, '20250310-001', 'submitted', 'ทดสอบส่งกลับเอกสาร', '', 0, NULL, '2025-03-10 05:37:04', '2025-03-10 05:37:04'),
(74, 13, 4, '20250311-001', 'submitted', 'ส่งเอกสารให้กลุ่มวิชาการพยาบาลกุมารเวชศาสตร์', '', 1, NULL, '2025-03-11 12:32:12', '2025-03-11 12:32:12'),
(75, 13, 2, '20250311-002', 'submitted', 'ทดสอบ3', '', 0, NULL, '2025-03-11 13:34:16', '2025-03-11 13:34:16'),
(76, 22, 4, '20250311-003', 'submitted', 'ทดสอบชื่อไฟล์', '', 0, NULL, '2025-03-11 18:48:05', '2025-03-11 18:48:05'),
(77, 11, 2, '20250311-004', 'submitted', 'irine', '', 0, NULL, '2025-03-11 20:07:19', '2025-03-11 20:07:19'),
(78, 11, 3, '20250311-005', 'submitted', 'ทดสอบแจ้งเตือนแบบกลุ่ม', '', 0, NULL, '2025-03-11 20:08:06', '2025-03-11 20:08:06'),
(79, 14, 3, '20250312-001', 'submitted', 'ทดสอบส่งรหัสอ้างอิงก่อนหน้า', '', 0, '1', '2025-03-12 15:24:04', '2025-03-12 15:24:04'),
(80, 14, 1, '20250312-002', 'submitted', 'ทดสอบอีกรอบ', '', 0, '20250101-038', '2025-03-12 15:25:59', '2025-03-12 15:25:59'),
(81, 11, 2, '20250313-001', 'submitted', 'ทดสอบปุ่มสถานะ', '', 0, '', '2025-03-13 10:47:47', '2025-03-13 10:47:47'),
(82, 11, 3, '20250313-002', 'submitted', 'ทดสอบทราบเอกสารเมื่อส่งกลับ', '', 0, '', '2025-03-13 11:40:10', '2025-03-13 11:40:10'),
(83, 14, 4, '20250313-003', 'submitted', 'ทดสอบส่งแบบกลุ่ม', '', 0, '', '2025-03-13 11:47:54', '2025-03-13 11:47:54'),
(84, 13, 3, '20250313-004', 'submitted', 'ทดสอบส่งแบบกลุ่มและส่งกลับ', '', 0, '', '2025-03-13 12:34:18', '2025-03-13 12:34:18'),
(85, 12, 3, '20250313-005', 'submitted', 'ทดสอบปุ่มส่งคืนและปุ่มทราบ', '', 0, '', '2025-03-13 12:49:53', '2025-03-13 12:49:53'),
(86, 14, 2, '20250313-006', 'submitted', 'ทดสอบปุ่ม', '', 0, '', '2025-03-13 13:44:21', '2025-03-13 13:44:21'),
(87, 14, 2, '20250313-007', 'submitted', 'ทดสอบส่งกลับเอกสาร ครั้งที่ 2', '', 0, '', '2025-03-13 13:55:19', '2025-03-13 13:55:19'),
(88, 14, 1, '20250313-008', 'submitted', 'ส่งแบบบุคคล', '', 0, '', '2025-03-13 16:10:23', '2025-03-13 16:10:23'),
(89, 13, 2, '20250313-009', 'submitted', 'ส่งแบบกลุ่ม', '', 0, '', '2025-03-13 16:14:37', '2025-03-13 16:14:37');

-- --------------------------------------------------------

--
-- Table structure for table `download_logs`
--

CREATE TABLE `download_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `download_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `download_logs`
--

INSERT INTO `download_logs` (`id`, `user_id`, `template_id`, `download_time`) VALUES
(1, 13, 20, '2025-03-11 16:50:21'),
(2, 13, 20, '2025-03-11 16:56:24'),
(3, 13, 20, '2025-03-11 17:01:54'),
(4, 22, 21, '2025-03-11 17:02:46'),
(5, 22, 21, '2025-03-11 17:05:52'),
(6, 22, 21, '2025-03-11 17:26:33'),
(7, 22, 21, '2025-03-11 17:49:30'),
(8, 22, 21, '2025-03-11 17:49:52'),
(9, 22, 21, '2025-03-11 17:51:08'),
(10, 22, 20, '2025-03-11 17:59:18');

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `folder_id` int(11) NOT NULL,
  `folder_name` varchar(255) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`folder_id`, `folder_name`, `created_by`, `created_at`, `updated_at`) VALUES
(4, 'เอกสาร 1', 3, '2025-01-20 18:56:04', '2025-01-20 18:56:04'),
(5, 'เอกสาร 2', 3, '2025-01-22 17:12:02', '2025-01-22 17:12:02'),
(6, 'เอกสาร 3', 3, '2025-01-22 19:23:11', '2025-01-22 19:23:11'),
(10, 'เอกสารทดสอบ', 3, '2025-03-02 07:35:58', '2025-03-02 07:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `submission_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`, `submission_id`) VALUES
(1, 24, 'เอกสาร \'ggg\' ถูกส่งมาจาก Kiya Test ถึง Kiya Test เมื่อ 02/03/2025 18:55:47', 0, '2025-03-02 17:55:47', NULL),
(2, 24, 'เอกสาร \'test\' ถูกส่งมาจาก staff user ถึง Kiya Test เมื่อ 03/03/2025 09:42:52', 0, '2025-03-03 08:42:52', NULL),
(3, 24, 'เอกสาร \'mfghosst\' ถูกส่งมาจาก staff user ถึง Kiya Test เมื่อ 04/03/2025 09:43:43', 0, '2025-03-04 08:43:43', NULL),
(4, 24, 'เอกสาร \'mfghosst\' ถูกส่งมาจาก staff user ถึง Kiya Test เมื่อ 04/03/2025 09:43:48', 0, '2025-03-04 08:43:48', NULL),
(5, 21, 'เอกสาร \'GDTest\' ถูกส่งมาจาก staff user ถึง ภาณุพงศ์ อาษากิจ เมื่อ 04/03/2025 15:58:28', 0, '2025-03-04 14:58:28', NULL),
(6, 21, 'เอกสาร \'Testkkk\' ถูกส่งมาจาก staff user ถึง ภาณุพงศ์ อาษากิจ เมื่อ 04/03/2025 16:22:32', 0, '2025-03-04 15:22:32', NULL),
(7, 21, 'เอกสาร \'eeeeeeee\' ถูกส่งมาจาก staff user ถึง ภาณุพงศ์ อาษากิจ เมื่อ 04/03/2025 16:24:37', 0, '2025-03-04 15:24:37', NULL),
(8, 21, 'เอกสาร \'Test234\' ถูกส่งมาจาก staff user ถึง ภาณุพงศ์ อาษากิจ เมื่อ 04/03/2025 16:36:12', 0, '2025-03-04 15:36:12', NULL),
(9, 21, 'คุณได้รับเอกสารใหม่: Noyt จาก staff user', 0, '2025-03-04 15:56:12', NULL),
(10, 21, 'คุณได้รับเอกสารใหม่: Noyt จาก staff user', 0, '2025-03-04 15:56:18', NULL),
(11, 21, 'คุณได้รับเอกสารใหม่: Noyt จาก staff user', 0, '2025-03-04 15:56:24', NULL),
(12, 21, 'คุณได้รับเอกสารใหม่: Noyt จาก staff user', 0, '2025-03-04 15:56:29', NULL),
(13, 21, 'คุณได้รับเอกสารใหม่: Noyt จาก staff user', 0, '2025-03-04 15:56:35', NULL),
(14, 21, 'คุณได้รับเอกสารใหม่: Noyt จาก staff user', 0, '2025-03-04 15:56:43', NULL),
(15, 21, 'คุณได้รับเอกสารใหม่: Noyt จาก staff user', 0, '2025-03-04 15:56:48', NULL),
(16, 21, 'คุณได้รับเอกสารใหม่: Noyt จาก staff user', 0, '2025-03-04 15:56:55', NULL),
(17, 21, 'คุณได้รับเอกสารใหม่: Noyt จาก staff user', 0, '2025-03-04 15:57:01', NULL),
(18, 21, 'คุณได้รับเอกสารใหม่: Noyt จาก staff user', 0, '2025-03-04 15:57:07', NULL),
(19, 21, 'คุณได้รับเอกสารใหม่: Noyrer จาก staff user', 0, '2025-03-04 16:00:20', NULL),
(20, 21, 'คุณได้รับเอกสารใหม่: ทดสอบ จาก user7 test', 0, '2025-03-04 16:36:13', NULL),
(21, 11, 'คุณได้รับเอกสารใหม่:  จาก user7 test', 0, '2025-03-04 16:44:20', NULL),
(22, 20, 'คุณได้รับเอกสารใหม่: thames จาก user2 test', 0, '2025-03-04 17:48:05', NULL),
(23, 20, 'คุณได้รับเอกสารใหม่: การทดสอบการส่ง จาก user2 test', 0, '2025-03-06 09:29:20', NULL),
(24, 13, 'คุณได้รับเอกสารใหม่: การทดสอบการส่ง จาก user2 test', 0, '2025-03-06 09:29:26', NULL),
(25, 13, 'คุณได้รับเอกสารใหม่: ขออนุมัติเปิดห้องการสอน  จาก user3 test', 0, '2025-03-06 16:55:37', NULL),
(26, 15, 'คุณได้รับเอกสารใหม่: ขออนุมัติเปิดห้องการสอน  จาก user3 test', 0, '2025-03-06 16:55:41', NULL),
(27, 13, 'คุณได้รับเอกสารใหม่: ทดสอบส่งกลับเอกสาร จาก user2 test', 0, '2025-03-10 05:37:04', NULL),
(28, 15, 'คุณได้รับเอกสารใหม่: ทดสอบส่งกลับเอกสาร จาก user2 test', 0, '2025-03-10 05:37:10', NULL),
(29, 11, 'คุณได้รับเอกสารใหม่: ทดสอบ3 จาก user4 test', 0, '2025-03-11 13:34:16', NULL),
(30, 14, 'คุณได้รับเอกสารใหม่: ทดสอบ3 จาก user4 test', 0, '2025-03-11 13:34:21', NULL),
(31, 3, 'เอกสาร Noyt ถูกส่งกลับจาก ภาณุพงศ์ อาษากิจ เนื่องจาก: เอผ', 0, '2025-03-11 14:33:19', NULL),
(32, 21, 'คุณได้รับเอกสารใหม่: กกกผผ จาก staff2 user', 0, '2025-03-11 14:36:36', NULL),
(33, 24, 'คุณได้รับเอกสารใหม่: Testnf จาก Test P3', 0, '2025-03-11 14:41:05', NULL),
(34, 23, 'เอกสาร Testnf ถูกส่งกลับจาก Test P4 เนื่องจาก: โอเค', 0, '2025-03-11 14:41:53', NULL),
(35, 24, 'คุณได้รับเอกสารใหม่: กกกผผผผะะะะ จาก Test P3', 0, '2025-03-11 15:05:57', NULL),
(36, 24, 'คุณได้รับเอกสารใหม่: กรอบ จาก Test P3', 0, '2025-03-11 16:29:13', NULL),
(37, 24, 'คุณได้รับเอกสารใหม่: นุ่มจัด จาก Test P3', 0, '2025-03-11 16:36:05', NULL),
(38, 23, 'เอกสาร นุ่มจัด ถูกส่งกลับจาก Test P4 เนื่องจาก: ', 0, '2025-03-11 16:36:45', NULL),
(39, 23, 'เอกสาร นุ่มจัด ถูกส่งกลับจาก Test P4 เนื่องจาก: ', 0, '2025-03-11 16:36:51', NULL),
(40, 24, 'คุณได้รับเอกสารใหม่: ดูนุ่มมากกก จาก Test P3', 0, '2025-03-11 19:50:18', 81),
(41, 17, 'คุณได้รับเอกสารใหม่: ปปปปผผ จาก Test P4', 0, '2025-03-13 14:04:47', 85),
(42, 21, 'คุณได้รับเอกสารใหม่: ปปปปผผ จาก Test P4', 0, '2025-03-13 14:04:53', 85),
(43, 23, 'คุณได้รับเอกสารใหม่: ปปปปผผ จาก Test P4', 0, '2025-03-13 14:04:57', 85),
(44, 23, 'คุณได้รับเอกสารใหม่: vvvzzz จาก Test P4', 0, '2025-03-13 14:11:04', 86),
(45, 21, 'คุณได้รับเอกสารใหม่: mmm จาก Test P4', 0, '2025-03-13 14:12:09', 87);

-- --------------------------------------------------------

--
-- Table structure for table `purposes`
--

CREATE TABLE `purposes` (
  `purpose_id` int(11) NOT NULL,
  `purpose_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purposes`
--

INSERT INTO `purposes` (`purpose_id`, `purpose_name`) VALUES
(1, 'เพื่อทราบ'),
(2, 'เพื่อพิจารณา'),
(3, 'เพื่ออนุมัติ'),
(4, 'เพื่อดำเนินการต่อ');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `template_id` int(11) NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `folder_id` int(11) DEFAULT NULL,
  `download_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`template_id`, `template_name`, `file_path`, `uploaded_by`, `created_at`, `folder_id`, `download_count`) VALUES
(11, 'แบบฟอร์มรายงานการประชุม (3).doc', 'doc_6797aaf1940666.73413901.doc', 3, '2025-01-27 15:49:05', 6, 0),
(12, 'แบบฟอร์ม 2', 'file_6797b32d92ca25.54951291.docx', 3, '2025-01-27 16:24:13', 5, 0),
(13, 'แบบฟอร์ม 3', 'file_6797bd83ce02c7.78034141.docx', 3, '2025-01-27 17:08:19', 5, 0),
(14, 'แบบฟอร์ม 4', 'file_6799608ac60fc9.44127539.docx', 3, '2025-01-28 22:56:10', NULL, 0),
(15, 'แบบฟอร์ม 5', 'file_67996c7e574839.56760356.doc', 3, '2025-01-28 23:47:10', 4, 0),
(16, 'test', 'file_67befd78af8452.18270287.docx', 3, '2025-02-26 11:39:36', 6, 0),
(17, 'แบบฟอร์ม 3', 'file_67befdefbec9c1.41520424.docx', 3, '2025-02-26 11:41:35', 6, 0),
(18, 'testtt', 'file_67befe1b1e7389.88669371.docx', 3, '2025-02-26 11:42:19', 5, 0),
(19, 'test', 'file_67bf5b2a0dffd5.95140044.docx', 3, '2025-02-26 18:19:22', 4, 1),
(20, 'ทดสอบดาวน์โหลด', 'file_67d03e731d8f75.93869158.docx', 22, '2025-03-11 13:45:23', 10, 0),
(21, 'แบบฟอร์มมมมมม', 'file_67d06cb10f6567.26156920.docx', 22, '2025-03-11 17:02:41', 10, 2),
(22, 'ทดสอบดาวน์โหลด', 'file_67d0747d7c9873.41299513.docx', 22, '2025-03-11 17:35:57', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `prefix` varchar(50) NOT NULL,
  `upassword` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('staff','lecturer','admin') NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `prefix`, `upassword`, `firstname`, `lastname`, `position`, `email`, `role`, `status`, `created_at`) VALUES
(1, '', '123', 'admin', 'user', '', 'admin@example.com', 'admin', '1', '2025-01-18 14:32:39'),
(2, '', '123', 'lecturer', 'user', '', 'lecturer@example.com', 'lecturer', '1', '2025-01-18 14:32:39'),
(3, '', '123', 'staff', 'user', '', 'staff@example.com', 'staff', '1', '2025-01-18 14:32:39'),
(10, '', '$2y$10$3rZyzyBiDweGCUsg4PW6oOtXk76jAGYfyeLF5Ak47YtM6ftqS7zbG', 'user1', 'dep1', '', 'dep1@example.com', 'lecturer', '1', '2025-02-22 19:31:47'),
(11, '', '$2y$10$3fgHKzDC0GF9FsA7CBk5UOG5/.w0SKnaEfZ3ibr5zY47okuZp8wJW', 'user2', 'test', '', 'dep2@example.com', 'lecturer', '1', '2025-02-22 19:41:08'),
(12, '', '$2y$10$IROPTNwDBuNknb3CXZrpyet1J48saKPyqe/XGIOG/6LXarH6sVGPu', 'user3', 'test', '', 'dep3@example.com', 'lecturer', '1', '2025-02-22 19:41:34'),
(13, '', '$2y$10$KQKYQABBwr7QNCFyWKZq2u5kP4FwRPq.7MutMbEcxe73n4cJlbF3i', 'user4', 'test', '', 'dep4@example.com', 'lecturer', '1', '2025-02-22 19:41:56'),
(14, '', '$2y$10$eFwd47EsikjKNZ0Hm2UT3.dNFcB/UdiaP1K6Hqq/5KMIq3yyAJOgK', 'user5', 'test', '', 'dep5@example.com', 'lecturer', '1', '2025-02-22 19:42:19'),
(15, '', '$2y$10$F6HJ7sCEXwJ9fOK4qgbCy.zs71Xh3HhLd5rq666vdDsJZhe8JGt7K', 'user6', 'test', '', 'dep6@example.com', 'lecturer', '1', '2025-02-22 19:42:47'),
(16, '', '$2y$10$.uguqLTNc7Q6BDWXayf2g.TLIge2jwRK0CZTZm7fjMSlC.HptNaeq', 'user7', 'test', '', 'dep7@example.com', 'lecturer', '1', '2025-02-22 19:43:10'),
(17, '', '$2y$10$F4VXIeNED/TPa3rnhHhM2.Xfhpmqs1SOaSK4.75bmJghKefaZYBoK', 'user8', 'test', '', 'dep8@example.com', 'lecturer', '1', '2025-02-22 19:43:44'),
(20, '', '$2y$10$agkNl6tE8OzB0ZKAA2PfL.pC7j3ERMxyXNFoxF2X7NdxPzyLiFtyy', 'ไอริณ', 'ชินะข่าย', '', 'thames32070@gmail.com', 'staff', '1', '2025-02-25 20:33:26'),
(21, '', '123', 'ภาณุพงศ์', 'อาษากิจ', '', 'kiamink338@gmail.com', 'staff', '1', '2025-03-04 14:56:23'),
(22, '', '$2y$10$xDFCpfPOv5FiOtIQ5WB43.yhNmhvcBcR7Tw7iC1DjpdnQ/ZyXtqJ2', 'staff2', 'user', '', 'staff2@example.com', 'staff', '1', '2025-03-11 12:17:34'),
(23, '', '$2y$10$Os.t276nDNQuJTz3T.220eC853j.iTSwHOeMqGmtoaJXr5O5Dxmk.', 'admin2', 'user', '', 'admin2@example.com', 'admin', '1', '2025-03-11 18:25:07');

-- --------------------------------------------------------

--
-- Table structure for table `user_department`
--

CREATE TABLE `user_department` (
  `user_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_department`
--

INSERT INTO `user_department` (`user_id`, `department_id`) VALUES
(10, 1),
(11, 2),
(12, 3),
(13, 4),
(14, 5),
(15, 6),
(16, 7),
(17, 8),
(20, 3),
(21, 8),
(22, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `document_files`
--
ALTER TABLE `document_files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `submission_id` (`submission_id`);

--
-- Indexes for table `document_history`
--
ALTER TABLE `document_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `submission_id` (`submission_id`),
  ADD KEY `action_by` (`action_by`);

--
-- Indexes for table `document_recipient`
--
ALTER TABLE `document_recipient`
  ADD PRIMARY KEY (`recipient_id`),
  ADD KEY `submission_id` (`submission_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `fk_document_recipient_department` (`department_id`);

--
-- Indexes for table `document_revisions`
--
ALTER TABLE `document_revisions`
  ADD PRIMARY KEY (`revision_id`),
  ADD KEY `submission_id` (`submission_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `document_submission`
--
ALTER TABLE `document_submission`
  ADD PRIMARY KEY (`submission_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `purpose_id` (`purpose_id`);

--
-- Indexes for table `download_logs`
--
ALTER TABLE `download_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `template_id` (`template_id`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`folder_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submission_id` (`submission_id`);

--
-- Indexes for table `purposes`
--
ALTER TABLE `purposes`
  ADD PRIMARY KEY (`purpose_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`template_id`),
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `fk_templates_folder` (`folder_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_department`
--
ALTER TABLE `user_department`
  ADD PRIMARY KEY (`user_id`,`department_id`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `document_files`
--
ALTER TABLE `document_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `document_history`
--
ALTER TABLE `document_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_recipient`
--
ALTER TABLE `document_recipient`
  MODIFY `recipient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `document_revisions`
--
ALTER TABLE `document_revisions`
  MODIFY `revision_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `document_submission`
--
ALTER TABLE `document_submission`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `download_logs`
--
ALTER TABLE `download_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `folder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `purposes`
--
ALTER TABLE `purposes`
  MODIFY `purpose_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `document_files`
--
ALTER TABLE `document_files`
  ADD CONSTRAINT `document_files_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `document_submission` (`submission_id`) ON DELETE CASCADE;

--
-- Constraints for table `document_history`
--
ALTER TABLE `document_history`
  ADD CONSTRAINT `document_history_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `document_submission` (`submission_id`),
  ADD CONSTRAINT `document_history_ibfk_2` FOREIGN KEY (`action_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `document_recipient`
--
ALTER TABLE `document_recipient`
  ADD CONSTRAINT `document_recipient_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `document_submission` (`submission_id`),
  ADD CONSTRAINT `document_recipient_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_document_recipient_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE;

--
-- Constraints for table `document_revisions`
--
ALTER TABLE `document_revisions`
  ADD CONSTRAINT `document_revisions_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `document_submission` (`submission_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `document_revisions_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `document_recipient` (`recipient_id`) ON DELETE CASCADE;

--
-- Constraints for table `document_submission`
--
ALTER TABLE `document_submission`
  ADD CONSTRAINT `document_submission_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `document_submission_ibfk_3` FOREIGN KEY (`purpose_id`) REFERENCES `purposes` (`purpose_id`);

--
-- Constraints for table `download_logs`
--
ALTER TABLE `download_logs`
  ADD CONSTRAINT `download_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `download_logs_ibfk_2` FOREIGN KEY (`template_id`) REFERENCES `templates` (`template_id`);

--
-- Constraints for table `folders`
--
ALTER TABLE `folders`
  ADD CONSTRAINT `folders_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `document_submission` (`submission_id`);

--
-- Constraints for table `templates`
--
ALTER TABLE `templates`
  ADD CONSTRAINT `fk_templates_folder` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`folder_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `templates_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_department`
--
ALTER TABLE `user_department`
  ADD CONSTRAINT `user_department_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_department_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
