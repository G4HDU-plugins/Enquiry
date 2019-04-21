CREATE TABLE `enquiry_forms` (
	`enquiry_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`enquiry_title` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	`enquiry_name` VARCHAR(50) DEFAULT NULL,
	`enquiry_address1` VARCHAR(50) DEFAULT NULL,
	`enquiry_address2` VARCHAR(50) DEFAULT NULL,
	`enquiry_town` VARCHAR(30) DEFAULT NULL,
	`enquiry_county` VARCHAR(30) DEFAULT NULL,
	`enquiry_postcode` VARCHAR(20) DEFAULT NULL,
	`enquiry_phone` VARCHAR(20) DEFAULT NULL,
	`enquiry_email` VARCHAR(50) DEFAULT NULL,
	`enquiry_agerange` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	`enquiry_gender` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	`enquiry_category` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`enquiry_otherinfo` TEXT,
	`enquiry_dateposted` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`enquiry_responder` VARCHAR(100) DEFAULT NULL,
	`enquiry_respondedon` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`enquiry_outcome` TEXT,
	`enquiry_closedon` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`enquiry_lastupdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY  (`enquiry_id`)
)ENGINE=myisam;,
CREATE TABLE `enquiry_categories` (
	`enquiry_category_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`enquiry_category_name` VARCHAR(50) NOT NULL,
	`enquiry_category_details` TINYTEXT NOT NULL,
    `enquiry_category_lastupdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY  (`enquiry_category_id`),
	UNIQUE INDEX  `enquiry_category_name` (`enquiry_category_name`)
)
ENGINE=myisam;
