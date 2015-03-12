CREATE TABLE IF NOT EXISTS `three_drops` (
  `id` int(11) NOT NULL auto_increment,
  `tier_one` varchar(255) NOT NULL,
  `tier_two` varchar(255) NOT NULL,
  `tier_three` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM ;

INSERT INTO `three_drops` (`id`, `tier_one`, `tier_two`, `tier_three`) VALUES
(1, 'Chevy', 'Camaro', 'Black'),
(2, 'Chevy', 'Camaro', 'White'),
(3, 'Chevy', 'Trailblazer', 'Blue'),
(4, 'Chevy', 'Trailblazer', 'Red'),
(5, 'Chevy', 'Camaro', 'Red'),
(6, 'Ford', 'Mustang', 'White'),
(7, 'Ford', 'Mustang', 'Red'),
(8, 'Ford', 'Mustang', 'Black'),
(9, 'Ford', 'F-350', 'White'),
(10, 'Ford', 'F-350', 'Green'),
(11, 'Honda', 'Civic', 'Black'),
(12, 'Honda', 'Civic', 'Red'),
(13, 'Honda', 'Civic', 'Silver'),
(14, 'Honda', 'Prelude', 'Red'),
(15, 'Honda', 'Prelude', 'White');
