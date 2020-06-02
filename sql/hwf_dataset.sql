CREATE TABLE `hwf_dataset` (
  `id` int(10) UNSIGNED NOT NULL,
  `kg` int(11) DEFAULT NULL,
  `fat` int(11) DEFAULT NULL,
  `date` VARCHAR(15) NOT NULL,
  `createtime` datetime NOT NULL,
  `updatetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `hwf_dataset`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `hwf_dataset`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `hwf_dataset` ADD `userid` INT(10) NOT NULL AFTER `id`;
ALTER TABLE `hwf_dataset` ADD `ampm` VARCHAR(50) NOT NULL AFTER `date`;
COMMIT;

