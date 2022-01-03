CREATE TABLE `selected`
(
  `id`       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `source`   VARCHAR(4096),
  `target`   VARCHAR(4096),
  `folder`   VARCHAR(255),
  `ordering` INT UNSIGNED
) ENGINE = INNODB;
