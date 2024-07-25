CREATE TABLE `Mitarbeiter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vorname` varchar(50) NOT NULL,
  `nachname` varchar(50) NOT NULL,
  `adresse` varchar(100) NOT NULL,
  `abteilung` varchar(50) NOT NULL,
  `telefon` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `passwort` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;