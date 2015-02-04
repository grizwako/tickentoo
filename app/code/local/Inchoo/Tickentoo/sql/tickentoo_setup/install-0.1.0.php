<?php

$installer = $this;
$installer->startSetup();
$installer->run("
CREATE TABLE `inchoo_tickentoo_ticket` (
  `ticket_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(160) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`ticket_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;



CREATE TABLE `inchoo_tickentoo_ticket_reply` (
  `reply_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  `author_type` enum('admin','customer') NOT NULL,
  `author_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`reply_id`),
  KEY `FK_replies_in_tickets_with_actions` (`ticket_id`),
  CONSTRAINT `FK_replies_in_tickets_with_actions` FOREIGN KEY (`ticket_id`) REFERENCES `inchoo_tickentoo_ticket` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
");
$installer->endSetup();