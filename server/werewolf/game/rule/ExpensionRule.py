#-*- coding:cp949 -*-
import random
import copy
import logging
from werewolf.game.GAME_STATE import GAME_STATE
from werewolf.game.entry.Role import Truecharacter
from werewolf.game.entry.Role import Race
from werewolf.game.rule.Rule import WerewolfRule

class ExpansionRule(WerewolfRule):
    min_players = 9
    max_players = 17

    # 기본 세팅
    temp_truecharacter = {}
    temp_truecharacter[9] = [2, 3, 6, 11, 15, 4, 5, 9]
    temp_truecharacter[10] = [1, 2, 3, 6, 11, 12, 4, 5, 9]
    temp_truecharacter[11] = [1, 1, 15, 2, 3, 6, 13, 4, 5, 10]
    temp_truecharacter[12] = [1, 1, 1, 1, 2, 3, 6, 13, 4, 5, 10]
    temp_truecharacter[13] = [1, 15, 2, 3, 6, 11, 12, 13, 4, 5, 9, 10]
    temp_truecharacter[14] = [1, 1, 1, 2, 3, 6, 11, 12, 13, 4, 5, 9, 10]
    temp_truecharacter[15] = [1, 1, 15, 2, 3, 6, 11, 12, 13, 4, 5, 5, 9, 10]
    temp_truecharacter[16] = [1, 1, 1, 1, 2, 3, 6, 11, 12, 13, 4, 5, 5, 9, 10]
    temp_truecharacter[17] = [1, 1, 1, 1, 2, 3, 6, 11, 12, 13, 4, 5, 5, 9, 10, 14]

    def __init__(self, game):
        WerewolfRule.__init__(self, game)
        logging.debug("expansion rule")

    def initGame(self):
        logging.info("init expansion rule")
        WerewolfRule.initGame(self)

    def nextTurn_2day(self):
        logging.info("2일째로 고고!")

        #일반 로그를 쓰지 않은 사람을 체크한다.
        self.game.entry.checkNoCommentPlayer()

        #희생양 NPC 습격
        victim = self.game.entry.getVictim()
        victim.toDeathByWerewolf()

        #돌연사 시킴
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("돌연 ")

        #코맨츠 초기화
        self.game.entry.initComment()

        #3. 게임 정보 업데이트
        self.game.setGameState("state", "게임중")
        self.game.setGameState("day", self.game.day+1)

    def nextTurn_Xday(self):
        logging.info("다음 날로 고고!")
        #일반 로그를 쓰지 않은 사람을 체크한다.
        self.game.entry.checkNoCommentPlayer()

        #투표 -살아 있는 참가자가 투표를 했는지 체크, 않했다면 랜덤 투표
        victim = self.decideByMajority()
        if victim:
            if victim.truecharacter == Truecharacter.DIABLO:
                if victim.awaken():
                    self.game.setGameState("win", "3")
                    if self.game.termOfDay == 60:
                        self.game.setGameState("state", GAME_STATE.TESTOVER)
                    else:
                        self.game.setGameState("state", GAME_STATE.GAMEOVER)
                    logging.info("디아블로 승리")
                    self.game.setGameState("day", self.game.day+1)
                    return
            victim.toDeath("심판")

        #돌연사 시킴
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("돌연 ")

        #코맨츠 초기화
        self.game.entry.initComment()

        #습격!
        assaultVictim = self.decideByWerewolf()
        if assaultVictim:
            logging.debug("assaultVictim: %s", assaultVictim)
            self.assaultByWerewolf(assaultVictim, victim)

        #종료 조건 확인
        #사람!
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        #for human in humanRace :
        #    print human

        #습격자!
        werewolfRace = self.game.entry.getEntryByRace(Race.WEREWOLF)
        #for werewolf in werewolfRace :
        #    print werewolf

        if (len(humanRace) <= len(werewolfRace)) or not humanRace:
            logging.info("인랑 승리")
            self.game.setGameState("win", "1")
            if self.game.termOfDay == 60:
                self.game.setGameState("state", GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state", GAME_STATE.GAMEOVER)

        elif not werewolfRace:
            logging.info("인간 승리")
            self.game.setGameState("win", "0")
            if self.game.termOfDay == 60:
                self.game.setGameState("state", GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state", GAME_STATE.GAMEOVER)
        else:
            logging.info("계속 진행")
            #self.game.setGameState("state","게임중")

        self.game.setGameState("day", self.game.day+1)

    def assaultByWerewolf(self, assaultVictim, victim):
        self.game.entry.recordAssaultResult(assaultVictim)

        guard = None
        hunterPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.BODYGUARD)[0]    

        if hunterPlayer.alive == "생존":
            logging.debug("hunberPlayer: %s", hunterPlayer)
            guard = hunterPlayer.guard()
            if guard is not None:
                guard = self.game.entry.getCharacter(guard['purpose'])
                logging.debug("guard: %s", guard)

        if assaultVictim.id == victim.id:
            logging.debug("습격 실패: 고습실")
        elif guard and assaultVictim.id == guard.id:
            logging.debug("습격 실패: 선방")
        else:
            logging.debug("습격 성공: %s", assaultVictim)
            assaultVictim.toDeath("습격")

    def decideByWerewolf(self):
        cursor = self.game.db.cursor

        logging.debug("습격!!!")
        #습격의 희생양들...
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        logging.debug("humanlist: %s", [str(player) for player in humanRace])

        #습격자!
        werewolfPlayers = self.game.entry.getPlayersByTruecharacter(Truecharacter.WEREWOLF, "('생존')")
        readerwerewolfPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.READERWEREWOLF)
        lonelywerewolfPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.LONELYWEREWOLF)
        logging.debug("%s", werewolfPlayers)

        if readerwerewolfPlayer:
            readerwerewolfPlayer = readerwerewolfPlayer[0]

        if lonelywerewolfPlayer:
            lonelywerewolfPlayer = lonelywerewolfPlayer[0]

        #살아 있는 인랑이 있을 때만 습격을 진행한다.
        if not werewolfPlayers and (not readerwerewolfPlayer or readerwerewolfPlayer.alive=="사망") \
                            and (not lonelywerewolfPlayer or lonelywerewolfPlayer.alive =="사망"):
            return

        #인랑들이 습격을 결정했는지 확인한다.
        for werewolfPlayer in werewolfPlayers:
            #습격을 안했다면! 랜덤 습격 시작
            if not werewolfPlayer.hasAssault():
                werewolfPlayer.assaultRandom(humanRace)
        if readerwerewolfPlayer and readerwerewolfPlayer.alive=="생존":
            if not readerwerewolfPlayer.hasAssault():
                readerwerewolfPlayer.assaultRandom(humanRace)
        if lonelywerewolfPlayer and lonelywerewolfPlayer.alive=="생존":
            if not lonelywerewolfPlayer.hasAssault():
                lonelywerewolfPlayer.assaultRandom(humanRace)

        #인랑들이 가장 좋아하는 사람을 찾는다.
        query = '''select `injured`, count(*)*2 as count from `zetyx_board_werewolf_deathNote` 
        where game = '%s' and day ='%s' 
        group by `injured` 
        order by `count`  DESC '''
        query %= (self.game.game, self.game.day)
        logging.debug(query)

        cursor.execute(query)
        result = cursor.fetchall()
        logging.debug(result)

        if lonelywerewolfPlayer and lonelywerewolfPlayer.alive == "생존":
            query = '''select `injured`, count(*) as count from `zetyx_board_werewolf_deathnotehalf` 
            where game = '%s' and day ='%s' 
            group by `injured` 
            order by `count`  DESC '''
            query %= (self.game.game, self.game.day)
            logging.debug(query)

            cursor.execute(query)
            result2 = cursor.fetchall()
            logging.debug(result2)

            if not result:
                result2 = result2[0]
                resultList = []
                for temp in result:
                    if temp['injured'] == result2['injured']:
                        temp['count'] += 1
                    resultList.append(temp)
                result = resultList
            else:
                result = result2

        count = 0
        injured_list = []
        logging.debug(result)

        for temp in result:
            if count < temp['count']:
                injured_list = []
                count = temp['count']
                injured_list.append(temp['injured'])
            elif count == temp['count']:
                logging.debug("count: %s", count)
                injured_list.append(temp['injured'])
            else:
                break
        injured = random.choice(injured_list)
        logging.debug("injured: %s in %s", injured, injured_list)
        return self.game.entry.getCharacter(injured)
