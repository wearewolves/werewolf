#-*- coding:cp949 -*-
import logging
import random
import copy
from werewolf.game.GAME_STATE import GAME_STATE
from werewolf.game.entry.Role import Truecharacter
from werewolf.game.entry.Role import Race
from werewolf.game.rule.RuleFactory import SUBRULE_NAME, getSubrule

class Rule(object):
    def __init__(self, game):
        self.min_players = None
        self.max_players = None
        self.truecharacter_list = {}
        self.game = game

class WerewolfRule(Rule):
    def __init__(self, game):
        super(WerewolfRule, self).__init__(game)

    def getTruecharacterList(self, number):
        raise NotImplementedError

    def nextTurn(self):
        if self.game.state == GAME_STATE.READY:
            if self.min_players <= self.game.players and self.game.players <= self.max_players:
                logging.info("게임 초기화 시작")
                self.initGame()
            else:
                self.game.deleteGame()
        elif self.game.state == GAME_STATE.PLAYING:
            if self.game.day == 1:
                self.nextTurn_2day()
            else:
                self.nextTurn_Xday()

    def nextTurn_2day(self):
        raise NotImplementedError
    def nextTurn_Xday(self):
        raise NotImplementedError

    def checkDelayToAllocComment(self):
        logging.info("로그 충전 완료")
        self.game.entry.allocComment()

    def checkDelayToFreeComment(self):
        logging.info("로그 해제 완료")
        self.game.entry.freeComment()

    def initGame(self):
        #플레이해본 사람
        expertPlayers = self.game.entry.getExpertPlayers()
        #print "expertPlayers",expertPlayers

        #초보자
        novicePlayers = self.game.entry.getNovicePlayers()
        #print "novicePlayers",novicePlayers

        #분배할 직업 리스트
        truecharacterList = self.getTruecharacterList(len(novicePlayers) + len(expertPlayers) + 1)
        logging.info("players: %d", len(novicePlayers) + len(expertPlayers) + 1)

        # 더미룰 직업 분배
        dummyrule = getSubrule(SUBRULE_NAME.NPC_ALLOCATION, self.game)
        if not dummyrule:
            try:
                truecharacterList.remove(Truecharacter.HUMAN)
                npc_role = Truecharacter.HUMAN
            except ValueError:
                logging.debug("NO HUMAN exists -> autometically dummyrule turnon")
                dummyrule = True
        if dummyrule:
            from werewolf.game.entry.Role import getNondummyList
            nondummy_list = getNondummyList(self.game)
            while True:
                npc_role = random.choice(truecharacterList)
                if not npc_role in nondummy_list:
                    truecharacterList.remove(npc_role)
                    break


        #마을 사람 배치
        random.shuffle(novicePlayers)
        logging.debug("noviceEntry: %s", [str(player) for player in novicePlayers])
        while novicePlayers:
            try:
                truecharacterList.remove(Truecharacter.HUMAN)
            except ValueError:
                logging.debug("초보자 할당: 남은 마을사람 부족 %d명", len(truecharacterList))
                break
            player = novicePlayers.pop()
            logging.debug("%s with job %d", player, Truecharacter.HUMAN)
            player.setTruecharacter(Truecharacter.HUMAN)

        restPlayers = expertPlayers + novicePlayers
        random.shuffle(restPlayers)
        logging.debug("restEntry: %s", [str(player) for player in restPlayers])
        logging.debug("restJob: %s", truecharacterList)
        while restPlayers:
            player = restPlayers.pop()
            job = truecharacterList.pop()
            logging.debug("%s with job %d", player, job)
            player.setTruecharacter(job)
        if truecharacterList:
            logging.error("Some roles are NOT assigned: %s", truecharacterList)

        #2. 희생자의 코멘트
        victim = self.game.entry.getVictim()
        victim.setTruecharacter(npc_role)
        logging.debug("victim: %s", victim)
        victim.writeWill()
        self.writePlayerWill()

        #3. 게임 정보 업데이트
        self.game.setGameState("state", GAME_STATE.PLAYING)
        self.game.setGameState("day", self.game.day+1)

        #보통 방으로..
        cursor = self.game.db.cursor
        query = "update `zetyx_board_werewolf` set `%s` = '%s'  where no = '%s'"
        query %= ("is_secret", 0, self.game.game)
        logging.debug(query)
        cursor.execute(query)

        #4. 코멘트 초기화
        self.game.entry.initComment()

    def writePlayerWill(self):
        pass

    def decideByMajority(self):
        cursor = self.game.db.cursor

        logging.info("투표!")
        alivePlayers = self.game.entry.getAliveEntry()

        #살아 있는 사람이 1명 초과일 경우에만 투표를 진행한다.
        if len(alivePlayers) < 2:
            return
        #모든 사람들이 투표를 결정했는지 확인한다.
        for alivePlayer in alivePlayers:
            #투표를 안했다면! 랜덤 투표 시작
            if not alivePlayer.hasVoted():
                alivePlayer.voteRandom(alivePlayers)

        #가장 표를 많이 받은 사람을 찾는다.
        query = '''select `candidacy`, count(*) as count from `zetyx_board_werewolf_vote` 
        where game = '%s' and day ='%s' 
        group by `candidacy` 
        order by `count`  DESC '''
        query %= (self.game.game, self.game.day)
        logging.debug(query)

        cursor.execute(query)
        result = cursor.fetchall()
        logging.debug(result)
        #print result

        count = 0
        candidacy_list = []

        for temp in result:
            if count <= temp['count']:
                count = temp['count']
                logging.debug("count: %s", count)
            else:
                break
            candidacy_list.append(temp['candidacy'])
        candidacy = random.choice(candidacy_list)
        logging.debug('Candidacy: %s in %s', candidacy, [str(player) for player in candidacy_list])
        return self.game.entry.getCharacter(candidacy)

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
