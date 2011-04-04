CREATE TABLE IF NOT EXISTS  `#__crosswords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `questions` int(10) unsigned NOT NULL DEFAULT '15',
  `rows` int(10) unsigned NOT NULL DEFAULT '15',
  `columns` int(10) unsigned NOT NULL DEFAULT '15',
  `catid` int(10) unsigned NOT NULL,
  `alias` varchar(255) NOT NULL,
  `published` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS  `#__crosswords_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `lft` int(10) unsigned NOT NULL DEFAULT '0',
  `rgt` int(10) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS  `#__crosswords_config` (
  `config_name` varchar(255) NOT NULL,
  `config_value` varchar(255) NOT NULL,
  PRIMARY KEY (`config_name`)
) TYPE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS  `#__crosswords_keywords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `keyword` varchar(32) NOT NULL,
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS  `#__crosswords_questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `keyid` int(10) unsigned NOT NULL,
  `row` int(10) unsigned NOT NULL,
  `column` int(10) unsigned NOT NULL,
  `axis` tinyint(3) unsigned NOT NULL,
  `position` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS  `#__crosswords_response_details` (
  `response_id` int(10) unsigned NOT NULL DEFAULT '0',
  `question_id` int(10) unsigned NOT NULL,
  `crossword_id` int(10) unsigned NOT NULL,
  `answer` varchar(32) NOT NULL,
  `valid` tinyint(1) unsigned NOT NULL DEFAULT '0'
) TYPE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS  `#__crosswords_responses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `solved` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM CHARACTER SET `utf8`;