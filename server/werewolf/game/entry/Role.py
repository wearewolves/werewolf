#-*- coding:cp949 -*-
import time
import random
import logging
from werewolf.database.DATABASE     import DATABASE
from werewolf.game.entry.Character  import *

class Race(object):
    HUMAN = 0
    WEREWOLF = 1

class Truecharacter(object):
    PLAYER = 0
    HUMAN = 1
    SEER = 2
    MEDIUM = 3
    POSSESSED = 4
    WEREWOLF = 5
    BODYGUARD = 6
    FREEMASONS = 7
    WEREHAMSTER = 8
    LONELYWEREWOLF = 9
    READERWEREWOLF = 10
    REVENGER = 11
    NOBILITY = 12
    CHIEF = 13
    DIABLO = 14
    SHERIFF = 15
    SEER_ODD = 16
    WEREWOLF_CON = 17
    CRUELWEREWOLF = 18
    HIDENOBILITY = 19


    # 더미룰을 위한 더미 리스트 (인랑리스트, 기타리스트)
    # 더미 인랑 리스트: 랑습룰시 더미 가능
    LIST_WEREWOLF = [WEREWOLF, LONELYWEREWOLF, READERWEREWOLF, WEREWOLF_CON, CRUELWEREWOLF]
    # 더미 기타 리스트: 어떠한 경우에도 더미가 되지 않음
    LIST_OTHERS = [WEREHAMSTER, DIABLO]

    #@staticmethod
    def get(i):
        for key, value in Truecharacter.__dict__.iteritems():
            if i == value:
                result = str()
                words = key.split('_')
                for word in words:
                    result += word.capitalize()
                return result
        return None
    get = staticmethod(get)

def getNondummyList(game):
    from werewolf.game.rule.RuleFactory import SUBRULE_NAME, getSubrule
    wolfAssault = getSubrule(SUBRULE_NAME.ASSAULT_ONESELF, game)
    if wolfAssault:
        return Truecharacter.LIST_OTHERS
    else:
        return Truecharacter.LIST_OTHERS + Truecharacter.LIST_WEREWOLF

class Human(Player):
    pass

class Seer(Player):
    def openEye(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_revelation`  where `game` = '%s' and `day` ='%s' and type = '점'; "
        query %= (self.game.game, self.game.day)
        logging.debug(query)
        cursor.execute(query)
        return cursor.fetchone()

    def seerRandom(self):
        # 모든 인원을 찾는다
        allEntry = self.game.entry.getAllEntry()
        # 랜덤 한 명을 고른다
        while True:
            targetPlayer = random.choice(allEntry)
            if targetPlayer.character == self.character:
                logging.debug("random assault choose oneself: %s -> %s", self, targetPlayer)
            else:
                break
        logging.debug("random seer: %s -> %s", self, targetPlayer)
        # race를 찾는다
        cursor = self.game.db.cursor
        query = "SELECT race FROM `zetyx_board_werewolf_truecharacter` WHERE no ='%s'"
        query %= (targetPlayer.truecharacter)
        logging.debug(query)
        cursor.execute(query)
        targetrace = cursor.fetchone()
        # 설정한다
        cursor2 = self.game.db.cursor
        query2 = "INSERT INTO `zetyx_board_werewolf_revelation`(`game`,`day`,`type`,`prophet`,`mystery`,`result`) VALUES ('%s','%s','점' ,'%s','%s','%s');"
        query2 %= (self.game.game, self.game.day, self.character, targetPlayer.character, targetrace['race'])
        logging.debug(query2)
        cursor2.execute(query2)

class Medium(Player):
    pass

class Possessed(Player):
    pass

class Werewolf(Player):
    def toDeath(self, deathType):
        # 투표사인 경우, 습격 설정 해제
        if deathType == "심판" and self.hasAssault():
            cursor = self.game.db.cursor
            query = "delete from `zetyx_board_werewolf_deathNote` where game = '%s' and day = '%s' and `werewolf` = '%s'"
            query %= (self.game.game, self.game.day, self.character)
            logging.debug(query)
            cursor.execute(query)
        Player.toDeath(self, deathType)

    def hasAssault(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_deathNote` where game = '%s' and day ='%s' and `werewolf`='%s'"
        query %= (self.game.game, self.game.day, self.character)
        logging.debug(query)
        cursor.execute(query)
        result = cursor.fetchone()
        return result is not None

    def assaultRandom(self, targetPlayers):
        cursor = self.game.db.cursor
        while True:
            targetPlayer = random.choice(targetPlayers)
            if targetPlayer.character == self.character:
                logging.debug("random assault choose oneself: %s -> %s", self, targetPlayer)
            else:
                break
        logging.debug("random assault: %s -> %s", self, targetPlayer)
        query = "INSERT INTO `zetyx_board_werewolf_deathNote`(`game`,`day`,`werewolf`,`injured`) VALUES ('%s', '%s','%s' ,'%s');"
        query %= (self.game.game, self.game.day, self.character, targetPlayer.character)
        logging.debug(query)
        cursor.execute(query)

class Bodyguard(Player):
    def guard(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_guard`  where `game` = '%s' and `hunter` = '%s' and `day` ='%s'; "
        query %= (self.game.game, self.character, self.game.day)
        logging.debug(query)
        cursor.execute(query)
        return cursor.fetchone()

class Freemasons(Player):
    pass

class Werehamster(Player):
    pass

class Lonelywerewolf(Werewolf):
    def hasAssault(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_deathnotehalf` where game = '%s' and day ='%s' and `werewolf`='%s'"
        query %= (self.game.game, self.game.day, self.character)
        logging.debug(query)
        cursor.execute(query)
        result = cursor.fetchone()
        return result is not None

    def assaultRandom(self,targetPlayers):
        cursor = self.game.db.cursor
        while True:
            targetPlayer = random.choice(targetPlayers)
            if targetPlayer.character == self.character:
                logging.debug("random assault choose oneself: %s -> %s", self, targetPlayer)
            else:
                break
        logging.debug("random assault: %s -> %s", self, targetPlayer)
        query = "INSERT INTO `zetyx_board_werewolf_deathnotehalf`(`game`,`day`,`werewolf`,`injured`) VALUES ('%s', '%s','%s' ,'%s');"
        query %= (self.game.game, self.game.day, self.character, targetPlayer.character)
        logging.debug(query)
        cursor.execute(query)

class Readerwerewolf(Werewolf):
    pass

class Revenger(Player):
    def toDeath(self, deathType):
        if deathType == "습격":
            self.revenge()
        Player.toDeath(self, deathType)

    def revenge(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_revenge`  where `game` = '%s'; "
        query %= (self.game.game)
        logging.debug(query)
        cursor.execute(query)
        target = cursor.fetchone()

        if target is not None:
            target = self.game.entry.getCharacter(target['target'])
            logging.debug("revenge target: %s", target)
            if target.alive == "생존":
                guard = None
                hunterPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.BODYGUARD)[0]

                if hunterPlayer.alive == "생존":
                    logging.debug("hunterPlayer: %s", hunterPlayer)
                    guard = hunterPlayer.guard()
                    if guard is not None:
                        guard = self.game.entry.getCharacter(guard['purpose'])
                        logging.debug("guard: %s", guard)

                if guard and target.id == guard.id:
                    logging.debug("복수 실패: 선방")
                else:
                    logging.debug("복수 성공")
                    target.toDeath("습격")
        else:
            logging.debug("revenge target: None")

class Nobility(Player):
    def toDeath(self, deathType):
        if deathType <> "심판":
            Player.toDeath(self, deathType)
        logging.debug("nobility is voted.")

class Chief(Player):
    pass

class Diablo(Player):
    def toDeath(self, deathType):
        if deathType <> "습격":
            Player.toDeath(self, deathType)

    def awaken(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_deathNote_result`  where `game` = '%s' and `injured` = '%s' ; "
        query %= (self.game.game, self.character)
        logging.debug(query)
        cursor.execute(query)
        result = cursor.fetchone()
        if result and result['injured'] == self.character:
            return True
        else:
            return False

class Sheriff(Player):
    def voteRandom(self, targetPlayers):
        cursor = self.game.db.cursor
        while True:
            targetPlayer = random.choice(targetPlayers)
            if targetPlayer.character == self.character:
                logging.debug("random vote choose oneself: %s -> %s", self, targetPlayer)
            else:
                break
        logging.debug("random vote: %s -> %s", self, targetPlayer)
        query = "INSERT INTO `zetyx_board_werewolf_vote` ( `game`,`day`,`voter`,`candidacy`) VALUES ('%s', '%s','%s' ,'%s');"
        query %= (self.game.game, self.game.day, self.character, targetPlayer.character)
        logging.debug(query)
        cursor.execute(query)
        cursor.execute(query)

class SeerOdd(Player):
    pass

class WerewolfCon(Player):
    pass

class Cruelwerewolf(Werewolf):
    pass

class Hidenobility(Nobility):
    pass
