#-*- coding:cp949 -*-
import time
import random
import logging
from werewolf.database.DATABASE     import DATABASE

class Character:
    def __init__(self, game, entry):
        self.game = game

        import re
        self.name = re.escape(entry['name'])
        #self.entry = entry
        self.no = entry['no']
        self.vote = entry['vote']
        self.id = entry['player']
        self.character = entry['character']
        self.truecharacter = entry['truecharacter']
        self.victim = entry['victim']
        self.alive = entry['alive']
        self.deathday = entry['deathday']
        self.deathtype = entry['deathtype']
        self.comment = entry['comment']
        self.suddenCount = entry['suddenCount']
        self.normal = entry['normal']
        self.memo = entry['memo']
        self.secret = entry['secret']
        self.grave = entry['grave']
        self.telepathy = entry['telepathy']
        self.ip = entry['ip']

    def toDeath(self, deathType):
        cursor = self.game.db.cursor
        query = "update `zetyx_board_werewolf_entry` set `alive`= '사망', `deathday` = '%s', `deathtype` ='%s'  where `game` = '%s' and `character` = '%s';"
        query %= (self.game.day, deathType, self.game.game, self.character)
        logging.debug(query)
        cursor.execute(query)
    
    def __str__(self):
        return "[<Character> id: %s, character: %s, role: %s]"%(self.id, self.character, self.truecharacter)

class Npc(Character):
    def toDeathByWerewolf(self):
        self.toDeath("습격")

    def writeWill(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_character` where no = '%s'"
        query %= (self.character)
        cursor.execute(query)
        deathTime = self.game.deathTime
        character_detail = cursor.fetchone()
        self.game.writeComment(1, "게임 마스터", "password", character_detail['comment'], "123.123.123.123", "일반", self.character, deathTime)

class Player(Character):
    def setTruecharacter(self, truecharacter):
        cursor = self.game.db.cursor
        logging.debug("setTruecharacter: %s", truecharacter)
        query = """update `zetyx_board_werewolf_entry` 
        set truecharacter= '%s' 
        where `game` = '%s' and `player` = '%s'"""
        query %= (truecharacter, self.game.game, self.id)
        logging.debug(query)
        cursor.execute(query)

    def setLevel(self, level):
        cursor = self.game.db.cursor
        query = "update `zetyx_member_table` set `level`= '%s' where no = '%s'"
        query %= (level, self.id)
        cursor.execute(query)

    def recordSuddenDeath(self):
        cursor = self.game.db.cursor
        reg_data = time.time()
        query = """INSERT INTO `zetyx_board_werewolf_suddenDeath` 
        (`game`,`name`,`player`,`character`,`truecharacter`,`deathday`,`reg_data`,`ip`) 
        VALUES('%s','%s','%s','%s','%s','%s','%s','%s') """
        query %= (self.game.game, self.name, self.id, self.character, self.truecharacter, self.game.day, reg_data, self.ip)
        logging.debug(query)
        cursor.execute(query)

    def hasVoted(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_vote` where game = '%s' and day ='%s' and voter='%s'"
        query %= (self.game.game, self.game.day, self.character)
        cursor.execute(query)
        result = cursor.fetchone()
        return result is not None

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
