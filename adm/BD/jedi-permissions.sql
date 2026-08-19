-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mariadb_db:3306
-- Tempo de geração: 18/08/2026 às 11:43
-- Versão do servidor: 11.4.2-MariaDB-ubu2404
-- Versão do PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `jedi-permissions`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_group`
--

CREATE TABLE `system_group` (
  `id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `system_group`
--

INSERT INTO `system_group` (`id`, `name`) VALUES
(1, 'Template - Admin'),
(2, 'Template - Users'),
(3, 'Application - Programs'),
(4, 'Gestor - JEDi Educa'),
(5, 'docente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_group_program`
--

CREATE TABLE `system_group_program` (
  `id` int(11) NOT NULL,
  `system_group_id` int(11) DEFAULT NULL,
  `system_program_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `system_group_program`
--

INSERT INTO `system_group_program` (`id`, `system_group_id`, `system_program_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 1, 16),
(17, 1, 17),
(18, 1, 18),
(19, 1, 19),
(20, 1, 20),
(21, 1, 21),
(22, 1, 22),
(23, 1, 23),
(24, 1, 24),
(25, 1, 25),
(26, 1, 26),
(27, 1, 27),
(28, 1, 28),
(29, 2, 29),
(30, 2, 30),
(31, 2, 31),
(32, 2, 32),
(33, 2, 33),
(34, 2, 34),
(35, 2, 35),
(36, 2, 36),
(37, 2, 37),
(38, 1, 38),
(39, 1, 39),
(40, 1, 40),
(41, 1, 41),
(42, 1, 42),
(43, 1, 43),
(44, 1, 44),
(45, 1, 45),
(46, 2, 46),
(47, 2, 47),
(48, 2, 48),
(49, 2, 49),
(50, 2, 50),
(51, 2, 51),
(52, 2, 52),
(53, 2, 53),
(54, 2, 54),
(55, 2, 55),
(56, 2, 56),
(57, 2, 57),
(58, 2, 58),
(59, 2, 59),
(60, 2, 60),
(61, 2, 61),
(62, 2, 62),
(63, 2, 63),
(64, 2, 64),
(65, 1, 65),
(66, 1, 66),
(67, 1, 67),
(71, 1, 69),
(76, 1, 68),
(77, 1, 70),
(78, 1, 71),
(80, 1, 73),
(81, 1, 72),
(82, 1, 74),
(83, 1, 75),
(84, 1, 76),
(85, 1, 77),
(86, 1, 78),
(89, 1, 79),
(90, 1, 80),
(91, 1, 81),
(92, 1, 82),
(93, 1, 83),
(94, 4, 65),
(95, 4, 66),
(96, 4, 67),
(97, 4, 68),
(98, 4, 69),
(99, 4, 70),
(100, 4, 71),
(101, 4, 72),
(102, 4, 73),
(103, 4, 75),
(104, 4, 76),
(105, 4, 77),
(106, 4, 78),
(107, 4, 79),
(108, 4, 80),
(109, 4, 81),
(110, 4, 82),
(111, 4, 83),
(112, 5, 48),
(113, 5, 65),
(114, 5, 66),
(115, 5, 67),
(116, 5, 68),
(117, 5, 69),
(118, 5, 70),
(119, 5, 71),
(120, 5, 72),
(121, 5, 73),
(122, 5, 74),
(123, 5, 75),
(124, 5, 76),
(125, 5, 77),
(126, 5, 78),
(127, 5, 79),
(128, 5, 80),
(129, 5, 81),
(130, 5, 82),
(131, 5, 83);

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_preference`
--

CREATE TABLE `system_preference` (
  `id` varchar(256) DEFAULT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_program`
--

CREATE TABLE `system_program` (
  `id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `controller` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `system_program`
--

INSERT INTO `system_program` (`id`, `name`, `controller`) VALUES
(1, 'System Administration Dashboard', 'SystemAdministrationDashboard'),
(2, 'System Program Form', 'SystemProgramForm'),
(3, 'System Program List', 'SystemProgramList'),
(4, 'System Group Form', 'SystemGroupForm'),
(5, 'System Group List', 'SystemGroupList'),
(6, 'System Unit Form', 'SystemUnitForm'),
(7, 'System Unit List', 'SystemUnitList'),
(8, 'System Role Form', 'SystemRoleForm'),
(9, 'System Role List', 'SystemRoleList'),
(10, 'System User Form', 'SystemUserForm'),
(11, 'System User List', 'SystemUserList'),
(12, 'System Preference form', 'SystemPreferenceForm'),
(13, 'System Log Dashboard', 'SystemLogDashboard'),
(14, 'System Access Log', 'SystemAccessLogList'),
(15, 'System ChangeLog View', 'SystemChangeLogView'),
(16, 'System Sql Log', 'SystemSqlLogList'),
(17, 'System Request Log', 'SystemRequestLogList'),
(18, 'System Request Log View', 'SystemRequestLogView'),
(19, 'System PHP Error', 'SystemPHPErrorLogView'),
(20, 'System Session vars', 'SystemSessionVarsView'),
(21, 'System Database Browser', 'SystemDatabaseExplorer'),
(22, 'System Table List', 'SystemTableList'),
(23, 'System Data Browser', 'SystemDataBrowser'),
(24, 'System SQL Panel', 'SystemSQLPanel'),
(25, 'System Modules', 'SystemModulesCheckView'),
(26, 'System files diff', 'SystemFilesDiff'),
(27, 'System Information', 'SystemInformationView'),
(28, 'System PHP Info', 'SystemPHPInfoView'),
(29, 'Common Page', 'CommonPage'),
(30, 'Welcome View', 'WelcomeView'),
(31, 'Welcome dashboard', 'WelcomeDashboardView'),
(32, 'System Profile View', 'SystemProfileView'),
(33, 'System Profile Form', 'SystemProfileForm'),
(34, 'System Notification List', 'SystemNotificationList'),
(35, 'System Notification Form View', 'SystemNotificationFormView'),
(36, 'System Support form', 'SystemSupportForm'),
(37, 'System Profile 2FA Form', 'SystemProfile2FAForm'),
(38, 'System Wiki list', 'SystemWikiList'),
(39, 'System Wiki form', 'SystemWikiForm'),
(40, 'System Wiki page picker', 'SystemWikiPagePicker'),
(41, 'System Post list', 'SystemPostList'),
(42, 'System Post form', 'SystemPostForm'),
(43, 'System schedule list', 'SystemScheduleList'),
(44, 'System schedule form', 'SystemScheduleForm'),
(45, 'System schedule log', 'SystemScheduleLogList'),
(46, 'System Message Form', 'SystemMessageForm'),
(47, 'System Message List', 'SystemMessageList'),
(48, 'System Message Form View', 'SystemMessageFormView'),
(49, 'System Documents', 'SystemDriveList'),
(50, 'System Folder form', 'SystemFolderForm'),
(51, 'System Share folder', 'SystemFolderShareForm'),
(52, 'System Share document', 'SystemDocumentShareForm'),
(53, 'System Document properties', 'SystemDocumentFormWindow'),
(54, 'System Folder properties', 'SystemFolderFormView'),
(55, 'System Document upload', 'SystemDriveDocumentUploadForm'),
(56, 'Post View list', 'SystemPostFeedView'),
(57, 'Post Comment form', 'SystemPostCommentForm'),
(58, 'Post Comment list', 'SystemPostCommentList'),
(59, 'System Wiki search', 'SystemWikiSearchList'),
(60, 'System Wiki view', 'SystemWikiView'),
(61, 'System Message Tag form', 'SystemMessageTagForm'),
(62, 'System Contacts list', 'SystemContactsList'),
(63, 'Text document editor', 'SystemTextDocumentEditor'),
(64, 'System document create form', 'SystemDriveDocumentCreateForm'),
(65, 'Association Rules', 'AssociationRulesView'),
(66, 'Apriori View', 'AprioriView'),
(67, 'Association Rules Form', 'AssociationRulesForm'),
(68, 'Statistics Avaliation View', 'StatisticsAvaliationView'),
(69, 'Statistics Avaliation Form', 'StatisticsAvaliationForm'),
(70, 'Statistics Category View', 'StatisticsCategoryView'),
(71, 'Statistics Category Form', 'StatisticsCategoryForm'),
(72, 'Statistics Match School Form', 'StatisticsMatchSchoolForm'),
(73, 'Statistics Match School View', 'StatisticsMatchSchoolView'),
(74, 'Jedi Educa Rest Data View', 'JediEducaRestDataView'),
(75, 'Distribution News Category View', 'DistributionNewsCategoryView'),
(76, 'Distribution News Category Form', 'DistributionNewsCategoryForm'),
(77, 'Textual Characteristics News View', 'TextualCharacteristicsNewsView'),
(78, 'Textual Characteristics News Form', 'TextualCharacteristicsNewsForm'),
(79, 'Class Profile Form', 'ClassProfileForm'),
(80, 'Class Profile View', 'ClassProfileView'),
(81, 'Match Summary Form', 'MatchSummaryForm'),
(82, 'Match Summary View', 'MatchSummaryView'),
(83, 'Cloud Word View', 'CloudWordView');

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_program_method_role`
--

CREATE TABLE `system_program_method_role` (
  `id` int(11) NOT NULL,
  `system_program_id` int(11) DEFAULT NULL,
  `system_role_id` int(11) DEFAULT NULL,
  `method_name` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_role`
--

CREATE TABLE `system_role` (
  `id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `custom_code` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `system_role`
--

INSERT INTO `system_role` (`id`, `name`, `custom_code`) VALUES
(1, 'Role A', ''),
(2, 'Role B', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_unit`
--

CREATE TABLE `system_unit` (
  `id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `connection_name` varchar(256) DEFAULT NULL,
  `custom_code` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `system_unit`
--

INSERT INTO `system_unit` (`id`, `name`, `connection_name`, `custom_code`) VALUES
(1, 'Unit A', 'unit_a', NULL),
(2, 'Unit B', 'unit_b', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_users`
--

CREATE TABLE `system_users` (
  `id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `login` varchar(256) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `accepted_term_policy` char(1) DEFAULT NULL,
  `phone` varchar(256) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `function_name` varchar(256) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `accepted_term_policy_at` varchar(20) DEFAULT NULL,
  `accepted_term_policy_data` text DEFAULT NULL,
  `frontpage_id` int(11) DEFAULT NULL,
  `system_unit_id` int(11) DEFAULT NULL,
  `active` char(1) DEFAULT NULL,
  `custom_code` varchar(256) DEFAULT NULL,
  `otp_secret` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `system_users`
--

INSERT INTO `system_users` (`id`, `name`, `login`, `password`, `email`, `accepted_term_policy`, `phone`, `address`, `function_name`, `about`, `accepted_term_policy_at`, `accepted_term_policy_data`, `frontpage_id`, `system_unit_id`, `active`, `custom_code`, `otp_secret`) VALUES
(1, 'Administrator', 'admin', '$2y$10$xuR3XEc3J6tpv7myC9gPj.Ab5GacSeHSZoYUTYtOg.cEc22G.iBwa', 'admin@admin.net', 'Y', '+123 456 789', 'Admin Street, 123', 'Administrator', 'I\'m the administrator', NULL, NULL, 30, NULL, 'Y', NULL, NULL),
(2, 'User', 'user', '$2y$10$MUYN29LOSHrCSGhrzvYG8O/PtAjbWvCubaUSTJGhVTJhm69WNFJs.', 'user@user.net', 'Y', '+123 456 789', 'User Street, 123', 'End user', 'I\'m the end user', NULL, NULL, 30, NULL, 'Y', NULL, NULL),
(3, 'Gestor - JEDi Educa', 'gestor.jedi', '$2y$10$O5LKH3NvKykRugjD.MAgpevQFEqRBGOEEXWy0/3OdONuusVhuACka', 'contato@jedieduca.com.br', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', NULL, NULL),
(4, 'Professor(a) do Colégio Estadual Almirante Tamandaré', 'professor', '$2y$10$zZlEOfCp0iVciO96gmEKfu20MhJttRSMf3JlVLaX73S3pMrd5meQm', 'professor.almirante@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_user_group`
--

CREATE TABLE `system_user_group` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) DEFAULT NULL,
  `system_group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `system_user_group`
--

INSERT INTO `system_user_group` (`id`, `system_user_id`, `system_group_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 2, 2),
(5, 3, 4),
(6, 4, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_user_old_password`
--

CREATE TABLE `system_user_old_password` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `created_at` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_user_program`
--

CREATE TABLE `system_user_program` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) DEFAULT NULL,
  `system_program_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_user_role`
--

CREATE TABLE `system_user_role` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) DEFAULT NULL,
  `system_role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `system_user_unit`
--

CREATE TABLE `system_user_unit` (
  `id` int(11) NOT NULL,
  `system_user_id` int(11) DEFAULT NULL,
  `system_unit_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `system_user_unit`
--

INSERT INTO `system_user_unit` (`id`, `system_user_id`, `system_unit_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1),
(4, 2, 2);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `system_group`
--
ALTER TABLE `system_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_group_name_idx` (`name`(250));

--
-- Índices de tabela `system_group_program`
--
ALTER TABLE `system_group_program`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_group_program_program_idx` (`system_program_id`),
  ADD KEY `sys_group_program_group_idx` (`system_group_id`);

--
-- Índices de tabela `system_preference`
--
ALTER TABLE `system_preference`
  ADD KEY `sys_preference_id_idx` (`id`(250));

--
-- Índices de tabela `system_program`
--
ALTER TABLE `system_program`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_program_name_idx` (`name`(250)),
  ADD KEY `sys_program_controller_idx` (`controller`(250));

--
-- Índices de tabela `system_program_method_role`
--
ALTER TABLE `system_program_method_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_program_method_role_program_idx` (`system_program_id`),
  ADD KEY `sys_program_method_role_role_idx` (`system_role_id`);

--
-- Índices de tabela `system_role`
--
ALTER TABLE `system_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_role_name_idx` (`name`(250));

--
-- Índices de tabela `system_unit`
--
ALTER TABLE `system_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_unit_name_idx` (`name`(250));

--
-- Índices de tabela `system_users`
--
ALTER TABLE `system_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_user_program_idx` (`frontpage_id`),
  ADD KEY `sys_users_name_idx` (`name`(250));

--
-- Índices de tabela `system_user_group`
--
ALTER TABLE `system_user_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_user_group_group_idx` (`system_group_id`),
  ADD KEY `sys_user_group_user_idx` (`system_user_id`);

--
-- Índices de tabela `system_user_old_password`
--
ALTER TABLE `system_user_old_password`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_user_old_password_user_idx` (`system_user_id`);

--
-- Índices de tabela `system_user_program`
--
ALTER TABLE `system_user_program`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_user_program_program_idx` (`system_program_id`),
  ADD KEY `sys_user_program_user_idx` (`system_user_id`);

--
-- Índices de tabela `system_user_role`
--
ALTER TABLE `system_user_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_user_role_user_idx` (`system_user_id`),
  ADD KEY `sys_user_role_role_idx` (`system_role_id`);

--
-- Índices de tabela `system_user_unit`
--
ALTER TABLE `system_user_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sys_user_unit_user_idx` (`system_user_id`),
  ADD KEY `sys_user_unit_unit_idx` (`system_unit_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
