/*
 Navicat Premium Data Transfer

 Source Server         : dengoid23-local
 Source Server Type    : MySQL
 Source Server Version : 80403 (8.4.3)
 Source Host           : localhost:3306
 Source Schema         : dengoid

 Target Server Type    : MySQL
 Target Server Version : 80403 (8.4.3)
 File Encoding         : 65001

 Date: 17/06/2026 10:50:56
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_org` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `role` enum('admin','editor','viewer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'viewer',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user_level` int NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mfa_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `mfa_attempts` int NOT NULL DEFAULT 0,
  `mfa_locked_until` timestamp NULL DEFAULT NULL,
  `mfa_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `password_changed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE,
  UNIQUE INDEX `users_nip_unique`(`nip` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'adminweb', NULL, 'Administrator Sistem', 'admin@admin.com', NULL, 'viewer', 1, NULL, NULL, '$2y$12$y1WqOx13KgwwsfqA7.oVfeyUrCkR9f0ittGdbIoJsW9YbWb4g1SDq', 1, NULL, '2024-01-22 03:39:10', '2026-03-03 06:23:47', 'LNJC45CTERWWODUG', 4, NULL, 1, '2026-04-30 13:56:31');
INSERT INTO `users` VALUES (5, 'contentman', NULL, 'Pengelola Konten', 'contentman@admin.com', NULL, 'viewer', 1, NULL, NULL, '$2y$12$50Z/mvm99xZFVXE0dq4LIOD40dIC8z7mpRqz95RxI4ZD/QCakM7zO', 2, NULL, '2024-11-07 14:49:51', '2026-03-04 01:39:56', 'T2TIWAGCAIUN67XR', 1, NULL, 1, '2026-04-06 14:27:46');
INSERT INTO `users` VALUES (6, 'manajerkonten', NULL, 'Manajer Konten', 'manajerkonten@den.go.id', NULL, 'viewer', 1, NULL, NULL, '$2y$10$8w1/8k89gVvgqxXqeY6g4err9QldsgXDCWbvlAx6WeiyhK/oAgPjK', 2, NULL, '2023-11-30 05:07:50', NULL, NULL, 0, NULL, 1, NULL);
INSERT INTO `users` VALUES (7, 'operator', NULL, 'Operator Kuesioner', 'operator@mail.com', NULL, 'viewer', 1, NULL, NULL, '$2y$12$u2jrQVvGnrmprv7Up9.TvO/aIQBkmOXJUI.NzLCx8JY/UeOcV5KGi', 3, NULL, '2025-12-21 09:56:13', '2026-04-06 14:42:12', 'JHFU4D4IGNUH3TLJ', 1, NULL, 1, '2026-04-06 14:28:34');
INSERT INTO `users` VALUES (8, 'auditor', NULL, 'System Auditor', 'auditor@den.go.id', NULL, 'viewer', 1, NULL, NULL, '$2y$12$kCTzia60tM4jWr3slqfBGuyGN7zAQYYnMIgYSUU3uOkpNE63at7yu', 4, NULL, '2026-03-04 01:33:52', '2026-03-04 01:40:27', 'DJRD4TYTD2NHDLAV', 1, NULL, 1, '2026-04-06 14:13:04');
INSERT INTO `users` VALUES (9, 'pratama', NULL, 'Ricky Pratama', 'pratamaricky@gmail.com', NULL, 'viewer', 1, NULL, NULL, '$2y$12$h0HKE1GnD9qsvb/4jYR1Xukqlo/GkV1FlAuqtvI9iC7dp3iaOd4IS', 3, NULL, '2026-03-12 07:08:26', '2026-04-06 15:57:28', 'OI7L56YDH4PIHNRD', 0, NULL, 1, NULL);

SET FOREIGN_KEY_CHECKS = 1;
