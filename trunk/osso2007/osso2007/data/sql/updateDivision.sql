-- =========================================
-- Division
DROP TABLE IF EXISTS `division`;
CREATE TABLE `division` (
  `division_id` int(10)  unsigned NOT NULL auto_increment,
  `sortx`       int(10)  unsigned default NULL,
  `desc_pick`   char(20) default NULL,
  `desc_long`   char(20) default NULL,
  PRIMARY KEY  (`division_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `division` VALUES 
( 1, 1,'U06B','U06 Boys' ),
( 2, 2,'U06G','U06 Girls'),
( 3, 3,'U06C','U06 Coed' ),
( 4, 4,'U08B','U08 Boys' ),
( 5, 5,'U08G','U08 Girls'),
( 6, 6,'U08C','U08 Coed' ),
( 7, 7,'U10B','U10 Boys' ),
( 8, 8,'U10G','U10 Girls'),
( 9, 9,'U10C','U10 Coed' ),
(10,10,'U12B','U12 Boys' ),
(11,11,'U12G','U12 Girls'),
(12,12,'U12C','U12 Coed' ),
(13,13,'U14B','U14 Boys' ),
(14,14,'U14G','U14 Girls'),
(15,15,'U14C','U14 Coed' ),
(16,16,'U16B','U16 Boys' ),
(17,17,'U16G','U16 Girls'),
(18,18,'U16C','U16 Coed' ),
(19,19,'U19B','U19 Boys' ),
(20,20,'U19G','U19 Girls'),
(21,21,'U19C','U19 Coed' ),
(22,22,'U05B','U05 Boys' ),
(23,23,'U05G','U05 Girls'),
(24,24,'U05C','U05 Coed' );