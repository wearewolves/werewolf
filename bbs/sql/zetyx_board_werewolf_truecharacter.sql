-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- ȣ��Ʈ: localhost
-- ó���� �ð�: 08-12-22 01:07 
-- ���� ����: 5.0.41
-- PHP ����: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- �����ͺ��̽�: `zeroboard`
-- 

-- --------------------------------------------------------

-- 
-- ���̺� ���� `zetyx_board_werewolf_truecharacter`
-- 

CREATE TABLE `zetyx_board_werewolf_truecharacter` (
  `no` int(20) unsigned NOT NULL auto_increment,
  `race` tinyint(10) NOT NULL default '0',
  `wintype` tinyint(10) NOT NULL default '0',
  `character` varchar(20) NOT NULL default '0',
  `secretchat` int(1) unsigned NOT NULL default '0',
  `forecast` int(1) unsigned NOT NULL default '0',
  `mediumism` int(1) unsigned NOT NULL default '0',
  `assault` int(1) unsigned NOT NULL default '0',
  `guard` int(1) unsigned NOT NULL default '0',
  `telepathy` int(1) unsigned NOT NULL default '0',
  `detect` int(1) unsigned NOT NULL default '0',
  `revenge` int(1) unsigned NOT NULL default '0',
  `half-assault` int(1) unsigned NOT NULL default '0',
  `secretletter` int(1) unsigned NOT NULL default '0',
  `double-vote` int(1) unsigned NOT NULL default '0',
  `forecast-odd` int(1) unsigned NOT NULL default '0',
  `assault-con` int(1) unsigned NOT NULL default '0',
  `mustkill` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`no`),
  KEY `headnum` (`character`)
) ENGINE=MyISAM  DEFAULT CHARSET=euckr AUTO_INCREMENT=18 ;

-- 
-- ���̺��� ���� ������ `zetyx_board_werewolf_truecharacter`
-- 

INSERT INTO `zetyx_board_werewolf_truecharacter` (`no`, `race`, `wintype`, `character`, `secretchat`, `forecast`, `mediumism`, `assault`, `guard`, `telepathy`, `detect`, `revenge`, `half-assault`, `secretletter`, `double-vote`, `forecast-odd`, `assault-con`, `mustkill`) VALUES 
(1, 0, 0, '�������', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 0, 0, '������', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 0, 0, '������', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 0, 1, '����', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 1, 1, '�ζ�', 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 0, 0, '��ɲ�', 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 0, 0, '�ʴɷ���', 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 2, 2, '�ܽ���', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, 1, 1, '�ܷο� ����', 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0),
(10, 1, 1, '�ζ� ����', 1, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(11, 0, 0, '������', 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0),
(12, 0, 0, '����', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(13, 0, 0, '����', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0),
(14, 3, 3, '��ƺ��', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(15, 0, 0, '���Ȱ�', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0),
(16, 0, 0, '���� ������', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0),
(17, 1, 1, '���� �ζ�', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0);
(18, 1, 1, '������ �ζ�', 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(19, 0, 0, '���� ����', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
