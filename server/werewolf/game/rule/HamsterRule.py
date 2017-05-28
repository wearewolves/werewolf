#-*- coding:cp949 -*-
import random
import copy
import logging
from werewolf.game.GAME_STATE import GAME_STATE
from werewolf.game.entry.Role import Truecharacter
from werewolf.game.entry.Role import Race
from werewolf.game.rule.Rule import WerewolfRule
from werewolf.game.rule.BasicRule import BasicRule

class HamsterRule(BasicRule):
    def __init__(self, game):
        super(HamsterRule, self).__init__(game)
        self.max_players = 17
        logging.debug("Hamstar Rule")

    def getTruecharacterList(self, number):
        if number < 17:
            rolelist = super(HamsterRule, self).getTruecharacterList(number)
        elif number == 17:
            rolelist = super(HamsterRule, self).getTruecharacterList(16)
            rolelist += [Truecharacter.WEREHAMSTER]
        logging.debug('The basic rolelist for %d: %s', number, rolelist)
        assert len(rolelist) == number, "The number of role is not proper"
        return rolelist

    def nextTurn(self):
        if self.game.state == GAME_STATE.READY:
            if self.min_players <= self.game.players and self.game.players <= self.max_players:
                logging.info("게임 초기화 시작")
                self.initGame()
            else:
                self.game.deleteGame()
        elif self.game.state==GAME_STATE.PLAYING:
            if self.game.day == 1:
                if self.game.players == 17:
                    self.nextTurn_2day()
                else:
                    BasicRule.nextTurn_2day(self)
            else:
                if self.game.players == 17:
                    self.nextTurn_Xday()
                else:
                    BasicRule.nextTurn_Xday(self)

    def initGame(self):
        logging.info("init Hamstar Rule")
        WerewolfRule.initGame(self)
        self.deleteTelepathy()

    def nextTurn_2day(self):
        logging.info("2일째로 고고!")

        #일반 로그를 쓰지 않은 사람을 체크한다.
        self.game.entry.checkNoCommentPlayer()

        #희생양 NPC 습격
        victim = self.game.entry.getVictim()
        victim.toDeathByWerewolf()

        #햄스터
        hamsterPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.WEREHAMSTER)[0]

        #햄 빔!
        self.assaultByForecast(hamsterPlayer)

        #돌연사 시킴 
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("돌연 ")

        #코멘트 초기화
        self.game.entry.initComment()
        self.deleteTelepathy()

        #3. 게임 정보 업데이트
        self.game.setGameState("state", "게임중")
        self.game.setGameState("day", self.game.day+1)

    def nextTurn_Xday(self):
        logging.info("다음 날로 고고!")

        #일반 로그를 쓰지 않은 사람을 체크한다.
        self.game.entry.checkNoCommentPlayer()

        #투표 -살아 있는 참가자가 투표를 했는지 체크, 안했다면 랜덤 투표
        victim = self.decideByMajority()
        if victim:
            victim.toDeath("심판")

        #돌연사 시킴
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("돌연 ")
        
        #코멘트 초기화
        self.game.entry.initComment()
        self.deleteTelepathy()

        #햄스터
        hamsterPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.WEREHAMSTER)[0]

        #햄 빔!
        self.assaultByForecast(hamsterPlayer)

        #습격!
        assaultVictim = self.decideByWerewolf()
        if assaultVictim:
            logging.debug("assaultVictim: %s", assaultVictim)
            self.assaultByWerewolfAndHamster(assaultVictim, victim, hamsterPlayer)

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
            if self.game.termOfDay == 60:
                self.game.setGameState("state", GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state", GAME_STATE.GAMEOVER)

            if hamsterPlayer.alive == "생존":
                logging.info("햄스터 승리")
                self.game.setGameState("win", "2")
            else:
                logging.info("인랑 승리")
                self.game.setGameState("win", "1")

        elif not werewolfRace:
            if self.game.termOfDay == 60:
                self.game.setGameState("state", GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state", GAME_STATE.GAMEOVER)

            if hamsterPlayer.alive == "생존":
                logging.info("햄스터 승리")
                self.game.setGameState("win", "2")
            else:
                logging.info("인간 승리")
                self.game.setGameState("win", "0")
        else:
            logging.info("계속 진행")
            #self.game.setGameState("state","게임중")

        self.game.setGameState("day", self.game.day+1)

    def assaultByForecast(self, hamsterPlayer):
        logging.debug("햄빔!!")
        forecastTarget = {}
        seerPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.SEER)[0]

        if seerPlayer.alive == "생존":
            logging.debug("seerPlayer: %s", seerPlayer)
            forecastTarget = seerPlayer.openEye()
            logging.debug("forecastTarget: %s", forecastTarget)

            if forecastTarget is not None:
                forecastTarget = self.game.entry.getCharacter(forecastTarget['mystery'])

        logging.debug("hamsterPlayer: %s", hamsterPlayer)

        if forecastTarget and hamsterPlayer.alive == "생존" and hamsterPlayer.id == forecastTarget.id:
            logging.debug("햄빔 성공: %s", hamsterPlayer)
            hamsterPlayer.toDeath("습격")
        else:
            logging.debug("햄빔 실패")

    def assaultByWerewolfAndHamster(self, assaultVictim, victim, hamsterPlayer):
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
        elif assaultVictim.id == hamsterPlayer.id:
            logging.debug("습격 실패: 점빔")
        else:
            logging.debug("습격 성공: %s", assaultVictim)
            assaultVictim.toDeath("습격")
