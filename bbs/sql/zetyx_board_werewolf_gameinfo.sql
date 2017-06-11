# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 매듭달 21일 PM 04:17 
# 서버 버전: 4.00.26
# PHP 버전: 4.4.7p1
# 데이터베이스 : `werewolf5`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_gameinfo`
#

CREATE TABLE `zetyx_board_werewolf_gameinfo` (
  `game` int(20) unsigned NOT NULL default '0',
  `rule` int(20) NOT NULL default '0',
  `day` tinyint(5) NOT NULL default '0',
  `startingTime` int(13) unsigned NOT NULL default '0',
  `deathtime` int(13) unsigned NOT NULL default '0',
  `players` int(20) unsigned NOT NULL default '0',
  `result` varchar(10) NOT NULL default '',
  `state` varchar(10) NOT NULL default '준비중',
  `termOfDay` int(13) unsigned NOT NULL default '0',
  `characterSet` int(20) unsigned NOT NULL default '0',
  `useTimetable` int(1) NOT NULL default '0',
  `win` int(1) NOT NULL default '0',
  `good` int(2) unsigned NOT NULL default '0',
  `bad` int(2) unsigned NOT NULL default '0'
) TYPE=MyISAM;

ALTER TABLE  `zetyx_board_werewolf_gameinfo` ADD  `seal` VARCHAR( 10 ) NOT NULL DEFAULT  '제안';
ALTER TABLE  `zetyx_board_werewolf_gameinfo` ADD  `seal_yes` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `zetyx_board_werewolf_gameinfo` ADD  `seal_no` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0';

ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `subRule` INT(20) NOT NULL DEFAULT '0' AFTER `rule`;

ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `delayAfter` MEDIUMINT(13) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `delayBefore` MEDIUMINT(13) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `delayAfterUsed` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `delayBeforeUsed` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';