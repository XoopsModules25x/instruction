CREATE TABLE `instruction_cat` (
  `cid` int(5) unsigned NOT NULL auto_increment,
  `pid` int(5) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `imgurl` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `weight` int(11) NOT NULL default '0',
  `datecreated` int(10) NOT NULL default '0',
  `dateupdated` int(10) NOT NULL default '0',
  `metakeywords` varchar(255) NOT NULL default '',
  `metadescription` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`cid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM ;

CREATE TABLE `instruction_instr` (
  `instrid` int(11) unsigned NOT NULL auto_increment,
  `cid` int(5) unsigned NOT NULL default '0',
  `uid` int(11) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `pages` int(11) unsigned NOT NULL default '0',
  `description` text NOT NULL,
  `datecreated` int(10) NOT NULL default '0',
  `dateupdated` int(10) NOT NULL default '0',
  `metakeywords` varchar(255) NOT NULL,
  `metadescription` varchar(255) NOT NULL,
  PRIMARY KEY  (`instrid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM ;

CREATE TABLE `instruction_page` (
  `pageid` int(11) unsigned NOT NULL auto_increment,
  `pid` int(11) unsigned NOT NULL default '0',
  `instrid` int(11) unsigned NOT NULL default '0',
  `uid` int(11) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `type` tinyint(1) unsigned NOT NULL default '0',
  `hometext` mediumtext NOT NULL,
  `footnote` text NOT NULL,
  `weight` int(11) NOT NULL default '0',
  `keywords` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `comments` int(11) unsigned NOT NULL default '0',
  `datecreated` int(10) NOT NULL default '0',
  `dateupdated` int(10) NOT NULL default '0',
  `dohtml` tinyint(1) unsigned NOT NULL default '0',
  `dosmiley` tinyint(1) unsigned NOT NULL default '0',
  `doxcode` tinyint(1) unsigned NOT NULL default '0',
  `dobr` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pageid`),
  KEY `instrid` (`instrid`),
  KEY `status` (`status`),
  KEY `weight` (`weight`),
  KEY `pid` (`pid`),
  KEY `type` (`type`)
) ENGINE=MyISAM ;
