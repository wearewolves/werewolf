#-*- coding:cp949 -*-
import time
import random
import logging
from werewolf.database.DATABASE     import DATABASE
from werewolf.game.entry.Role       import *

class Entry:
    def __init__(self, game):
        self.game = game

    def checkNoCommentPlayer(self):
        cursor = self.game.db.cursor

        query ="""update `zetyx_board_werewolf_entry`
        set `suddenCount` = `suddenCount` + 1 
        WHERE game ='%s' and alive='생존' and comment = '0' and  victim = '0'"""
        query %= self.game.game
        cursor.execute(query)

        noMannerPlayers = self.getNoMannerPlayers()
        logging.debug("noMannerPlayers: %s", [str(player) for player in noMannerPlayers])
        #print noMannerPlayers

        for player in noMannerPlayers:
            player.recordSuddenDeath()
            player.setLevel(8)

    def getVictim(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_entry` where game = '%s' and victim= '1'"
        query %= (self.game.game)
        cursor.execute(query)
        victim = cursor.fetchall()
        victim = self.makePlayer(victim)[0]
        return victim

    def getNoMannerPlayers(self):
        cursor = self.game.db.cursor

        maxNoCommentCount = self.getMaxNoCommentCount()
        query = "SELECT * FROM `zetyx_board_werewolf_entry` WHERE game ='%s' and alive='생존' and `suddenCount` = '%s' and victim = 0 "
        query %= (self.game.game, maxNoCommentCount)
        cursor.execute(query)

        noMannerPlayers = cursor.fetchall()
        noMannerPlayers = self.makePlayer(noMannerPlayers)
        return noMannerPlayers

    def getMaxNoCommentCount(self):
        if self.game.termOfDay <= 1800:
            maxSuddenCount = 100
        else:
            maxSuddenCount = 1
        return maxSuddenCount

    def getPlayersByTruecharacter(self, truecharacter, alive="('생존','사망')"):
        cursor = self.game.db.cursor

        query = "SELECT * FROM `zetyx_board_werewolf_entry` WHERE game ='%s' and  `truecharacter` = '%s' and alive in %s"
        query %= (self.game.game, truecharacter, alive)

        logging.debug(query)
        cursor.execute(query)
        allEntry = self.makePlayer(cursor.fetchall())
        logging.debug('%s', [str(player) for player in allEntry])
        return allEntry

    def getExpertPlayers(self):
        cursor = self.game.db.cursor
        query = """SELECT * 
        FROM `zetyx_board_werewolf_entry`,`zetyx_board_werewolf_record` 
        WHERE game ='%s'and `zetyx_board_werewolf_entry`.player = `zetyx_board_werewolf_record`.player"""

        query ="""
        SELECT entry.*, count(  *  ) as count  
        FROM  `zetyx_board_werewolf_entry`  AS entry 
        inner JOIN  `zetyx_board_werewolf_entry`  AS record 
        ON entry.player = record.player 
        WHERE entry.victim = 0 AND entry.`game`  = '%s'
        GROUP  BY entry.player HAVING count(  *  )  > 1
        """
        query %= self.game.game
        logging.debug(query)
        cursor.execute(query)
        recordEntry = cursor.fetchall()
        logging.debug('%s', [str(player) for player in recordEntry])

        recordEntry = self.makePlayer(recordEntry)
        return recordEntry
        """
        SELECT record.player, count(  *  )  FROM  `zetyx_board_werewolf_entry`  AS entry,
         `zetyx_board_werewolf_entry`  AS record 
         WHERE entry.`game`  = 877 AND record.`game`  != 877 AND entry.player = record.player AND entry.victim = 0 
         GROUP  BY record.`player` 
        """

    def getNovicePlayers(self):
        cursor = self.game.db.cursor

        query ="""SELECT * FROM `zetyx_board_werewolf_entry` left join `zetyx_board_werewolf_record` 
        on `zetyx_board_werewolf_entry`.player = `zetyx_board_werewolf_record`.player 
        WHERE game ='%s' and isnull(`zetyx_board_werewolf_record`.player) and `victim` = 0""" 

        query ="""
        SELECT entry.*, count(  *  ) as count  
        FROM  `zetyx_board_werewolf_entry`  AS entry 
        inner JOIN  `zetyx_board_werewolf_entry`  AS record 
        ON entry.player = record.player 
        WHERE entry.victim = 0 AND entry.`game`  = '%s'
        GROUP  BY entry.player HAVING count(  *  )  = 1
        """  
        query %= self.game.game
        logging.debug(query)
        cursor.execute(query)
        recordEntry = cursor.fetchall()
        logging.debug('%s', [str(player) for player in recordEntry])

        recordEntry = self.makePlayer(recordEntry)
        return recordEntry

    def initComment(self):
        cursor = self.game.db.cursor
        query = """update `zetyx_board_werewolf_entry` 
        set comment = '0', normal ='20', memo  ='10' , secret  ='40' , grave  ='20', telepathy ='1' ,isConfirm='0'
        where game = '%s'"""
        query %= (self.game.game)
        logging.debug(query)
        cursor.execute(query)

    def makePlayer(self, entryList):
        resultList = []
        for entry in entryList:
            if entry['victim'] == 1:
                logging.debug("make NPC")
                resultList.append(Npc(self.game, entry))
            else:
                logging.debug("make PC %s", entry['player'])
                if entry['truecharacter'] == 0:
                    resultList.append(Player(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.HUMAN:
                    resultList.append(Human(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.SEER:
                    resultList.append(Seer(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.MEDIUM:
                    resultList.append(Medium(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.POSSESSED:
                    resultList.append(Possessed(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.WEREWOLF:
                    resultList.append(Werewolf(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.BODYGUARD:
                    resultList.append(Bodyguard(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.FREEMASONS:
                    resultList.append(Freemasons(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.WEREHAMSTER:
                    resultList.append(Werehamster(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.LONELYWEREWOLF:
                    resultList.append(Loneywerewolf(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.READERWEREWOLF:
                    resultList.append(Readerwerewolf(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.REVENGER:
                    resultList.append(Revenger(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.NOBILITY:
                    resultList.append(Nobility(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.CHIEF:
                    resultList.append(Chief(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.DIABLO:
                    resultList.append(Diablo(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.SHERIFF:
                    resultList.append(Sheriff(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.SEER_ODD:
                    resultList.append(SeerOdd(self.game, entry))
                elif entry['truecharacter'] == Truecharacter.WEREWOLF_CON:
                    resultList.append(WerewolfCon(self.game, entry))
	return resultList

    """
    def getAllEntry(self):
        cursor = self.db.cursor
        
        query = "SELECT player FROM `zetyx_board_werewolf_entry` WHERE game ='%s' and  victim = '0'"
        query%=self.game.game
        #print query
        cursor.execute(query)
        allEntry = list(cursor.fetchall())
        #print list(allEntry)
        return allEntry        
    """

    def getEntryByRace(self, truecharacter, alive="생존"):
        cursor = self.game.db.cursor
        query = """SELECT * FROM `zetyx_board_werewolf_entry`,`zetyx_board_werewolf_truecharacter`  
        WHERE `zetyx_board_werewolf_entry`.truecharacter = `zetyx_board_werewolf_truecharacter`.no and game ='%s' and  `race`= '%s' and alive='%s'"""
        query %= (self.game.game, truecharacter, alive)
        logging.debug(query)
        cursor.execute(query)
        return self.makePlayer(cursor.fetchall())

    def getAliveEntry(self):
        cursor = self.game.db.cursor
        query = "SELECT * FROM `zetyx_board_werewolf_entry` WHERE game ='%s' and alive='생존'"
        query %= (self.game.game)
        logging.debug(query)
        cursor.execute(query)
        return self.makePlayer(cursor.fetchall())

    def getCharacter(self, character):
        cursor = self.game.db.cursor
        query = "SELECT * FROM `zetyx_board_werewolf_entry` WHERE `game` ='%s' and `character` ='%s'"
        query %= (self.game.game, character)
        logging.debug(query)
        cursor.execute(query)
        return self.makePlayer(cursor.fetchall())[0]

    def recordAssaultResult(self, victim):
        cursor = self.game.db.cursor
        query = "insert into `zetyx_board_werewolf_deathNote_result` ( `game` , `day` ,  `injured`) values ('%s','%s','%s')"
        query%=(self.game.game, self.game.day, victim.character)
        #print query
        cursor.execute(query)

    def getAllAlivePlayerCounter(self):
        cursor = self.game.db.cursor
        query = "SELECT count(*) FROM `zetyx_board_werewolf_entry` WHERE game ='%s' and alive='생존' and victim ='0'"
        query %= (self.game.game)
        logging.debug(query)
        cursor.execute(query)
        return cursor.fetchone()['count(*)']

    def getAllConfirmCounter(self):
        cursor = self.game.db.cursor
        query = "SELECT count(*) FROM `zetyx_board_werewolf_entry` WHERE game ='%s' and alive='생존' and victim ='0' and isConfirm ='1'"
        query %= (self.game.game)
        logging.debug(query)
        cursor.execute(query)
        return cursor.fetchone()['count(*)']

    def getSuddenPlayerCount(self):
        cursor = self.game.db.cursor
        query = "SELECT count(*) FROM `zetyx_board_werewolf_entry` WHERE game ='%s' and deathtype='돌연'"
        query %= (self.game.game)
        logging.debug(query)
        cursor.execute(query)
        return cursor.fetchone()['count(*)']
