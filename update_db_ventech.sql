CREATE TABLE `account` (
                           `id_account` int(11) NOT NULL,
                           `username` text DEFAULT NULL,
                           `password` text DEFAULT NULL,
                           `email` text DEFAULT NULL,
                           `status` int(11) DEFAULT NULL,
                           `created_at` timestamp NULL DEFAULT current_timestamp(),
                            `updated_at` timestamp NULL DEFAULT null ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
                                `id_certificate` int(11) NOT NULL,
                                `id_employee` int(11) DEFAULT NULL,
                                `certificate` text DEFAULT NULL,
                                `end_date_certificate` date DEFAULT NULL,
                                `id_type_certificate` int(11) DEFAULT NULL,
                                `created_at` timestamp NULL DEFAULT current_timestamp() ,
                                 `updated_at` timestamp NULL DEFAULT null ON UPDATE current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id_certificate`, `id_employee`, `certificate`, `end_date_certificate`, `id_type_certificate`, `created_at`, `updated_at`) VALUES
                                                                                                                                                           (108, 1613, 'nguyen minh hoang- sp3361 sistymax.pdf', NULL, 6, '2024-07-22 01:41:27', null),
                                                                                                                                                           (109, 1613, 'NGUYEN MINH HOANG.pdf', NULL, 6, '2024-07-22 01:41:27', null),
                                                                                                                                                           (110, 1613, 'chung nhan Autocad 1.pdf', NULL, 6, '2024-07-22 01:41:27', null),
                                                                                                                                                           (111, 1613, 'chung nhan Autocad.pdf', NULL, 6, '2024-07-22 01:41:27', null);

-- --------------------------------------------------------

--
-- Table structure for table `certificate_type`
--

CREATE TABLE `certificate_type` (
                                    `id_certificate_type` int(11) NOT NULL,
                                    `certificate_type_name` text DEFAULT NULL,
                                    `created_at` timestamp NULL DEFAULT current_timestamp() ,
                                     `updated_at` timestamp NULL DEFAULT null ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
                            `id_contact` int(11) NOT NULL,
                            `phone_number` text DEFAULT NULL,
                            `passport_number` text DEFAULT NULL,
                            `passport_issue_date` datetime DEFAULT NULL,
                            `passport_expiry_date` datetime DEFAULT NULL,
                            `passport_place_issue` text DEFAULT NULL,
                            `cic_number` text DEFAULT NULL,
                            `cic_issue_date` datetime DEFAULT NULL,
                            `cic_expiry_date` datetime DEFAULT NULL,
                            `cic_place_issue` text DEFAULT NULL,
                            `current_residence` text DEFAULT NULL,
                            `permanent_address` text DEFAULT NULL,
                            `medical_checkup_date` datetime DEFAULT NULL,
                            `created_at` timestamp NULL DEFAULT current_timestamp() ,
                             `updated_at` timestamp NULL DEFAULT null ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
                             `id_employee` int(11) NOT NULL,
                             `employee_code` text DEFAULT NULL,
                             `photo` text DEFAULT NULL,
                             `last_name` text DEFAULT NULL,
                             `first_name` text DEFAULT NULL,
                             `en_name` text DEFAULT NULL,
                             `gender` text DEFAULT NULL,
                             `marital_status` text DEFAULT NULL,
                             `date_of_birth` datetime DEFAULT NULL,
                             `national` text DEFAULT NULL,
                             `military_service` text DEFAULT NULL,
                             `id_contact` int(11) DEFAULT NULL,
                             `created_at` timestamp NULL DEFAULT current_timestamp() ,
                              `updated_at` timestamp NULL DEFAULT null ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
                           `id_history` int(11) NOT NULL,
                           `created_at` timestamp NULL DEFAULT NULL,
                           `id_account` int(11) DEFAULT NULL,
                           `history_name` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_category`
--

CREATE TABLE `job_category` (
                                `id_job_category` int(11) NOT NULL,
                                `job_category_name` text DEFAULT NULL,
                                `created_at` timestamp NULL DEFAULT current_timestamp() ,
                                 `updated_at` timestamp NULL DEFAULT null ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `job_category`
--

INSERT INTO `job_category` (`id_job_category`, `job_category_name`, `created_at`, `updated_at`) VALUES
                                                                                                    (1, 'Engineer', '2024-07-22 01:42:09', null),
                                                                                                    (2, 'Office', '2024-07-22 01:42:09', null);

-- --------------------------------------------------------

--
-- Table structure for table `job_country`
--

CREATE TABLE `job_country` (
                               `id_country` int(11) NOT NULL,
                               `country_name` text DEFAULT NULL,
                               `created_at` timestamp NULL DEFAULT current_timestamp() ,
                                `updated_at` timestamp NULL DEFAULT null ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `job_country`
--

INSERT INTO `job_country` (`id_country`, `country_name`, `created_at`, `updated_at`) VALUES
                                                                                         (1, 'VIETNAM', '2024-07-22 01:42:13', null),
                                                                                         (3, 'MYANMAR', '2024-07-22 01:42:13', null),
                                                                                         (4, 'CAMBODIA', '2024-07-22 01:42:13', null),
                                                                                         (5, 'MALAYSIA', '2024-07-22 01:42:13', null),
                                                                                         (6, 'SINGAPORE', '2024-07-22 01:42:13', null);

-- --------------------------------------------------------

--
-- Table structure for table `job_detail`
--

CREATE TABLE `job_detail` (
                              `id_job_detail` int(11) NOT NULL,
                              `job_title` text DEFAULT NULL,
                              `id_job_category` int(11) DEFAULT NULL,
                              `id_job_type_contract` int(11) DEFAULT NULL,
                              `id_job_team` int(11) DEFAULT NULL,
                              `id_job_country` int(11) DEFAULT NULL,
                              `id_job_level` int(11) DEFAULT NULL,
                              `id_job_location` int(11) DEFAULT NULL,
                              `id_job_position` int(11) DEFAULT NULL,
                              `start_date` datetime DEFAULT NULL,
                              `end_date` datetime DEFAULT NULL,
                              `email` text DEFAULT NULL,
                              `id_certificate` int(11) DEFAULT NULL,
                              `created_at` timestamp NULL DEFAULT current_timestamp() ,
                               `updated_at` timestamp NULL DEFAULT null ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_level`
--

CREATE TABLE `job_level` (
                             `id_level` int(11) NOT NULL,
                             `level_name` text DEFAULT NULL,
                             `created_at` timestamp NULL DEFAULT current_timestamp() ,
                              `updated_at` timestamp NULL DEFAULT null ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `job_level`
--

INSERT INTO `job_level` (`id_level`, `level_name`, `created_at`, `updated_at`) VALUES
                                                                                   (1, '1', '2024-07-22 01:42:25', null),
                                                                                   (2, '2', '2024-07-22 01:42:25', null),
                                                                                   (3, '3', '2024-07-22 01:42:25', null),
                                                                                   (4, '4', '2024-07-22 01:42:25', null),
                                                                                   (5, '5', '2024-07-22 01:42:25', null),
                                                                                   (6, '6', '2024-07-22 01:42:25', null),
                                                                                   (7, '7', '2024-07-22 01:42:25', null),
                                                                                   (8, '0.0', '2024-07-22 01:42:25', null),
                                                                                   (9, '0.1', '2024-07-22 01:42:25', null),
                                                                                   (10, '0.2', '2024-07-22 01:42:25', null),
                                                                                   (11, '0.3', '2024-07-22 01:42:25', null),
                                                                                   (12, '0.4', '2024-07-22 01:42:25', null),
                                                                                   (13, '0.5', '2024-07-22 01:42:25', null),
                                                                                   (14, '0.6', '2024-07-22 01:42:25', null),
                                                                                   (15, '0.7', '2024-07-22 01:42:25', null),
                                                                                   (16, '0.8', '2024-07-22 01:42:25', null),
                                                                                   (17, '0.9', '2024-07-22 01:42:25', null),
                                                                                   (18, '1.0', '2024-07-22 01:42:25', null),
                                                                                   (19, '1.1', '2024-07-22 01:42:25', null),
                                                                                   (20, '1.2', '2024-07-22 01:42:25', null),
                                                                                   (21, '1.3', '2024-07-22 01:42:25', null),
                                                                                   (22, '1.4', '2024-07-22 01:42:25', null),
                                                                                   (23, '1.5', '2024-07-22 01:42:25', null),
                                                                                   (24, '1.6', '2024-07-22 01:42:25', null),
                                                                                   (25, '1.7', '2024-07-22 01:42:25', null),
                                                                                   (26, '1.8', '2024-07-22 01:42:25', null),
                                                                                   (27, '1.9', '2024-07-22 01:42:25', null),
                                                                                   (28, '2.0', '2024-07-22 01:42:25', null),
                                                                                   (29, '2.1', '2024-07-22 01:42:25', null),
                                                                                   (30, '2.2', '2024-07-22 01:42:25', null),
                                                                                   (31, '2.3', '2024-07-22 01:42:25', null),
                                                                                   (32, '2.4', '2024-07-22 01:42:25', null),
                                                                                   (33, '2.5', '2024-07-22 01:42:25', null),
                                                                                   (34, '2.6', '2024-07-22 01:42:25', null),
                                                                                   (35, '2.7', '2024-07-22 01:42:25', null),
                                                                                   (36, '2.8', '2024-07-22 01:42:25', null),
                                                                                   (37, '2.9', '2024-07-22 01:42:25', null),
                                                                                   (38, '3.0', '2024-07-22 01:42:25', null),
                                                                                   (39, '3.1', '2024-07-22 01:42:25', null),
                                                                                   (40, '3.2', '2024-07-22 01:42:25', null),
                                                                                   (41, '3.3', '2024-07-22 01:42:25', null),
                                                                                   (42, '3.4', '2024-07-22 01:42:25', null),
                                                                                   (43, '3.5', '2024-07-22 01:42:25', null),
                                                                                   (44, '3.6', '2024-07-22 01:42:25', null),
                                                                                   (45, '3.7', '2024-07-22 01:42:25', null),
                                                                                   (46, '3.8', '2024-07-22 01:42:25', null),
                                                                                   (47, '3.9', '2024-07-22 01:42:25', null),
                                                                                   (48, '4.0', '2024-07-22 01:42:25', null),
                                                                                   (49, '4.1', '2024-07-22 01:42:25', null),
                                                                                   (50, '4.2', '2024-07-22 01:42:25', null),
                                                                                   (51, '4.3', '2024-07-22 01:42:25', null),
                                                                                   (52, '4.4', '2024-07-22 01:42:25', null),
                                                                                   (53, '4.5', '2024-07-22 01:42:25', null),
                                                                                   (54, '4.6', '2024-07-22 01:42:25', null),
                                                                                   (55, '4.7', '2024-07-22 01:42:25', null),
                                                                                   (56, '4.8', '2024-07-22 01:42:25', null),
                                                                                   (57, '4.9', '2024-07-22 01:42:25', null),
                                                                                   (58, '5.0', '2024-07-22 01:42:25', null),
                                                                                   (59, '5.1', '2024-07-22 01:42:25', null),
                                                                                   (60, '5.2', '2024-07-22 01:42:25', null),
                                                                                   (61, '5.3', '2024-07-22 01:42:25', null),
                                                                                   (62, '5.4', '2024-07-22 01:42:25', null),
                                                                                   (63, '5.5', '2024-07-22 01:42:25', null),
                                                                                   (64, '5.6', '2024-07-22 01:42:25', null),
                                                                                   (65, '5.7', '2024-07-22 01:42:25', null),
                                                                                   (66, '5.8', '2024-07-22 01:42:25', null),
                                                                                   (67, '5.9', '2024-07-22 01:42:25', null),
                                                                                   (68, '6.0', '2024-07-22 01:42:25', null),
                                                                                   (69, '6.1', '2024-07-22 01:42:25', null),
                                                                                   (70, '6.2', '2024-07-22 01:42:25', null),
                                                                                   (71, '6.3', '2024-07-22 01:42:25', null),
                                                                                   (72, '6.4', '2024-07-22 01:42:25', null),
                                                                                   (73, '6.5', '2024-07-22 01:42:25', null),
                                                                                   (74, '6.6', '2024-07-22 01:42:25', null),
                                                                                   (75, '6.7', '2024-07-22 01:42:25', null),
                                                                                   (76, '6.8', '2024-07-22 01:42:25', null),
                                                                                   (77, '6.9', '2024-07-22 01:42:25', null),
                                                                                   (78, '7.0', '2024-07-22 01:42:25', null),
                                                                                   (79, '7.1', '2024-07-22 01:42:25', null),
                                                                                   (80, '7.2', '2024-07-22 01:42:25', null),
                                                                                   (81, '7.3', '2024-07-22 01:42:25', null),
                                                                                   (82, '7.4', '2024-07-22 01:42:25', null),
                                                                                   (83, '7.5', '2024-07-22 01:42:25', null),
                                                                                   (84, '7.6', '2024-07-22 01:42:25', null),
                                                                                   (85, '7.7', '2024-07-22 01:42:25', null),
                                                                                   (86, '7.8', '2024-07-22 01:42:25', null),
                                                                                   (87, '7.9', '2024-07-22 01:42:25', null),
                                                                                   (88, '8.0', '2024-07-22 01:42:25', null),
                                                                                   (89, '8.1', '2024-07-22 01:42:25', null),
                                                                                   (90, '8.2', '2024-07-22 01:42:25', null),
                                                                                   (91, '8.3', '2024-07-22 01:42:25', null),
                                                                                   (92, '8.4', '2024-07-22 01:42:25', null),
                                                                                   (93, '8.5', '2024-07-22 01:42:25', null),
                                                                                   (94, '8.6', '2024-07-22 01:42:25', null),
                                                                                   (95, '8.7', '2024-07-22 01:42:25', null),
                                                                                   (96, '8.8', '2024-07-22 01:42:25', null),
                                                                                   (97, '8.9', '2024-07-22 01:42:25', null),
                                                                                   (98, '9.0', '2024-07-22 01:42:25', null),
                                                                                   (99, '9.1', '2024-07-22 01:42:25', null),
                                                                                   (100, '9.2', '2024-07-22 01:42:25', null),
                                                                                   (101, '9.3', '2024-07-22 01:42:25', null),
                                                                                   (102, '9.4', '2024-07-22 01:42:25', null),
                                                                                   (103, '9.5', '2024-07-22 01:42:25', null),
                                                                                   (104, '9.6', '2024-07-22 01:42:25', null),
                                                                                   (105, '9.7', '2024-07-22 01:42:25', null),
                                                                                   (106, '9.8', '2024-07-22 01:42:25', null),
                                                                                   (107, '9.9', '2024-07-22 01:42:25', null),
                                                                                   (108, '10.0', '2024-07-22 01:42:25', null);

-- --------------------------------------------------------

--
-- Table structure for table `job_location`
--

CREATE TABLE `job_location` (
                                `id_location` int(11) NOT NULL,
                                `location_name` text DEFAULT NULL,
                                `created_at` timestamp NULL DEFAULT current_timestamp() ,
                                 `updated_at` timestamp NULL DEFAULT null ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `job_location`
--

INSERT INTO `job_location` (`id_location`, `location_name`, `created_at`, `updated_at`) VALUES
                                                                                            (3, 'YANGON', '2024-07-22 01:42:31', null),
                                                                                            (4, 'PHNOM PENH', '2024-07-22 01:42:31', null),
                                                                                            (5, 'MALAYSIA', '2024-07-22 01:42:31', null),
                                                                                            (6, 'SINGAPORE', '2024-07-22 01:42:31', null);

-- --------------------------------------------------------

--
-- Table structure for table `job_position`
--

CREATE TABLE `job_position` (
                                `id_position` int(11) NOT NULL,
                                `position_name` text DEFAULT NULL,
                                `created_at` timestamp NULL DEFAULT current_timestamp() ,
                                 `updated_at` timestamp NULL DEFAULT null ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `job_position`
--

INSERT INTO `job_position` (`id_position`, `position_name`, `created_at`, `updated_at`) VALUES
                                                                                            (1, 'System Analyst', '2024-07-22 01:42:37', null),
                                                                                            (2, 'Software Developer', '2024-07-22 01:42:37', null),
                                                                                            (3, 'Project Manager', '2024-07-22 01:42:37', null),
                                                                                            (4, 'Network Infrastructure Specialist', '2024-07-22 01:42:37', null),
                                                                                            (5, 'Information Security Specialist', '2024-07-22 01:42:37', null),
                                                                                            (6, 'Database Specialist', '2024-07-22 01:42:37', null),
                                                                                            (7, 'Helpdesk/Desktop Support Specialist', '2024-07-22 01:42:37', null),
                                                                                            (8, 'Data Analyst', '2024-07-22 01:42:37', null),
                                                                                            (9, 'CEO', '2024-07-22 01:42:37', null),
                                                                                            (10, 'none', '2024-07-22 01:42:37', null);

-- --------------------------------------------------------

--
-- Table structure for table `job_team`
--

CREATE TABLE `job_team` (
                            `id_team` int(11) NOT NULL,
                            `team_name` text DEFAULT NULL,
                            `created_at` timestamp NULL DEFAULT current_timestamp() ,
                             `updated_at` timestamp NULL DEFAULT null ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `job_team`
--

INSERT INTO `job_team` (`id_team`, `team_name`, `created_at`, `updated_at`) VALUES
                                                                                (12, '1.POD', '2024-07-22 01:42:44', null),
                                                                                (13, '2.SST', '2024-07-22 01:42:44', null),
                                                                                (14, '3.PLD', '2024-07-22 01:42:44', null),
                                                                                (15, '4.FID', '2024-07-22 01:42:44', null),
                                                                                (16, '5.ITS', '2024-07-22 01:42:44', null),
                                                                                (17, '6.CDT', '2024-07-22 01:42:44', null),
                                                                                (18, '7.ICT', '2024-07-22 01:42:44', null),
                                                                                (19, 'none', '2024-07-22 01:42:44', null);

-- --------------------------------------------------------

--
-- Table structure for table `job_type_contract`
--

CREATE TABLE `job_type_contract` (
                                     `id_type_contract` int(11) NOT NULL,
                                     `type_contract_name` text DEFAULT NULL,
                                     `created_at` timestamp NULL DEFAULT current_timestamp() ,
                                      `updated_at` timestamp NULL DEFAULT null ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `job_type_contract`
--

INSERT INTO `job_type_contract` (`id_type_contract`, `type_contract_name`, `created_at`, `updated_at`) VALUES
                                                                                                           (1, 'Fixed-Term Contract', '2024-07-22 01:42:50', null),
                                                                                                           (2, 'Permanent Contract', '2024-07-22 01:42:50', null),
                                                                                                           (3, 'Probationary Contract', '2024-07-22 01:42:50', null),
                                                                                                           (4, 'Resignation/Leaving Contract', '2024-07-22 01:42:50', null),
                                                                                                           (5, 'Intern', '2024-07-22 01:42:50', null);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
    ADD PRIMARY KEY (`id_account`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
    ADD PRIMARY KEY (`id_certificate`);

--
-- Indexes for table `certificate_type`
--
ALTER TABLE `certificate_type`
    ADD PRIMARY KEY (`id_certificate_type`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
    ADD PRIMARY KEY (`id_contact`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
    ADD PRIMARY KEY (`id_employee`),
    ADD UNIQUE KEY `employees_pk` (`id_contact`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
    ADD PRIMARY KEY (`id_history`);

--
-- Indexes for table `job_category`
--
ALTER TABLE `job_category`
    ADD PRIMARY KEY (`id_job_category`);

--
-- Indexes for table `job_country`
--
ALTER TABLE `job_country`
    ADD PRIMARY KEY (`id_country`);

--
-- Indexes for table `job_detail`
--
ALTER TABLE `job_detail`
    ADD PRIMARY KEY (`id_job_detail`);

--
-- Indexes for table `job_level`
--
ALTER TABLE `job_level`
    ADD PRIMARY KEY (`id_level`);

--
-- Indexes for table `job_location`
--
ALTER TABLE `job_location`
    ADD PRIMARY KEY (`id_location`);

--
-- Indexes for table `job_position`
--
ALTER TABLE `job_position`
    ADD PRIMARY KEY (`id_position`);

--
-- Indexes for table `job_team`
--
ALTER TABLE `job_team`
    ADD PRIMARY KEY (`id_team`);

--
-- Indexes for table `job_type_contract`
--
ALTER TABLE `job_type_contract`
    ADD PRIMARY KEY (`id_type_contract`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
    MODIFY `id_account` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
    MODIFY `id_certificate` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `certificate_type`
--
ALTER TABLE `certificate_type`
    MODIFY `id_certificate_type` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
    MODIFY `id_contact` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
    MODIFY `id_employee` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
    MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_category`
--
ALTER TABLE `job_category`
    MODIFY `id_job_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `job_country`
--
ALTER TABLE `job_country`
    MODIFY `id_country` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `job_detail`
--
ALTER TABLE `job_detail`
    MODIFY `id_job_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_level`
--
ALTER TABLE `job_level`
    MODIFY `id_level` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `job_location`
--
ALTER TABLE `job_location`
    MODIFY `id_location` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `job_position`
--
ALTER TABLE `job_position`
    MODIFY `id_position` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `job_team`
--
ALTER TABLE `job_team`
    MODIFY `id_team` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `job_type_contract`
--
ALTER TABLE `job_type_contract`
    MODIFY `id_type_contract` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

