-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 25, 2024 at 12:08 AM
-- Server version: 11.3.2-MariaDB-1:11.3.2+maria~ubu2204
-- PHP Version: 8.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistema`
--

-- --------------------------------------------------------

--
-- Table structure for table `assoc`
--

CREATE TABLE `assoc` (
  `AlunoID` char(16) NOT NULL,
  `RespCPF` char(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `assoc`
--

INSERT INTO `assoc` (`AlunoID`, `RespCPF`) VALUES
('112384', '12345678909'),
('112385', '12345678909'),
('132347', '12345678909'),
('132349', '12345678909'),
('132350', '12345678909'),
('132351', '12345678909'),
('132352', '12345678909'),
('132353', '12345678909'),
('132354', '12345678909'),
('132355', '12345678909'),
('132356', '12345678909'),
('132457', '12345678909'),
('132458', '12345678909'),
('232356', '12345678909'),
('232357', '12345678909');

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `auth_groups_users`
--

INSERT INTO `auth_groups_users` (`id`, `user_id`, `group`, `created_at`) VALUES
(1, 1, 'superadmin', '2024-05-24 16:24:27'),
(2, 2, 'user', '2024-05-24 16:29:49');

-- --------------------------------------------------------

--
-- Table structure for table `auth_identities`
--

CREATE TABLE `auth_identities` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `secret` varchar(255) NOT NULL,
  `secret2` varchar(255) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `extra` text DEFAULT NULL,
  `force_reset` tinyint(1) NOT NULL DEFAULT 0,
  `last_used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `auth_identities`
--

INSERT INTO `auth_identities` (`id`, `user_id`, `type`, `name`, `secret`, `secret2`, `expires`, `extra`, `force_reset`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'email_password', NULL, 'admin@admin.com', '$2y$12$7CKwtX6Rg4/9PjyNObxLBOVg8UJsRIdWQCsteU2B9RipXWUAdlQny', NULL, NULL, 0, '2024-05-24 20:04:57', '2024-05-24 16:24:01', '2024-05-24 20:04:57'),
(2, 2, 'email_password', NULL, 'ex@emplo.com', '$2y$12$kfl/ueD3BE3PKtx3Q1SOy.Glcpt3318BenFeuvRNF2f2KN1aBbPmO', NULL, NULL, 0, '2024-05-24 16:40:56', '2024-05-24 16:29:48', '2024-05-24 16:40:56');

-- --------------------------------------------------------

--
-- Table structure for table `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `ip_address`, `user_agent`, `id_type`, `identifier`, `user_id`, `date`, `success`) VALUES
(1, '10.0.2.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/115.0', 'email_password', 'admin@admin.com', 1, '2024-05-24 16:24:42', 1),
(2, '10.0.2.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/115.0', 'email_password', 'ex@emplo.com', 2, '2024-05-24 16:30:13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions_users`
--

CREATE TABLE `auth_permissions_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `permission` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_remember_tokens`
--

CREATE TABLE `auth_remember_tokens` (
  `id` int(11) UNSIGNED NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_token_logins`
--

CREATE TABLE `auth_token_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cad_alunos`
--

CREATE TABLE `cad_alunos` (
  `Matr` char(16) NOT NULL,
  `Aluno` varchar(255) NOT NULL,
  `Turma` varchar(16) NOT NULL,
  `Tag_ID` char(16) NOT NULL,
  `Tag_exists` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cad_alunos`
--

INSERT INTO `cad_alunos` (`Matr`, `Aluno`, `Turma`, `Tag_ID`, `Tag_exists`) VALUES
('112384', 'Ana Clara Ferreira', 'INF1 2022', '323496A9', 1),
('112385', 'Gabriel Oliveira Rocha', 'INF1 2022', '323496B0', 1),
('132347', 'Maria Clara de Souza', 'INF1 2022', '123456A2', 0),
('132349', 'Ana Luiza Correia', 'INF1 2022', '123456A4', 1),
('132350', 'Felipe Cardoso Pinto', 'INF1 2022', '123456A5', 1),
('132351', 'Beatriz Rocha Silva', 'INF1 2022', '123456A6', 1),
('132352', 'Guilherme Sousa Costa', 'INF1 2022', '123456A7', 1),
('132353', 'Laura Rodrigues Melo', 'INF1 2022', '123456A8', 1),
('132354', 'Bruno Ferreira Barbosa', 'INF1 2022', '123456A9', 0),
('132355', 'Camila Dias Lopes', 'INF1 2022', '123456B1', 0),
('132356', 'Rodrigo Almeida Santos', 'INF1 2022', '123456B2', 1),
('132457', 'Lucas Pereira Lima', 'ADM1 2022', '423456B3', 1),
('132458', 'Julia Castro Alves', 'ADM1 2022', '423456B4', 1),
('232356', 'João Silva Costa', 'INF1 2023', '223656B2', 0),
('232357', 'Beatriz Souza Martins', 'INF1 2023', '223656B3', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cad_resp`
--

CREATE TABLE `cad_resp` (
  `CPF` char(11) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `NomeCompleto` varchar(255) DEFAULT NULL,
  `shield_id` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cad_resp`
--

INSERT INTO `cad_resp` (`CPF`, `Email`, `NomeCompleto`, `shield_id`) VALUES
('12345678909', 'ex@emplo.com', 'Lucas Martins Ribeiro', 2);

-- --------------------------------------------------------

--
-- Table structure for table `eventos`
--

CREATE TABLE `eventos` (
  `EventID` int(11) NOT NULL,
  `AlunoID` char(16) DEFAULT NULL,
  `DataHora` timestamp NULL DEFAULT NULL,
  `Tipo` enum('Entrada','Saída') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `eventos`
--

INSERT INTO `eventos` (`EventID`, `AlunoID`, `DataHora`, `Tipo`) VALUES
(1, '132347', '2024-05-23 19:48:07', 'Entrada'),
(2, '132347', '2024-05-24 07:48:07', 'Saída'),
(5, '132349', '2024-05-21 19:48:07', 'Entrada'),
(6, '132349', '2024-05-22 19:48:07', 'Saída'),
(7, '132350', '2024-05-20 19:48:07', 'Entrada'),
(8, '132350', '2024-05-21 19:48:07', 'Saída'),
(9, '132351', '2024-05-19 19:48:07', 'Entrada'),
(10, '132351', '2024-05-20 19:48:07', 'Saída'),
(11, '132352', '2024-05-18 19:48:07', 'Entrada'),
(12, '132352', '2024-05-19 19:48:07', 'Saída'),
(13, '132353', '2024-05-17 19:48:07', 'Entrada'),
(14, '132353', '2024-05-18 19:48:07', 'Saída'),
(15, '132354', '2024-05-17 19:48:07', 'Entrada'),
(16, '232356', '2024-05-24 08:00:00', 'Entrada'),
(17, '232356', '2024-05-24 17:00:00', 'Saída'),
(18, '112384', '2024-05-23 08:30:00', 'Entrada'),
(19, '112384', '2024-05-23 16:30:00', 'Saída'),
(20, '132457', '2024-05-22 09:00:00', 'Entrada'),
(21, '132457', '2024-05-22 18:00:00', 'Saída'),
(22, '232357', '2024-05-21 08:15:00', 'Entrada'),
(23, '232357', '2024-05-21 16:45:00', 'Saída'),
(24, '112385', '2024-05-20 08:45:00', 'Entrada'),
(25, '112385', '2024-05-20 17:15:00', 'Saída'),
(26, '132458', '2024-05-19 09:30:00', 'Entrada'),
(27, '132458', '2024-05-19 18:30:00', 'Saída'),
(28, '232356', '2024-05-23 08:00:00', 'Entrada'),
(29, '232356', '2024-05-23 17:00:00', 'Saída'),
(30, '232356', '2024-05-22 08:00:00', 'Entrada'),
(31, '232356', '2024-05-22 17:00:00', 'Saída'),
(32, '112384', '2024-05-22 08:30:00', 'Entrada'),
(33, '112384', '2024-05-22 16:30:00', 'Saída'),
(34, '112384', '2024-05-21 08:30:00', 'Entrada'),
(35, '112384', '2024-05-21 16:30:00', 'Saída'),
(36, '132457', '2024-05-21 09:00:00', 'Entrada'),
(37, '132457', '2024-05-21 18:00:00', 'Saída'),
(38, '132457', '2024-05-20 09:00:00', 'Entrada'),
(39, '132457', '2024-05-20 18:00:00', 'Saída'),
(40, '232357', '2024-05-20 08:15:00', 'Entrada'),
(41, '232357', '2024-05-20 16:45:00', 'Saída'),
(42, '232357', '2024-05-19 08:15:00', 'Entrada'),
(43, '232357', '2024-05-19 16:45:00', 'Saída'),
(44, '112385', '2024-05-19 08:45:00', 'Entrada'),
(45, '112385', '2024-05-19 17:15:00', 'Saída'),
(46, '112385', '2024-05-18 08:45:00', 'Entrada'),
(47, '112385', '2024-05-18 17:15:00', 'Saída'),
(48, '132458', '2024-05-18 09:30:00', 'Entrada'),
(49, '132458', '2024-05-18 18:30:00', 'Saída'),
(50, '132458', '2024-05-17 09:30:00', 'Entrada'),
(51, '132458', '2024-05-17 18:30:00', 'Saída');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2020-12-28-223112', 'CodeIgniter\\Shield\\Database\\Migrations\\CreateAuthTables', 'default', 'CodeIgniter\\Shield', 1716578579, 1),
(2, '2021-07-04-041948', 'CodeIgniter\\Settings\\Database\\Migrations\\CreateSettingsTable', 'default', 'CodeIgniter\\Settings', 1716578579, 1),
(3, '2021-11-14-143905', 'CodeIgniter\\Settings\\Database\\Migrations\\AddContextColumn', 'default', 'CodeIgniter\\Settings', 1716578579, 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(9) NOT NULL,
  `class` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(31) NOT NULL DEFAULT 'string',
  `context` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `status`, `status_message`, `active`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', NULL, NULL, 0, '2024-05-24 21:06:39', '2024-05-24 16:24:00', '2024-05-24 16:24:00', NULL),
(2, '12345678909', NULL, NULL, 0, '2024-05-24 16:40:56', '2024-05-24 16:29:48', '2024-05-24 16:29:48', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_groups_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_secret` (`type`,`secret`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_permissions_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `auth_remember_tokens_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cad_alunos`
--
ALTER TABLE `cad_alunos`
  ADD PRIMARY KEY (`Matr`),
  ADD UNIQUE KEY `Tag_ID` (`Tag_ID`);

--
-- Indexes for table `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`EventID`),
  ADD KEY `AlunoID` (`AlunoID`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `auth_identities`
--
ALTER TABLE `auth_identities`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eventos`
--
ALTER TABLE `eventos`
  MODIFY `EventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD CONSTRAINT `auth_identities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD CONSTRAINT `auth_permissions_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD CONSTRAINT `auth_remember_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`AlunoID`) REFERENCES `cad_alunos` (`Matr`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
