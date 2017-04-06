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
# 테이블 구조 `zetyx_board_werewolf_record`
#

CREATE TABLE `zetyx_board_werewolf_record` (
  `no` int(20) unsigned NOT NULL auto_increment,
  `player` int(20) NOT NULL default '0',
  `werewolfWin` int(10) unsigned NOT NULL default '0',
  `werewolfLose` int(10) unsigned NOT NULL default '0',
  `humanWin` int(10) unsigned NOT NULL default '0',
  `humanLose` int(10) unsigned NOT NULL default '0',
  `hamsterWin` int(10) unsigned NOT NULL default '0',
  `hamsterLose` int(10) unsigned NOT NULL default '0',
  `suddenDeath` int(10) unsigned NOT NULL default '0',
  `vothDeath` int(10) NOT NULL default '0',
  `assaultDeath` int(10) NOT NULL default '0',
  `meek` int(10) NOT NULL default '0',
  `fortuneteller` int(10) NOT NULL default '0',
  `medium` int(10) NOT NULL default '0',
  `madman` int(10) NOT NULL default '0',
  `werewolf` int(10) NOT NULL default '0',
  `hunter` int(10) NOT NULL default '0',
  `psychic` int(10) NOT NULL default '0',
  `hamster` int(10) NOT NULL default '0',
  PRIMARY KEY  (`no`)
) TYPE=MyISAM;
