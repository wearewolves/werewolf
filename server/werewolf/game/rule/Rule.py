#-*- coding:cp949 -*-
from werewolf.database.DATABASE import DATABASE
from werewolf.game.GAME_STATE import GAME_STATE
from werewolf.game.entry.Entry import Truecharacter
from werewolf.game.entry.Entry import Race
import logging
import random
import copy

class Rule:
    def __init__(self, game):
        self.game = game

class WerewolfRule(Rule):
    def nextTurn(self):
        if self.game.state== GAME_STATE.READY:
            if(self.min_players <= self.game.players and self.game.players <= self.max_players):
                logging.info("게임 초기화 시작")
                self.initGame()
            else:
                self.game.deleteGame()
        elif self.game.state==GAME_STATE.PLAYING:
            if(self.game.day == 1):
                self.nexeTurn_2day()
            else:
                self.nextTurn_Xday()
            
    def initGame(self):
        #플레이해본 사람
        expertPlayers = self.game.entry.getExpertPlayers()
        #print "expertPlayers",expertPlayers

        #초보자
        novicePlayers = self.game.entry.getNovicePlayers()
        #print "novicePlayers",novicePlayers

        truecharacterList = copy.copy(self.temp_truecharacter[len(novicePlayers) + len(expertPlayers) + 1])
        logging.info("players: %d", len(novicePlayers) + len(expertPlayers) + 1)

        #마을 사람 배치
        random.shuffle(novicePlayers)
        logging.debug("noviceEntry: %s", novicePlayers)
        while novicePlayers:
            try:
                truecharacterList.remove(Truecharacter.HUMAN)
            except ValueError:
                logging.debug("초보자 할당: 남은 마을사람 부족 %d명", len(truecharacterList))
                break
            player = novicePlayers.pop()
            logging.debug("player: %d with job %d", player.id, Truecharacter.HUMAN)
            player.setTruecharacter(Truecharacter.HUMAN)

        restPlayers = expertPlayers + novicePlayers
        random.shuffle(restPlayers)
        logging.debug("restEntry: %s", restPlayers)
        logging.debug("restJob: %s", truecharacterList)
        while restPlayers:
            player = restPlayers.pop()
            job = truecharacterList.pop()
            logging.debug("player: %d with job %d", player.id, job)
            player.setTruecharacter(job)
        if not truecharacterList:
            logging.error("Some roles are NOT assigned: %s", truecharacterList)

        #2. 희생자의 코멘트
        victim = self.game.entry.getVictim()
        logging.debug("victim: %s", victim)
        victim.writeWill()

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
        candidacy = random.choice(cadidacy_list)
        return self.game.entry.getCharacter(candidacy)

    def decideByWerewolf(self):
        cursor = self.game.db.cursor

        logging.info("습격!!")
        #습격의 희생양들...
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        logging.debug("%s", alivePlayers)

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
        logging.debug("injured: %s in %s", injured, injured_list)
        return self.game.entry.getCharacter(injured)
