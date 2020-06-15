CREATE TABLE `hwf_group` (
  `id` int(11) UNSIGNED NOT NULL,
  `groupid` int(100) NOT NULL,
  `userid` int(100) NOT NULL,
  `isauth` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT ' ',
  `createtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `hwf_group`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `hwf_group`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

