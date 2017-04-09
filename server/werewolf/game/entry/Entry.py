#-*- coding:cp949 -*-
from werewolf.database.DATABASE     import DATABASE
import time
import random

class Entry:
    def __init__(self,game):
        self.game = game

    def checkNoCommentPlayer(self):
        cursor = self.game.db.cursor
        
        query ="""update `zetyx_board_werewolf_entry` 
        set `suddenCount` = `suddenCount` + 1 
        WHERE game ='%s' and alive='생존' and comment = '0' and  victim = '0'""";
        query%=self.game.game
        cursor.execute(query)        
        
        noMannerPlayers = self.getNoMannerPlayers()  
        #print noMannerPlayers
         
        for player in noMannerPlayers:
            player.recordSuddenDeath()            
            player.setLevel(8)
            
        
    def getVictim(self):
        cursor = self.game.db.cursor
        
        query ="select * from `zetyx_board_werewolf_entry` where game = '%s' and victim= '1'"
        query%=(self.game.game)
        cursor.execute(query)
        victim = cursor.fetchall()
        victim = self.makePlayer(victim)[0]               
        return victim        

    def getNoMannerPlayers(self):
        cursor = self.game.db.cursor

        maxNoCommentCount = self.getMaxNoCommentCount()
        
        query = "SELECT * FROM `zetyx_board_werewolf_entry` WHERE game ='%s' and alive='생존' and `suddenCount` = '%s' and victim = 0 "
        query%=(self.game.game,maxNoCommentCount)
        cursor.execute(query)
        
        noMannerPlayers = cursor.fetchall()
        
        noMannerPlayers = self.makePlayer(noMannerPlayers)
        return noMannerPlayers

    def getMaxNoCommentCount(self):
        if(self.game.termOfDay <= 1800):
            maxSuddenCount = 3
        else :
            maxSuddenCount = 1
        return  maxSuddenCount    

    def getPlayersByTruecharacter(self,truecharacter,alive="('생존','사망')"):
        cursor = self.game.db.cursor
        
        query = "SELECT * FROM `zetyx_board_werewolf_entry` WHERE game ='%s' and  `truecharacter` = '%s' and alive in %s"
        query%=(self.game.game,truecharacter,alive)
        
        #print query
        cursor.execute(query)
        allEntry = self.makePlayer(cursor.fetchall())
        #print list(allEntry)
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
        query%=self.game.game
        #print query
        cursor.execute(query)
        recordEntry = cursor.fetchall()
        #print recordEntry
        
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

        query%=self.game.game
        
        #print query
        cursor.execute(query)
        recordEntry = cursor.fetchall()
        #print recordEntry
        
        recordEntry = self.makePlayer(recordEntry)
	random.shuffle(recordEntry)

        return recordEntry
    
    
    def initComment(self):
        cursor = self.game.db.cursor
                
        query = """update `zetyx_board_werewolf_entry` 
        set comment = '0', normal ='20', memo  ='10' , secret  ='40' , grave  ='20', telepathy ='1' ,isConfirm='0'
        where game = '%s'""";
        query%=(self.game.game)
        #print query 
        cursor.execute(query)
    
    def makePlayer(self,entryList):
        resultList =[]
        for entry in entryList:
            if entry['victim'] == 1:
                #print "make NPC"
                resultList.append(Npc(self.game,entry))
            else:
                #print "make PC",entry['player']
                if entry['truecharacter'] == 0:
                    resultList.append(Player(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.HUMAN:
                    resultList.append(Human(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.SEER:
                    resultList.append(Seer(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.MEDIUM:
                    resultList.append(Medium(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.POSSESSED:
                    resultList.append(Possessed(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.WEREWOLF:
                    resultList.append(Werewolf(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.BODYGUARD:
                    resultList.append(Bodyguard(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.FREEMASONS:
                    resultList.append(Freemasons(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.WEREHAMSTER:
                    resultList.append(Werehamster(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.LONELYWEREWOLF:
                    resultList.append(Loneywerewolf(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.READERWEREWOLF:
                    resultList.append(Readerwerewolf(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.REVENGER:
                    resultList.append(Revenger(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.NOBILITY:
                    resultList.append(Nobility(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.CHIEF:
                    resultList.append(Chief(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.DIABLO:
                    resultList.append(Diablo(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.SHERIFF:
                    resultList.append(Sheriff(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.SEER_ODD:
                    resultList.append(SeerOdd(self.game,entry))
                elif entry['truecharacter'] == Truecharacter.WEREWOLF_CON:
                    resultList.append(WerewolfCon(self.game,entry))                    
	return  resultList
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
    def getEntryByRace(self,truecharacter,alive="생존"):
        cursor = self.game.db.cursor
        
        query = """SELECT * FROM `zetyx_board_werewolf_entry`,`zetyx_board_werewolf_truecharacter`  
        WHERE `zetyx_board_werewolf_entry`.truecharacter = `zetyx_board_werewolf_truecharacter`.no and game ='%s' and  `race`= '%s' and alive='%s'"""
        query%=(self.game.game,truecharacter,alive)
        #print query
        cursor.execute(query)
        return self.makePlayer(cursor.fetchall())
    
    def getAliveEntry(self):
        cursor = self.game.db.cursor
        
        query = "SELECT * FROM `zetyx_board_werewolf_entry` WHERE game ='%s' and alive='생존'"
        query%= (self.game.game)
        #print query
        cursor.execute(query)
        return self.makePlayer(cursor.fetchall())

    def getCharacter(self,character):
        cursor = self.game.db.cursor
        query = "SELECT * FROM `zetyx_board_werewolf_entry` WHERE `game` ='%s' and `character` ='%s'"
        query%= (self.game.game,character)
        #print query
        cursor.execute(query)
        return self.makePlayer(cursor.fetchall())[0]
    def recordAssaultResult(self,victim):
        cursor = self.game.db.cursor
        query   = "insert into `zetyx_board_werewolf_deathNote_result` ( `game` , `day` ,  `injured`) values ('%s','%s','%s')"
        query%=(self.game.game,self.game.day,victim.character)
        #print query
        cursor.execute(query)
    def getAllAlivePlayerCounter(self):
        cursor = self.game.db.cursor
        
        query = "SELECT count(*) FROM `zetyx_board_werewolf_entry` WHERE game ='%s' and alive='생존' and victim ='0'"
        query%= (self.game.game)
        #print query
        cursor.execute(query)
        return cursor.fetchone()['count(*)']

    def getAllConfirmCounter(self):
        cursor = self.game.db.cursor
        
        query = "SELECT count(*) FROM `zetyx_board_werewolf_entry` WHERE game ='%s' and alive='생존' and victim ='0' and isConfirm ='1'"
        query%= (self.game.game)
        #print query
        cursor.execute(query)
        return cursor.fetchone()['count(*)']

    def getSuddenPlayerCount(self):
        cursor = self.game.db.cursor
        
        query = "SELECT count(*) FROM `zetyx_board_werewolf_entry` WHERE game ='%s' and deathtype='돌연'"
        query%= (self.game.game)
        #print query
        cursor.execute(query)
        return cursor.fetchone()['count(*)']

            
class Character:
    def __init__(self,game,entry):
        self.game = game
        
        import re
        self.name= re.escape(entry['name'])
        #self.entry = entry 
        self.no = entry['no']
        self.vote= entry['vote']
        self.id= entry['player']
        self.character= entry['character']
        self.truecharacter= entry['truecharacter']
        self.victim= entry['victim']
        self.alive= entry['alive']
        self.deathday= entry['deathday']
        self.deathtype= entry['deathtype']
        self.comment= entry['comment']
        self.suddenCount= entry['suddenCount']
        self.normal = entry['normal']
        self.memo = entry['memo']
        self.secret = entry['secret']
        self.grave = entry['grave']
        self.telepathy = entry['telepathy']
        self.ip = entry['ip']
        
    def toDeath(self,deathType):
        cursor = self.game.db.cursor
        
        query = "update `zetyx_board_werewolf_entry` set `alive`= '사망', `deathday` = '%s', `deathtype` ='%s'  where `game` = '%s' and `character` = '%s';" ;
        query%=(self.game.day,deathType,self.game.game,self.character)
        #print query
        cursor.execute(query)
            
class Npc(Character):
    def toDeathByWerewolf(self):
        self.toDeath("습격")
        
    def writeWill(self):
        cursor = self.game.db.cursor
        
        query ="select * from `zetyx_board_werewolf_character` where no = '%s'"
        query%=(self.character)        
        cursor.execute(query)
        
        time = self.game.deathTime 
        
        character_detail = cursor.fetchone()
        self.game.writeComment(1,"게임 마스터","password",character_detail['comment'],"123.123.123.123","일반",self.character,time)
    
class Player(Character):
    def setTruecharacter(self,truecharacter):
        cursor = self.game.db.cursor
        
        #print "setTruecharacter",truecharacter
        query = """update `zetyx_board_werewolf_entry` 
        set truecharacter= '%s' 
        where `game` = '%s' and `player` = '%s'"""
        query%=truecharacter,self.game.game,self.id
        #print query
        cursor.execute(query)
    def setLevel(self,level):
        cursor = self.game.db.cursor
        query =  "update `zetyx_member_table` set `level`= '%s' where no = '%s'";
        query%= (level,self.id)
        cursor.execute(query)
        
    def recordSuddenDeath(self):
        cursor = self.game.db.cursor
        reg_data = time.time()
        query = """INSERT INTO `zetyx_board_werewolf_suddenDeath` 
        (`game`,`name`,`player`,`character`,`truecharacter`,`deathday`,`reg_data`,`ip`) 
        VALUES('%s','%s','%s','%s','%s','%s','%s','%s') """
        query %= (self.game.game,self.name,self.id,self.character,self.truecharacter,self.game.day,reg_data,self.ip)
        #print query
        cursor.execute(query)
    def hasVoted(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_vote` where game = '%s' and day ='%s' and voter='%s'"
        query%=(self.game.game,self.game.day,self.character)
        cursor.execute(query)
        result = cursor.fetchone()
        return result is not None 
    
    def voteRandom(self,targetPlayers):
        cursor = self.game.db.cursor
        while True:
            rand = random.randrange(0,len(targetPlayers))
            if targetPlayers[rand].character == self.character:
                #print "!!!",rand,targetPlayers[rand].character,self.character
		pass
            else:
                break
        query = "INSERT INTO `zetyx_board_werewolf_vote` ( `game`,`day`,`voter`,`candidacy`) VALUES ('%s', '%s','%s' ,'%s');";
        query%= (self.game.game, self.game.day,self.character , targetPlayers[rand].character )
        #print query
        cursor.execute(query)
        
class Race:
    HUMAN = 0
    WEREWOLF = 1
                
class Truecharacter:
    PLAYER = 0
    HUMAN = 1
    SEER = 2
    MEDIUM= 3    
    POSSESSED = 4
    WEREWOLF = 5
    BODYGUARD = 6    
    FREEMASONS = 7    
    WEREHAMSTER = 8  
    LONELYWEREWOLF = 9    
    READERWEREWOLF = 10
    REVENGER = 11
    NOBILITY= 12    
    CHIEF = 13    
    DIABLO =  14  
    SHERIFF = 15
    SEER_ODD = 16
    WEREWOLF_CON = 17    

class Human(Player):
    pass
        
class Seer(Player):
    def openEye(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_revelation`  where `game` = '%s' and `day` ='%s' and type = '점'; " ;
        query%=(self.game.game,self.game.day)
        #print query
        cursor.execute(query)
        return cursor.fetchone()    

class Medium(Player):
    pass     

class Possessed(Player):
    pass     

class Werewolf(Player):
    def hasAssault(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_deathNote` where game = '%s' and day ='%s' and `werewolf`='%s'"
        query%=(self.game.game,self.game.day,self.character)
        #print query
        cursor.execute(query)
        result = cursor.fetchone()
        return result is not None 
                    
    def assaultRandom(self,targetPlayers):
        cursor = self.game.db.cursor
        while True:
            rand = random.randrange(0,len(targetPlayers))
            if targetPlayers[rand].character == self.character:
                #print "!!!",rand,targetPlayers[rand].character,self.character
		pass
            else:
                break
        query = "INSERT INTO `zetyx_board_werewolf_deathNote`(`game`,`day`,`werewolf`,`injured`) VALUES ('%s', '%s','%s' ,'%s');";
        query%= (self.game.game, self.game.day,self.character, targetPlayers[rand].character )
        #print query
        cursor.execute(query)        
        
class Bodyguard(Player):
    def guard(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_guard`  where `game` = '%s' and `hunter` = '%s' and `day` ='%s'; " ;
        query%=(self.game.game,self.character,self.game.day)
        #print query
        cursor.execute(query)
        return cursor.fetchone()
        
class Freemasons(Player):
    pass 

class Werehamster(Player):
    pass     

class Loneywerewolf(Werewolf):
    def hasAssault(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_deathnotehalf` where game = '%s' and day ='%s' and `werewolf`='%s'"
        query%=(self.game.game,self.game.day,self.character)
        #print query
        cursor.execute(query)
        result = cursor.fetchone()
        return result is not None 
                    
    def assaultRandom(self,targetPlayers):
        cursor = self.game.db.cursor
        while True:
            rand = random.randrange(0,len(targetPlayers))
            if targetPlayers[rand].character == self.character:
                #print "!!!",rand,targetPlayers[rand].character,self.character
		pass
            else:
                break
        query = "INSERT INTO `zetyx_board_werewolf_deathnotehalf`(`game`,`day`,`werewolf`,`injured`) VALUES ('%s', '%s','%s' ,'%s');";
        query%= (self.game.game, self.game.day,self.character, targetPlayers[rand].character )
        #print query
        cursor.execute(query)        

class Readerwerewolf(Werewolf):
    pass     

class Revenger(Player):
    def toDeath(self,deathType):
	if(deathType =="습격"):
		self.revenge();
	Player.toDeath(self,deathType) 

    def revenge(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_revenge`  where `game` = '%s'; " ;
        query%=(self.game.game)
        #print query
        cursor.execute(query)
        target = cursor.fetchone()

	if target is not None:
		target = self.game.entry.getCharacter(target['target'])
	        if(target.alive == "생존"):
		        guard={}
		        hunterPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.BODYGUARD)[0]    

		        if(hunterPlayer.alive == "생존"):
		            #print "hunterPlayer",hunterPlayer        
		            guard = hunterPlayer.guard()
		            if guard is not None:
		                guard = self.game.entry.getCharacter(guard['purpose'])
		                #print "guard", guard
                
            
		        if(guard and target.id == guard.id):
		            #print "습격 실패: " 
			    pass
		        else:
		            #print "습격  성공", target
		            target.toDeath("습격")   

class Nobility(Player):
    def toDeath(self,deathType):
	if(deathType <>"심판"):
		Player.toDeath(self,deathType) 


class Chief(Player):
    pass     

class Diablo(Player):
    def toDeath(self,deathType):
	if(deathType <>"습격"):
		Player.toDeath(self,deathType) 

    def awaken(self):
        cursor = self.game.db.cursor
        query = "select * from `zetyx_board_werewolf_deathNote_result`  where `game` = '%s' and `injured` = '%s' ; " ;
        query%=(self.game.game,self.character)
        #print query
        cursor.execute(query)
        result = cursor.fetchone()

	if(result and result['injured'] == self.character):
		return True
	else:
		return False

class Sheriff(Player):
    def voteRandom(self,targetPlayers):
        cursor = self.game.db.cursor
        while True:
            rand = random.randrange(0,len(targetPlayers))
            if targetPlayers[rand].character == self.character:
                #print "!!!",rand,targetPlayers[rand].character,self.character
		pass
            else:
                break
        query = "INSERT INTO `zetyx_board_werewolf_vote` ( `game`,`day`,`voter`,`candidacy`) VALUES ('%s', '%s','%s' ,'%s');";
        query%= (self.game.game, self.game.day,self.character , targetPlayers[rand].character )
        #print query
        cursor.execute(query)
        cursor.execute(query)

class SeerOdd(Player):
    pass     

class WerewolfCon(Player):
    pass      
        
