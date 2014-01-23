-- --------------------------------------------------------
-- Host:                         localhost
-- Server Version:               5.5.16 - MySQL Community Server (GPL)
-- Server Betriebssystem:        Win32
-- HeidiSQL Version:             8.2.0.4692
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Exportiere Struktur von Tabelle test.film_benutzer
CREATE TABLE IF NOT EXISTS `film_benutzer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `kurz` varchar(10) DEFAULT NULL,
  `passwort` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Exportiere Daten aus Tabelle test.film_benutzer: ~11 rows (ungefähr)
/*!40000 ALTER TABLE `film_benutzer` DISABLE KEYS */;
INSERT INTO `film_benutzer` (`id`, `name`, `kurz`, `passwort`) VALUES
	(1, 'Ernst Grillmayer', 'EG', '12345'),
	(2, 'Christian Mecke', 'CM', '12345'),
	(3, 'Eva Mecke', 'EM', '12345'),
	(4, 'Sandra Kattner', 'SK', '12345'),
	(5, 'Heiko Kattner', 'HK', '12345'),
	(6, 'Sabine Grillmayer', 'SG', '12345'),
	(7, 'Markus Grillmayer', 'MG', '12345'),
	(8, 'Andreas Weber', 'AW', '12345'),
	(9, 'Manuel Fröhlich', 'MF', '13245'),
	(10, 'Julia Orth', 'JO', '12345'),
	(11, 'Max Orth', 'MO', '12345');
/*!40000 ALTER TABLE `film_benutzer` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
