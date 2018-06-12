<?php
//alert($db_version);
global $c;
$db_version = regValue('db_version');
switch ($db_version) {
	case 0:{
		$c->query("CREATE TABLE IF NOT EXISTS `sys_register` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `key` varchar(128) NOT NULL,
		  `value` text,
		  `group` varchar(32) DEFAULT NULL,
		  `description` text,
		  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `register_key` (`key`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		regUpdate('db_version', $db_version + 1);
	}
	case 1:{
		$c->query("CREATE TABLE IF NOT EXISTS `application_status`(
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(256) NOT NULL,
				`details` TEXT,				
			  `entry_by` int(10) unsigned NOT NULL,
			  `entry_time` datetime NOT NULL,
			  `modify_by` int(10) unsigned DEFAULT NULL,
			  `modify_time` datetime DEFAULT NULL,
			  `trash` tinyint(1) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		regUpdate('db_version', $db_version + 1);
	}
	case 2:{
		$c->query("CREATE TABLE IF NOT EXISTS `exchange_bank_account`(
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(256) NOT NULL,
				`country` ENUM('BD', 'MY') NOT NULL,
				`details` TEXT,				
			  `entry_by` int(10) unsigned NOT NULL,
			  `entry_time` datetime NOT NULL,
			  `modify_by` int(10) unsigned DEFAULT NULL,
			  `modify_time` datetime DEFAULT NULL,
			  `trash` tinyint(1) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`),
			  CONSTRAINT `exchange_entry_by` FOREIGN KEY (`entry_by`) REFERENCES `sys_user` (`id`),
			  CONSTRAINT `exchange_modify_by` FOREIGN KEY (`modify_by`) REFERENCES `sys_user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		$c->query("CREATE TABLE IF NOT EXISTS `exchange`(
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(256) NOT NULL,
				`details` TEXT,				
			  `entry_by` int(10) unsigned NOT NULL,
			  `entry_time` datetime NOT NULL,
			  `modify_by` int(10) unsigned DEFAULT NULL,
			  `modify_time` datetime DEFAULT NULL,
			  `trash` tinyint(1) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`),
			  CONSTRAINT `ex_entry_by` FOREIGN KEY (`entry_by`) REFERENCES `sys_user` (`id`),
			  CONSTRAINT `ex_modify_by` FOREIGN KEY (`modify_by`) REFERENCES `sys_user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		regUpdate('db_version', $db_version + 1);
	}
	case 3:{
		$c->query("ALTER TABLE worker ADD COLUMN notes2 TEXT AFTER notes;");
		$c->query("ALTER TABLE worker ADD COLUMN notes3 TEXT AFTER notes2;");
		regUpdate('db_version', $db_version + 1);		
	}
	case 4:{
		$c->query("ALTER TABLE page_pricing ADD COLUMN number_of_column INT UNSIGNED DEFAULT 1;");
		regUpdate('db_version', $db_version + 1);		
	}
	case 5:{
		$c->query("ALTER TABLE page_pricing ADD COLUMN price DECIMAL(10,2) UNSIGNED DEFAULT 1;");
		regUpdate('db_version', $db_version + 1);		
	}
	case 7:{
		$c->query("ALTER TABLE page_pricing ADD COLUMN `group` int UNSIGNED NULL;");
		regUpdate('db_version', $db_version + 1);		
	}
}