DROP TABLE IF EXISTS `eayso_vol_certs`;
CREATE TABLE         `eayso_vol_certs` (
  `eayso_vol_cert_id` int (10) unsigned NOT NULL auto_increment,
  `aysoid`            char(20) default NULL,
  `cert_cat`          int (10) unsigned default NULL,
  `cert_type`         int (10) unsigned default NULL,
  `cert_desc`         char(40) default NULL,
  `cert_date`         char (8) default NULL,

  PRIMARY KEY (`eayso_vol_cert_id`),
  UNIQUE  KEY  `eayso_vol_certs_i0` (`aysoid`,`cert_desc`,`cert_date`),
          KEY  `eayso_vol_certs_i1` (`cert_desc`,`cert_date`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
