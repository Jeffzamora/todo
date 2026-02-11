-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+deb12u1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 11-02-2026 a las 02:38:28
-- Versión del servidor: 10.11.14-MariaDB-0+deb12u2
-- Versión de PHP: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `todo_pro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `audit_log`
--

CREATE TABLE `audit_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(30) NOT NULL,
  `entity` varchar(60) NOT NULL,
  `entity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `meta` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `audit_log`
--

INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity`, `entity_id`, `ip`, `user_agent`, `meta`, `created_at`) VALUES
(412, NULL, 'login_failed', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"demo\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-19 21:23:06'),
(413, 1, 'login', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"demo\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-01-19 21:23:17'),
(414, NULL, 'login_failed', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"admin\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-19 21:41:57'),
(415, NULL, 'login_failed', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"admin\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-19 21:42:08'),
(416, NULL, 'login_failed', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"admin\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-19 21:42:24'),
(417, 1, 'login', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"demo\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-01-19 21:43:05'),
(418, NULL, 'login_failed', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"admin\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-19 21:51:51'),
(419, NULL, 'login_failed', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"mayrab\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-19 21:52:10'),
(420, NULL, 'login_failed', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"mayrab\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-19 21:53:47'),
(421, NULL, 'login_failed', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"mayrab\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-19 21:54:13'),
(422, NULL, 'login_failed', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"Mayrab\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-19 21:54:37'),
(423, NULL, 'login_failed', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"mayrab\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-19 21:54:58'),
(424, 3, 'login', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"admin\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-01-21 09:45:51'),
(425, 3, 'login', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"admin\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-01-21 10:09:21'),
(426, 3, 'create', 'projects', 9, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"payload\":{\"nombre\":\"limpiar carro\",\"icono\":\"🛠️\",\"color\":\"#3B82F6\",\"orden\":1},\"path\":\"/todo/public/projects\",\"method\":\"POST\"}', '2026-01-21 10:09:38'),
(427, 3, 'create', 'projects', 10, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"payload\":{\"nombre\":\"vender tosco\",\"icono\":\"🛠️\",\"color\":\"#06B6D4\",\"orden\":0},\"path\":\"/todo/public/projects\",\"method\":\"POST\"}', '2026-01-21 10:09:59'),
(428, 3, 'create', 'tasks', 17, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"data_keys\":[\"titulo\",\"descripcion\",\"estado\",\"project_id\"]}', '2026-01-21 10:10:53'),
(429, 3, 'create', 'tasks', 18, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"data_keys\":[\"titulo\",\"descripcion\",\"estado\",\"project_id\"]}', '2026-01-21 10:11:04'),
(430, 3, 'create', 'tasks', 19, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"data_keys\":[\"titulo\",\"descripcion\",\"estado\",\"project_id\"]}', '2026-01-21 10:11:15'),
(431, 3, 'create_reminder', 'tasks', 18, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"reminder_id\":17,\"remind_at\":\"2026-01-21 07:18:00\"}', '2026-01-21 10:13:21'),
(432, 3, 'create', 'task_recurring', 20, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"payload\":{\"task_id\":18,\"rule\":{\"freq\":\"daily\",\"interval\":1,\"timezone\":\"America/Los_Angeles\",\"at_time\":\"08:00:00\",\"create\":{\"copy_tags\":true}}},\"path\":\"/todo/public/recurring\",\"method\":\"POST\"}', '2026-01-21 10:13:34'),
(433, 3, 'update', 'task_recurring', 20, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"changed\":[\"is_active\"],\"path\":\"/todo/public/recurring/20\",\"method\":\"PATCH\"}', '2026-01-21 10:13:52'),
(434, 3, 'create', 'tasks', 20, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"data_keys\":[\"titulo\",\"descripcion\",\"estado\",\"project_id\"]}', '2026-01-21 10:14:46'),
(435, 3, 'create', 'tasks', 21, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"data_keys\":[\"titulo\",\"descripcion\",\"estado\",\"project_id\"]}', '2026-01-21 10:15:37'),
(436, 3, 'create', 'tasks', 22, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"data_keys\":[\"titulo\",\"descripcion\",\"estado\",\"project_id\"]}', '2026-01-21 10:16:06'),
(437, 3, 'create_reminder', 'tasks', 22, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"reminder_id\":18,\"remind_at\":\"2026-01-21 06:25:00\"}', '2026-01-21 10:17:56'),
(438, 3, 'create', 'task_recurring', 21, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"payload\":{\"task_id\":22,\"rule\":{\"freq\":\"daily\",\"interval\":1,\"timezone\":\"America/Los_Angeles\",\"at_time\":\"08:00:00\",\"create\":{\"copy_tags\":true}}},\"path\":\"/todo/public/recurring\",\"method\":\"POST\"}', '2026-01-21 10:18:18'),
(439, 3, 'login', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"admin\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-01-21 10:30:54'),
(440, NULL, 'login_failed', 'auth', NULL, '186.77.136.32', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"admin\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-21 17:40:53'),
(441, NULL, 'login_failed', 'auth', NULL, '186.77.136.32', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"admin\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-21 17:41:21'),
(442, NULL, 'login_failed', 'auth', NULL, '186.77.136.32', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"admin123\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-21 17:42:10'),
(443, NULL, 'login_failed', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"maritza\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-21 18:31:27'),
(444, 5, 'login', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"maritzar\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-01-21 18:33:22'),
(445, 3, 'login', 'auth', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"admin\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-01-21 19:00:35'),
(446, 5, 'login', 'auth', NULL, '186.77.137.224', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"maritzar\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-01-21 19:15:40'),
(447, 3, 'login', 'auth', NULL, '174.195.195.241', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"admin\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-01-21 21:54:50'),
(448, 3, 'status', 'tasks', 22, '174.195.195.241', 'Dart/3.8 (dart:io)', '{\"status\":\"done\",\"path\":\"/todo/public/tasks/22/status\",\"method\":\"PATCH\"}', '2026-01-21 21:55:32'),
(449, NULL, 'login_failed', 'auth', NULL, '174.195.198.14', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"admin\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-23 03:25:47'),
(450, 3, 'login', 'auth', NULL, '174.195.198.14', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"admin\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-01-23 03:25:55'),
(451, NULL, 'login_failed', 'auth', NULL, '174.195.129.62', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"admin@devzamora.com\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-01-28 15:24:04'),
(452, 4, 'login', 'auth', NULL, '172.56.178.88', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"mayrab\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-01-29 01:51:51'),
(453, 4, 'create', 'tasks', 23, '172.56.178.88', 'Dart/3.8 (dart:io)', '{\"data_keys\":[\"titulo\",\"descripcion\",\"estado\",\"project_id\"]}', '2026-01-29 01:52:45'),
(454, 3, 'login', 'auth', NULL, '174.193.130.213', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"admin@devzamora.com\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-01-29 10:18:08'),
(455, 3, 'status', 'tasks', 19, '174.193.130.213', 'Dart/3.8 (dart:io)', '{\"status\":\"done\",\"path\":\"/todo/public/tasks/19/status\",\"method\":\"PATCH\"}', '2026-01-29 10:18:21'),
(456, 3, 'status', 'tasks', 17, '174.193.130.213', 'Dart/3.8 (dart:io)', '{\"status\":\"done\",\"path\":\"/todo/public/tasks/17/status\",\"method\":\"PATCH\"}', '2026-01-29 10:18:37'),
(457, 3, 'status', 'tasks', 18, '174.193.130.213', 'Dart/3.8 (dart:io)', '{\"status\":\"done\",\"path\":\"/todo/public/tasks/18/status\",\"method\":\"PATCH\"}', '2026-01-29 10:18:44'),
(458, 3, 'create', 'shopping_templates', 7, '174.193.130.213', 'Dart/3.8 (dart:io)', '{\"payload\":{\"nombre\":\"supermercado fitness\"},\"path\":\"/todo/public/shopping/templates\",\"method\":\"POST\"}', '2026-01-29 10:19:10'),
(459, 3, 'create', 'shopping_lists', 15, '174.193.130.213', 'Dart/3.8 (dart:io)', '{\"from_template_id\":7,\"payload\":{\"nombre\":\"fitn\"},\"path\":\"/todo/public/shopping/templates/7/create-list\",\"method\":\"POST\"}', '2026-01-29 10:19:25'),
(460, 3, 'create', 'shopping_template_items', 7, '174.193.130.213', 'Dart/3.8 (dart:io)', '{\"template_id\":7,\"payload\":{\"nombre\":\"pollo\",\"cantidad\":null,\"precio_estimado\":30,\"precio_real\":null,\"unidad\":\"libra\",\"categoria\":\"carnes\",\"prioridad\":\"normal\"},\"path\":\"/todo/public/shopping/templates/7/items\",\"method\":\"POST\"}', '2026-01-29 10:20:24'),
(461, 3, 'update', 'shopping_template_items', 7, '174.193.130.213', 'Dart/3.8 (dart:io)', '{\"template_id\":7,\"changed\":[\"nombre\",\"cantidad\",\"precio_estimado\",\"precio_real\",\"unidad\",\"categoria\",\"prioridad\"],\"path\":\"/todo/public/shopping/templates/7/items/7\",\"method\":\"PATCH\"}', '2026-01-29 10:22:55'),
(462, 3, 'create', 'pantry_items', 10, '174.193.130.213', 'Dart/3.8 (dart:io)', '{\"payload\":{\"nombre\":\"pollo\",\"stock\":0,\"unidad\":\"libra\",\"min_stock\":0},\"path\":\"/todo/public/pantry\",\"method\":\"POST\"}', '2026-01-29 10:23:25'),
(463, 4, 'login', 'auth', NULL, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"mayrab\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-02-09 22:03:34'),
(464, 4, 'create', 'shopping_lists', 16, '172.56.233.15', 'Dart/3.8 (dart:io)', '{\"nombre\":\"mayra\"}', '2026-02-09 22:04:04'),
(465, 4, 'create', 'shopping_items', 19, '172.56.232.139', 'Dart/3.8 (dart:io)', '{\"list_id\":16,\"nombre\":\"multivitaminico\"}', '2026-02-09 22:05:03'),
(466, 4, 'create', 'shopping_items', 20, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":16,\"nombre\":\"agua\"}', '2026-02-09 22:05:15'),
(467, 4, 'create', 'shopping_items', 21, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":16,\"nombre\":\"proteína en polvo\"}', '2026-02-09 22:05:29'),
(468, 4, 'create', 'shopping_items', 22, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":16,\"nombre\":\"omega 3 de 1000mg\"}', '2026-02-09 22:05:57'),
(469, 4, 'create', 'shopping_items', 23, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":16,\"nombre\":\"magnesio de 400g\"}', '2026-02-09 22:06:11'),
(470, 4, 'create', 'shopping_items', 24, '172.56.233.73', 'Dart/3.8 (dart:io)', '{\"list_id\":16,\"nombre\":\"vitamina D3 2000ui\"}', '2026-02-09 22:07:40'),
(471, 4, 'create', 'shopping_items', 25, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":16,\"nombre\":\"sal de gimalaya\"}', '2026-02-09 22:08:12'),
(472, 4, 'create', 'shopping_lists', 17, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"nombre\":\"jd\"}', '2026-02-09 22:08:36'),
(473, 4, 'create', 'shopping_items', 26, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":17,\"nombre\":\"multivitaminico\"}', '2026-02-09 22:08:51'),
(474, 4, 'create', 'shopping_items', 27, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":17,\"nombre\":\"omega 3\"}', '2026-02-09 22:08:59'),
(475, 4, 'create', 'shopping_items', 28, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":17,\"nombre\":\"vitamina D3\"}', '2026-02-09 22:09:08'),
(476, 4, 'create', 'shopping_items', 29, '172.56.233.73', 'Dart/3.8 (dart:io)', '{\"list_id\":17,\"nombre\":\"magnesio 400\"}', '2026-02-09 22:09:19'),
(477, 4, 'create', 'shopping_items', 30, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":17,\"nombre\":\"proteina whey\"}', '2026-02-09 22:10:01'),
(478, 4, 'create', 'shopping_items', 31, '172.56.233.73', 'Dart/3.8 (dart:io)', '{\"list_id\":17,\"nombre\":\"zinc\"}', '2026-02-09 22:10:08'),
(479, 4, 'create', 'shopping_lists', 18, '172.56.233.73', 'Dart/3.8 (dart:io)', '{\"nombre\":\"general de comida\"}', '2026-02-09 22:11:08'),
(480, 4, 'create', 'shopping_items', 32, '172.56.233.73', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"arroz integral\"}', '2026-02-09 22:11:32'),
(481, 4, 'create', 'shopping_items', 33, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"quinoa\"}', '2026-02-09 22:12:33'),
(482, 4, 'create', 'shopping_items', 34, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"manzana\"}', '2026-02-09 22:12:49'),
(483, 4, 'create', 'shopping_items', 35, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"naranja\"}', '2026-02-09 22:12:56'),
(484, 4, 'create', 'shopping_items', 36, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"platano\"}', '2026-02-09 22:13:02'),
(485, 4, 'create', 'shopping_items', 37, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"almendras\"}', '2026-02-09 22:14:07'),
(486, 4, 'create', 'shopping_items', 38, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"polla\"}', '2026-02-09 22:15:55'),
(487, 4, 'create', 'shopping_items', 39, '172.56.232.67', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"pescado\"}', '2026-02-09 22:16:24'),
(488, 4, 'create', 'shopping_items', 40, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"pan integral\"}', '2026-02-09 22:16:44'),
(489, 4, 'update', 'shopping_items', 40, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"changed\":[\"nombre\",\"cantidad\",\"unidad\"]}', '2026-02-09 22:17:00'),
(490, 4, 'create', 'shopping_items', 41, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"avena\"}', '2026-02-09 22:17:46'),
(491, 4, 'create', 'shopping_items', 42, '172.56.232.97', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"yogurt  griego\"}', '2026-02-09 22:17:55'),
(492, 4, 'create', 'shopping_items', 43, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"fresas\"}', '2026-02-09 22:18:01'),
(493, 4, 'login', 'auth', NULL, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"mayrab\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-02-09 22:20:37'),
(494, 4, 'create', 'shopping_items', 44, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"huevo\"}', '2026-02-09 22:20:56'),
(495, 4, 'create', 'shopping_items', 45, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"atun\"}', '2026-02-09 22:21:41'),
(496, 4, 'create', 'shopping_items', 46, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"lechuga\"}', '2026-02-09 22:21:47'),
(497, 4, 'create', 'shopping_items', 47, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"zanahoria\"}', '2026-02-09 22:21:54'),
(498, 4, 'create', 'shopping_items', 48, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"chayote\"}', '2026-02-09 22:22:02'),
(499, 4, 'create', 'shopping_items', 49, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"papa\"}', '2026-02-09 22:22:07'),
(500, 4, 'create', 'shopping_items', 50, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"jitomate\"}', '2026-02-09 22:22:13'),
(501, 4, 'create', 'shopping_items', 51, '172.56.233.97', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"limones\"}', '2026-02-09 22:22:19'),
(502, 4, 'create', 'shopping_items', 52, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"apio\"}', '2026-02-09 22:22:44'),
(503, 4, 'create', 'shopping_items', 53, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"proteina shake\"}', '2026-02-09 22:25:13'),
(504, 4, 'create', 'shopping_items', 54, '172.56.233.151', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"Carne magra\"}', '2026-02-09 22:25:31'),
(505, 4, 'create', 'shopping_items', 55, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"espinaca\"}', '2026-02-09 22:34:21'),
(506, 4, 'create', 'shopping_items', 56, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"pepino\"}', '2026-02-09 22:34:29'),
(507, 4, 'create', 'shopping_items', 57, '172.56.235.122', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"brocoli\"}', '2026-02-09 22:34:52'),
(508, 4, 'create', 'shopping_items', 58, '172.56.234.48', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"sishini\"}', '2026-02-09 22:35:31'),
(509, 4, 'login', 'auth', NULL, '172.56.232.73', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"mayrab\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-02-09 22:37:06'),
(510, 4, 'create', 'shopping_items', 59, '172.56.232.73', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"requeson\"}', '2026-02-09 22:37:17'),
(511, 4, 'login', 'auth', NULL, '172.56.235.6', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"mayrab\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-02-09 23:15:38'),
(512, 4, 'check', 'shopping_items', 58, '172.56.235.6', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:15:45'),
(513, 4, 'check', 'shopping_items', 58, '172.56.235.6', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:15:45'),
(514, 4, 'check', 'shopping_items', 57, '172.56.235.6', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:15:49'),
(515, 4, 'check', 'shopping_items', 56, '172.56.233.91', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:15:52'),
(516, 4, 'check', 'shopping_items', 55, '172.56.233.91', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:15:54'),
(517, 4, 'check', 'shopping_items', 52, '172.56.233.91', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:15:56'),
(518, 4, 'check', 'shopping_items', 48, '172.56.235.188', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:16:33'),
(519, 4, 'check', 'shopping_items', 47, '172.56.235.188', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:16:36'),
(520, 4, 'check', 'shopping_items', 46, '172.56.235.188', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:16:37'),
(521, 4, 'check', 'shopping_items', 36, '172.56.234.84', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:16:48'),
(522, 4, 'check', 'shopping_items', 35, '172.56.233.91', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:16:50'),
(523, 4, 'check', 'shopping_items', 34, '172.56.233.91', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:16:51'),
(524, 4, 'check', 'shopping_items', 50, '172.56.233.91', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:17:00'),
(525, 4, 'create', 'shopping_items', 60, '172.56.233.91', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"melon\"}', '2026-02-09 23:17:42'),
(526, 4, 'check', 'shopping_items', 60, '172.56.233.91', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:17:45'),
(527, 4, 'create', 'shopping_items', 61, '172.56.233.91', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"nombre\":\"Aguacate\"}', '2026-02-09 23:18:01'),
(528, 4, 'check', 'shopping_items', 61, '172.56.233.91', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:18:03'),
(529, 4, 'check', 'shopping_items', 49, '172.56.233.91', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-09 23:21:37'),
(530, 4, 'login', 'auth', NULL, '172.56.235.204', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"mayrab\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-02-10 00:23:04'),
(531, 4, 'check', 'shopping_items', 40, '172.56.235.204', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-10 00:23:26'),
(532, 4, 'check', 'shopping_items', 33, '172.56.235.204', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-10 00:23:32'),
(533, 4, 'check', 'shopping_items', 53, '172.56.233.153', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-10 00:33:13'),
(534, 4, 'check', 'shopping_items', 53, '172.56.233.153', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-10 00:33:13'),
(535, 4, 'check', 'shopping_items', 41, '172.56.235.204', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-10 00:33:22'),
(536, 4, 'check', 'shopping_items', 37, '172.56.235.204', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-10 00:33:37'),
(537, 4, 'check', 'shopping_items', 37, '172.56.235.204', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-10 00:33:42'),
(538, 4, 'check', 'shopping_items', 42, '172.56.235.204', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-10 00:37:15'),
(539, NULL, 'login_failed', 'auth', NULL, '172.56.179.148', 'Dart/3.8 (dart:io)', '{\"ok\":false,\"identifier\":\"mayra@devzamora.com\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\",\"error\":\"invalid_credentials\"}', '2026-02-10 00:51:37'),
(540, 4, 'login', 'auth', NULL, '172.56.179.161', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"mayrab\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-02-10 00:51:56'),
(541, 4, 'check', 'shopping_items', 54, '172.56.179.20', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-10 00:54:12'),
(542, 4, 'check', 'shopping_items', 38, '172.56.178.107', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-10 00:54:48'),
(543, 4, 'check', 'shopping_items', 39, '172.56.179.214', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-10 00:57:35'),
(544, 4, 'check', 'shopping_items', 39, '172.56.178.107', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-10 00:57:35'),
(545, 4, 'check', 'shopping_items', 44, '172.56.179.214', 'Dart/3.8 (dart:io)', '{\"list_id\":18,\"is_checked\":1}', '2026-02-10 00:57:52'),
(546, 4, 'login', 'auth', NULL, '172.56.179.109', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"mayrab\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-02-10 01:18:54'),
(547, 4, 'check', 'shopping_items', 27, '172.56.178.4', 'Dart/3.8 (dart:io)', '{\"list_id\":17,\"is_checked\":1}', '2026-02-10 01:19:18'),
(548, 4, 'check', 'shopping_items', 26, '172.56.179.109', 'Dart/3.8 (dart:io)', '{\"list_id\":17,\"is_checked\":1}', '2026-02-10 01:19:58'),
(549, 4, 'check', 'shopping_items', 20, '172.56.179.109', 'Dart/3.8 (dart:io)', '{\"list_id\":16,\"is_checked\":1}', '2026-02-10 01:21:50'),
(550, 4, 'check', 'shopping_items', 19, '172.56.179.0', 'Dart/3.8 (dart:io)', '{\"list_id\":16,\"is_checked\":1}', '2026-02-10 01:21:58'),
(551, 4, 'check', 'shopping_items', 21, '172.56.179.109', 'Dart/3.8 (dart:io)', '{\"list_id\":16,\"is_checked\":1}', '2026-02-10 01:22:25'),
(552, 4, 'check', 'shopping_items', 28, '172.56.178.156', 'Dart/3.8 (dart:io)', '{\"list_id\":17,\"is_checked\":1}', '2026-02-10 01:24:55'),
(553, 4, 'check', 'shopping_items', 24, '172.56.179.109', 'Dart/3.8 (dart:io)', '{\"list_id\":16,\"is_checked\":1}', '2026-02-10 01:25:06'),
(554, 4, 'check', 'shopping_items', 31, '172.56.179.228', 'Dart/3.8 (dart:io)', '{\"list_id\":17,\"is_checked\":1}', '2026-02-10 01:25:35'),
(555, 4, 'check', 'shopping_items', 23, '172.56.179.109', 'Dart/3.8 (dart:io)', '{\"list_id\":16,\"is_checked\":1}', '2026-02-10 01:26:42'),
(556, 4, 'check', 'shopping_items', 29, '172.56.179.109', 'Dart/3.8 (dart:io)', '{\"list_id\":17,\"is_checked\":1}', '2026-02-10 01:26:46'),
(557, 4, 'check', 'shopping_items', 22, '172.56.179.61', 'Dart/3.8 (dart:io)', '{\"list_id\":16,\"is_checked\":1}', '2026-02-10 01:27:38'),
(558, 4, 'login', 'auth', NULL, '172.56.179.192', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"identifier\":\"mayrab\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/login\"}', '2026-02-10 23:48:54'),
(559, NULL, 'refresh', 'auth', NULL, '172.56.179.192', 'Dart/3.8 (dart:io)', '{\"ok\":true,\"rt_hash\":\"eebf978a4b696a9d2ccff9f1bbdff348504fe4bdd7b04f59910c396446675c3a\",\"device_id\":null,\"method\":\"POST\",\"path\":\"/todo/public/auth/refresh\"}', '2026-02-11 03:37:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pantry_items`
--

CREATE TABLE `pantry_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(140) NOT NULL,
  `stock` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unidad` varchar(20) DEFAULT NULL,
  `min_stock` decimal(10,2) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pantry_items`
--

INSERT INTO `pantry_items` (`id`, `user_id`, `nombre`, `stock`, `unidad`, `min_stock`, `updated_at`) VALUES
(10, 3, 'pollo', 0.00, 'libra', 0.00, '2026-01-29 10:23:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `icono` varchar(40) DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `estado` enum('activo','archivado') NOT NULL DEFAULT 'activo',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `nombre`, `color`, `icono`, `orden`, `estado`, `created_at`, `updated_at`) VALUES
(9, 3, 'limpiar carro', '#3B82F6', '🛠️', 1, 'activo', '2026-01-21 10:09:38', '2026-01-21 10:09:38'),
(10, 3, 'vender tosco', '#06B6D4', '🛠️', 0, 'activo', '2026-01-21 10:09:59', '2026-01-21 10:09:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `refresh_tokens`
--

CREATE TABLE `refresh_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token_hash` char(64) NOT NULL,
  `device_id` varchar(120) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `revoked_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `refresh_tokens`
--

INSERT INTO `refresh_tokens` (`id`, `user_id`, `token_hash`, `device_id`, `ip`, `user_agent`, `expires_at`, `revoked_at`, `created_at`) VALUES
(165, 1, '9e2501b088352e562339dfdc8c06bda87bed7faefe1a0e13ace85e023ee048a7', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '2026-02-18 20:23:17', NULL, '2026-01-19 21:23:17'),
(166, 1, 'a107a45e54505e74c6dd67469c4853559347f5dad71278c597342d2bf4b2361c', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '2026-02-18 20:43:05', NULL, '2026-01-19 21:43:05'),
(167, 3, 'f29fb05bdafcb9c9170dbb19fa6de9395f82253832910c75b9c4f873bfd55ca7', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '2026-02-20 08:45:51', NULL, '2026-01-21 09:45:51'),
(168, 3, '3b1ee73ca827204700c2542f576ec95e1e279f10e1994ca18e729cd4e63216ef', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '2026-02-20 09:09:21', NULL, '2026-01-21 10:09:21'),
(169, 3, '2e8e8ef278ab72c01100effa7249306a8cec1e7da6531b1d7f8e547552c358c1', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '2026-02-20 09:30:54', NULL, '2026-01-21 10:30:54'),
(170, 5, 'c393e2397140410b35d9bbf6707172d5341f86c477d2b8751c4910aa680d3666', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '2026-02-20 17:33:22', NULL, '2026-01-21 18:33:22'),
(171, 3, '20eea9953160a5c59e6adb66cbc8449288807daa26a095306f923bc008943928', NULL, '35.149.44.17', 'Dart/3.8 (dart:io)', '2026-02-20 18:00:35', NULL, '2026-01-21 19:00:35'),
(172, 5, 'e786f6ff0015e9a46215af1a20a79bc39b62a7f3ddb787f8c5c2d6a0d0872511', NULL, '186.77.137.224', 'Dart/3.8 (dart:io)', '2026-02-20 18:15:40', NULL, '2026-01-21 19:15:40'),
(173, 3, '3b401d4d88d20a1952202d430d73069391a7eabe09b27c0f00b04a56bd050e7a', NULL, '174.195.195.241', 'Dart/3.8 (dart:io)', '2026-02-20 20:54:50', NULL, '2026-01-21 21:54:50'),
(174, 3, 'f5511c2232c38c51571dca5c889c69a8b2373ec7558accc7db0f6cb5cf46113b', NULL, '174.195.198.14', 'Dart/3.8 (dart:io)', '2026-02-22 02:25:55', NULL, '2026-01-23 03:25:55'),
(175, 4, '20077fb804e25f4f7f7e64ddb41724062c72358ec4084e2a957ea44374ae4706', NULL, '172.56.178.88', 'Dart/3.8 (dart:io)', '2026-02-28 00:51:51', NULL, '2026-01-29 01:51:51'),
(176, 3, 'fcfac2cf1bda6a4cfe85bf1bea59a7b9a1fa7fa51d3521f76116825d6f816cbc', NULL, '174.193.130.213', 'Dart/3.8 (dart:io)', '2026-02-28 09:18:08', NULL, '2026-01-29 10:18:08'),
(177, 4, '45e739ba734db3ac8bf2ca0ee0b981c1b691ddc815df7f5757b6054f74b299f0', NULL, '172.56.235.122', 'Dart/3.8 (dart:io)', '2026-03-11 21:03:34', NULL, '2026-02-09 22:03:34'),
(178, 4, '1f2e780df8390741a2ac456c4bee7c6fb1ce4354b8a4ac4ef7fdcf0c16b0fa17', NULL, '172.56.235.122', 'Dart/3.8 (dart:io)', '2026-03-11 21:20:37', NULL, '2026-02-09 22:20:37'),
(179, 4, '58c552119556196f8089517df8e306ae62643d787268e75de6f43ba0e3790c48', NULL, '172.56.232.73', 'Dart/3.8 (dart:io)', '2026-03-11 21:37:06', NULL, '2026-02-09 22:37:06'),
(180, 4, '975c96f903b5b63cb7774b11c25ead6ff2dc23464cc9712607e79fdb2f468319', NULL, '172.56.235.6', 'Dart/3.8 (dart:io)', '2026-03-11 22:15:38', NULL, '2026-02-09 23:15:38'),
(181, 4, '559d989e145eadb16d4ee02e6c6d0ebd1dae665b3ba873167968103a2be91750', NULL, '172.56.235.204', 'Dart/3.8 (dart:io)', '2026-03-11 23:23:04', NULL, '2026-02-10 00:23:04'),
(182, 4, 'b9b81beda5fe3f319fbddd05187a325cdb2199f3bd513b058ed9a93fd9cdcac6', NULL, '172.56.179.161', 'Dart/3.8 (dart:io)', '2026-03-11 23:51:56', NULL, '2026-02-10 00:51:56'),
(183, 4, '4bcc9403a5e303d41bdec17d23413c1609e30eea18db95c496d00a28a4418075', NULL, '172.56.179.109', 'Dart/3.8 (dart:io)', '2026-03-12 00:18:54', NULL, '2026-02-10 01:18:54'),
(184, 4, '09ca3cc0f100b579bc1f14f7976cd0a507d69d332d8b0efc7dfb8f38e4be5a88', NULL, '172.56.179.192', 'Dart/3.8 (dart:io)', '2026-03-12 22:48:54', '2026-02-11 03:37:44', '2026-02-10 23:48:54'),
(185, 4, '688b4432589db37d101d89814b067be1233cf9088a62bcb301f5afe9e1e5e3a3', NULL, NULL, NULL, '2026-03-13 02:37:44', NULL, '2026-02-11 03:37:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shopping_items`
--

CREATE TABLE `shopping_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `list_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(140) NOT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `unidad` varchar(20) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `precio_estimado` decimal(10,2) DEFAULT NULL,
  `precio_real` decimal(10,2) DEFAULT NULL,
  `is_checked` tinyint(1) NOT NULL DEFAULT 0,
  `prioridad` enum('none','low','medium','high') NOT NULL DEFAULT 'none',
  `orden` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `shopping_items`
--

INSERT INTO `shopping_items` (`id`, `list_id`, `nombre`, `cantidad`, `unidad`, `categoria`, `marca`, `precio_estimado`, `precio_real`, `is_checked`, `prioridad`, `orden`, `created_at`, `updated_at`) VALUES
(19, 16, 'multivitaminico', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:05:03', '2026-02-10 01:21:58'),
(20, 16, 'agua', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:05:15', '2026-02-10 01:21:50'),
(21, 16, 'proteína en polvo', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:05:29', '2026-02-10 01:22:25'),
(22, 16, 'omega 3 de 1000mg', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:05:57', '2026-02-10 01:27:38'),
(23, 16, 'magnesio de 400g', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:06:11', '2026-02-10 01:26:42'),
(24, 16, 'vitamina D3 2000ui', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:07:40', '2026-02-10 01:25:06'),
(25, 16, 'sal de gimalaya', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'none', 0, '2026-02-09 22:08:12', '2026-02-09 22:08:12'),
(26, 17, 'multivitaminico', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:08:51', '2026-02-10 01:19:58'),
(27, 17, 'omega 3', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:08:59', '2026-02-10 01:19:18'),
(28, 17, 'vitamina D3', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:09:08', '2026-02-10 01:24:55'),
(29, 17, 'magnesio 400', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:09:19', '2026-02-10 01:26:46'),
(30, 17, 'proteina whey', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'none', 0, '2026-02-09 22:10:01', '2026-02-09 22:10:01'),
(31, 17, 'zinc', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:10:08', '2026-02-10 01:25:35'),
(32, 18, 'arroz integral', 4.00, 'lb', NULL, NULL, NULL, NULL, 0, 'none', 0, '2026-02-09 22:11:32', '2026-02-09 22:11:32'),
(33, 18, 'quinoa', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:12:33', '2026-02-10 00:23:32'),
(34, 18, 'manzana', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:12:49', '2026-02-09 23:16:51'),
(35, 18, 'naranja', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:12:56', '2026-02-09 23:16:50'),
(36, 18, 'platano', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:13:02', '2026-02-09 23:16:48'),
(37, 18, 'almendras', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:14:07', '2026-02-10 00:33:42'),
(38, 18, 'polla', 3.00, 'lb', NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:15:55', '2026-02-10 00:54:48'),
(39, 18, 'pescado', 3.00, 'lb', NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:16:24', '2026-02-10 00:57:35'),
(40, 18, 'pan integral', 4.00, 'bolsas', NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:16:44', '2026-02-10 00:23:26'),
(41, 18, 'avena', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:17:46', '2026-02-10 00:33:22'),
(42, 18, 'yogurt  griego', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:17:55', '2026-02-10 00:37:15'),
(43, 18, 'fresas', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'none', 0, '2026-02-09 22:18:01', '2026-02-09 22:18:01'),
(44, 18, 'huevo', 4.00, 'dz', NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:20:56', '2026-02-10 00:57:52'),
(45, 18, 'atun', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'none', 0, '2026-02-09 22:21:41', '2026-02-09 22:21:41'),
(46, 18, 'lechuga', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:21:47', '2026-02-09 23:16:37'),
(47, 18, 'zanahoria', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:21:54', '2026-02-09 23:16:36'),
(48, 18, 'chayote', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:22:02', '2026-02-09 23:16:33'),
(49, 18, 'papa', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:22:07', '2026-02-09 23:21:37'),
(50, 18, 'jitomate', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:22:13', '2026-02-09 23:17:00'),
(51, 18, 'limones', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'none', 0, '2026-02-09 22:22:19', '2026-02-09 22:22:19'),
(52, 18, 'apio', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:22:44', '2026-02-09 23:15:56'),
(53, 18, 'proteina shake', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:25:13', '2026-02-10 00:33:13'),
(54, 18, 'Carne magra', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:25:31', '2026-02-10 00:54:12'),
(55, 18, 'espinaca', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:34:21', '2026-02-09 23:15:54'),
(56, 18, 'pepino', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:34:29', '2026-02-09 23:15:52'),
(57, 18, 'brocoli', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:34:52', '2026-02-09 23:15:49'),
(58, 18, 'sishini', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 22:35:31', '2026-02-09 23:15:45'),
(59, 18, 'requeson', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'none', 0, '2026-02-09 22:37:17', '2026-02-09 22:37:17'),
(60, 18, 'melon', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 23:17:42', '2026-02-09 23:17:45'),
(61, 18, 'Aguacate', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'none', 0, '2026-02-09 23:18:01', '2026-02-09 23:18:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shopping_lists`
--

CREATE TABLE `shopping_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `tienda` varchar(120) DEFAULT NULL,
  `presupuesto` decimal(10,2) DEFAULT NULL,
  `currency` char(3) NOT NULL DEFAULT 'USD',
  `estado` enum('open','completed','archived') NOT NULL DEFAULT 'open',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `shopping_lists`
--

INSERT INTO `shopping_lists` (`id`, `user_id`, `nombre`, `tienda`, `presupuesto`, `currency`, `estado`, `created_at`, `updated_at`) VALUES
(15, 3, 'fitn', NULL, NULL, 'USD', 'open', '2026-01-29 10:19:25', '2026-01-29 10:19:25'),
(16, 4, 'mayra', NULL, NULL, 'USD', 'open', '2026-02-09 22:04:04', '2026-02-09 22:04:04'),
(17, 4, 'jd', NULL, NULL, 'USD', 'open', '2026-02-09 22:08:36', '2026-02-09 22:08:36'),
(18, 4, 'general de comida', NULL, NULL, 'USD', 'open', '2026-02-09 22:11:08', '2026-02-09 22:11:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shopping_templates`
--

CREATE TABLE `shopping_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `shopping_templates`
--

INSERT INTO `shopping_templates` (`id`, `user_id`, `nombre`, `created_at`) VALUES
(7, 3, 'supermercado fitness', '2026-01-29 10:19:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shopping_template_items`
--

CREATE TABLE `shopping_template_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `template_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(140) NOT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `unidad` varchar(20) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `prioridad` enum('none','low','medium','high') NOT NULL DEFAULT 'none',
  `orden` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `shopping_template_items`
--

INSERT INTO `shopping_template_items` (`id`, `template_id`, `nombre`, `cantidad`, `unidad`, `categoria`, `marca`, `prioridad`, `orden`, `created_at`) VALUES
(7, 7, 'pollo', 5.00, 'libra', 'carnes', NULL, 'none', 0, '2026-01-29 10:20:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tags`
--

INSERT INTO `tags` (`id`, `user_id`, `nombre`, `color`, `created_at`) VALUES
(9, 3, 'urgente', NULL, '2026-01-21 10:13:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `titulo` varchar(160) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `prioridad` enum('low','med','high','urgent') NOT NULL DEFAULT 'med',
  `estado` enum('todo','doing','done','archived') NOT NULL DEFAULT 'todo',
  `start_at` datetime DEFAULT NULL,
  `due_at` datetime DEFAULT NULL,
  `done_at` datetime DEFAULT NULL,
  `is_star` tinyint(1) NOT NULL DEFAULT 0,
  `orden` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `project_id`, `parent_id`, `titulo`, `descripcion`, `prioridad`, `estado`, `start_at`, `due_at`, `done_at`, `is_star`, `orden`, `created_at`, `updated_at`, `deleted_at`) VALUES
(17, 3, 10, NULL, 'lavar tosco', '', 'med', 'done', NULL, NULL, '2026-01-29 10:18:37', 0, 0, '2026-01-21 10:10:53', '2026-01-29 10:18:37', NULL),
(18, 3, 10, NULL, 'tomar fotos', '', 'med', 'done', NULL, NULL, '2026-01-29 10:18:44', 0, 0, '2026-01-21 10:11:04', '2026-01-29 10:18:44', NULL),
(19, 3, 10, NULL, 'publicar fotos', '', 'med', 'done', NULL, NULL, '2026-01-29 10:18:21', 0, 0, '2026-01-21 10:11:15', '2026-01-29 10:18:21', NULL),
(20, 3, 9, NULL, 'lavar lunita', '', 'med', 'todo', NULL, NULL, NULL, 0, 0, '2026-01-21 10:14:46', '2026-01-21 10:14:46', NULL),
(21, 3, 9, NULL, 'lavar estrellita', '', 'med', 'todo', NULL, NULL, NULL, 0, 0, '2026-01-21 10:15:37', '2026-01-21 10:15:37', NULL),
(22, 3, NULL, NULL, 'descongelar carne', '', 'med', 'done', NULL, NULL, '2026-01-21 21:55:31', 0, 0, '2026-01-21 10:16:06', '2026-01-21 21:55:31', NULL),
(23, 4, NULL, NULL, 'ssn', '', 'med', 'todo', NULL, NULL, NULL, 0, 0, '2026-01-29 01:52:45', '2026-01-29 01:52:45', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `task_recurring`
--

CREATE TABLE `task_recurring` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `rule_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`rule_json`)),
  `next_run_at` datetime DEFAULT NULL,
  `last_run_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `task_recurring`
--

INSERT INTO `task_recurring` (`id`, `task_id`, `rule_json`, `next_run_at`, `last_run_at`, `is_active`, `created_at`, `updated_at`) VALUES
(20, 18, '{\"freq\":\"daily\",\"interval\":1,\"timezone\":\"America/Los_Angeles\",\"at_time\":\"08:00:00\",\"by_weekday\":null,\"by_monthday\":null,\"create\":{\"copy_tags\":true}}', '2026-01-22 08:00:00', NULL, 0, '2026-01-21 10:13:34', '2026-01-21 10:13:52'),
(21, 22, '{\"freq\":\"daily\",\"interval\":1,\"timezone\":\"America/Los_Angeles\",\"at_time\":\"08:00:00\",\"by_weekday\":null,\"by_monthday\":null,\"create\":{\"copy_tags\":true}}', '2026-01-22 08:00:00', NULL, 1, '2026-01-21 10:18:18', '2026-01-21 10:18:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `task_reminders`
--

CREATE TABLE `task_reminders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `remind_at` datetime NOT NULL,
  `channel` enum('mobile_local','push','email') NOT NULL DEFAULT 'mobile_local',
  `sent_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `task_reminders`
--

INSERT INTO `task_reminders` (`id`, `task_id`, `remind_at`, `channel`, `sent_at`, `created_at`) VALUES
(17, 18, '2026-01-21 07:18:00', 'mobile_local', NULL, '2026-01-21 10:13:21'),
(18, 22, '2026-01-21 06:25:00', 'mobile_local', NULL, '2026-01-21 10:17:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `task_tags`
--

CREATE TABLE `task_tags` (
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `task_tags`
--

INSERT INTO `task_tags` (`task_id`, `tag_id`, `created_at`) VALUES
(18, 9, '2026-01-21 10:13:01'),
(22, 9, '2026-01-21 10:17:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `apellido` varchar(80) DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `ultimo_login_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `email`, `password_hash`, `nombre`, `apellido`, `estado`, `ultimo_login_at`, `created_at`, `updated_at`) VALUES
(1, 'demo', 'demo@mail.com', '$2y$10$ZZonuqSYJRI6eepMFppmoOVkmd0DzpPMK5uvGGt7folqYWzFTM1OO', 'Demo', 'User', 'activo', '2026-01-19 21:43:05', '2026-01-13 00:19:09', '2026-01-19 21:43:05'),
(2, 'user1', 'tester@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Tester', 'User', 'activo', NULL, '2026-01-15 21:41:30', '2026-01-16 07:02:02'),
(3, 'admin', 'admin@devzamora.com', '$2y$10$eYU55ctbO3sxBW.3nvamv.ofMoA47DKp5m9iwARJ8c4ZnApHyCFbm', 'Jefferson', 'Zamora', 'activo', '2026-01-29 10:18:08', '2026-01-19 21:37:07', '2026-01-29 10:18:08'),
(4, 'mayrab', 'mayra.bocanegra@devzamora.com', '$2y$10$eYU55ctbO3sxBW.3nvamv.ofMoA47DKp5m9iwARJ8c4ZnApHyCFbm', 'Mayra', 'Bocanegra', 'activo', '2026-02-10 23:48:54', '2026-01-19 21:37:07', '2026-02-10 23:48:54'),
(5, 'maritzar', 'maritza.ramirez@devzamora.com', '$2y$10$eYU55ctbO3sxBW.3nvamv.ofMoA47DKp5m9iwARJ8c4ZnApHyCFbm', 'Maritza', 'Ramirez', 'activo', '2026-01-21 19:15:40', '2026-01-19 21:37:07', '2026-01-21 19:15:40'),
(6, 'jack', 'jack.rivera@devzamora.com', '$2y$10$eYU55ctbO3sxBW.3nvamv.ofMoA47DKp5m9iwARJ8c4ZnApHyCFbm', 'Jack', 'Rivera', 'activo', NULL, '2026-01-19 21:37:07', '2026-01-21 09:39:48');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audit_user` (`user_id`),
  ADD KEY `idx_audit_entity` (`entity`,`entity_id`),
  ADD KEY `idx_audit_created` (`created_at`);

--
-- Indices de la tabla `pantry_items`
--
ALTER TABLE `pantry_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_pantry_user_nombre` (`user_id`,`nombre`),
  ADD KEY `idx_pantry_user` (`user_id`);

--
-- Indices de la tabla `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_projects_user` (`user_id`),
  ADD KEY `idx_projects_estado` (`estado`);

--
-- Indices de la tabla `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_refresh_token_hash` (`token_hash`),
  ADD KEY `idx_refresh_user` (`user_id`),
  ADD KEY `idx_refresh_expires` (`expires_at`);

--
-- Indices de la tabla `shopping_items`
--
ALTER TABLE `shopping_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_shopitems_list` (`list_id`),
  ADD KEY `idx_shopitems_checked` (`is_checked`),
  ADD KEY `idx_shopitems_categoria` (`categoria`);

--
-- Indices de la tabla `shopping_lists`
--
ALTER TABLE `shopping_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_shoplist_user` (`user_id`),
  ADD KEY `idx_shoplist_estado` (`estado`);

--
-- Indices de la tabla `shopping_templates`
--
ALTER TABLE `shopping_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_templates_user` (`user_id`);

--
-- Indices de la tabla `shopping_template_items`
--
ALTER TABLE `shopping_template_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_templateitems_template` (`template_id`);

--
-- Indices de la tabla `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_tags_user_nombre` (`user_id`,`nombre`),
  ADD KEY `idx_tags_user` (`user_id`);

--
-- Indices de la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tasks_user` (`user_id`),
  ADD KEY `idx_tasks_project` (`project_id`),
  ADD KEY `idx_tasks_parent` (`parent_id`),
  ADD KEY `idx_tasks_estado` (`estado`),
  ADD KEY `idx_tasks_due` (`due_at`),
  ADD KEY `idx_tasks_deleted` (`deleted_at`);

--
-- Indices de la tabla `task_recurring`
--
ALTER TABLE `task_recurring`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_recurring_task` (`task_id`),
  ADD KEY `idx_recurring_next` (`next_run_at`),
  ADD KEY `idx_task_recurring_due` (`is_active`,`next_run_at`);

--
-- Indices de la tabla `task_reminders`
--
ALTER TABLE `task_reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reminders_task` (`task_id`),
  ADD KEY `idx_reminders_time` (`remind_at`);

--
-- Indices de la tabla `task_tags`
--
ALTER TABLE `task_tags`
  ADD PRIMARY KEY (`task_id`,`tag_id`),
  ADD KEY `idx_task_tags_tag` (`tag_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuarios_username` (`username`),
  ADD UNIQUE KEY `uq_usuarios_email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=560;

--
-- AUTO_INCREMENT de la tabla `pantry_items`
--
ALTER TABLE `pantry_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT de la tabla `shopping_items`
--
ALTER TABLE `shopping_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `shopping_lists`
--
ALTER TABLE `shopping_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `shopping_templates`
--
ALTER TABLE `shopping_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `shopping_template_items`
--
ALTER TABLE `shopping_template_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `task_recurring`
--
ALTER TABLE `task_recurring`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `task_reminders`
--
ALTER TABLE `task_reminders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pantry_items`
--
ALTER TABLE `pantry_items`
  ADD CONSTRAINT `fk_pantry_user` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `fk_projects_user` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD CONSTRAINT `fk_refresh_user` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `shopping_items`
--
ALTER TABLE `shopping_items`
  ADD CONSTRAINT `fk_shopitems_list` FOREIGN KEY (`list_id`) REFERENCES `shopping_lists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `shopping_lists`
--
ALTER TABLE `shopping_lists`
  ADD CONSTRAINT `fk_shoplist_user` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `shopping_templates`
--
ALTER TABLE `shopping_templates`
  ADD CONSTRAINT `fk_templates_user` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `shopping_template_items`
--
ALTER TABLE `shopping_template_items`
  ADD CONSTRAINT `fk_templateitems_template` FOREIGN KEY (`template_id`) REFERENCES `shopping_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `fk_tags_user` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_tasks_parent` FOREIGN KEY (`parent_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tasks_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tasks_user` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `task_recurring`
--
ALTER TABLE `task_recurring`
  ADD CONSTRAINT `fk_recurring_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `task_reminders`
--
ALTER TABLE `task_reminders`
  ADD CONSTRAINT `fk_reminders_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `task_tags`
--
ALTER TABLE `task_tags`
  ADD CONSTRAINT `fk_tasktags_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tasktags_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
