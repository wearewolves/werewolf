-- phpMyAdmin SQL Dump
--
-- ���̺��� ���� ������ `zetyx_board_werewolf_subrule`
--

-- http://www.phpmyadmin.net
--
-- ȣ��Ʈ: localhost
-- ó���� �ð�: 17-05-21 00:21 
-- ���� ����: 4.0.22
-- PHP ����: 4.4.9p2


--
-- �����ͺ��̽�: `werewolf6`
--

-- --------------------------------------------------------

--
-- ���̺� ���� `zetyx_board_werewolf_subrule`
--

CREATE TABLE IF NOT EXISTS `zetyx_board_werewolf_subrule` (
  `no` int(20) unsigned NOT NULL auto_increment,
  `name` varchar(20) default NULL,
  PRIMARY KEY  (`no`)
) TYPE=MyISAM;

INSERT INTO `zetyx_board_werewolf_subrule` (`no`, `name`) VALUES
(1, '�ζ� ���� ����'),
(2, 'NPC ���� ���� �ο�'),
(3, '�ڷ��Ľ� ��� �Ұ�'),
(4, '��� ��ǥ');
(5, '������ �ɷ� ����ȭ');