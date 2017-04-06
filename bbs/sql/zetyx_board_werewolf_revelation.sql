# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 물오름달 31일 PM 04:02 
# 서버 버전: 4.00.22
# PHP 버전: 4.4.1
# 데이터베이스 : `werewolf2`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_revelation`
#

CREATE TABLE `zetyx_board_werewolf_revelation` (
  `game` int(20) unsigned NOT NULL default '0',
  `day` tinyint(5) unsigned NOT NULL default '0',
  `type` varchar(10) default NULL,
  `prophet` int(20) unsigned NOT NULL default '0',
  `mystery` int(20) unsigned NOT NULL default '0',
  `result` tinyint(1) unsigned NOT NULL default '0'
) TYPE=MyISAM;

