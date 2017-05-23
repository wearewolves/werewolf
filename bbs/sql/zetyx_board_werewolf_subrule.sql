-- phpMyAdmin SQL Dump
--
-- 테이블의 덤프 데이터 `zetyx_board_werewolf_subrule`
--

-- http://www.phpmyadmin.net
--
-- 호스트: localhost
-- 처리한 시간: 17-05-21 00:21 
-- 서버 버전: 4.0.22
-- PHP 버전: 4.4.9p2


--
-- 데이터베이스: `werewolf6`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `zetyx_board_werewolf_subrule`
--

CREATE TABLE IF NOT EXISTS `zetyx_board_werewolf_subrule` (
  `no` int(20) unsigned NOT NULL auto_increment,
  `name` varchar(20) default NULL,
  PRIMARY KEY  (`no`)
) TYPE=MyISAM;

INSERT INTO `zetyx_board_werewolf_subrule` (`no`, `name`) VALUES
(1, '인랑 습격 가능'),
(2, 'NPC 직업 랜덤 부여'),
(3, '텔레파시 사용 불가'),
(4, '비밀 투표');
