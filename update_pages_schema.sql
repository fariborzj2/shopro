ALTER TABLE `pages`
ADD COLUMN `short_description` TEXT AFTER `content`,
ADD COLUMN `meta_title` VARCHAR(255) AFTER `short_description`,
ADD COLUMN `meta_keywords` VARCHAR(255) AFTER `meta_title`,
ADD COLUMN `meta_description` TEXT AFTER `meta_keywords`;
