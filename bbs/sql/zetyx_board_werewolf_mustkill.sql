-- phpMyAdmin SQL Dump
--
-- 테이블의 덤프 데이터 `zetyx_board_werewolf_mustkill`
--

-- http://www.phpmyadmin.net
--
-- 호스트: localhost
-- 처리한 시간: 17-08-26 00:21 
-- 서버 버전: 4.0.22
-- PHP 버전: 4.4.9p2


--
-- 데이터베이스: `werewolf6`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `zetyx_board_werewolf_mustkill`
--

CREATE TABLE IF NOT EXISTS `zetyx_board_werewolf_mustkill` (
  `game` int(20) unsigned NOT NULL DEFAULT 0,
	`day` tinyint(5) unsigned NOT NULL DEFAULT 0,
	`target` int(20) unsigned NOT NULL DEFAULT 0
) TYPE=MyISAM;