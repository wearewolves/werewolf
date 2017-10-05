-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- 호스트: localhost
-- 처리한 시간: 08-12-22 01:09 
-- 서버 버전: 5.0.41
-- PHP 버전: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- 데이터베이스: `zeroboard`
-- 

-- --------------------------------------------------------

-- 
-- 테이블 구조 `zetyx_board_werewolf_rule`
-- 

CREATE TABLE `zetyx_board_werewolf_rule` (
  `no` int(20) unsigned NOT NULL auto_increment,
  `name` varchar(20) default NULL,
  `min_player` int(10) NOT NULL default '0',
  `max_player` int(10) NOT NULL default '0',
  PRIMARY KEY  (`no`)
) ENGINE=MyISAM  DEFAULT CHARSET=euckr AUTO_INCREMENT=5 ;

-- 
-- 테이블의 덤프 데이터 `zetyx_board_werewolf_rule`
-- 

INSERT INTO `zetyx_board_werewolf_rule` (`no`, `name`, `min_player`, `max_player`) VALUES 
(1, '기본', 11, 16),
(2, '햄스터', 11, 17),
(3, '익스펜션', 9, 17),
(4, '신뢰도', 11, 17);
(5, '인스턴트', 7, 8);
(6, '참살', 11, 16);
