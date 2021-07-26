#-*- coding:cp949 -*-
import logging
import random
import copy
from werewolf.game.GAME_STATE import GAME_STATE
from werewolf.game.entry.Role import Truecharacter
from werewolf.game.entry.Role import Race
from werewolf.game.rule.Rule import WerewolfRule

class MustkillRule(WerewolfRule):
    def __init__(self, game):
        super(MustkillRule, self).__init__(game)
        self.min_players = 11
        self.max_players = 16
        logging.debug("MustKillRule")

    def getTruecharacterList(self, number):
        #16���� �ٽ� �ٲ� �� �ֱ⿡ �켱 �ٸ� ��Ʈ�� Ÿ�� �׳� �д�
        if number == 16:
            rolelist = [Truecharacter.HUMAN] * 7 + [Truecharacter.CHIEF] +\
                       [Truecharacter.SEER, Truecharacter.MEDIUM, Truecharacter.BODYGUARD, Truecharacter.HIDENOBILITY] +\
                       [Truecharacter.WEREWOLF] * 2 + [Truecharacter.POSSESSED] + [Truecharacter.CRUELWEREWOLF]
        else:
            rolelist = [Truecharacter.HUMAN] * 4 + \
                    [Truecharacter.SEER, Truecharacter.MEDIUM, Truecharacter.BODYGUARD, Truecharacter.HIDENOBILITY] +\
                    [Truecharacter.WEREWOLF] * 1 + [Truecharacter.POSSESSED] + [Truecharacter.CRUELWEREWOLF]
            if number < 15:
                rolelist += [Truecharacter.HUMAN] * (number-11)
            if number == 15:
                rolelist += [Truecharacter.HUMAN] * 2 + [Truecharacter.WEREWOLF] + [Truecharacter.CHIEF]
        logging.debug('The MustKill rolelist for %d: %s', number, rolelist)
        assert len(rolelist) == number, "The number of role is not proper"
        return rolelist

    def initGame(self):
        logging.info("init MustKill Rule")
        WerewolfRule.initGame(self)
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
        
        #�� ��ǥ 
        publicSeer = self.decideByPublicSeer()
        if publicSeer:
            #�����̸� ã�´� 
            seerPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.SEER)[0]
            publicSeer.toSeer(seerPlayer)

        #������ ��Ŵ
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("���� ")

        #�ڸ�Ʈ �ʱ�ȭ
        self.game.entry.initComment()
        self.deleteTelepathy()

        #3. ���� ���� ������Ʈ
        self.game.setGameState("state", "������")
        self.game.setGameState("day", self.game.day+1)

    def nextTurn_Xday(self):
        logging.info("�������� ���!")
        #�Ϲ� �α׸� ���� ���� ����� üũ�Ѵ�.
        self.game.entry.checkNoCommentPlayer()

        #��ǥ - ����ִ� �����ڰ� ��ǥ�� �ߴ��� üũ, �� �ߴٸ� ���� ��ǥ
        victim = self.decideByMajority()
        if victim:
            victim.toDeath("����")

        #�� ��ǥ 
        publicSeer = self.decideByPublicSeer()
        if publicSeer:
            #�����̸� ã�´� 
            seerPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.SEER)[0]
            publicSeer.toSeer(seerPlayer)
            
        #������ ��Ŵ
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("���� ")

        #�ڸ�Ʈ �ʱ�ȭ
        self.game.entry.initComment()
        self.deleteTelepathy()

        #����! �ƴϸ� ����
        mustKillVictim = self.mustkillbycruelWerewolf()
        if mustKillVictim:
            logging.debug("must kill assaultVictim: %s", mustKillVictim)
            self.assaultByCruelWerewolf(mustKillVictim, victim)
        else:
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

    def assaultByCruelWerewolf(self, assaultVictim, victim):
        self.game.entry.recordAssaultResult(assaultVictim)

        if assaultVictim.id == victim.id:
            logging.debug("���� ����: �����")
        else:
            logging.debug("���� ����: %s", assaultVictim)
            assaultVictim.toDeath("����")

    def deleteTelepathy(self):
        from werewolf.game.rule.RuleFactory import SUBRULE_NAME, getSubrule
        tele = getSubrule(SUBRULE_NAME.TELEPATHY_NONE, self.game)
        if tele:
            cursor = self.game.db.cursor
            query = """update `zetyx_board_werewolf_entry` set telepathy ='0' where game = '%s'"""
            query %= (self.game.game)
            logging.debug(query)
            cursor.execute(query)

    def decideByWerewolf(self):
        cursor = self.game.db.cursor

        logging.debug("����!!!")
        #������ ������...
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        logging.debug("humanlist: %s", [str(player) for player in humanRace])

        #������!
        werewolfPlayers = self.game.entry.getPlayersByTruecharacter(Truecharacter.WEREWOLF, "('����')")
        cruelwerewolfPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.CRUELWEREWOLF, "('����')")
        logging.debug("%s", werewolfPlayers)

        if cruelwerewolfPlayer:
            cruelwerewolfPlayer = cruelwerewolfPlayer[0]

        #��� �ִ� �ζ��� ���� ���� ������ �����Ѵ�.
        if not werewolfPlayers and not cruelwerewolfPlayer:
            return

        #�ζ����� ������ �����ߴ��� Ȯ���Ѵ�.
        for werewolfPlayer in werewolfPlayers:
            #������ ���ߴٸ�! ���� ���� ����
            if not werewolfPlayer.hasAssault():
                werewolfPlayer.assaultRandom(humanRace)
        if cruelwerewolfPlayer:
            if not cruelwerewolfPlayer.hasAssault():
                cruelwerewolfPlayer.assaultRandom(humanRace)

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

    def mustkillbycruelWerewolf(self):
        cursor = self.game.db.cursor

        logging.debug("����!!!")
        #������ ������...
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        logging.debug("humanlist: %s", [str(player) for player in humanRace])

        #������!
        cruelwerewolfPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.CRUELWEREWOLF, "('����')")

        if cruelwerewolfPlayer:
            cruelwerewolfPlayer = cruelwerewolfPlayer[0]

        #��� �ִ� ��Ȥ�� �ζ��� ���� ���� ������ �����Ѵ�.
        if not cruelwerewolfPlayer:
            return

        #������ ����� �ִ��� ã�´�.
        query = """select * from `zetyx_board_werewolf_mustkill` 
        where game = '%s' and day ='%s'"""
        query %= (self.game.game, self.game.day)
        logging.debug(query)

        cursor.execute(query)
        result = cursor.fetchall()
        logging.debug(result)

        if not result:
            return

        injured_list = []

        for temp in result:
            injured_list.append(temp['target'])
        target_num = random.choice(injured_list)

        #logging.debug("mustkill Injured: %s", result['target'])
        return self.game.entry.getCharacter(target_num)
