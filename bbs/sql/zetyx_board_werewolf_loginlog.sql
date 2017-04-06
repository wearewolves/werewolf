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
# 테이블 구조 `zetyx_board_werewolf_loginlog`
#

CREATE TABLE `zetyx_board_werewolf_loginlog` (
  `no` int(20) unsigned NOT NULL auto_increment,
  `name` varchar(20) default NULL,
  `ismember` int(20) unsigned NOT NULL default '0',
  `reg_date` int(13) default NULL,
  `log_date` varchar(20) default NULL,
  `ip` varchar(15) default NULL,
  PRIMARY KEY  (`no`)
) TYPE=MyISAM;


