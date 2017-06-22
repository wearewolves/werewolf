#-*- coding:cp949 -*-
import random
import copy
import logging
from werewolf.game.GAME_STATE import GAME_STATE
from werewolf.game.entry.Role import Truecharacter
from werewolf.game.entry.Role import Race
from werewolf.game.rule.Rule import WerewolfRule

class ExpansionRule(WerewolfRule):
    def __init__(self, game):
        super(ExpansionRule, self).__init__(game)
        self.min_players = 9
        self.max_players = 17
        logging.debug("expansion rule")

    def getTruecharacterList(self, number):
        if number < 11:
            rolelist = [Truecharacter.SEER, Truecharacter.MEDIUM, Truecharacter.BODYGUARD] +\
                        [Truecharacter.REVENGER, Truecharacter.SHERIFF, Truecharacter.HUMAN] +\
                        [Truecharacter.WEREWOLF, Truecharacter.LONELYWEREWOLF, Truecharacter.POSSESSED]
            if number == 10:
                rolelist += [Truecharacter.NOBILITY, Truecharacter.HUMAN]
                rolelist.remove(Truecharacter.SHERIFF)
        elif number < 12:
            rolelist = [Truecharacter.SEER, Truecharacter.MEDIUM, Truecharacter.BODYGUARD] +\
                        [Truecharacter.SHERIFF, Truecharacter.CHIEF] + [Truecharacter.HUMAN]*3 +\
                        [Truecharacter.WEREWOLF, Truecharacter.READERWEREWOLF, Truecharacter.POSSESSED]
            if number == 12:
                rolelist += [Truecharacter.HUMAN]*2
                rolelist.remove(Truecharacter.SHERIFF)
        else:
            rolelist = [Truecharacter.SEER, Truecharacter.MEDIUM, Truecharacter.BODYGUARD] +\
                        [Truecharacter.REVENGER, Truecharacter.SHERIFF, Truecharacter.REVENGER] +\
                        [Truecharacter.WEREWOLF, Truecharacter.READERWEREWOLF, Truecharacter.LONELYWEREWOLF] +\
                        [Truecharacter.POSSESSED, Truecharacter.NOBILITY] + [Truecharacter.HUMAN]*2
            if number == 13:
                pass
            elif number == 14:
                rolelist += [Truecharacter.HUMAN]*2
                rolelist.remove(Truecharacter.SHERIFF)
            else:
                rolelist += [Truecharacter.WEREWOLF] + [Truecharacter.HUMAN]
                if number > 15:
                    rolelist += [Truecharacter.HUMAN]*2
                    rolelist.remove(Truecharacter.SHERIFF)
                    if number == 17:
                        rolelist += [Truecharacter.DIABLO]
        logging.debug('The basic rolelist for %d: %s', number, rolelist)
        assert len(rolelist) == number, "The number of role is not proper"
        return rolelist

    def initGame(self):
        logging.info("init Expansion Rule")
        WerewolfRule.initGame(self)
    
    def writePlayerWill(self):
        lone_wolf = self.game.entry.getPlayersByTruecharacter(Truecharacter.LONELYWEREWOLF)
        if lone_wolf is not None:
            lone_wolf[0].writeWill("���� �ܷο� �����Դϴ�. (�ڵ� ������ �α��Դϴ�.)", "���")

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

        #�ڸ�Ʈ �ʱ�ȭ
        self.game.entry.initComment()

        #3. ���� ���� ������Ʈ
        self.game.setGameState("state", "������")
        self.game.setGameState("day", self.game.day+1)

    def nextTurn_Xday(self):
        logging.info("���� ���� ���!")
        #�Ϲ� �α׸� ���� ���� ����� üũ�Ѵ�.
        self.game.entry.checkNoCommentPlayer()

        #��ǥ - ����ִ� �����ڰ� ��ǥ�� �ߴ��� üũ, �� �ߴٸ� ���� ��ǥ
        victim = self.decideByMajority()
        if victim:
            if victim.truecharacter == Truecharacter.DIABLO:
                if victim.awaken():
                    logging.info("��ƺ�� �¸�")
                    self.game.setGameState("win", "3")
                    if self.game.termOfDay == 60:
                        self.game.setGameState("state", GAME_STATE.TESTOVER)
                    else:
                        self.game.setGameState("state", GAME_STATE.GAMEOVER)
                    self.game.entry.allocComment()
                    self.game.setGameState("day", self.game.day+1)
                    return
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

        logging.debug("����!!!")
        #������ ������...
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        logging.debug("humanlist: %s", [str(player) for player in humanRace])

        #������!
        werewolfPlayers = self.game.entry.getPlayersByTruecharacter(Truecharacter.WEREWOLF, "('����')")
        readerwerewolfPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.READERWEREWOLF)
        lonelywerewolfPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.LONELYWEREWOLF)
        logging.debug("%s", werewolfPlayers)

        if readerwerewolfPlayer:
            readerwerewolfPlayer = readerwerewolfPlayer[0]

        if lonelywerewolfPlayer:
            lonelywerewolfPlayer = lonelywerewolfPlayer[0]

        #��� �ִ� �ζ��� ���� ���� ������ �����Ѵ�.
        if not werewolfPlayers and (not readerwerewolfPlayer or readerwerewolfPlayer.alive=="���") \
                            and (not lonelywerewolfPlayer or lonelywerewolfPlayer.alive =="���"):
            return

        #�ζ����� ������ �����ߴ��� Ȯ���Ѵ�.
        for werewolfPlayer in werewolfPlayers:
            #������ ���ߴٸ�! ���� ���� ����
            if not werewolfPlayer.hasAssault():
                werewolfPlayer.assaultRandom(humanRace)
        if readerwerewolfPlayer and readerwerewolfPlayer.alive=="����":
            if not readerwerewolfPlayer.hasAssault():
                readerwerewolfPlayer.assaultRandom(humanRace)
        if lonelywerewolfPlayer and lonelywerewolfPlayer.alive=="����":
            if not lonelywerewolfPlayer.hasAssault():
                lonelywerewolfPlayer.assaultRandom(humanRace)

        #�ζ����� ���� �����ϴ� ����� ã�´�.
        query = '''select `injured`, count(*)*2 as count from `zetyx_board_werewolf_deathNote` 
        where game = '%s' and day ='%s' 
        group by `injured` 
        order by `count`  DESC '''
        query %= (self.game.game, self.game.day)
        logging.debug(query)

        cursor.execute(query)
        result = cursor.fetchall()
        logging.debug(result)

        if lonelywerewolfPlayer and lonelywerewolfPlayer.alive == "����":
            query = '''select `injured`, count(*) as count from `zetyx_board_werewolf_deathnotehalf` 
            where game = '%s' and day ='%s' 
            group by `injured` 
            order by `count`  DESC '''
            query %= (self.game.game, self.game.day)
            logging.debug(query)

            cursor.execute(query)
            result2 = cursor.fetchall()
            logging.debug(result2)

            if result:
                result2 = result2[0]
                resultList = []
                for temp in result:
                    if temp['injured'] == result2['injured']:
                        temp['count'] += 1
                    resultList.append(temp)
                result = resultList
                logging.debug('lonelywerewolf (with werewolf): %s', resultList)
            else:
                result = result2
                logging.debug('lonelywerewolf (no werewolf): %s', result)

        logging.debug(result)
        count = 0
        injured_list = []

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
