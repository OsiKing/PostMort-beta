	CREATE TABLE `newsletter` (
	  `id` int(11) NOT NULL,
	  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
	  `verification_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	  `is_verified` int(1) NOT NULL DEFAULT 0,
	  `created_date` datetime NOT NULL,
	  `modified_date` datetime NOT NULL DEFAULT current_timestamp(),
	  `status` int(1) NOT NULL DEFAULT 1
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

	ALTER TABLE `newsletter`
  		ADD PRIMARY KEY (`id`);

	ALTER TABLE `newsletter`
  		MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;