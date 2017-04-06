# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 물오름달 31일 PM 04:01 
# 서버 버전: 4.00.22
# PHP 버전: 4.4.1
# 데이터베이스 : `werewolf2`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_characterSet`
#

CREATE TABLE `zetyx_board_werewolf_characterSet` (
  `no` int(20) unsigned NOT NULL auto_increment,
  `name` varchar(30) default NULL,
  `maker` varchar(30) NOT NULL default '',
  `ismember` int(20) NOT NULL default '0',
  `is_use` int(1) unsigned NOT NULL default '0',
  `reg_date` int(13) NOT NULL default '0',
  `mod_date` int(13) NOT NULL default '0',
  `memo` text NOT NULL,
  PRIMARY KEY  (`no`)
) TYPE=MyISAM PACK_KEYS=0;

#
# 테이블의 덤프 데이터 `zetyx_board_werewolf_characterSet`
#

INSERT INTO `zetyx_board_werewolf_characterSet` VALUES (1, '평범한 마을 사람들', '디굴디굴대마왕', 33, 1, 0, 0, '');

