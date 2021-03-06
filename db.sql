CREATE TABLE `film_leihliste` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`usernr` INT(11) NULL DEFAULT NULL,
	`datum` TIMESTAMP NULL DEFAULT NULL,
	`filmnr` INT(11) NULL DEFAULT NULL,
	`kopiert` TINYINT(1) NULL DEFAULT '0',
	`angeschaut` TINYINT(1) NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=2;


CREATE TABLE `film_benutzer` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(250) NULL DEFAULT NULL,
	`kurz` VARCHAR(10) NULL DEFAULT NULL,
	`passwort` VARCHAR(50) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;
