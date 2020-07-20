#-*- coding:cp949 -*-
import random
import copy
import logging
from werewolf.game.GAME_STATE import GAME_STATE
from werewolf.game.entry.Role import Truecharacter
from werewolf.game.entry.Role import Race
from werewolf.game.rule.Rule import WerewolfRule

class InstantRule(WerewolfRule):
    def __init__(self, game):
        super(InstantRule, self).__init__(game)
        self.min_players = 7
        self.max_players = 8
        logging.debug("instant rule")

    def getTruecharacterList(self, number):
        if number == 7:
            rolelist = [Truecharacter.SEER, Truecharacter.BODYGUARD] +\
                        [Truecharacter.REVENGER] + [Truecharacter.HUMAN]*2 +\
                        [Truecharacter.WEREWOLF]*2
        elif number == 8:
            rolelist = [Truecharacter.SEER, Truecharacter.BODYGUARD] +\
                        [Truecharacter.REVENGER] + [Truecharacter.HUMAN]*3 +\
                        [Truecharacter.WEREWOLF]*2
						
        logging.debug('The instant rolelist for %d: %s', number, rolelist)
        assert len(rolelist) == number, "The number of role is not proper"
        return rolelist

    def initGame(self):
        logging.info("init Instant Rule")
        WerewolfRule.initGame(self)
        
        cursor = self.game.db.cursor
        query = """update `zetyx_board_werewolf_entry` set normal ='0' where game = '%s'"""
        query %= (self.game.game)
        logging.debug(query)
        cursor.execute(query)

        #점쟁이를 찾는다 
        seerPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.SEER)[0]
        #랜덤으로 점설정을 해준다.
        seerPlayer.seerRandom()

		# 날을 진행 
        #victim = self.game.entry.getVictim()
        #victim.toDeathByWerewolf()
        self.game.entry.initComment()
        self.game.setGameState("state", "게임중")
        self.game.setGameState("day", self.game.day+1)
        
    def writePlayerWill(self):
        # 생존로그가 필요하지 않지만 가시적으로 남기는 것
        # 돌연 체크는 zetyx_board_werewolf_entry의 comment 1 값으로 체크한다
        allentry_list = self.game.entry.getAllEntry()
        for entry_part in allentry_list:
            entry_part.writeWill("생존 로그입니다. (자동 생성된 로그입니다.)", "일반")

    def checkDelayToAllocComment(self):
        logging.info("InstantRule CheckDelay")
        logging.debug("game day: %d", self.game.day)
        if self.game.day == 1:
            logging.info("Instant deleteNormallog")
            self.deleteNormallog()
        else:
            logging.info("Instant alloc")
            self.game.entry.allocComment()
            

    def nextTurn_2day(self):
        logging.info("2일째로 고고!")

        #self.nextTurn_Xday()

        #희생양 NPC 습격
        victim = self.game.entry.getVictim()
        victim.toDeathByWerewolf()

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
            self.game.entry.allocComment()

        elif not werewolfRace:
            logging.info("인간 승리")
            self.game.setGameState("win", "0")
            if self.game.termOfDay == 60:
                self.game.setGameState("state", GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state", GAME_STATE.GAMEOVER)
            self.game.entry.allocComment()

        else:
            logging.info("계속 진행")
            #self.game.setGameState("state","게임중")

        #3. 게임 정보 업데이트
        self.game.setGameState("state", "게임중")
        self.game.setGameState("day", self.game.day+1)

    def nextTurn_Xday(self):
        logging.info("다음 날로 고고!")
       
        #일반 로그를 쓰지 않은 사람을 체크한다.
        self.game.entry.checkNoCommentPlayer()

        #투표 - 살아있는 참가자가 투표를 했는지 체크, 안 했다면 랜덤 투표
        victim = self.decideByMajority()
        if victim:
            victim.toDeath("심판")

        #돌연사 시킴
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("돌연 ")

        #코멘트 초기화
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
            self.game.entry.allocComment()

        elif not werewolfRace:
            logging.info("인간 승리")
            self.game.setGameState("win", "0")
            if self.game.termOfDay == 60:
                self.game.setGameState("state", GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state", GAME_STATE.GAMEOVER)
            self.game.entry.allocComment()

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

        logging.info("습격!!")
        #습격의 희생양들...
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        logging.debug("%s", humanRace)

        #습격자!
        werewolfPlayers = self.game.entry.getPlayersByTruecharacter(Truecharacter.WEREWOLF, "('생존')")
        logging.debug("%s", werewolfPlayers)

        #살아 있는 인랑이 있을 때만 습격을 진행한다.
        if not werewolfPlayers:
            return

        #인랑들이 습격을 결정했는지 확인한다.
        for werewolfPlayer in werewolfPlayers:
            #습격을 안했다면! 랜덤 습격 시작
            if not werewolfPlayer.hasAssault():
                werewolfPlayer.assaultRandom(humanRace)


        #인랑들이 가장 좋아하는 사람을 찾는다.
        query = '''select `injured`, count(*) as count from `zetyx_board_werewolf_deathNote` 
        where game = '%s' and day ='%s' 
        group by `injured` 
        order by `count`  DESC '''
        query %= (self.game.game, self.game.day)
        logging.debug(query)

        cursor.execute(query)
        result = cursor.fetchall()
        logging.debug(result)

        count = 0
        injured_list = []

        for temp in result:
            if count <= temp['count']:
                count = temp['count']
                logging.debug("count: %s", count)
            else:
                break
            injured_list.append(temp['injured'])
        injured = random.choice(injured_list)
        logging.debug("Injured: %s in %s", injured, [str(player) for player in injured_list])
        return self.game.entry.getCharacter(injured)

    # 일반로그를 모두 없애고 comment 부분을 1로 체크해준다.(comment가 1이어야 돌연을 안 한다)
    def deleteNormallog(self):
        cursor = self.game.db.cursor
        query = """update `zetyx_board_werewolf_entry` set normal ='0', comment = '1' where game = '%s'"""
        query %= (self.game.game)
        logging.debug(query)
        cursor.execute(query)
