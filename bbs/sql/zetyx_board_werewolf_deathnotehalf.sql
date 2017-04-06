# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 매듭달 21일 PM 04:24 
# 서버 버전: 4.00.26
# PHP 버전: 4.4.7p1
# 데이터베이스 : `werewolf5`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_deathnotehalf`
#

CREATE TABLE `zetyx_board_werewolf_deathnotehalf` (
  `game` int(20) unsigned NOT NULL default '0',
  `DAY` tinyint(5) unsigned NOT NULL default '0',
  `werewolf` int(20) unsigned NOT NULL default '0',
  `injured` int(20) unsigned NOT NULL default '0'
) TYPE=MyISAM;
