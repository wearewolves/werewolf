# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 물오름달 31일 PM 04:04 
# 서버 버전: 4.00.22
# PHP 버전: 4.4.1
# 데이터베이스 : `werewolf2`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_comment_werewolf_commentType`
#

CREATE TABLE `zetyx_board_comment_werewolf_commentType` (
  `game` int(20) NOT NULL default '0',
  `comment` int(11) NOT NULL default '0',
  `type` varchar(20) default '0',
  `character` int(20) NOT NULL default '0'
) TYPE=MyISAM;

# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 매듭달 21일 PM 04:20 
# 서버 버전: 4.00.26
# PHP 버전: 4.4.7p1
# 데이터베이스 : `werewolf5`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_bigmouth`
#

CREATE TABLE `zetyx_board_werewolf_bigmouth` (
  `year` int(20) unsigned NOT NULL default '0',
  `MONTH` int(20) unsigned NOT NULL default '0',
  `player` int(20) unsigned NOT NULL default '0',
  `commentCount` int(20) unsigned NOT NULL default '0',
  `gameCount` int(20) unsigned NOT NULL default '0'
) TYPE=MyISAM;

# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 물오름달 31일 PM 04:00 
# 서버 버전: 4.00.22
# PHP 버전: 4.4.1
# 데이터베이스 : `werewolf2`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_character`
#

CREATE TABLE `zetyx_board_werewolf_character` (
  `no` int(20) unsigned NOT NULL auto_increment,
  `set` int(20) NOT NULL default '0',
  `character` varchar(20) NOT NULL default 'no name',
  `half_image` varchar(255) NOT NULL default '',
  `greeting` text NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY  (`no`)
) TYPE=MyISAM;

#
# 테이블의 덤프 데이터 `zetyx_board_werewolf_character`
#

INSERT INTO `zetyx_board_werewolf_character` VALUES (1, 1, '꽃집 아저씨 벤카이스', 'ben.jpg', '허허 꽃사세요. \r\n이쁘고 싱싱한 꽃이 가득입니다.\r\n\r\n저희 집 꽃은 향기가 아주 좋습니다.\r\n향기를 한번 맡으면 피로가 싹 풀리죠!', '뭐라고! 인랑이 있다고요?!\r\n...\r\n\r\n사실 저희 집 꽃에서는 인랑을 쫓아내는 특수한 향이 나온답니다!\r\n다 팔리기 전에 빨리 사가세요!!');
INSERT INTO `zetyx_board_werewolf_character` VALUES (2, 1, '경찰 다이거츠', 'daiguts.jpg', '안녕하세요.\r\n마을의 안녕과 질서를 유지하는 다이거츠입니다.\r\n\r\n하하. 인랑이 있냐고요?\r\n걱정하지 마세요. 인랑따윈 없습니다.', '상부에서 인랑이 존재할 지도 모른다는 연락을 받았습니다.\r\n위험할 지 모르니 빨리 안전한 곳으로 대피하세요.');
INSERT INTO `zetyx_board_werewolf_character` VALUES (3, 1, '여고생 글라리스', 'glaris.jpg', '아자 난 여고생이다!', '인랑도 나의 귀여움으로 녹여주겠어♡');
INSERT INTO `zetyx_board_werewolf_character` VALUES (4, 1, '회사원 그레이', 'gray.jpg', '쳇 회사는 지겨워 죽겠어.', '뭐.. 인랑이 있다고?\r\n\r\n썰렁한 농담이구만..');
INSERT INTO `zetyx_board_werewolf_character` VALUES (5, 1, '사장님 헤일렌', 'heilen.jpg', '허허 반갑소. 난 마을의 경제를 책임지고 있지.\r\n\r\n마을에 인랑이 나온다는 소문 때문에 관광객이 많아져서 다행이야. 허허.', '쯧쯧..\r\n어떤 놈이 또 이상한 소문을 내고 다니는가 보군. 가끔 마을에 자기가 인랑을 봤다고 외치는 미친 놈이 나오거든.');
INSERT INTO `zetyx_board_werewolf_character` VALUES (6, 1, '야구선수 하이크만', 'hikeman.jpg', '하하!! 이번 휴가 재밌게 보내려고 이런 촌 구석에 와봤어. \r\n\r\n이 마을에는 인랑이란게 나온다는 소문이 있다면서?\r\n하하하. 재미있는 휴가가 되겠어.', '하..하하. 남은 훈련이 있어서 빨리 돌아가야겠어.\r\n');
INSERT INTO `zetyx_board_werewolf_character` VALUES (7, 1, '목사 홀그렌', 'holgren.jpg', '인랑 따위는 없습니다.\r\n소문에 흔들리지 말고 믿음을 굳게 지키십시오.', '인랑이 존재한다니..\r\n\r\n오오. 이것은 신의 시련입니다.\r\n당신의 믿음을 증명하시오.');
INSERT INTO `zetyx_board_werewolf_character` VALUES (8, 1, '여행자 케이시', 'keici.jpg', '이 마을에 특이한 소문이 있다면서?\r\n소문이 사실이면 좋겠군.', '드디어 찾았다!\r\n이번에는 꼭 잡고 말겠어.');
INSERT INTO `zetyx_board_werewolf_character` VALUES (9, 1, '걸인 무명', 'mu.jpg', '아이고 배고파..\r\n\r\n길에 떨어진 만두없나.. 닭꼬치도 좋고..', '뭐? 무서운 인랑이 나타났다고?\r\n\r\n여보슈. 인랑보다 사채업자가 더 무서운 거라고!');
INSERT INTO `zetyx_board_werewolf_character` VALUES (10, 1, '양아치 피시번', 'pcburn.jpg', '뭐. 인랑을 아냐고?\r\n크크크크. 그럼 아주 자알 알지. 크크크.', '뭐 인랑이 나타났다고?...\r\n\r\n이번엔 누가 소문을 낸 거지?\r\n이 몸의 재미를 가로채다니.');
INSERT INTO `zetyx_board_werewolf_character` VALUES (11, 1, '수녀 프리시아', 'prisia.jpg', '늘 평안이 함께 하시길.', '인랑이 나타났다고요?!\r\n\r\n엑소시스트를 준비하겠습니다.');
INSERT INTO `zetyx_board_werewolf_character` VALUES (12, 1, '가정주부 퓨리엘', 'puriel.jpg', '호호호. 인랑을 아냐고요?\r\n\r\n그럼요. 사실... 이건 비밀인데.\r\n우리 마을은 인랑님이 보호해 주고 있으신걸요.', '아아. 드디어 인랑님을 만날 수 있을지 모르겠군요.');
INSERT INTO `zetyx_board_werewolf_character` VALUES (13, 1, '촌장 슈랭크', 'syurank.jpg', '이 마을의 촌장 슈랭크라 하오.\r\n\r\n허허. 인랑이라.. 인랑은 이 마을의 오래된 전설이지.', '아니 그 전설이 사실이었단 말인가!\r\n\r\n그럴 리 없어. 그럴 리가….');
INSERT INTO `zetyx_board_werewolf_character` VALUES (14, 1, '변호사 발데스', 'valdes.jpg', '어떠한 사건도 변호할 수 있는 유능한 변호사 발데스입니다.\r\n\r\n제가 변호하지 못하는 일이란 있을 수 없죠.', '인랑님 제가 변호해 드리겠습니다.\r\n지금 바로 전화주세요.');
INSERT INTO `zetyx_board_werewolf_character` VALUES (15, 1, '체육선생 울버린', 'wolver2.jpg', '학교는 내가 지킨다!\r\n\r\n엎드려 이 자식아!!', '뭐 인랑이 나타났다고?\r\n...\r\n\r\n엎드려 이 자식아!!');
INSERT INTO `zetyx_board_werewolf_character` VALUES (16, 1, '어린이 더블류', 'ww.jpg', '인랑이요?\r\n새로 나온 아이템인가요?', '인랑 그런거 보다 내가 가지고 있는 아이템이 짱쎈거에요.\r\n우씨 아빠한테 이를거야.');
INSERT INTO `zetyx_board_werewolf_character` VALUES (17, 1, '마담 지르타스', 'zir.jpg', '호호호. 쓸데없는 소문은 잊어버리고 한잔 받으세요.', '설마 인랑도 미인을 좋아하는 건 아니겠죠?\r\n\r\n나의 이 미모 때문에 팔자가 사나워요. \r\n아휴. 이제는 인랑까지...');
INSERT INTO `zetyx_board_werewolf_character` VALUES (18, 1, '여고생 엘토리아', '16.jpg', '어머나 여기는 어디지? \r\n잘 부탁합니다. 데헷', '행동 프로세스에 일시적 장애. \r\n수면모드로 이행합니다 삐- ');
INSERT INTO `zetyx_board_werewolf_character` VALUES (19, 1, '여행자 아리스', 'aris.jpg', '불가사의한 세계만을 여행하는 시간 여행자 아리스라고 해요~', '꺅!! 흰 옷입은 사람들이 쫓아와요!! 도와주세요!!');
INSERT INTO `zetyx_board_werewolf_character` VALUES (20, 1, '꽃집 시이라', 'benkaisy.jpg', '꽃 사세여~', '꽃 밭의 꽃들이 전부 다…. 도대체 누가 이런 짓을 TT');
INSERT INTO `zetyx_board_werewolf_character` VALUES (21, 1, '경찰 마리', 'daiguta.jpg', '마을을 수호하는 순경 마리입니다! 수상한 자가 있으면 바로바로 \r\n파출소로 연락주세요!\r\n', '옆 마을 순경 다이거츠와 연락이 안되네? 뭔 일이라도 있나…');
INSERT INTO `zetyx_board_werewolf_character` VALUES (22, 1, '가정주부 퓨리', 'fury.jpg', '제 아내가 저보다 월급이 많은 관계로… 저는 집에서 밥하고 빨래하는게 \r\n더 낫다고 하더군요;;\r\n', '오늘은 아내님을 위해 뭘 만들까~ ');
INSERT INTO `zetyx_board_werewolf_character` VALUES (23, 1, '남고생 레릭', 'glassian.jpg', '천재 고교생 탐정. 래릭이라고 합니다.\r\n모든 사건은 제가 해결하겠습니다.', '모든 비밀을 알았어!!! 범인은…');
INSERT INTO `zetyx_board_werewolf_character` VALUES (24, 1, 'OL 그레인져', 'graya.jpg', '커피 심부름은 안하는 그레인져 입니다.', '지들은 손이 없어 발이 없어…');
INSERT INTO `zetyx_board_werewolf_character` VALUES (25, 1, '야구선수 아만다', 'hikewoman.jpg', '여기 우리 오빠가 휴가를 왔다고 들었는데… ', '이건…오빠의 야구 배트…?');
INSERT INTO `zetyx_board_werewolf_character` VALUES (26, 1, '전도사 레나', 'holgrena.jpg', '신앙의 힘으로 모든 것을 이겨냅시다. 믿음만이 우리를 구원할 겁니다.', '여기 넣어뒀던 비자금이 다 어디로 갔지… 빨리 여길 떠야 하는데…');
INSERT INTO `zetyx_board_werewolf_character` VALUES (27, 1, '사장 일레느', 'irenu.jpg', '일 해 일!! 할 일이 없어도 해!', '당신. 내일 해고야.');
INSERT INTO `zetyx_board_werewolf_character` VALUES (28, 1, '걸인 뮤 메이', 'mumei.jpg', '배고파…', '어디서 맛있는 냄새가…');
INSERT INTO `zetyx_board_werewolf_character` VALUES (29, 1, '양아치 버지니아', 'pcburnne.jpg', '이 마을의 건달패는 내가 꽉 쥐고 있지. 혹시 뒷 정보가 필요하면 나에게 오라구~', '뭐? 당했어? 누가?');
INSERT INTO `zetyx_board_werewolf_character` VALUES (30, 1, '수도사 프리스트', 'priest.jpg', '수도원에서 파견나온 견습 수도사 입니다. 여러분 죄송하지만 이 십자가를 한 번씩\r\n만져 주실 수 있나요? \r\n', '….이 십자가. 효과가 없나;; 이번에 잡을 녀석이 흡혈귀가 아니었나?');
INSERT INTO `zetyx_board_werewolf_character` VALUES (31, 1, '촌장 쉬린', 'shrin.jpg', '어서 오세요. 촌장 쉬린입니다. \r\n작은 마을이지만 편히 쉬다 가세요.', '왜 이렇게 다리가 쑤시는지 원… 비가 오려나?');
INSERT INTO `zetyx_board_werewolf_character` VALUES (32, 1, '변호사 발테시아', 'valdesia.jpg', '역시 나의 실력은 이런 시골마을까지 알려져 있었어! ', '어제 먹은게 안 좋은가… 왜 이리 속이 안 좋지…');
INSERT INTO `zetyx_board_werewolf_character` VALUES (33, 1, '체육선생 린다', 'wolverina.jpg', '학교는 제가 지켜요.', '거기 농땡이치는 녀석!! 운동장 100바퀴 뛰고 와!!');
INSERT INTO `zetyx_board_werewolf_character` VALUES (34, 1, '어린이 더블린', 'wrin.jpg', '나…봤어… 크고 커다란… 그림자…', '토끼 인형… 만져 볼래요…?');
INSERT INTO `zetyx_board_werewolf_character` VALUES (35, 1, '마담 지르타크', 'zirtak.jpg', '어머나~ 이런 곳에 처음 보는 멋진 남자가. \r\n어서와요. 지크타크라고 한답니다. \r\n외상은 사절이에요~\r\n', '손님 어제 계산을 잊으신 것 같은데요. 140만원입니다.\r\n카드 결제도 되걸랑요? 우후훗.\r\n');

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
# 테이블 구조 `zetyx_board_werewolf_deathNote_result`
#

CREATE TABLE `zetyx_board_werewolf_deathNote_result` (
  `game` int(20) unsigned NOT NULL default '0',
  `day` tinyint(5) unsigned NOT NULL default '0',
  `injured` int(20) unsigned NOT NULL default '0'
) TYPE=MyISAM;

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
# 테이블 구조 `zetyx_board_werewolf_deathNote`
#

CREATE TABLE `zetyx_board_werewolf_deathNote` (
  `game` int(20) unsigned NOT NULL default '0',
  `day` tinyint(5) unsigned NOT NULL default '0',
  `werewolf` int(20) unsigned NOT NULL default '0',
  `injured` int(20) unsigned NOT NULL default '0'
) TYPE=MyISAM;

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

# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 매듭달 21일 PM 04:18 
# 서버 버전: 4.00.26
# PHP 버전: 4.4.7p1
# 데이터베이스 : `werewolf5`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_detect`
#

CREATE TABLE `zetyx_board_werewolf_detect` (
  `game` int(20) unsigned NOT NULL default '0',
  `DAY` tinyint(5) unsigned NOT NULL default '0',
  `target` int(20) unsigned NOT NULL default '0'
) TYPE=MyISAM;
# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 매듭달 21일 PM 04:18 
# 서버 버전: 4.00.26
# PHP 버전: 4.4.7p1
# 데이터베이스 : `werewolf5`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_entry`
#

CREATE TABLE `zetyx_board_werewolf_entry` (
  `no` int(20) unsigned NOT NULL auto_increment,
  `game` int(20) unsigned NOT NULL default '1',
  `vote` int(1) unsigned NOT NULL default '0',
  `name` varchar(20) default NULL,
  `player` int(20) unsigned NOT NULL default '0',
  `character` int(20) unsigned NOT NULL default '0',
  `truecharacter` int(20) unsigned NOT NULL default '0',
  `victim` int(1) unsigned NOT NULL default '0',
  `alive` varchar(10) NOT NULL default '생존',
  `deathday` int(6) default '0',
  `deathtype` varchar(10) default NULL,
  `comment` int(1) unsigned NOT NULL default '0',
  `suddenCount` int(1) unsigned NOT NULL default '0',
  `normal` int(8) unsigned NOT NULL default '20',
  `memo` int(8) unsigned NOT NULL default '10',
  `secret` int(8) unsigned NOT NULL default '40',
  `grave` int(8) unsigned NOT NULL default '20',
  `telepathy` int(8) unsigned NOT NULL default '1',
  `ip` varchar(15) default NULL,
  `isConfirm` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`no`)
) TYPE=MyISAM;


# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 매듭달 21일 PM 04:17 
# 서버 버전: 4.00.26
# PHP 버전: 4.4.7p1
# 데이터베이스 : `werewolf5`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_gameinfo`
#

CREATE TABLE `zetyx_board_werewolf_gameinfo` (
  `game` int(20) unsigned NOT NULL default '0',
  `rule` int(20) NOT NULL default '0',
  `day` tinyint(5) NOT NULL default '0',
  `startingTime` int(13) unsigned NOT NULL default '0',
  `deathtime` int(13) unsigned NOT NULL default '0',
  `players` int(20) unsigned NOT NULL default '0',
  `result` varchar(10) NOT NULL default '',
  `state` varchar(10) NOT NULL default '준비중',
  `termOfDay` int(13) unsigned NOT NULL default '0',
  `characterSet` int(20) unsigned NOT NULL default '0',
  `useTimetable` int(1) NOT NULL default '0',
  `win` int(1) NOT NULL default '0',
  `good` int(2) unsigned NOT NULL default '0',
  `bad` int(2) unsigned NOT NULL default '0'
) TYPE=MyISAM;

ALTER TABLE  `zetyx_board_werewolf_gameinfo` ADD  `seal` VARCHAR( 10 ) NOT NULL DEFAULT  '제안';
ALTER TABLE  `zetyx_board_werewolf_gameinfo` ADD  `seal_yes` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `zetyx_board_werewolf_gameinfo` ADD  `seal_no` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0';

ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `subRule` INT(20) NOT NULL DEFAULT '0' AFTER `rule`;

ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `delayAfter` MEDIUMINT(13) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `delayBefore` MEDIUMINT(13) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `delayAfterUsed` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `delayBeforeUsed` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';

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
# 테이블 구조 `zetyx_board_werewolf_guard`
#

CREATE TABLE `zetyx_board_werewolf_guard` (
  `game` int(20) unsigned NOT NULL default '0',
  `day` tinyint(5) unsigned NOT NULL default '0',
  `hunter` int(20) unsigned NOT NULL default '0',
  `purpose` int(20) unsigned NOT NULL default '0',
  `result` varchar(7) default NULL
) TYPE=MyISAM;

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
# 테이블 구조 `zetyx_board_werewolf_revelation`
#

CREATE TABLE `zetyx_board_werewolf_revelation` (
  `game` int(20) unsigned NOT NULL default '0',
  `day` tinyint(5) unsigned NOT NULL default '0',
  `type` varchar(10) default NULL,
  `prophet` int(20) unsigned NOT NULL default '0',
  `mystery` int(20) unsigned NOT NULL default '0',
  `result` tinyint(1) unsigned NOT NULL default '0'
) TYPE=MyISAM;

# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 매듭달 21일 PM 04:16 
# 서버 버전: 4.00.26
# PHP 버전: 4.4.7p1
# 데이터베이스 : `werewolf5`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_revenge`
#

CREATE TABLE `zetyx_board_werewolf_revenge` (
  `game` int(20) unsigned NOT NULL default '0',
  `target` int(20) unsigned NOT NULL default '0'
) TYPE=MyISAM;

#
# 테이블의 덤프 데이터 `zetyx_board_werewolf_revenge`
#

INSERT INTO `zetyx_board_werewolf_revenge` VALUES (1285, 728);
INSERT INTO `zetyx_board_werewolf_revenge` VALUES (1289, 22);
INSERT INTO `zetyx_board_werewolf_revenge` VALUES (1307, 2739);
INSERT INTO `zetyx_board_werewolf_revenge` VALUES (1308, 176);
INSERT INTO `zetyx_board_werewolf_revenge` VALUES (1306, 895);
INSERT INTO `zetyx_board_werewolf_revenge` VALUES (1331, 196);

# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 매듭달 21일 PM 04:15 
# 서버 버전: 4.00.26
# PHP 버전: 4.4.7p1
# 데이터베이스 : `werewolf5`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_rule`
#

CREATE TABLE `zetyx_board_werewolf_rule` (
  `no` int(20) unsigned NOT NULL auto_increment,
  `name` varchar(20) default NULL,
  `min_player` int(10) NOT NULL default '0',
  `max_player` int(10) NOT NULL default '0',
  PRIMARY KEY  (`no`)
) TYPE=MyISAM;

#
# 테이블의 덤프 데이터 `zetyx_board_werewolf_rule`
#

INSERT INTO `zetyx_board_werewolf_rule` VALUES (1, '기본', 11, 16);
INSERT INTO `zetyx_board_werewolf_rule` VALUES (2, '햄스터', 11, 17);
INSERT INTO `zetyx_board_werewolf_rule` VALUES (3, '익스펜션', 9, 17);


# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 매듭달 21일 PM 04:14 
# 서버 버전: 4.00.26
# PHP 버전: 4.4.7p1
# 데이터베이스 : `werewolf5`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_secretletter`
#

CREATE TABLE `zetyx_board_werewolf_secretletter` (
  `game` int(20) unsigned NOT NULL default '0',
  `day` tinyint(5) unsigned NOT NULL default '0',
  `from` int(20) unsigned NOT NULL default '0',
  `to` int(20) unsigned NOT NULL default '0',
  `message` int(11) unsigned NOT NULL default '0',
  `answer` int(11) unsigned NOT NULL default '0'
) TYPE=MyISAM;
# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 물오름달 31일 PM 04:03 
# 서버 버전: 4.00.22
# PHP 버전: 4.4.1
# 데이터베이스 : `werewolf2`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_suddenDeath`
#

CREATE TABLE `zetyx_board_werewolf_suddenDeath` (
  `no` int(20) unsigned NOT NULL auto_increment,
  `game` int(20) unsigned NOT NULL default '0',
  `name` varchar(20) default NULL,
  `player` int(20) unsigned NOT NULL default '0',
  `character` int(20) unsigned NOT NULL default '0',
  `truecharacter` int(20) unsigned NOT NULL default '0',
  `deathday` tinyint(4) unsigned NOT NULL default '0',
  `ip` varchar(15) default NULL,
  `reg_data` int(13) default NULL,
  PRIMARY KEY  (`no`)
) TYPE=MyISAM;

# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 매듭달 21일 PM 04:11 
# 서버 버전: 4.00.26
# PHP 버전: 4.4.7p1
# 데이터베이스 : `werewolf5`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_timetable`
#

CREATE TABLE zetyx_board_werewolf_timetable (
  game int(20) unsigned NOT NULL default '0',
  DAY tinyint(5) unsigned NOT NULL default '0',
  reg_date int(13) default NULL
) TYPE=MyISAM;
# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 매듭달 21일 PM 04:10 
# 서버 버전: 4.00.26
# PHP 버전: 4.4.7p1
# 데이터베이스 : `werewolf5`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_truecharacter`
#

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
  PRIMARY KEY  (`no`),
  KEY `headnum` (`character`)
) TYPE=MyISAM;

#
# 테이블의 덤프 데이터 `zetyx_board_werewolf_truecharacter`
#

INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (1, 0, 0, '마을사람', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (2, 0, 0, '점쟁이', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (3, 0, 0, '영매자', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (4, 0, 1, '광인', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (5, 1, 1, '인랑', 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (6, 0, 0, '사냥꾼', 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0);
INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (7, 0, 0, '초능력자', 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0);
INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (8, 2, 2, '햄스터', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (9, 1, 1, '외로운 늑대', 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0);
INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (10, 1, 1, '인랑 리더', 1, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0);
INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (11, 0, 0, '복수자', 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0);
INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (12, 0, 0, '귀족', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (13, 0, 0, '촌장', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0);
INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (14, 3, 3, '디아블로', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `zetyx_board_werewolf_truecharacter` VALUES (15, 0, 0, '보안관', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1);   
# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# 호스트: localhost
# 처리한 시간: 2008년 물오름달 31일 PM 04:03 
# 서버 버전: 4.00.22
# PHP 버전: 4.4.1
# 데이터베이스 : `werewolf2`
# --------------------------------------------------------

#
# 테이블 구조 `zetyx_board_werewolf_vote`
#

CREATE TABLE `zetyx_board_werewolf_vote` (
  `game` int(20) unsigned NOT NULL default '0',
  `day` tinyint(5) unsigned NOT NULL default '0',
  `voter` int(20) unsigned NOT NULL default '0',
  `candidacy` int(20) unsigned NOT NULL default '0',
  KEY `headnum` (`game`)
) TYPE=MyISAM;
