<?php
require_once 'class/Database.php';
require_once 'database.php';

$pdo = new Database($DB_USER, $DB_PASSWORD);

$pdo->query("
CREATE DATABASE IF NOT EXISTS `$DB_NAME`;

USE `$DB_NAME`;

CREATE TABLE IF NOT EXISTS `comm` (
  `comm_id` int(4) NOT NULL,
  `up_id` int(11) NOT NULL,
  `us_id` int(11) NOT NULL,
  `comm_val` longtext NOT NULL,
  `comm_date` datetime NOT NULL,
  `login_us` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `img` (
  `up_id` int(11) UNSIGNED NOT NULL,
  `up_date` datetime NOT NULL,
  `up_usid` int(11) NOT NULL,
  `up_path` varchar(250) NOT NULL,
  `up_likes` int(11) DEFAULT '0',
  `up_dislikes` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `likes` (
  `like_id` int(11) NOT NULL,
  `up_id` int(11) NOT NULL,
  `tab` varchar(255) NOT NULL,
  `us_id` int(11) NOT NULL,
  `like_val` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `forename` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `token` varchar(60) DEFAULT NULL,
  `check` int(11) DEFAULT NULL,
  `mail_com` int(11) DEFAULT '0',
  `reset_token` varchar(60) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `remember_token` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `comm`
  ADD PRIMARY KEY (`comm_id`);

ALTER TABLE `img`
  ADD PRIMARY KEY (`up_id`);

ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `comm`
  MODIFY `comm_id` int(4) NOT NULL AUTO_INCREMENT;

ALTER TABLE `img`
  MODIFY `up_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
")



?>