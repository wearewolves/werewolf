# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# ȣ��Ʈ: localhost
# ó���� �ð�: 2008�� �������� 31�� PM 04:03 
# ���� ����: 4.00.22
# PHP ����: 4.4.1
# �����ͺ��̽� : `werewolf2`
# --------------------------------------------------------

#
# ���̺� ���� `zetyx_board_werewolf_seervote`
#

CREATE TABLE `zetyx_board_werewolf_seervote` (
  `game` int(20) unsigned NOT NULL default '0',
  `day` tinyint(5) unsigned NOT NULL default '0',
  `voter` int(20) unsigned NOT NULL default '0',
  `candidacy` int(20) unsigned NOT NULL default '0',
  KEY `headnum` (`game`)
) TYPE=MyISAM;
