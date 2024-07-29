SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `account` (
  `id_account` int(11) NOT NULL,
  `username` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `permission` int(11) DEFAULT -1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_employee` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `account` VALUES
(2, 'admin', '$2y$10$QsqZrRmRRYoLSzAIx8fZVeEBfcDJk66s1RX6JCBrqHVAdex5kDazi', 'admin@gmail.com', 1, 2, '2024-07-22 15:59:16', '2024-07-26 09:27:24', 3),
(10, 'super', '$2y$10$QsqZrRmRRYoLSzAIx8fZVeEBfcDJk66s1RX6JCBrqHVAdex5kDazi', 'super@gmail.com', 1, 1, '2024-07-26 08:38:03', '2024-07-26 09:14:21', 1),
(23, 'sxnd', '$2y$10$lT6PMASTl364nenASLnqqOGDaRq5yvrhhJDxZRfM3CZ0YlqQQGI2K', 'sxnd@gmail.com', 1, 2, '2024-07-26 03:39:39', '2024-07-26 08:42:11', 70),
(24, 'nhan', '$2y$10$QsqZrRmRRYoLSzAIx8fZVeEBfcDJk66s1RX6JCBrqHVAdex5kDazi', 'nhan@gmail.com', 1, 0, '2024-07-26 04:35:27', '2024-07-26 09:29:00', 71),
(25, 'tuananh09', '$2y$10$ZIKYmHfjnN5Y0VMt2Ss.IO0o0R6jHeFN8YKqDjbuP/D/XCmkRvpkW', 'tuananh@gmail.com', 1, 1, '2024-07-26 07:14:30', '2024-07-26 08:40:14', 73);

CREATE TABLE `account_import` (
  `id` int(11) NOT NULL,
  `email` text DEFAULT NULL,
  `ho_ten` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `certificates` (
  `id_certificate` int(11) NOT NULL,
  `id_employee` int(11) DEFAULT NULL,
  `certificate` text DEFAULT NULL,
  `end_date_certificate` date DEFAULT NULL,
  `id_type_certificate` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `certificates` VALUES
(112, 1, NULL, NULL, NULL, '2024-07-25 01:58:46', '2024-07-25 01:58:46'),
(113, 2, NULL, NULL, NULL, '2024-07-25 01:58:46', '2024-07-25 01:58:46'),
(114, 3, NULL, NULL, NULL, '2024-07-25 01:58:46', '2024-07-25 01:58:46'),
(115, 4, NULL, NULL, NULL, '2024-07-25 01:58:46', '2024-07-25 01:58:46'),
(116, 1, 'NGUYEN MINH HOANG.pdf', '2024-07-20', 1, '2024-07-25 13:21:01', '2024-07-25 13:21:01');

CREATE TABLE `certificate_type` (
  `id_certificate_type` int(11) NOT NULL,
  `certificate_type_name` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `certificate_type` VALUES
(1, 'type 111111111111111111111', '2024-07-25 01:56:16', '2024-07-25 02:09:47'),
(2, 'type 2', '2024-07-25 01:56:16', '2024-07-25 01:56:16');

CREATE TABLE `contacts` (
  `id_contact` int(11) NOT NULL,
  `phone_number` text DEFAULT NULL,
  `cic_number` text DEFAULT NULL,
  `cic_issue_date` date DEFAULT NULL,
  `cic_expiry_date` date DEFAULT NULL,
  `cic_place_issue` text DEFAULT NULL,
  `current_residence` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `contacts` VALUES
(1, '123456789', '123', '2024-07-03', '2024-07-11', '123', '123', '123', '2024-07-24 03:12:13', '2024-07-26 00:50:08'),
(3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:47:30', '2024-07-24 03:47:30'),
(4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:51:59', '2024-07-24 03:51:59'),
(5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:00', '2024-07-24 03:52:00'),
(6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:01', '2024-07-24 03:52:01'),
(7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:01', '2024-07-24 03:52:01'),
(8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:01', '2024-07-24 03:52:01'),
(9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:02', '2024-07-24 03:52:02'),
(10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:02', '2024-07-24 03:52:02'),
(11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:03', '2024-07-24 03:52:03'),
(12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:03', '2024-07-24 03:52:03'),
(13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:03', '2024-07-24 03:52:03'),
(14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:04', '2024-07-24 03:52:04'),
(15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:04', '2024-07-24 03:52:04'),
(16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:04', '2024-07-24 03:52:04'),
(17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:05', '2024-07-24 03:52:05'),
(18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:05', '2024-07-24 03:52:05'),
(19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:05', '2024-07-24 03:52:05'),
(20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:06', '2024-07-24 03:52:06'),
(21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:06', '2024-07-24 03:52:06'),
(22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:06', '2024-07-24 03:52:06'),
(23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:06', '2024-07-24 03:52:06'),
(24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:13', '2024-07-24 03:52:13'),
(25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:13', '2024-07-24 03:52:13'),
(26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:14', '2024-07-24 03:52:14'),
(27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:14', '2024-07-24 03:52:14'),
(28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:14', '2024-07-24 03:52:14'),
(29, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:15', '2024-07-24 03:52:15'),
(30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:15', '2024-07-24 03:52:15'),
(31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:15', '2024-07-24 03:52:15'),
(32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:21', '2024-07-24 03:52:21'),
(33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:21', '2024-07-24 03:52:21'),
(34, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:21', '2024-07-24 03:52:21'),
(35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:22', '2024-07-24 03:52:22'),
(36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:22', '2024-07-24 03:52:22'),
(37, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:23', '2024-07-24 03:52:23'),
(38, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:23', '2024-07-24 03:52:23'),
(39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:24', '2024-07-24 03:52:24'),
(40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:24', '2024-07-24 03:52:24'),
(41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:24', '2024-07-24 03:52:24'),
(42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:25', '2024-07-24 03:52:25'),
(43, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:25', '2024-07-24 03:52:25'),
(44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:25', '2024-07-24 03:52:25'),
(45, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:26', '2024-07-24 03:52:26'),
(46, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:26', '2024-07-24 03:52:26'),
(47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:26', '2024-07-24 03:52:26'),
(48, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:27', '2024-07-24 03:52:27'),
(49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:27', '2024-07-24 03:52:27'),
(50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:28', '2024-07-24 03:52:28'),
(51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:28', '2024-07-24 03:52:28'),
(52, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:28', '2024-07-24 03:52:28'),
(53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:29', '2024-07-24 03:52:29'),
(54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:29', '2024-07-24 03:52:29'),
(55, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:29', '2024-07-24 03:52:29'),
(56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:30', '2024-07-24 03:52:30'),
(57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:32', '2024-07-24 03:52:32'),
(58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:33', '2024-07-24 03:52:33'),
(59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:38', '2024-07-24 03:52:38'),
(60, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:38', '2024-07-24 03:52:38'),
(61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:39', '2024-07-24 03:52:39'),
(62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:39', '2024-07-24 03:52:39'),
(63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-24 03:52:40', '2024-07-24 03:52:40'),
(64, '3339965555', '111', '0001-11-11', '0111-01-01', '1', '111', '333dd', '2024-07-24 08:54:15', '2024-07-25 03:39:24'),
(71, '123', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-25 14:49:38', '2024-07-25 14:49:38'),
(72, '123', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-25 14:51:57', '2024-07-25 14:51:57'),
(73, '123', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-25 14:54:45', '2024-07-25 14:54:45'),
(74, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 02:09:03', '2024-07-26 02:09:03'),
(75, '1', '1', '0001-01-01', '0001-01-01', '1', '1', '1', '2024-07-26 03:39:10', '2024-07-26 08:42:59'),
(76, '123', '123', '0123-03-12', '0123-03-12', '123', '123', '123', '2024-07-26 04:33:47', '2024-07-26 07:46:19'),
(77, '123123132', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 04:53:58', '2024-07-26 04:53:58'),
(78, '0123456789', NULL, NULL, NULL, NULL, NULL, 'hhhhhhhh', '2024-07-26 07:13:19', '2024-07-26 07:45:14'),
(79, '12312312312', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 08:43:50', '2024-07-26 08:43:50'),
(80, '12312312312', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 08:43:50', '2024-07-26 08:43:50'),
(81, '12313131515212', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 08:44:44', '2024-07-26 08:44:44'),
(82, '132131245645654', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 08:55:57', '2024-07-26 08:55:57'),
(83, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 09:03:33', '2024-07-26 09:03:33'),
(84, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 09:03:45', '2024-07-26 09:03:45');

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `department` VALUES
(1, 'Administration', NULL),
(2, 'Finance, HR & Administration', NULL),
(3, 'Research', NULL),
(4, 'Information Technology', NULL),
(5, 'Support', NULL),
(6, 'Network Engineering', NULL),
(7, 'Sales and Marketing', NULL),
(8, 'Helpdesk', NULL),
(9, 'Project Management', NULL),
(10, 'ICT', NULL);

CREATE TABLE `employees` (
  `id_employee` int(11) NOT NULL,
  `employee_code` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `photo` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `last_name` text DEFAULT NULL,
  `first_name` text DEFAULT NULL,
  `en_name` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `gender` text DEFAULT NULL,
  `marital_status` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `national` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `military_service` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `cv` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `id_contact` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `department_id` int(11) DEFAULT NULL,
  `fired` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `employees` VALUES
(1, '001', 'uploads/1/cat.png', 'Nguyễn Tấn', 'Kiệt', 'Michael Teo', '0', 'Married', '2024-07-24', 'Vietnam', 'Done', '[\"CV Nguyen Minh Hoang_VNG DC Project _1.1.docx\",\"chung nhan Autocad.pdf\",\"chung nhan Autocad 1.pdf\",\"nguyen minh hoang- sp3361 sistymax.pdf\"]', 1, '2024-07-22 00:05:47', '2024-07-26 08:25:58', NULL, 'false'),
(2, '002', '12082743518478816220174488420380296291394412o.jpg', 'Trungg', 'Phan', NULL, NULL, NULL, '2024-07-24', NULL, NULL, NULL, 2, '2024-07-22 01:49:06', '2024-07-25 10:36:46', NULL, 'false'),
(3, '003', 'images.jpg', 'Mai Hiếu', 'Nghĩa', NULL, NULL, NULL, '2024-07-25', NULL, NULL, NULL, 3, '2024-07-22 16:00:39', '2024-07-26 00:27:09', NULL, 'false'),
(4, '004', NULL, 'Liêu Thị Ngọc', 'Phẩm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2024-07-24 03:29:49', '2024-07-25 03:06:52', NULL, 'false'),
(5, '005', 'photo2.png', 'Trần Thị Thanh', 'Thúy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, '2024-07-24 03:30:03', '2024-07-25 11:43:49', NULL, 'false'),
(6, '006', NULL, 'Cổ Thiên', 'Cơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, '2024-07-24 03:30:16', '2024-07-24 03:49:09', NULL, NULL),
(7, '007', NULL, 'Đoàn Nguyễn Thùy', 'Duyên', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2024-07-24 03:30:26', '2024-07-24 03:49:10', NULL, NULL),
(8, '008', NULL, 'Nguyễn Minh', 'Hoàng', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, '2024-07-24 03:30:36', '2024-07-24 03:49:10', NULL, NULL),
(9, '009', NULL, 'Ngô', 'Tuyển', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, '2024-07-24 03:31:01', '2024-07-24 03:49:10', NULL, NULL),
(10, '010', NULL, 'Thạch Vĩnh', 'Viễn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, '2024-07-24 03:31:01', '2024-07-24 03:49:10', NULL, NULL),
(11, '011', NULL, 'Jackary', 'Ya', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, '2024-07-24 03:31:01', '2024-07-24 03:49:10', NULL, NULL),
(12, '012', NULL, 'Nguyễn Hữu', 'Tới', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2024-07-24 03:31:37', '2024-07-24 03:49:09', NULL, NULL),
(13, '013', NULL, 'Hồ Lệ', 'Hằng', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, '2024-07-24 03:31:37', '2024-07-24 03:49:10', NULL, NULL),
(14, '014', NULL, 'Lê Việt', 'Anh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, '2024-07-24 03:32:07', '2024-07-24 03:49:10', NULL, NULL),
(15, '015', NULL, 'ABDo', 'RoMan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, '2024-07-24 03:32:07', '2024-07-24 03:49:10', NULL, NULL),
(16, '016', NULL, 'Châu Gia', 'Cốp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, '2024-07-24 03:32:07', '2024-07-24 03:49:10', NULL, NULL),
(17, '017', NULL, 'Trịnh Thị Lan', 'Anh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 17, '2024-07-24 03:32:17', '2024-07-24 03:49:10', NULL, NULL),
(18, '018', NULL, 'Hoàng Thị Hoàng', 'Oanh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 18, '2024-07-24 03:32:38', '2024-07-24 03:49:10', NULL, NULL),
(19, '019', NULL, 'Khưu Minh', 'Trung', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19, '2024-07-24 03:32:52', '2024-07-24 03:49:10', NULL, NULL),
(20, NULL, NULL, 'Mai Đặng Kiều', 'My', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 20, '2024-07-24 03:34:03', '2024-07-25 02:39:52', NULL, NULL),
(21, NULL, NULL, 'Nguyễn Văn', 'Tân', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 21, '2024-07-24 03:34:41', '2024-07-24 03:49:10', NULL, NULL),
(22, NULL, NULL, 'Trần Trọng', 'Bình', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 22, '2024-07-24 03:34:41', '2024-07-24 03:49:10', NULL, NULL),
(23, NULL, NULL, 'Đỗ Gia', 'Bảo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 23, '2024-07-24 03:36:27', '2024-07-24 03:49:10', NULL, NULL),
(24, NULL, NULL, 'Y Don', 'RBăm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 24, '2024-07-24 03:36:27', '2024-07-24 03:49:10', NULL, NULL),
(25, NULL, NULL, 'Lâm Minh', 'Nhật', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25, '2024-07-24 03:36:27', '2024-07-24 03:49:10', NULL, NULL),
(26, NULL, NULL, 'Hoàng Thanh', 'Vũ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26, '2024-07-24 03:38:51', '2024-07-24 03:49:10', NULL, NULL),
(27, NULL, NULL, 'Huỳnh Kim', 'Sang', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 27, '2024-07-24 03:38:51', '2024-07-24 03:49:10', NULL, NULL),
(28, NULL, NULL, 'Lê Thanh', 'Hào', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 28, '2024-07-24 03:38:51', '2024-07-24 03:49:10', NULL, NULL),
(29, NULL, NULL, 'Lý Trung', 'Tâm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 29, '2024-07-24 03:38:51', '2024-07-24 03:49:10', NULL, NULL),
(30, NULL, NULL, 'Nguyễn Anh', 'Tuấn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30, '2024-07-24 03:38:51', '2024-07-24 03:49:09', NULL, NULL),
(31, NULL, NULL, 'Nguyễn Hoàng', 'Lưu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 31, '2024-07-24 03:38:51', '2024-07-24 03:49:10', NULL, NULL),
(32, NULL, NULL, 'Trần Tuấn', 'Lĩnh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 32, '2024-07-24 03:38:51', '2024-07-24 03:49:10', NULL, NULL),
(33, NULL, NULL, 'Đỗ Nhất', 'Duy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 33, '2024-07-24 03:38:51', '2024-07-24 03:49:09', NULL, NULL),
(34, NULL, NULL, 'Huỳnh Hoàng', 'Thắng', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34, '2024-07-24 03:38:51', '2024-07-24 03:49:10', NULL, NULL),
(35, NULL, NULL, 'Trần Thanh', 'Trung', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35, '2024-07-24 03:38:51', '2024-07-24 03:49:10', NULL, NULL),
(36, NULL, NULL, 'Nguyễn Lê Nhất', 'Thanh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 36, '2024-07-24 03:38:51', '2024-07-24 03:49:10', NULL, NULL),
(37, NULL, NULL, 'Lê Trương Trường', 'Giang', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 37, '2024-07-24 03:38:51', '2024-07-24 03:49:10', NULL, NULL),
(38, NULL, NULL, 'Nguyễn Phước', 'Thiện', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 38, '2024-07-24 03:38:51', '2024-07-24 03:49:10', NULL, NULL),
(39, NULL, NULL, 'Nguyễn Hữu', 'Khải', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 39, '2024-07-24 03:38:51', '2024-07-24 03:49:10', NULL, NULL),
(40, NULL, NULL, 'Nguyễn Văn', 'Đạt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 40, '2024-07-24 03:38:51', '2024-07-24 03:49:10', NULL, NULL),
(41, NULL, NULL, 'Nguyễn Trọng', 'Thủy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 41, '2024-07-24 03:40:28', '2024-07-24 03:49:10', NULL, NULL),
(42, NULL, NULL, 'Nguyễn Tiến', 'Anh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 42, '2024-07-24 03:40:28', '2024-07-24 03:49:10', NULL, NULL),
(43, NULL, NULL, 'Phan Công', 'Huy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 43, '2024-07-24 03:40:29', '2024-07-24 03:49:10', NULL, NULL),
(44, NULL, NULL, 'Aly', 'Fin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 44, '2024-07-24 03:40:29', '2024-07-24 03:49:10', NULL, NULL),
(45, NULL, NULL, NULL, 'Azit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 45, '2024-07-24 03:40:29', '2024-07-24 03:49:09', NULL, NULL),
(46, NULL, NULL, 'Võ Ngọc', 'Hậu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 46, '2024-07-24 03:40:29', '2024-07-24 03:49:10', NULL, NULL),
(47, NULL, NULL, 'Hồ Văn', 'Thọ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 47, '2024-07-24 03:40:29', '2024-07-24 03:49:10', NULL, NULL),
(48, NULL, NULL, 'Phạm Anh', 'Văn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 48, '2024-07-24 03:40:29', '2024-07-24 03:49:10', NULL, NULL),
(49, NULL, NULL, 'Nguyễn Văn', 'Cường', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 49, '2024-07-24 03:40:29', '2024-07-24 03:49:10', NULL, NULL),
(50, NULL, NULL, 'Ngô Minh', 'Quý', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 50, '2024-07-24 03:40:29', '2024-07-24 03:49:10', NULL, NULL),
(51, NULL, NULL, 'Lâm Tuấn', 'Phát', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 51, '2024-07-24 03:44:52', '2024-07-24 03:49:10', NULL, NULL),
(52, NULL, NULL, 'Đinh Tuấn Nhật', 'Đăng', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 52, '2024-07-24 03:44:52', '2024-07-24 03:49:10', NULL, NULL),
(53, NULL, NULL, 'Nguyễn Thanh', 'Tâm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 53, '2024-07-24 03:44:52', '2024-07-24 03:49:10', NULL, NULL),
(54, NULL, NULL, 'Abdoro', 'Set', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 54, '2024-07-24 03:44:52', '2024-07-24 03:49:10', NULL, NULL),
(55, NULL, NULL, 'Quách Thành', 'Thông', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55, '2024-07-24 03:44:52', '2024-07-24 03:49:10', NULL, NULL),
(56, NULL, NULL, 'Huỳnh Thanh', 'Phương', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 56, '2024-07-24 03:44:52', '2024-07-24 03:49:10', NULL, NULL),
(57, NULL, NULL, 'Nguyễn Nhựt', 'Quang', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 57, '2024-07-24 03:44:52', '2024-07-24 03:49:10', NULL, NULL),
(58, NULL, NULL, 'Hoàng Đức', 'Huy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 58, '2024-07-24 03:44:52', '2024-07-24 03:49:10', NULL, NULL),
(59, NULL, NULL, 'Đoàn Văn', 'Chiến', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 59, '2024-07-24 03:45:16', '2024-07-24 03:49:10', NULL, NULL),
(60, NULL, NULL, 'Lữ', 'Nghĩa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 60, '2024-07-24 03:45:16', '2024-07-24 03:49:10', NULL, NULL),
(61, NULL, NULL, 'Nguyễn Tấn', 'Thật', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 61, '2024-07-24 03:45:50', '2024-07-24 03:49:10', NULL, NULL),
(62, NULL, NULL, 'Nguyễn Ngọc', 'Chi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 62, '2024-07-24 03:45:50', '2024-07-24 03:49:10', NULL, NULL),
(63, '1', 'Capture001.png', 'Trungg', 'Phan', '1', '0', 'Single', '2024-07-02', 'Vietnam', 'Done', NULL, 64, '2024-07-24 08:54:15', '2024-07-25 03:39:24', NULL, NULL),
(64, NULL, 'uploads/2/12082743518478816220174488420380296291394412o.jpg', NULL, NULL, NULL, '0', 'Single', NULL, 'Vietnam', 'Done', NULL, 65, '2024-07-25 00:56:35', '2024-07-26 04:58:58', NULL, NULL),
(66, '12', 'uploads/2/12082743518478816220174488420380296291394412o.jpg', '123', '3123', '123', '0', 'Single', '2024-07-03', 'Vietnam', 'Done', NULL, 71, '2024-07-25 14:49:38', '2024-07-26 04:58:58', NULL, 'false'),
(67, '123', 'uploads/2/12082743518478816220174488420380296291394412o.jpg', '12', 'ádbzxcb', '3123', '0', 'Single', '0123-03-12', 'Vietnam', 'Done', NULL, 72, '2024-07-25 14:51:57', '2024-07-26 04:58:59', NULL, 'false'),
(68, '123', 'uploads/68/IMG_6302.PNG', '3123', '12', '13', '0', 'Single', '0123-03-12', 'Vietnam', 'Done', NULL, 73, '2024-07-25 14:54:45', '2024-07-26 06:50:51', NULL, 'false'),
(69, '1', 'uploads/2/12082743518478816220174488420380296291394412o.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 74, '2024-07-26 02:09:03', '2024-07-26 04:58:59', NULL, 'true'),
(70, '999', 'uploads/70/z5659460006520_56c2fedb5e39896d0e2fb61ba170a0b1.jpg', '', 'Sơn Xuân Đi', '', '0', 'Single', '0111-11-11', 'Vietnam', 'Done', NULL, 75, '2024-07-26 03:39:10', '2024-07-26 08:42:59', NULL, 'false'),
(71, '4568', 'uploads/71/cat.png', 'Tran', 'Trong Nhan', 'Bee', '0', 'Single', '2003-12-12', 'Vietnam', 'Done', '[\"cat.png\"]', 76, '2024-07-26 04:33:47', '2024-07-26 07:58:36', NULL, 'false'),
(72, '6666', 'uploads/2/12082743518478816220174488420380296291394412o.jpg', 'A', 'Nguyen Van', 'anguyenvan', '0', 'Single', '2024-07-02', 'Vietnam', 'Done', NULL, 77, '2024-07-26 04:53:58', '2024-07-26 04:58:58', NULL, 'false'),
(73, '24092003', 'uploads/73/Screenshot 2024-07-16 225303.png', 'Tuan Anh', 'Huynh', 'mr Anh', '0', 'Single', '2003-09-24', 'Vietnam', 'No yet', NULL, 78, '2024-07-26 07:13:19', '2024-07-26 07:44:38', NULL, 'false'),
(74, '12431414', NULL, 'abc', 'nguyen van', 'mr abc', '0', 'Single', NULL, 'Vietnam', 'Done', NULL, 79, '2024-07-26 08:43:50', '2024-07-26 08:43:50', NULL, 'false'),
(75, '12431414', NULL, 'abc', 'nguyen van', 'mr abc', '0', 'Single', NULL, 'Vietnam', 'Done', NULL, 80, '2024-07-26 08:43:50', '2024-07-26 08:44:04', NULL, 'true'),
(76, '123411231', NULL, 'hung', 'nguyen huynh', 'mr hung', '0', 'Single', NULL, 'Vietnam', 'Done', NULL, 81, '2024-07-26 08:44:44', '2024-07-26 08:44:44', NULL, 'false'),
(77, '1234124124314', NULL, 'kiet', 'huynh anh', 'mr kiet lat', '0', 'Single', NULL, 'Vietnam', 'Done', NULL, 82, '2024-07-26 08:55:57', '2024-07-26 08:55:57', NULL, 'false'),
(78, NULL, NULL, NULL, NULL, NULL, '0', 'Single', NULL, 'Vietnam', 'Done', NULL, 83, '2024-07-26 09:03:33', '2024-07-26 09:03:52', NULL, 'true'),
(79, NULL, NULL, NULL, NULL, NULL, '0', 'Single', NULL, 'Vietnam', 'Done', NULL, 84, '2024-07-26 09:03:45', '2024-07-26 09:03:55', NULL, 'true');

CREATE TABLE `history` (
  `id_history` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `id_account` int(11) DEFAULT NULL,
  `history_name` text DEFAULT NULL,
  `id_history_type` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `history_type` (
  `id_history_type` int(11) NOT NULL,
  `history_type_name` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `history_type` VALUES
(1, 'Recent Project');

CREATE TABLE `holidays` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days` int(11) NOT NULL,
  `year` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `job_category` (
  `id_job_category` int(11) NOT NULL,
  `job_category_name` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `job_category` VALUES
(1, 'Engineer', '2024-07-21 18:42:09', NULL),
(2, 'Office', '2024-07-21 18:42:09', NULL);

CREATE TABLE `job_country` (
  `id_country` int(11) NOT NULL,
  `country_name` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `job_country` VALUES
(1, 'VIETNAM', '2024-07-21 18:42:13', NULL),
(2, 'INDO', '2024-07-25 01:40:42', '2024-07-25 01:40:42'),
(3, 'MYANMAR', '2024-07-21 18:42:13', NULL),
(4, 'CAMBODIA', '2024-07-21 18:42:13', NULL),
(5, 'MALAYSIA', '2024-07-21 18:42:13', NULL),
(6, 'SINGAPORE', '2024-07-21 18:42:13', NULL);

CREATE TABLE `job_detail` (
  `id_job_detail` int(11) NOT NULL,
  `id_job_title` int(11) DEFAULT NULL,
  `id_job_category` int(11) DEFAULT NULL,
  `id_job_type_contract` int(11) DEFAULT NULL,
  `id_job_team` int(11) DEFAULT NULL,
  `id_job_country` int(11) DEFAULT NULL,
  `id_job_level` int(11) DEFAULT NULL,
  `id_job_location` int(11) DEFAULT NULL,
  `id_job_position` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_employee` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `job_detail` VALUES
(1, 1, 2, 3, 14, 2, 1, 4, 2, '2024-07-26', '2024-07-26', '2024-07-22 01:50:39', '2024-07-26 01:36:44', 1),
(2, NULL, NULL, NULL, NULL, 1, NULL, NULL, 9, NULL, NULL, '2024-07-22 02:00:14', '2024-07-25 07:33:43', 2),
(3, 2, 2, 2, 13, 2, 2, 4, 2, '2024-07-17', '2024-07-24', '2024-07-22 16:00:52', '2024-07-26 02:31:46', 3),
(4, 1, 1, 1, 3, 2, 1, 3, 2, '0011-01-01', '0001-01-01', '2024-07-24 08:54:15', '2024-07-26 01:32:43', 63),
(7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-25 14:54:45', '2024-07-25 14:54:45', 68),
(10, 19, 2, 2, 13, 1, 20, 4, 2, '0001-01-01', '2024-07-26', '2024-07-26 03:39:10', '2024-07-26 08:43:42', 70),
(11, 3, 2, 2, 13, 2, 3, 1, 2, '0123-03-12', '0123-03-12', '2024-07-26 04:33:47', '2024-07-26 07:58:53', 71),
(12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 04:53:58', '2024-07-26 04:53:58', 72),
(13, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, NULL, NULL, '2024-07-26 07:13:19', '2024-07-26 07:44:56', 73),
(14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 08:43:50', '2024-07-26 08:43:50', 74),
(15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 08:43:50', '2024-07-26 08:43:50', 75),
(16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 08:44:44', '2024-07-26 08:44:44', 76),
(17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 08:55:57', '2024-07-26 08:55:57', 77),
(18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 09:03:33', '2024-07-26 09:03:33', 78),
(19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 09:03:45', '2024-07-26 09:03:45', 79);

CREATE TABLE `job_level` (
  `id_level` int(11) NOT NULL,
  `level_name` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `job_level` VALUES
(1, '1', '2024-07-21 18:42:25', '2024-07-26 03:22:12'),
(2, '2', '2024-07-21 18:42:25', NULL),
(3, '3', '2024-07-21 18:42:25', NULL),
(4, '4', '2024-07-21 18:42:25', NULL),
(5, '5', '2024-07-21 18:42:25', NULL),
(6, '6', '2024-07-21 18:42:25', NULL),
(7, '7', '2024-07-21 18:42:25', NULL),
(8, '0.0', '2024-07-21 18:42:25', NULL),
(9, '0.1', '2024-07-21 18:42:25', NULL),
(10, '0.2', '2024-07-21 18:42:25', NULL),
(11, '0.3', '2024-07-21 18:42:25', NULL),
(12, '0.4', '2024-07-21 18:42:25', NULL),
(13, '0.5', '2024-07-21 18:42:25', NULL),
(14, '0.6', '2024-07-21 18:42:25', NULL),
(15, '0.7', '2024-07-21 18:42:25', NULL),
(16, '0.8', '2024-07-21 18:42:25', NULL),
(17, '0.9', '2024-07-21 18:42:25', NULL),
(18, '1.0', '2024-07-21 18:42:25', NULL),
(19, '1.1', '2024-07-21 18:42:25', NULL),
(20, '1.2', '2024-07-21 18:42:25', NULL),
(21, '1.3', '2024-07-21 18:42:25', NULL),
(22, '1.4', '2024-07-21 18:42:25', NULL),
(23, '1.5', '2024-07-21 18:42:25', NULL),
(24, '1.6', '2024-07-21 18:42:25', NULL),
(25, '1.7', '2024-07-21 18:42:25', NULL),
(26, '1.8', '2024-07-21 18:42:25', NULL),
(27, '1.9', '2024-07-21 18:42:25', NULL),
(28, '2.0', '2024-07-21 18:42:25', NULL),
(29, '2.1', '2024-07-21 18:42:25', NULL),
(30, '2.2', '2024-07-21 18:42:25', NULL),
(31, '2.3', '2024-07-21 18:42:25', NULL),
(32, '2.4', '2024-07-21 18:42:25', NULL),
(33, '2.5', '2024-07-21 18:42:25', NULL),
(34, '2.6', '2024-07-21 18:42:25', NULL),
(35, '2.7', '2024-07-21 18:42:25', NULL),
(36, '2.8', '2024-07-21 18:42:25', NULL),
(37, '2.9', '2024-07-21 18:42:25', NULL),
(38, '3.0', '2024-07-21 18:42:25', NULL),
(39, '3.1', '2024-07-21 18:42:25', NULL),
(40, '3.2', '2024-07-21 18:42:25', NULL),
(41, '3.3', '2024-07-21 18:42:25', NULL),
(42, '3.4', '2024-07-21 18:42:25', NULL),
(43, '3.5', '2024-07-21 18:42:25', NULL),
(44, '3.6', '2024-07-21 18:42:25', NULL),
(45, '3.7', '2024-07-21 18:42:25', NULL),
(46, '3.8', '2024-07-21 18:42:25', NULL),
(47, '3.9', '2024-07-21 18:42:25', NULL),
(48, '4.0', '2024-07-21 18:42:25', NULL),
(49, '4.1', '2024-07-21 18:42:25', NULL),
(50, '4.2', '2024-07-21 18:42:25', NULL),
(51, '4.3', '2024-07-21 18:42:25', NULL),
(52, '4.4', '2024-07-21 18:42:25', NULL),
(53, '4.5', '2024-07-21 18:42:25', NULL),
(54, '4.6', '2024-07-21 18:42:25', NULL),
(55, '4.7', '2024-07-21 18:42:25', NULL),
(56, '4.8', '2024-07-21 18:42:25', NULL),
(57, '4.9', '2024-07-21 18:42:25', NULL),
(58, '5.0', '2024-07-21 18:42:25', NULL),
(59, '5.1', '2024-07-21 18:42:25', NULL),
(60, '5.2', '2024-07-21 18:42:25', NULL),
(61, '5.3', '2024-07-21 18:42:25', NULL),
(62, '5.4', '2024-07-21 18:42:25', NULL),
(63, '5.5', '2024-07-21 18:42:25', NULL),
(64, '5.6', '2024-07-21 18:42:25', NULL),
(65, '5.7', '2024-07-21 18:42:25', NULL),
(66, '5.8', '2024-07-21 18:42:25', NULL),
(67, '5.9', '2024-07-21 18:42:25', NULL),
(68, '6.0', '2024-07-21 18:42:25', NULL),
(69, '6.1', '2024-07-21 18:42:25', NULL),
(70, '6.2', '2024-07-21 18:42:25', NULL),
(71, '6.3', '2024-07-21 18:42:25', NULL),
(72, '6.4', '2024-07-21 18:42:25', NULL),
(73, '6.5', '2024-07-21 18:42:25', NULL),
(74, '6.6', '2024-07-21 18:42:25', NULL),
(75, '6.7', '2024-07-21 18:42:25', NULL),
(76, '6.8', '2024-07-21 18:42:25', NULL),
(77, '6.9', '2024-07-21 18:42:25', NULL),
(78, '7.0', '2024-07-21 18:42:25', NULL),
(79, '7.1', '2024-07-21 18:42:25', NULL),
(80, '7.2', '2024-07-21 18:42:25', NULL),
(81, '7.3', '2024-07-21 18:42:25', NULL),
(82, '7.4', '2024-07-21 18:42:25', NULL),
(83, '7.5', '2024-07-21 18:42:25', NULL),
(84, '7.6', '2024-07-21 18:42:25', NULL),
(85, '7.7', '2024-07-21 18:42:25', NULL),
(86, '7.8', '2024-07-21 18:42:25', NULL),
(87, '7.9', '2024-07-21 18:42:25', NULL),
(88, '8.0', '2024-07-21 18:42:25', NULL),
(89, '8.1', '2024-07-21 18:42:25', NULL),
(90, '8.2', '2024-07-21 18:42:25', NULL),
(91, '8.3', '2024-07-21 18:42:25', NULL),
(92, '8.4', '2024-07-21 18:42:25', NULL),
(93, '8.5', '2024-07-21 18:42:25', NULL),
(94, '8.6', '2024-07-21 18:42:25', NULL),
(95, '8.7', '2024-07-21 18:42:25', NULL),
(96, '8.8', '2024-07-21 18:42:25', NULL),
(97, '8.9', '2024-07-21 18:42:25', NULL),
(98, '9.0', '2024-07-21 18:42:25', NULL),
(99, '9.1', '2024-07-21 18:42:25', NULL),
(100, '9.2', '2024-07-21 18:42:25', NULL),
(101, '9.3', '2024-07-21 18:42:25', NULL),
(102, '9.4', '2024-07-21 18:42:25', NULL),
(103, '9.5', '2024-07-21 18:42:25', NULL),
(104, '9.6', '2024-07-21 18:42:25', NULL),
(105, '9.7', '2024-07-21 18:42:25', NULL),
(106, '9.8', '2024-07-21 18:42:25', NULL),
(107, '9.9', '2024-07-21 18:42:25', NULL),
(108, '10.0', '2024-07-21 18:42:25', NULL);

CREATE TABLE `job_location` (
  `id_location` int(11) NOT NULL,
  `location_name` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `job_location` VALUES
(1, 'YANGON', '2024-07-21 18:42:31', '2024-07-26 03:22:37'),
(2, 'PHNOM PENH', '2024-07-21 18:42:31', '2024-07-26 03:22:39'),
(3, 'MALAYSIA', '2024-07-21 18:42:31', '2024-07-26 03:22:42'),
(4, 'SINGAPORE', '2024-07-21 18:42:31', '2024-07-26 03:22:43');

CREATE TABLE `job_position` (
  `id_position` int(11) NOT NULL,
  `position_name` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `job_position` VALUES
(1, 'System Analyst', '2024-07-21 18:42:37', NULL),
(2, 'Software Developer', '2024-07-21 18:42:37', NULL),
(3, 'Project Manager', '2024-07-21 18:42:37', NULL),
(4, 'Network Infrastructure Specialist', '2024-07-21 18:42:37', NULL),
(5, 'Information Security Specialist', '2024-07-21 18:42:37', NULL),
(6, 'Database Specialist', '2024-07-21 18:42:37', NULL),
(7, 'Helpdesk/Desktop Support Specialist', '2024-07-21 18:42:37', NULL),
(8, 'Data Analyst', '2024-07-21 18:42:37', NULL),
(9, 'CEO', '2024-07-21 18:42:37', NULL);

CREATE TABLE `job_team` (
  `id_team` int(11) NOT NULL,
  `team_name` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `job_team` VALUES
(12, '1.POD', '2024-07-21 18:42:44', NULL),
(13, '2.SST', '2024-07-21 18:42:44', NULL),
(14, '3.PLD', '2024-07-21 18:42:44', NULL),
(15, '4.FID', '2024-07-21 18:42:44', NULL),
(16, '5.ITS', '2024-07-21 18:42:44', NULL),
(17, '6.CDT', '2024-07-21 18:42:44', NULL),
(18, '7.ICT', '2024-07-21 18:42:44', NULL),
(19, 'none', '2024-07-21 18:42:44', NULL);

CREATE TABLE `job_title` (
  `id_job_title` int(11) NOT NULL,
  `job_title` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `job_title` VALUES
(1, 'CEO'),
(2, 'Legal Officer'),
(3, 'Senior Project Administrator'),
(4, 'Deputy Director'),
(5, 'Project Support Assistant'),
(6, 'Head of SEA Business Development'),
(7, 'Solutions sale Account manager'),
(8, 'Senior Procurement Specialist'),
(9, 'Procurement Specialist'),
(10, 'Procurement Coordinator'),
(11, 'Project Coordinator Specialist'),
(12, 'Financial Specialist'),
(13, 'Senior Financial Specialist'),
(14, 'Senior IT Systems Specialist'),
(15, 'Junior IT Technician'),
(16, 'IT Support Engineer'),
(17, 'Junior IT Technician'),
(18, 'IT Support Specialist'),
(19, 'Project Deployment Specialist'),
(20, 'Sales Solutions Specialist'),
(21, 'Project Consultant'),
(22, 'Project Consulting Specialist'),
(23, 'ICT Engineer Specialist'),
(24, 'ICT Supervisor'),
(25, 'ICT Engineer'),
(26, 'Junior ICT Technican'),
(27, 'ICT Engineer'),
(28, 'ICT Engineer Specialist'),
(29, 'Trainee'),
(30, 'none'),
(31, 'Head Of Representative Hanoi Office'),
(32, 'Head Of Representative Hanoi Office'),
(33, 'Senior IT Support Specialist'),
(34, 'Senior IT Support Specialist');

CREATE TABLE `job_type_contract` (
  `id_type_contract` int(11) NOT NULL,
  `type_contract_name` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `job_type_contract` VALUES
(1, 'Fixed-Term Contract', '2024-07-21 18:42:50', NULL),
(2, 'Permanent Contract', '2024-07-21 18:42:50', NULL),
(3, 'Probationary Contract', '2024-07-21 18:42:50', NULL),
(4, 'Resignation/Leaving Contract', '2024-07-21 18:42:50', NULL),
(5, 'Intern', '2024-07-21 18:42:50', NULL);

CREATE TABLE `materials` (
  `material_id` int(11) NOT NULL,
  `material_code` text DEFAULT NULL,
  `material_name` text NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `labor_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `vat` decimal(5,2) DEFAULT NULL,
  `delivery_time` mediumtext DEFAULT NULL,
  `warranty_time` mediumtext DEFAULT NULL,
  `remarks` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `materials` VALUES
(10, 'DS-2CD3143G2-IU', 'CCTV SYSTEM', '4 MP Vandal WDR Fixed Dome Network Camera', 'HIKVISION', NULL, 'pcs', 24, 1999000.00, NULL, 47976000.00, 10.00, '8-12 weeks', '5 years', NULL),
(11, 'Accessories for wireless', 'WI-FI ACCESSORIES', 'Hanging Rod/tube and Accessories supporting for mounting AP', 'Vietnam', NULL, 'lot', 1, 15000000.00, NULL, 15000000.00, 10.00, NULL, NULL, NULL),
(12, 'DS-2CD3T43G2-2IS', 'CCTV SYSTEM', '4 MP WDR EXIR Fixed Bullet Network Camera', 'HIKVISION', NULL, NULL, 8, 2272000.00, NULL, 18176000.00, 10.00, '8-12 weeks', '5 years', NULL),
(13, 'DS-9664NI-M8', 'CCTV SYSTEM', 'NVR Centraliza monitor & Storage for All Camera', 'HIKVISION', NULL, 'pcs', 1, 8333000.00, NULL, 8333000.00, 10.00, '8-12 weeks', '1 years', NULL),
(14, 'no', 'CCTV SYSTEM', 'Accessories supporting for Camera (Hanging Rods, PVC Tubes for Wiring, HDMI Cable)', 'Vietnam', NULL, 'lot', 1, 30000000.00, NULL, 30000000.00, 10.00, '2 weeks', NULL, NULL),
(15, 'no', 'CCTV SYSTEM', 'WD Purple 6TB WD63PURZ', 'WD', NULL, 'pcs', 4, 4222000.00, NULL, 16888000.00, 10.00, NULL, '1 year', NULL),
(16, 'PBXact Software Version (PBXT-SWR-0050)', 'TELEPHONE & INTERCOM', 'IP Phone PABX', 'Yealink', NULL, 'pcs', 1, 25000000.00, NULL, 25000000.00, 0.00, '2-3 weeks', NULL, NULL),
(17, 'SIP-T30P', 'TELEPHONE & INTERCOM', 'Điện thoại VoIP Yealink SIP-T30P Yealink', 'Yealink', NULL, 'pcs', 8, 1080000.00, NULL, 8640000.00, 10.00, '2-3 weeks', NULL, NULL),
(18, 'no', 'NETWORK CABLING FIBER & COPPER & TRUNKING', 'Trunking 100x200 + Accessories', 'Vietnam', NULL, 'm', 140, 350000.00, NULL, 49000000.00, 10.00, '1-2 weeks', NULL, NULL),
(19, 'no', 'NETWORK CABLING FIBER & COPPER & TRUNKING', 'Category 6 Cable, 4 pair, 23 AWG, U/UTP, CM, 305m, Reel in box, Blue (BOX)', 'CommScope', NULL, 'box', 25, 2900000.00, NULL, 72500000.00, 10.00, NULL, NULL, NULL),
(20, 'no', 'NETWORK CABLING FIBER & COPPER & TRUNKING', 'Copper Cabling Accessories (Patchpannel, Module Jack, Cable Management,', 'CommScope', NULL, 'lot', 1, 95000000.00, NULL, 95000000.00, 10.00, NULL, NULL, NULL),
(21, 'no', 'NETWORK CABLING FIBER & COPPER & TRUNKING', 'Fiber OM3 4FO MM ( meter )', 'CommScope', NULL, 'm', 200, 46000.00, NULL, 9200000.00, 10.00, NULL, NULL, NULL),
(22, 'no', 'NETWORK CABLING FIBER & COPPER & TRUNKING', 'Fiber Cabling Accessories', 'CommScope', NULL, 'lot', 1, 30000000.00, NULL, 30000000.00, 10.00, NULL, NULL, NULL),
(23, 'no', 'NETWORK CABLING FIBER & COPPER & TRUNKING', 'Rack Cabinet 42U 600x 1000', 'Amtec/ EKO', NULL, 'pcs', 1, 12498000.00, NULL, 12498000.00, 10.00, '2-3 weeks', NULL, NULL),
(24, 'no', 'NETWORK CABLING FIBER & COPPER & TRUNKING', 'Rack Cabinet 19\" 15U 600x 600', 'Amtec/ EKO', NULL, 'pcs', 1, 7280000.00, NULL, 7280000.00, 10.00, '2-3 weeks', NULL, NULL),
(25, 'no', 'NETWORK CABLING FIBER & COPPER & TRUNKING', 'PDU C13, C14, 6 sockets', 'Amtec/ EKO', NULL, 'pcs', 1, 960000.00, NULL, 960000.00, 10.00, '2-3 weeks', NULL, NULL),
(26, 'no', 'NETWORK CABLING FIBER & COPPER & TRUNKING', 'PDU 6 Socket Universal', 'Amtec/ EKO', NULL, 'pcs', 2, 960000.00, NULL, 1920000.00, 10.00, '2-3 weeks', NULL, NULL),
(27, 'SRT5KRMXLI', 'NETWORK CABLING FIBER & COPPER & TRUNKING', 'APC Smart-UPS 5000VA 230V Rackmount', 'APC', NULL, 'lot', 1, 61000000.00, NULL, 61000000.00, 10.00, '2-3 weeks', '3 years', NULL),
(28, 'no', 'NETWORK CABLING FIBER & COPPER & TRUNKING', 'Electricity Cabinet & Accessories, cabling for IDF Power Supply', 'Vietnam', NULL, 'set', 1, 15000000.00, NULL, 15000000.00, 10.00, NULL, NULL, NULL),
(29, 'no', 'NETWORK CABLING FIBER & COPPER & TRUNKING', 'Other accessories, electrical, service', 'Vietnam', NULL, 'set', 1, 15000000.00, NULL, 15000000.00, 10.00, NULL, NULL, NULL),
(30, 'VX-3004F', 'PUBLIC ADDRESSING SYSTEM', 'Khung hệ thống 1 ngõ ra Toa VX-3004F', 'TOA/Taiwan', NULL, 'pcs', 1, 42771000.00, NULL, 42771000.00, 10.00, '6-8 weeks', '1 year', NULL),
(31, 'VX-030DA', 'PUBLIC ADDRESSING SYSTEM', 'Modun tăng âm 300W Toa VX-030DA', 'TOA/Taiwan', NULL, 'pcs', 1, 9562000.00, NULL, 9562000.00, 10.00, '6-8 weeks', '1 year', NULL),
(32, 'VX-3150DS', 'PUBLIC ADDRESSING SYSTEM', 'Bộ cấp nguồn TOA VX-3150DS', 'TOA/Taiwan', NULL, 'pcs', 1, 47168000.00, NULL, 47168000.00, 10.00, '6-8 weeks', '1 year', NULL),
(58, 'RM-300X', 'PUBLIC ADDRESSING SYSTEM', 'Remote microphone Toa RM-300X', 'TOA/Taiwan', NULL, 'pcs', 1, 12852000.00, NULL, 12852000.00, 10.00, '6-8 weeks', '1 year', NULL),
(59, 'PC-658R', 'PUBLIC ADDRESSING SYSTEM', 'Loa gắn trần 6W Toa PC-658R', 'TOA/Indonesia', NULL, 'pcs', 30, 245000.00, NULL, 7350000.00, 10.00, '6-8 weeks', '1 year', NULL),
(60, 'AT-4060', 'PUBLIC ADDRESSING SYSTEM', 'Chiết áp loa 60W AT-4060', 'TOA/Indonesia', NULL, 'pcs', 3, 681000.00, NULL, 2043000.00, 10.00, '6-8 weeks', '1 year', NULL),
(62, 'no', 'PUBLIC ADDRESSING SYSTEM', 'PA\'s Accessories (Cabling, PVC Tube for Wiring, Hanging Rods)', 'Vietnam', NULL, 'lot', 1, 35000000.00, NULL, 35000000.00, 10.00, NULL, NULL, NULL),
(63, 'Network & Firewall Implementing Service', 'INSTALLATION & IMPLEMENTATION SERVICES', 'Installation Service - Full Package', 'Ventech', '1', 'Full Package', 1, 15000000.00, NULL, 15000000.00, 8.00, NULL, NULL, NULL),
(64, 'Wireless Implementing Service', 'INSTALLATION & IMPLEMENTATION SERVICES', 'Installation Service - Full Package', 'Ventech', '1', 'Full Package', 1, 10000000.00, NULL, 10000000.00, 8.00, NULL, NULL, NULL),
(65, 'Server Implementing Service', 'INSTALLATION & IMPLEMENTATION SERVICES', '\"Installation Service - Full Package\r\nHyper-V, Import Host Image, Replication 2 host\"', 'Ventech', '1', 'Full Package', 1, 15000000.00, NULL, 15000000.00, 8.00, NULL, NULL, NULL),
(66, 'Rack, Patch Pannel, UPS Implementing Service', 'INSTALLATION & IMPLEMENTATION SERVICES', 'Installation Service - Full Package', 'Ventech', '1', 'Full Package', 1, 15000000.00, NULL, 15000000.00, 8.00, NULL, NULL, NULL),
(67, 'Implementing End Point Devices', 'INSTALLATION & IMPLEMENTATION SERVICES', 'Implement POS, Scale, PC, Printer', 'Ventech', '1', 'Full Package', 1, 15000000.00, NULL, 15000000.00, 8.00, NULL, NULL, NULL),
(68, 'Installation and implementation CCTV system', 'INSTALLATION & IMPLEMENTATION SERVICES', 'Mouting Camera and setup CVR/NVR, storage', 'Ventech', '1', 'Full Package', 1, 24000000.00, NULL, 24000000.00, 8.00, NULL, NULL, NULL),
(69, 'Installation PABX system with IP Phone for operation', 'INSTALLATION & IMPLEMENTATION SERVICES', 'PABX setup and connecting to PSTN/SIP Trunking, deliver IP phone to all users of stores', 'Ventech', '1', 'Full Package', 1, 5000000.00, NULL, 5000000.00, 8.00, NULL, NULL, NULL),
(70, 'Implement trunking at ceiling', 'INSTALLATION & IMPLEMENTATION SERVICES', 'Implement trunking, conduit at ceiling', 'Ventech', '1', 'Full Package', 1, 28000000.00, NULL, 28000000.00, 8.00, NULL, NULL, NULL),
(71, 'Implement trunking at under floor', 'INSTALLATION & IMPLEMENTATION SERVICES', 'Implement trunking, conduit under floor', 'Ventech', '1', 'Full Package', 1, 10000000.00, NULL, 10000000.00, 8.00, NULL, NULL, NULL),
(72, 'LAN,Cabling Implementing Service', 'INSTALLATION & IMPLEMENTATION SERVICES', 'Installation Service - Full Package\r\n- Implement Copper Cable\r\n- Test all Data and fiber Node by Fluke test\r\n- Mounting \\ Labling\\Recabling if have.', 'Ventech', '1', 'Full Package', 1, 95000000.00, NULL, 95000000.00, 8.00, NULL, NULL, NULL),
(73, 'Installation for Public Addressing', 'INSTALLATION & IMPLEMENTATION SERVICES', 'Installation for Public Addressing', 'Ventech', '1', 'Full Package', 1, 20000000.00, NULL, 20000000.00, 8.00, NULL, NULL, NULL),
(74, 'Support and Handover system', 'INSTALLATION & IMPLEMENTATION SERVICES', '\"Documented and Handover\r\nAbove but not limited all thing to completed system\r\nCare services 30days after project closed\"', 'Ventech', '1', 'Full Package', 1, 20000000.00, NULL, 20000000.00, 8.00, NULL, NULL, NULL),
(75, 'Project Managerment', 'INSTALLATION & IMPLEMENTATION SERVICES', 'LOCATION: Hong Ngu - Dong Thap\r\nTransportation, Warehouse\r\nShop Drawing, As - built drawing, documentation\r\nSafety equipment\r\nAccommodation, Travelling, OT fee,…', 'Ventech', '1', 'Full Package', 1, 30000000.00, NULL, 30000000.00, 8.00, NULL, NULL, NULL);

CREATE TABLE `medical_checkup` (
  `id_medical_checkup` int(11) NOT NULL,
  `medical_checkup_file` text DEFAULT NULL,
  `medical_checkup_issue_date` date DEFAULT NULL,
  `id_employee` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `medical_checkup` VALUES
(5, 'chung nhan Autocad 1.pdf', '2024-07-03', 1, '2024-07-24 19:54:41', '2024-07-24 19:54:41'),
(6, 'chung nhan Autocad 1.pdf', '2024-07-03', 1, '2024-07-24 19:54:41', '2024-07-24 19:54:41');

CREATE TABLE `passport` (
  `id_passport` int(11) NOT NULL,
  `passport_number` text DEFAULT NULL,
  `passport_issue_date` date DEFAULT NULL,
  `passport_expiry_date` date DEFAULT NULL,
  `passport_place_issue` text DEFAULT NULL,
  `passport_file` text DEFAULT NULL,
  `id_employee` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `passport` VALUES
(1, '4188', NULL, NULL, NULL, NULL, 1, '2024-07-26 00:41:42', '2024-07-26 00:41:42'),
(2, '234', '0234-04-23', '0234-04-23', '234', NULL, 71, '2024-07-26 07:51:54', '2024-07-26 07:51:54'),
(3, '1', '0001-01-01', '0001-01-01', '1', NULL, 70, '2024-07-26 08:42:59', '2024-07-26 08:42:59');

CREATE TABLE `phases` (
  `phase_id` int(11) NOT NULL,
  `phase_name` varchar(255) NOT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `phases` VALUES
(1, 'Công việc chính 1', 1),
(2, 'Công việc chính 2', 2),
(3, 'Công việc chính 3', 3),
(4, 'Công việc chính 4', 4);

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `project_name` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `project_description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `project_address` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `project_main_contractor` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `project_contact_name` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `project_contact_website` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `project_contact_phone` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `project_date_start` date DEFAULT NULL,
  `project_date_end` date DEFAULT NULL,
  `phase_id` int(11) DEFAULT NULL,
  `employees_id` int(11) DEFAULT NULL,
  `project_price_contingency` bigint(20) DEFAULT NULL,
  `project_contact_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `projects` VALUES
(1, 'MPFMP_GS101 Note: Ministry of Planing', 'Installation Data - Power - Raised floor - Datacenter system for 6 sites (5 sites at Nay Pyi Taw and 1 site at Yangon)', 'Yangon and Nay Pyi Taw', 'FPT', NULL, NULL, NULL, '2024-02-12', '2024-07-24', 1, NULL, 100000, 'Vĩnh Long'),
(2, 'Project 2', NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-11', '2024-07-24', 2, NULL, NULL, NULL),
(3, 'Project 3', NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-23', '2024-07-24', 3, NULL, NULL, NULL),
(4, 'Project 4', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-23', '2024-07-24', 4, NULL, NULL, NULL),
(5, 'Project 5', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-23', '2024-07-24', 5, NULL, NULL, NULL);

CREATE TABLE `project_cost` (
  `project_cost_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `project_cost_description` text NOT NULL,
  `project_cost_labor_qty` bigint(20) DEFAULT NULL,
  `project_cost_labor_unit` text DEFAULT NULL,
  `project_cost_budget_qty` bigint(20) DEFAULT NULL,
  `project_budget_unit` text DEFAULT NULL,
  `project_cost_labor_cost` bigint(20) DEFAULT NULL,
  `project_cost_misc_cost` bigint(20) DEFAULT NULL,
  `project_cost_perdiempay` bigint(20) DEFAULT NULL,
  `project_cost_remaks` text DEFAULT NULL,
  `project_cost_group_id` int(11) NOT NULL,
  `project_cost_datagroup_id` int(11) DEFAULT NULL,
  `project_cost_ot_budget` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

INSERT INTO `project_cost` VALUES
(2, 1, 'Cost of (Engineer) Labor ', 15, 'Manday', 120, 'Day', 300000, NULL, 345000, '', 1, 1, NULL),
(3, 1, 'Cost of (PM) Labor', 3, 'Manday', 120, 'Day', 350000, NULL, 414000, '', 1, 2, NULL),
(4, 1, 'Cost of Local Labor (day wage)', 12, 'Manday', 120, 'Day', 170000, NULL, 153000, '', 1, 3, NULL),
(5, 1, 'Cost of Visa', 15, 'Manday', 3, 'Project', 1610000, NULL, NULL, NULL, 1, 4, NULL),
(6, 1, 'Cost of Insurance', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, NULL),
(7, 1, 'Cost of Project Management', 2, 'Site', 1, 'Project', 5000000, NULL, NULL, NULL, 1, 6, NULL),
(8, 1, 'Air Ticket', 18, 'người', 2, 'vé', 3500000, NULL, NULL, NULL, 2, 7, NULL),
(9, 1, 'Bus Ticket', 18, 'Vé', 3, 'lần', 306000, NULL, NULL, NULL, 2, 8, NULL),
(10, 1, 'Hotel / House Rental', 2, 'phòng', 1, 'dự án', 59250050, NULL, NULL, NULL, 2, 9, NULL),
(11, 1, 'Cost of Commuting', 1, 'Site', 1, 'dự án', 20000000, NULL, NULL, NULL, 2, 10, NULL),
(12, 1, 'Cost of Renting car', 1, 'dự án', 1, 'dự án', 61200000, NULL, NULL, NULL, 2, 11, NULL),
(13, 1, 'Cost of Renting scraffold', NULL, 'N/A', NULL, NULL, NULL, NULL, NULL, NULL, 3, 12, NULL),
(14, 1, 'Cost of Renting warehouse', NULL, 'N/A', NULL, NULL, NULL, NULL, NULL, NULL, 3, 13, NULL),
(15, 1, 'Cost of Coordinate with Fit-out contractor and/or others….', NULL, 'N/A', NULL, NULL, NULL, NULL, NULL, NULL, 3, 14, NULL),
(16, 1, 'Cost of Transportation and delivery of material to the site', NULL, 'N/A', NULL, NULL, NULL, NULL, NULL, NULL, 3, 15, NULL),
(17, 1, 'Cost of Other', NULL, 'N/A', NULL, NULL, NULL, NULL, NULL, NULL, 4, 16, NULL),
(18, 1, 'Cost of Other', NULL, 'N/A', NULL, NULL, NULL, NULL, NULL, NULL, 4, 17, NULL),
(19, 1, 'Cost of marketing', 1, 'dự án', 1, 'dự án', 50000000, NULL, NULL, NULL, 4, 18, NULL),
(20, 1, 'Cost of Backoffice', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 19, NULL),
(21, 1, 'financial expenses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 20, NULL),
(22, 1, 'Cost of Other', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 21, NULL),
(23, 1, 'Cost Of Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 22, NULL),
(24, 1, 'Cost of Other', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 23, NULL),
(25, 1, 'Cost of Other', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 24, NULL);

CREATE TABLE `project_cost_datagroup` (
  `project_cost_datagroup_id` int(11) NOT NULL,
  `project_cost_group_id` int(11) DEFAULT NULL,
  `project_cost_groupdata_name` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `project_cost_datagroup` VALUES
(1, 1, 'Cost of (Engineer) Labor'),
(2, 1, 'Cost of (PM) Labor'),
(3, 1, 'Cost of Local Labor (day wage)'),
(4, 1, 'Cost of Visa'),
(5, 1, 'Cost of Insurance'),
(6, 1, 'Cost of Project Management'),
(7, 2, 'Air Ticket'),
(8, 2, 'Bus Ticket'),
(9, 2, 'Hotel / House Rental'),
(10, 2, 'Cost of Commuting'),
(11, 2, 'Cost of Renting car'),
(12, 3, 'Cost of Renting scraffold'),
(13, 3, 'Cost of Renting warehouse'),
(14, 3, 'Cost of Coordinate with Fit-out contractor and/or others….'),
(15, 3, 'Cost of Transportation and delivery of material to the site'),
(16, 4, 'Cost of Other'),
(17, 4, 'Cost of Other'),
(18, 4, 'Cost of marketing'),
(19, 5, 'Cost of Backoffice'),
(20, 5, 'financial expenses'),
(21, 5, 'Cost of Other'),
(22, 6, 'Cost Of Commission'),
(23, 6, 'Cost of Other'),
(24, 6, 'Cost of Other');

CREATE TABLE `project_cost_group` (
  `project_cost_group_id` int(11) NOT NULL,
  `project_cost_group_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

INSERT INTO `project_cost_group` VALUES
(1, 'COST OF LABOR'),
(2, 'COST OF TRAVEL'),
(3, 'COST OF RENTING'),
(4, 'COST OF OTHER'),
(5, 'COST OF BACKOFFICE'),
(6, 'COST OF COMMISSION');

CREATE TABLE `project_daily_report_workday` (
  `pdrw_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `project_workday` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `project_daily_report_worker` (
  `pdrworker_id` int(11) NOT NULL,
  `pdrw_id` int(11) NOT NULL,
  `id_employee` int(11) NOT NULL,
  `pdrworker_overtime_start` time DEFAULT NULL,
  `pdrworker_overtime_end` time DEFAULT NULL,
  `pdrworker_work_name` date NOT NULL,
  `pdrworker_location` text NOT NULL,
  `pdrworker_quantity` int(11) NOT NULL,
  `pdrworker_completed` tinyint(1) NOT NULL,
  `pdrworker_interrupt` text DEFAULT NULL,
  `pdrworker_action_nextday` text DEFAULT NULL,
  `pdrworker_construction_schedule` text NOT NULL,
  `pdrworker_remaks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `recent_project` (
  `id_recent_project` int(11) NOT NULL,
  `id_account` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `recent_project` VALUES
(1, 10, 1, '2024-07-26 02:13:14'),
(2, 10, 2, '2024-07-26 02:13:14'),
(3, 10, 3, '2024-07-26 02:32:51'),
(4, 10, 4, '2024-07-26 02:32:51'),
(5, 10, 5, '2024-07-26 02:32:51');

CREATE TABLE `sub_tasks` (
  `sub_task_id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `sub_task_name` varchar(255) NOT NULL,
  `progress` varchar(50) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `initial_quantity` int(11) DEFAULT NULL,
  `engineers` varchar(255) DEFAULT NULL,
  `report_information` text DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `actual_quantity` int(11) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `difficulties` text DEFAULT NULL,
  `request` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sub_tasks` VALUES
(26, 16, 'sub-task1', NULL, '2024-07-24', '2024-09-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 27, 'More', NULL, NULL, NULL, NULL, '5', NULL, NULL, NULL, NULL, NULL, NULL),
(61, 32, 'sss', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `phase_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `task_name` varchar(255) NOT NULL,
  `progress` varchar(50) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `initial_quantity` int(11) DEFAULT NULL,
  `engineers` varchar(255) DEFAULT NULL,
  `report_information` text DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `actual_quantity` int(11) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `difficulties` text DEFAULT NULL,
  `request` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tasks` VALUES
(16, 2, 2, 'Task 1', NULL, '2024-04-30', '2025-01-31', NULL, 'Edge', NULL, NULL, NULL, NULL, NULL, 'Task Request'),
(17, 2, 2, 'Task 2', NULL, '2024-07-26', '2024-11-30', NULL, 'Edge', NULL, NULL, NULL, NULL, NULL, 'Task Request'),
(27, 1, NULL, 'Task 12', NULL, '2024-07-05', '2024-08-02', NULL, 'abc', NULL, NULL, NULL, NULL, NULL, 'abc'),
(32, 1, NULL, 'asd', NULL, '2024-07-02', '2024-08-03', NULL, '72', NULL, NULL, NULL, NULL, NULL, NULL);

CREATE TABLE `team` (
  `id_team` int(11) NOT NULL,
  `team_name` text DEFAULT NULL,
  `team_count` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `team_detail` (
  `id_team_detail` int(11) NOT NULL,
  `id_employee` int(11) DEFAULT NULL,
  `id_team` int(11) DEFAULT NULL,
  `id_team_position` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `team_position` (
  `id_team_position` int(11) NOT NULL,
  `position_name` text DEFAULT NULL,
  `team_permission` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


ALTER TABLE `account`
  ADD PRIMARY KEY (`id_account`);

ALTER TABLE `account_import`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id_certificate`);

ALTER TABLE `certificate_type`
  ADD PRIMARY KEY (`id_certificate_type`);

ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id_contact`);

ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

ALTER TABLE `employees`
  ADD PRIMARY KEY (`id_employee`);

ALTER TABLE `history`
  ADD PRIMARY KEY (`id_history`);

ALTER TABLE `history_type`
  ADD PRIMARY KEY (`id_history_type`);

ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `job_category`
  ADD PRIMARY KEY (`id_job_category`);

ALTER TABLE `job_country`
  ADD PRIMARY KEY (`id_country`);

ALTER TABLE `job_detail`
  ADD PRIMARY KEY (`id_job_detail`);

ALTER TABLE `job_level`
  ADD PRIMARY KEY (`id_level`);

ALTER TABLE `job_location`
  ADD PRIMARY KEY (`id_location`);

ALTER TABLE `job_position`
  ADD PRIMARY KEY (`id_position`);

ALTER TABLE `job_team`
  ADD PRIMARY KEY (`id_team`);

ALTER TABLE `job_title`
  ADD PRIMARY KEY (`id_job_title`);

ALTER TABLE `job_type_contract`
  ADD PRIMARY KEY (`id_type_contract`);

ALTER TABLE `materials`
  ADD PRIMARY KEY (`material_id`);

ALTER TABLE `medical_checkup`
  ADD PRIMARY KEY (`id_medical_checkup`);

ALTER TABLE `passport`
  ADD PRIMARY KEY (`id_passport`);

ALTER TABLE `phases`
  ADD PRIMARY KEY (`phase_id`),
  ADD KEY `phases_projects` (`project_id`);

ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`);

ALTER TABLE `project_cost`
  ADD PRIMARY KEY (`project_cost_id`),
  ADD KEY `project_cost_project_cost_group_project_cost_group_id_fk` (`project_cost_group_id`),
  ADD KEY `project_cost_project_cost_datagroup_project_cost_datagroup_id_fk` (`project_cost_datagroup_id`);

ALTER TABLE `project_cost_datagroup`
  ADD PRIMARY KEY (`project_cost_datagroup_id`),
  ADD KEY `cost_datagroup_cost_group_cost_group_id_fk` (`project_cost_group_id`);

ALTER TABLE `project_cost_group`
  ADD PRIMARY KEY (`project_cost_group_id`);

ALTER TABLE `recent_project`
  ADD PRIMARY KEY (`id_recent_project`);

ALTER TABLE `sub_tasks`
  ADD PRIMARY KEY (`sub_task_id`),
  ADD KEY `task_id` (`task_id`);

ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `phase_id` (`phase_id`);

ALTER TABLE `team`
  ADD PRIMARY KEY (`id_team`);

ALTER TABLE `team_detail`
  ADD PRIMARY KEY (`id_team_detail`);

ALTER TABLE `team_position`
  ADD PRIMARY KEY (`id_team_position`);


ALTER TABLE `account`
  MODIFY `id_account` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

ALTER TABLE `account_import`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `certificates`
  MODIFY `id_certificate` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

ALTER TABLE `certificate_type`
  MODIFY `id_certificate_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `contacts`
  MODIFY `id_contact` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE `employees`
  MODIFY `id_employee` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

ALTER TABLE `history`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `history_type`
  MODIFY `id_history_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

ALTER TABLE `job_category`
  MODIFY `id_job_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `job_country`
  MODIFY `id_country` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `job_detail`
  MODIFY `id_job_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

ALTER TABLE `job_level`
  MODIFY `id_level` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

ALTER TABLE `job_location`
  MODIFY `id_location` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `job_position`
  MODIFY `id_position` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `job_team`
  MODIFY `id_team` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

ALTER TABLE `job_title`
  MODIFY `id_job_title` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

ALTER TABLE `job_type_contract`
  MODIFY `id_type_contract` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

ALTER TABLE `medical_checkup`
  MODIFY `id_medical_checkup` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

ALTER TABLE `passport`
  MODIFY `id_passport` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `phases`
  MODIFY `phase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `project_cost`
  MODIFY `project_cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

ALTER TABLE `project_cost_datagroup`
  MODIFY `project_cost_datagroup_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

ALTER TABLE `recent_project`
  MODIFY `id_recent_project` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `sub_tasks`
  MODIFY `sub_task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

ALTER TABLE `team`
  MODIFY `id_team` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `team_detail`
  MODIFY `id_team_detail` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `team_position`
  MODIFY `id_team_position` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `phases`
  ADD CONSTRAINT `phases_projects` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`);

ALTER TABLE `project_cost`
  ADD CONSTRAINT `project_cost_project_cost_datagroup_project_cost_datagroup_id_fk` FOREIGN KEY (`project_cost_datagroup_id`) REFERENCES `project_cost_datagroup` (`project_cost_datagroup_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_cost_project_cost_group_project_cost_group_id_fk` FOREIGN KEY (`project_cost_group_id`) REFERENCES `project_cost_group` (`project_cost_group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `project_cost_datagroup`
  ADD CONSTRAINT `cost_datagroup_cost_group_cost_group_id_fk` FOREIGN KEY (`project_cost_group_id`) REFERENCES `project_cost_group` (`project_cost_group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `sub_tasks`
  ADD CONSTRAINT `sub_tasks_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`);

ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_phases_phase_id_fk` FOREIGN KEY (`phase_id`) REFERENCES `phases` (`phase_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
