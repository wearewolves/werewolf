# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 물오름달 31일 PM 04:03 
# 서버 버전: 4.00.22
# PHP 버전: 4.4.1
# 데이터베이스 : `werewolf2`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_suddenDeath`
#

CREATE TABLE `zetyx_board_werewolf_suddenDeath` (
  `no` int(20) unsigned NOT NULL auto_increment,
  `game` int(20) unsigned NOT NULL default '0',
  `name` varchar(20) default NULL,
  `player` int(20) unsigned NOT NULL default '0',
  `character` int(20) unsigned NOT NULL default '0',
  `truecharacter` int(20) unsigned NOT NULL default '0',
  `deathday` tinyint(4) unsigned NOT NULL default '0',
  `ip` varchar(15) default NULL,
  `reg_data` int(13) default NULL,
  PRIMARY KEY  (`no`)
) TYPE=MyISAM;

