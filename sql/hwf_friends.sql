SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `hwf_friends_request` (
  `id` int(10) UNSIGNED NOT NULL,
  `userid` int(10) NOT NULL,
  `friendsid` int(10) NOT NULL,
  `isauth` VARCHAR(20) NOT NULL DEFAULT '0',
  `updatedate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `hwf_friends`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `hwf_friends`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
