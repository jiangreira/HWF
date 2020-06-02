CREATE TABLE `hwf_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acc` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pwd` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createtime` datetime DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `hwf_user`
  ADD PRIMARY KEY (`id`);

INSERT INTO `hwf_user` (`id`, `name`, `acc`, `pwd`, `createtime`, `updatetime`) VALUES
(NULL, 'test', 'test', '1234', '2020-05-29 23:17:48', '2020-05-29 23:21:04');
