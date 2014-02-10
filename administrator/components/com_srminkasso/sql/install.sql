#
# Tabelle `#__srmink_leistungsarten`
# Kapitel 3
# Vorbereitung und Erstinstallation
#

DROP TABLE IF EXISTS `#__srmink_leistungsarten`;
CREATE TABLE IF NOT EXISTS `#__srmink_leistungsarten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titel` varchar(50) DEFAULT NULL,
  `konto` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__srmink_leistungen`;
CREATE TABLE IF NOT EXISTS `#__srmink_leistungen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_leistungsart` smallint(11) DEFAULT NULL,
  `fk_fakturierung` smallint(11) DEFAULT NULL,
  `titel` varchar(100) DEFAULT NULL,
  `datum` datetime DEFAULT '0000-00-00 00:00:00',
  `preis` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `beschreibung` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__srmink_positionen`;
CREATE TABLE IF NOT EXISTS `#__srmink_positionen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_leistung` smallint(11) DEFAULT NULL,
  `fk_userid` smallint(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__srmink_fakturierungen`;
CREATE TABLE `#__srmink_fakturierungen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titel` varchar(100) NOT NULL,
  `datum` date NOT NULL DEFAULT '0000-00-00',
  `faellig` date DEFAULT '0000-00-00',
  `Notiz` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
