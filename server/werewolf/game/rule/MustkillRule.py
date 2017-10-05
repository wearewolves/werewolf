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
        #16인은 다시 바뀔 수 있기에 우선 다른 루트를 타게 그냥 둔다
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
                mason.writeWill("저는 초능력자입니다. (자동 생성된 로그입니다.)", "텔레")

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

        #코멘트 초기화
        self.game.entry.initComment()
        self.deleteTelepathy()

        #3. 게임 정보 업데이트
        self.game.setGameState("state", "게임중")
        self.game.setGameState("day", self.game.day+1)

    def nextTurn_Xday(self):
        logging.info("다음날로 고고!")
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
        self.deleteTelepathy()

        #참살! 아니면 습격
        mustKillVictim = self.mustkillbycruelWerewolf()
        if mustKillVictim:
            logging.debug("must kill assaultVictim: %s", mustKillVictim)
            self.assaultByCruelWerewolf(mustKillVictim, victim)
        else:
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

    def assaultByCruelWerewolf(self, assaultVictim, victim):
        self.game.entry.recordAssaultResult(assaultVictim)

        if assaultVictim.id == victim.id:
            logging.debug("습격 실패: 고습실")
        else:
            logging.debug("습격 성공: %s", assaultVictim)
            assaultVictim.toDeath("습격")

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

        logging.debug("습격!!!")
        #습격의 희생양들...
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        logging.debug("humanlist: %s", [str(player) for player in humanRace])

        #습격자!
        werewolfPlayers = self.game.entry.getPlayersByTruecharacter(Truecharacter.WEREWOLF, "('생존')")
        cruelwerewolfPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.CRUELWEREWOLF, "('생존')")
        logging.debug("%s", werewolfPlayers)

        if cruelwerewolfPlayer:
            cruelwerewolfPlayer = cruelwerewolfPlayer[0]

        #살아 있는 인랑이 있을 때만 습격을 진행한다.
        if not werewolfPlayers and not cruelwerewolfPlayer:
            return

        #인랑들이 습격을 결정했는지 확인한다.
        for werewolfPlayer in werewolfPlayers:
            #습격을 안했다면! 랜덤 습격 시작
            if not werewolfPlayer.hasAssault():
                werewolfPlayer.assaultRandom(humanRace)
        if cruelwerewolfPlayer:
            if not cruelwerewolfPlayer.hasAssault():
                cruelwerewolfPlayer.assaultRandom(humanRace)

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

    def mustkillbycruelWerewolf(self):
        cursor = self.game.db.cursor

        logging.debug("참살!!!")
        #습격의 희생양들...
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        logging.debug("humanlist: %s", [str(player) for player in humanRace])

        #습격자!
        cruelwerewolfPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.CRUELWEREWOLF, "('생존')")

        if cruelwerewolfPlayer:
            cruelwerewolfPlayer = cruelwerewolfPlayer[0]

        #살아 있는 잔혹한 인랑이 있을 때만 참살을 진행한다.
        if not cruelwerewolfPlayer:
            return

        #참살한 대상이 있는지 찾는다.
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
