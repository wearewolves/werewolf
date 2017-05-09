#-*- coding:cp949 -*-
import logging
import random
import copy
from werewolf.game.GAME_STATE import GAME_STATE
from werewolf.game.entry.Role import Truecharacter
from werewolf.game.entry.Role import Race
from werewolf.game.rule.Rule import WerewolfRule

class BasicRule(WerewolfRule):
    def __init__(self, game):
        super(BasicRule, self).__init__(game)
        # �⺻ ����
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

    def getTruecharacterList(self, number):
        if number == 16:
            rolelist = [Truecharacter.HUMAN] * 4 + [Truecharacter.FREEMASONS] * 2 +\
                       [Truecharacter.SEER, Truecharacter.MEDIUM, Truecharacter.BODYGUARD] +\
                       [Truecharacter.WEREWOLF] * 3 + [Truecharacter.POSSESSED]
            return rolelist
        rolelist = [Truecharacter.HUMAN] * 4 + \
                   [Truecharacter.SEER, Truecharacter.MEDIUM, Truecharacter.BODYGUARD] +\
                   [Truecharacter.WEREWOLF] * 2 + [Truecharacter.POSSESSED]
        if number < 15:
            rolelist += [Truecharacter.HUMAN] * (number-11)
            return rolelist
        if number == 15:
            rolelist += [Truecharacter.HUMAN] * 3 + [Truecharacter.WEREWOLF]
            return rolelist

    def initGame(self):
        logging.info("init Basic Rule")
        super(BasicRule, self).initGame()
        self.deleteTelepathy()

    def writePlayerWill(self):
        from werewolf.game.rule.RuleFactory import SUBRULE_NAME, getSubrule
        tele = getSubrule(SUBRULE_NAME.TELEPATHY_NONE, self.game)
        if tele:
            mason_list = self.game.entry.getPlayersByTruecharacter(Truecharacter.FREEMASONS)
            for mason in mason_list:
                mason.writeWill("���� �ʴɷ����Դϴ�. (�ڵ� ������ �α��Դϴ�.)", "�ڷ�")

    def nextTurn_2day(self):
        logging.info("2��°�� ���!")

        #�Ϲ� �α׸� ���� ���� ����� üũ�Ѵ�.
        self.game.entry.checkNoCommentPlayer()

        #����� NPC ����
        victim = self.game.entry.getVictim()
        victim.toDeathByWerewolf()

        #������ ��Ŵ
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("���� ")

        #�ڸ��� �ʱ�ȭ
        self.game.entry.initComment()
        self.deleteTelepathy()

        #3. ���� ���� ������Ʈ
        self.game.setGameState("state", "������")
        self.game.setGameState("day", self.game.day+1)

    def nextTurn_Xday(self):
        logging.info("�������� ���!")
        #�Ϲ� �α׸� ���� ���� ����� üũ�Ѵ�.
        self.game.entry.checkNoCommentPlayer()

        #��ǥ -��� �ִ� �����ڰ� ��ǥ�� �ߴ��� üũ, ���ߴٸ� ���� ��ǥ
        victim = self.decideByMajority()
        if victim:
            victim.toDeath("����")

        #������ ��Ŵ
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("���� ")

        #�ڸ��� �ʱ�ȭ
        self.game.entry.initComment()
        self.deleteTelepathy()

        #����!
        assaultVictim = self.decideByWerewolf()
        if assaultVictim:
            logging.info("assaultVictim: %s", assaultVictim)
            self.assaultByWerewolf(assaultVictim, victim)

        #���� ���� Ȯ��
        #���!
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        #for human in humanRace :
        #    print human

        #������!
        werewolfRace = self.game.entry.getEntryByRace(Race.WEREWOLF)
        #for werewolf in werewolfRace :
        #    print werewolf

        if (len(humanRace) <= len(werewolfRace)) or not humanRace:
            logging.info("�ζ� �¸�")
            self.game.setGameState("win", "1")
            if self.game.termOfDay == 60:
                self.game.setGameState("state", GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state", GAME_STATE.GAMEOVER)

        elif not werewolfRace:
            logging.info("�ΰ� �¸�")
            self.game.setGameState("win", "0")
            if self.game.termOfDay == 60:
                self.game.setGameState("state", GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state", GAME_STATE.GAMEOVER)
        else:
            logging.info("��� ����")
            #self.game.setGameState("state","������")

        self.game.setGameState("day", self.game.day+1)

    def assaultByWerewolf(self, assaultVictim, victim):
        self.game.entry.recordAssaultResult(assaultVictim)

        guard = None
        hunterPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.BODYGUARD)[0]

        if hunterPlayer.alive == "����":
            logging.debug("hunterPlayer: %s", hunterPlayer)
            guard = hunterPlayer.guard()
            if guard is not None:
                guard = self.game.entry.getCharacter(guard['purpose'])
                logging.debug("guard: %s", guard)

        if assaultVictim.id == victim.id:
            logging.debug("���� ����: �����")
        elif guard and assaultVictim.id == guard.id:
            logging.debug("���� ����: ����")
        else:
            logging.debug("���� ����: %s", assaultVictim)
            assaultVictim.toDeath("����")

    def deleteTelepathy(self):
        from werewolf.game.rule.RuleFactory import SUBRULE_NAME, getSubrule
        tele = getSubrule(SUBRULE_NAME.NPC_ALLOCATION, self.game)
        if tele:
            cursor = self.game.db.cursor
            query = """update `zetyx_board_werewolf_entry` set telepathy ='0' where game = '%s'"""
            query %= (self.game.game)
            logging.debug(query)
            cursor.execute(query)
