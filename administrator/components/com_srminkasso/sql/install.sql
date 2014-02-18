CREATE TABLE IF NOT EXISTS `#__srmink_positionen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_leistung` smallint(11) DEFAULT NULL,
  `fk_userid` smallint(11) DEFAULT NULL,
  `individual_preis` decimal(20,2) DEFAULT '0.00',
  `fk_faktura` int(11) DEFAULT '0',
  `kommentar` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=247 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__srmink_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  `typ` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__srmink_fakturierungen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titel` varchar(40) NOT NULL,
  `datum` date NOT NULL DEFAULT '0000-00-00',
  `faellig` date DEFAULT '0000-00-00',
  `kopftext` text,
  `fusstext` text,
  `fk_fakturastatus` int(11) NOT NULL DEFAULT '0',
  `fk_template` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__srmink_leistungen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_leistungsart` smallint(11) NOT NULL,
  `fk_fakturierung` smallint(11) DEFAULT NULL,
  `titel` varchar(100) NOT NULL,
  `datum` date NOT NULL DEFAULT '0000-00-00',
  `beschreibung` text,
  `preis` decimal(20,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__srmink_leistungsarten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titel` varchar(50) DEFAULT NULL,
  `konto` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__srmink_positionen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_leistung` smallint(11) DEFAULT NULL,
  `fk_userid` smallint(11) DEFAULT NULL,
  `individual_preis` decimal(20,2) DEFAULT '0.00',
  `fk_faktura` int(11) DEFAULT '0',
  `kommentar` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=247 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__srmink_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titel` varchar(50) NOT NULL,
  `body` text,
  `position` text,
  `aktiv` int(11) NOT NULL DEFAULT '1',
  `rand_links` int(11) DEFAULT '20',
  `rand_rechts` int(11) DEFAULT '20',
  `rand_oben` int(11) DEFAULT '20',
  `rand_unten` int(11) DEFAULT '20',
  `image_zeigen` int(11) DEFAULT '0',
  `image_x` int(11) DEFAULT NULL,
  `image_y` int(11) DEFAULT NULL,
  `image_breite` int(11) DEFAULT NULL,
  `image_hoehe` int(11) DEFAULT NULL,
  `image_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__srmink_userfaktura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_userid` smallint(11) DEFAULT NULL,
  `totalbetrag` decimal(20,2) DEFAULT '0.00',
  `fk_faktura` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `zahlungsdatum` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

INSERT INTO `#__srmink_status`
(`id`,
 `status`,
 `typ`)
VALUES
  (1,'offen',1),
  (2,'versendet',1),
  (3,'archiviert',1),
  (4,'offen',2),
  (5,'bezahlt',2);
