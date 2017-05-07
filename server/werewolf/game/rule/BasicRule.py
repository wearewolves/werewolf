#-*- coding:cp949 -*-
import logging
import random
import copy
from werewolf.game.GAME_STATE import GAME_STATE
from werewolf.game.entry.Role import Truecharacter
from werewolf.game.entry.Role import Race
from werewolf.game.rule.Rule import WerewolfRule
from werewolf.game.rule.RuleFactory import SUBRULE_NAME, getSubrule

class BasicRule(WerewolfRule):
    def __init__(self, game):
        super(BasicRule, self).__init__(game)
        # 기본 세팅
        self.min_players = 11
        self.max_players = 16
        self.truecharacter_list[11] = [Truecharacter.HUMAN] * 4 + \
                                      [Truecharacter.SEER, Truecharacter.MEDIUM, Truecharacter.BODYGUARD] +\
                                      [Truecharacter.WEREWOLF] * 2 + [Truecharacter.POSSESSED]
        self.truecharacter_list[12] = copy.copy(self.truecharacter_list[11]) + [Truecharacter.HUMAN]
        self.truecharacter_list[13] = copy.copy(self.truecharacter_list[12]) + [Truecharacter.HUMAN]
        self.truecharacter_list[14] = copy.copy(self.truecharacter_list[13]) + [Truecharacter.HUMAN]
        self.truecharacter_list[15] = copy.copy(self.truecharacter_list[14]) + [Truecharacter.WEREWOLF]
        self.truecharacter_list[16] = [Truecharacter.HUMAN] * 4 + [Truecharacter.FREEMASONS] * 2 +\
                                      [Truecharacter.SEER, Truecharacter.MEDIUM, Truecharacter.BODYGUARD] +\
                                      [Truecharacter.WEREWOLF] * 3 + [Truecharacter.POSSESSED]
        logging.debug("basicRule")

    def initGame(self):
        logging.info("init Basic Rule")
        super(BasicRule, self).initGame()

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
        logging.info("다음날로 고고!")
        #일반 로그를 쓰지 않은 사람을 체크한다.
        self.game.entry.checkNoCommentPlayer()

        #투표 -살아 있는 참가자가 투표를 했는지 체크, 않했다면 랜덤 투표
        victim = self.decideByMajority()
        if victim:
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
            logging.info("assaultVictim: %s", assaultVictim)
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
            logging.debug("hunterPlayer: %s", hunterPlayer)
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
