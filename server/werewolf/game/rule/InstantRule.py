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
        self.min_players = 8
        self.max_players = 9
        logging.debug("instant rule")

    def getTruecharacterList(self, number):
        if number == 8:
            rolelist = [Truecharacter.SEER, Truecharacter.BODYGUARD] +\
                        [Truecharacter.REVENGER] + [Truecharacter.HUMAN]*3 +\
                        [Truecharacter.WEREWOLF]*2
        elif number == 9:
            rolelist = [Truecharacter.SEER, Truecharacter.BODYGUARD] +\
                        [Truecharacter.REVENGER] + [Truecharacter.HUMAN]*4 +\
                        [Truecharacter.WEREWOLF]*2
						
        logging.debug('The instant rolelist for %d: %s', number, rolelist)
        assert len(rolelist) == number, "The number of role is not proper"
        return rolelist

    def initGame(self):
        logging.info("init Instant Rule")
        WerewolfRule.initGame(self)
        self.deletenormallog()

		#�����̸� ã�´�
        seerPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.SEER)[0]
        #�������� �������� ���ش�.
        seerPlayer.seerRandom()

        # 2��°�� ����
        victim = self.game.entry.getVictim()
        victim.toDeathByWerewolf()
        self.game.entry.initComment()
        self.deleteNormallog()
        self.game.setGameState("state", "������")
        self.game.setGameState("day", self.game.day+1)

    def nextTurn_2day(self):
        raise NotImplementedError("InstantRule must not call nextTurn_2day")

    def nextTurn_Xday(self):
        logging.info("���� ���� ���!")
		
        if self.game.day > 2:
			#�Ϲ� �α׸� ���� ���� ����� üũ�Ѵ�.
			self.game.entry.checkNoCommentPlayer()

			#��ǥ - ����ִ� �����ڰ� ��ǥ�� �ߴ��� üũ, �� �ߴٸ� ���� ��ǥ
			victim = self.decideByMajority()
			if victim:
				victim.toDeath("����")

			#������ ��Ŵ
			noMannerPlayers = self.game.entry.getNoMannerPlayers()
			for noMannerPlayer in noMannerPlayers:
				noMannerPlayer.toDeath("���� ")

        #�ڸ�Ʈ �ʱ�ȭ
        self.game.entry.initComment()

        #����!
        assaultVictim = self.decideByWerewolf()
        if assaultVictim:
            logging.debug("assaultVictim: %s", assaultVictim)
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
            self.game.entry.allocComment()

        elif not werewolfRace:
            logging.info("�ΰ� �¸�")
            self.game.setGameState("win", "0")
            if self.game.termOfDay == 60:
                self.game.setGameState("state", GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state", GAME_STATE.GAMEOVER)
            self.game.entry.allocComment()

        else:
            logging.info("��� ����")
            #self.game.setGameState("state","������")

        self.game.setGameState("day", self.game.day+1)

    def assaultByWerewolf(self, assaultVictim, victim):
        self.game.entry.recordAssaultResult(assaultVictim)

        guard = None
        hunterPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.BODYGUARD)[0]    

        if hunterPlayer.alive == "����":
            logging.debug("hunberPlayer: %s", hunterPlayer)
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

    def decideByWerewolf(self):
        cursor = self.game.db.cursor

        logging.info("����!!")
        #������ ������...
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        logging.debug("%s", humanRace)

        #������!
        werewolfPlayers = self.game.entry.getPlayersByTruecharacter(Truecharacter.WEREWOLF, "('����')")
        logging.debug("%s", werewolfPlayers)

        #��� �ִ� �ζ��� ���� ���� ������ �����Ѵ�.
        if not werewolfPlayers:
            return

        #�ζ����� ������ �����ߴ��� Ȯ���Ѵ�.
        for werewolfPlayer in werewolfPlayers:
            #������ ���ߴٸ�! ���� ���� ����
            if not werewolfPlayer.hasAssault():
                werewolfPlayer.assaultRandom(humanRace)


        #�ζ����� ���� �����ϴ� ����� ã�´�.
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

    def deleteNormallog(self):
        cursor = self.game.db.cursor
        query = """update `zetyx_board_werewolf_entry` set normal ='0' where game = '%s'"""
        query %= (self.game.game)
        logging.debug(query)
        cursor.execute(query)
