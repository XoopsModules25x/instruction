CREATE TABLE `instruction_cat` (
  `cid`             INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid`             INT(5) UNSIGNED NOT NULL DEFAULT '0',
  `title`           VARCHAR(255)    NOT NULL DEFAULT '',
  `imgurl`          VARCHAR(255)    NOT NULL DEFAULT '',
  `description`     TEXT            NOT NULL,
  `weight`          INT(11)         NOT NULL DEFAULT '0',
  `datecreated`     INT(10)         NOT NULL DEFAULT '0',
  `dateupdated`     INT(10)         NOT NULL DEFAULT '0',
  `metakeywords`    VARCHAR(255)    NOT NULL DEFAULT '',
  `metadescription` VARCHAR(255)    NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`),
  KEY `pid` (`pid`)
)
  ENGINE = MyISAM;

CREATE TABLE `instruction_instr` (
  `instrid`         INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `cid`             INT(5) UNSIGNED     NOT NULL DEFAULT '0',
  `uid`             INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  `title`           VARCHAR(255)        NOT NULL DEFAULT '',
  `status`          TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `pages`           INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  `description`     TEXT                NOT NULL,
  `datecreated`     INT(10)             NOT NULL DEFAULT '0',
  `dateupdated`     INT(10)             NOT NULL DEFAULT '0',
  `metakeywords`    VARCHAR(255)        NOT NULL,
  `metadescription` VARCHAR(255)        NOT NULL,
  PRIMARY KEY (`instrid`),
  KEY `cid` (`cid`)
)
  ENGINE = MyISAM;

CREATE TABLE `instruction_page` (
  `pageid`      INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `pid`         INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  `instrid`     INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  `uid`         INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  `title`       VARCHAR(255)        NOT NULL DEFAULT '',
  `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `type`        TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `hometext`    MEDIUMTEXT          NOT NULL,
  `footnote`    TEXT                NOT NULL,
  `weight`      INT(11)             NOT NULL DEFAULT '0',
  `keywords`    VARCHAR(255)        NOT NULL DEFAULT '',
  `description` VARCHAR(255)        NOT NULL DEFAULT '',
  `comments`    INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  `datecreated` INT(10)             NOT NULL DEFAULT '0',
  `dateupdated` INT(10)             NOT NULL DEFAULT '0',
  `dohtml`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `dosmiley`    TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `doxcode`     TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `dobr`        TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`pageid`),
  KEY `instrid` (`instrid`),
  KEY `status` (`status`),
  KEY `weight` (`weight`),
  KEY `pid` (`pid`),
  KEY `type` (`type`)
)
  ENGINE = MyISAM;
