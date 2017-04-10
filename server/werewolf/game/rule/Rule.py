#-*- coding:cp949 -*-
from werewolf.database.DATABASE import DATABASE
from werewolf.game.GAME_STATE import GAME_STATE
from werewolf.game.entry.Entry import Truecharacter
from werewolf.game.entry.Entry import Race
import random
import copy

class Rule:
    def __init__(self,game):
        self.game = game

class WerewolfRule(Rule):
    def nextTurn(self):
        if(self.game.state== GAME_STATE.READY):
            if(self.min_players <= self.game.players and self.game.players <= self.max_players):
                print "게임 초기화 시작"
                self.initGame()                
            else:
                pass
                self.game.deleteGame()
        elif(self.game.state==GAME_STATE.PLAYING):
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

        truecharacterList = copy.copy(self.temp_truecharacter[len(novicePlayers) + len(expertPlayers)+1 ])
        print "players",len(novicePlayers) + len(expertPlayers)+1
        #마을 사람 배치
        while(len(novicePlayers)>0):
            #print "len(novicePlayers)",len(novicePlayers)
            #print "truecharacterList",truecharacterList
            if(truecharacterList[0] != Truecharacter.HUMAN):
                break
            ram = random.randrange(0,len(novicePlayers))
            #print "random",ram
            player = novicePlayers.pop()
            job = truecharacterList.pop(0)            
            #print "player: ",player.id,"job: ",job
            player.setTruecharacter(job)

        restPlayers =expertPlayers + novicePlayers
        #print "restEntry:", restPlayers
        #print "restJob:", truecharacterList
        
        #남은 직업 배치
        while(len(restPlayers)>0):
            player = restPlayers.pop(0)
            job = truecharacterList.pop(random.randrange(0,len(truecharacterList))  )            
            #print "player: ",player.id,"job: ",job
            player.setTruecharacter(job)
            
        #2. 희생자의 코멘트
        victim =self.game.entry.getVictim()
        #print "victim",victim
        victim.writeWill()

        #3. 게임 정보 업데이트
        self.game.setGameState("state",GAME_STATE.PLAYING)
        self.game.setGameState("day",self.game.day+1)
        
        #보통 방으로..
        cursor = self.game.db.cursor
        query = "update `zetyx_board_werewolf` set `%s` = '%s'  where no = '%s'"
        query%=("is_secret",0,self.game.game)
        #print query
        cursor.execute(query) 
        
        #4. 코멘트 초기화
        self.game.entry.initComment()

    def decideByMajority(self):
        cursor = self.game.db.cursor
        
        print "투표!"
        alivePlayers = self.game.entry.getAliveEntry()
                
        #살아 있는 사람이 1명 이상일 경우에만 투표를 진행한다.
        if(len(alivePlayers)<=1 ): return
        #모든 사람들이 투표를 결정했는지 확인한다.
        for alivePlayer in alivePlayers:
            #투표를 안했다면! 렌덤 투표 시작     
            if alivePlayer.hasVoted() is False:
                alivePlayer.voteRandom(alivePlayers)
        
        #가장 표를 많이 받은 사람을 찾는다.
        query = '''select `candidacy`, count(*) as count from `zetyx_board_werewolf_vote` 
        where game = '%s' and day ='%s' 
        group by `candidacy` 
        order by `count`  DESC '''
        query%=(self.game.game,self.game.day)
        #print query
        
        cursor.execute(query)
        result = cursor.fetchall()
        #print result
        
        count = 0
        candidacy_list=[]
        
        for temp in result :
            if count <= temp['count']: 
                count =temp['count']
                #print "count", count
            else:
                break
            candidacy_list.append(temp['candidacy'])
            
        return self.game.entry.getCharacter(candidacy_list[random.randrange(0,len(candidacy_list))])   
   
    def decideByWerewolf(self):
        cursor = self.game.db.cursor
        
        print "습격!!"
        #습격의 희생양들...
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        #print alivePlayers

        #습격자!
        werewolfPlayers = self.game.entry.getPlayersByTruecharacter(Truecharacter.WEREWOLF,"('생존')")
        #print werewolfPlayers 
        
        #살아 있는 인랑이 있을 때만 습격을 진행한다.
        if(len(werewolfPlayers) ==0 ): 
            return        

        #인랑들이 습격을 결정했는지 확인한다.
        for werewolfPlayer in werewolfPlayers:
            #습격을 안했다면! 렌덤 습격 시작     
            if werewolfPlayer.hasAssault() is False:
                werewolfPlayer.assaultRandom(humanRace )


        #인랑들이 가장 좋아하는 사람을 찾는다.
        query = '''select `injured`, count(*) as count from `zetyx_board_werewolf_deathNote` 
        where game = '%s' and day ='%s' 
        group by `injured` 
        order by `count`  DESC '''
        query%=(self.game.game,self.game.day)
        #print query
        
        cursor.execute(query)
        result = cursor.fetchall()
        #print result
        
        count = 0
        injured_list=[]
        
        for temp in result :
            if count <= temp['count']: 
                count =temp['count']
                #print "count", count
            else:
                break
            injured_list.append(temp['injured'])
        #print injured_list[random.randrange(0,len(injured_list))],injured_list
        return self.game.entry.getCharacter(injured_list[random.randrange(0,len(injured_list))])
           
class BasicRule(WerewolfRule):
    min_players = 11
    max_players  = 16
    
    # 기본 세팅
    temp_truecharacter ={}
    temp_truecharacter[11] =  [1,1,1,1,2,3,4,5,5,6]
    temp_truecharacter[12] =  [1,1,1,1,1,2,3,4,5,5,6]
    temp_truecharacter[13] =  [1,1,1,1,1,1,2,3,4,5,5,6]
    temp_truecharacter[14] =  [1,1,1,1,1,1,1,2,3,4,5,5,6]
    temp_truecharacter[15] =  [1,1,1,1,1,1,1,2,3,4,5,5,5,6]
    temp_truecharacter[16] =  [1,1,1,1,1,1,2,3,4,5,5,5,6,7,7]    

    def __init__(self,game):
        WerewolfRule.__init__(self, game)
        #print "basicRule"    
    def initGame(self):
        print "init Basic Rule"
        WerewolfRule.initGame(self)
    def nexeTurn_2day(self):
        print "2일째로 고고!"

        #일반 로그를 쓰지 않은 사람을 체크한다.
        self.game.entry.checkNoCommentPlayer()

        #희생양 NPC 습격
        victim =self.game.entry.getVictim()
        victim.toDeathByWerewolf()

        #돌연사 시킴 
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("돌연 ")       
        
        #코맨츠 초기화
        self.game.entry.initComment()

        #3. 게임 정보 업데이트
        self.game.setGameState("state","게임중")
        self.game.setGameState("day",self.game.day+1)

    def nextTurn_Xday(self):
        print "다음 날로 고고!"                
        #일반 로그를 쓰지 않은 사람을 체크한다.
        self.game.entry.checkNoCommentPlayer()
        
        #투표 -살아 있는 참가자가 투표를 했는지 체크, 않했다면 랜덤 투표
        victim = self.decideByMajority()
        if victim:
            victim.toDeath("심판") 
        
        #돌연사 시킴 
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("돌연 ")       
        
        #코맨츠 초기화
        self.game.entry.initComment()
        
        #습격!
        assaultVictim = self.decideByWerewolf()
        if assaultVictim:
            #print "assaultVictim",assaultVictim
            self.assaultByWerewolf(assaultVictim,victim)
            
        #종료 조건 확인
        #사람!
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        #for human in humanRace :
        #    print human
        
        #습격자!
        werewolfRace = self.game.entry.getEntryByRace(Race.WEREWOLF)
        #for werewolf in werewolfRace :
        #    print werewolf
        
        if((len(humanRace) <= len(werewolfRace)) or (len(humanRace) == 0)):
            print "인랑 승리"
            self.game.setGameState("win","1")
            if(self.game.termOfDay == 60):
                self.game.setGameState("state",GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state",GAME_STATE.GAMEOVER)
            
        elif(len(werewolfRace) == 0):
            print "인간 승리"
            self.game.setGameState("win","0")
            if(self.game.termOfDay == 60):
                self.game.setGameState("state",GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state",GAME_STATE.GAMEOVER)
        else:
            print "계속 진행"
            #self.game.setGameState("state","게임중")
        
        self.game.setGameState("day",self.game.day+1)

    def assaultByWerewolf(self,assaultVictim,victim):
        self.game.entry.recordAssaultResult(assaultVictim)
            
        guard={}
        hunterPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.BODYGUARD)[0]    

        if(hunterPlayer.alive == "생존"):
            #print "hunterPlayer",hunterPlayer        
            guard = hunterPlayer.guard()
            if guard is not None:
                guard = self.game.entry.getCharacter(guard['purpose'])
                #print "guard", guard
                
        if assaultVictim.id == victim.id:
            #print "습격 실패: (고습실)"
            pass
        elif guard and assaultVictim.id == guard.id:
            #print "습격 실패: (선방)" 
            pass
        else:
            #print "습격 성공", assaultVictim
            assaultVictim.toDeath("습격")       

        #print "guard: ",guard
        #print "assaultVictim: ", assaultVictim
        #print "victim: ", victim        

class HamsterRule(BasicRule):
    min_players = 11
    max_players = 17
    
    # 기본 세팅
    temp_truecharacter ={}
    temp_truecharacter[11] =  [1,1,1,1,2,3,4,5,5,6]
    temp_truecharacter[12] =  [1,1,1,1,1,2,3,4,5,5,6]
    temp_truecharacter[13] =  [1,1,1,1,1,1,2,3,4,5,5,6]
    temp_truecharacter[14] =  [1,1,1,1,1,1,1,2,3,4,5,5,6]
    temp_truecharacter[15] =  [1,1,1,1,1,1,1,2,3,4,5,5,5,6]
    temp_truecharacter[16] =  [1,1,1,1,1,1,2,3,4,5,5,5,6,7,7]    
    temp_truecharacter[17] =  [1,1,1,1,1,1,2,3,4,5,5,5,6,7,7,8]    

    def __init__(self,game):
        WerewolfRule.__init__(self, game)
        #print "Hamster Rule"

    def nextTurn(self):
        if(self.game.state== GAME_STATE.READY):
            if(self.min_players <= self.game.players and self.game.players <= self.max_players):
                print "게임 초기화 시작"
                self.initGame()                
            else:
                pass
                self.game.deleteGame()
        elif(self.game.state==GAME_STATE.PLAYING):
            if(self.game.day == 1):
                if(self.game.players == 17):
                    self.nexeTurn_2day()                    
                else:
                    BasicRule.nexeTurn_2day(self)        
            else:
                if(self.game.players == 17):
                    self.nextTurn_Xday()
                else:
                    BasicRule.nextTurn_Xday(self)        

    def initGame(self):
        print "initHamster!!"
        WerewolfRule.initGame(self)        
    def nexeTurn_2day(self):
        print "2일째로 고고!"

        #일반 로그를 쓰지 않은 사람을 체크한다.
        self.game.entry.checkNoCommentPlayer()

        #희생양 NPC 습격
        victim =self.game.entry.getVictim()
        victim.toDeathByWerewolf()
        
        #햄스터
        hamsterPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.WEREHAMSTER)[0] 
        
        #점 습!
        self.assaultByForecast(hamsterPlayer)

        #돌연사 시킴 
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("돌연 ")       
        
        #코맨츠 초기화
        self.game.entry.initComment()

        #3. 게임 정보 업데이트
        self.game.setGameState("state","게임중")
        self.game.setGameState("day",self.game.day+1)
                    
    def nextTurn_Xday(self):
        print "다음 날로 고고!"                
        #일반 로그를 쓰지 않은 사람을 체크한다.
        self.game.entry.checkNoCommentPlayer()
        
        #투표 -살아 있는 참가자가 투표를 했는지 체크, 않했다면 랜덤 투표
        victim = self.decideByMajority()
        if victim:
            victim.toDeath("심판") 
        
        #돌연사 시킴 
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("돌연 ")       
        
        #코맨츠 초기화
        self.game.entry.initComment()

        #햄스터
        hamsterPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.WEREHAMSTER)[0] 
        
        #점 습!
        self.assaultByForecast(hamsterPlayer)

        #습격!
        assaultVictim = self.decideByWerewolf()
        if assaultVictim:
            #print "assaultVictim",assaultVictim
            self.assaultByWerewolfAndHamster(assaultVictim,victim,hamsterPlayer)
            
        #종료 조건 확인
        #사람!
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        #for human in humanRace :
        #    print human
        
        #습격자!
        werewolfRace = self.game.entry.getEntryByRace(Race.WEREWOLF)
        #for werewolf in werewolfRace :
        #    print werewolf
        
        if((len(humanRace) <= len(werewolfRace)) or (len(humanRace) == 0)):
            if(self.game.termOfDay == 60):
                self.game.setGameState("state",GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state",GAME_STATE.GAMEOVER)

            if hamsterPlayer.alive == "생존" :
                print "햄스터 승리"
                self.game.setGameState("win","2")
            else:
                print "인랑 승리"
                self.game.setGameState("win","1")
            
        elif(len(werewolfRace) == 0):
            if(self.game.termOfDay == 60):
                self.game.setGameState("state",GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state",GAME_STATE.GAMEOVER)

            if hamsterPlayer.alive == "생존" :
                print "햄스터 승리"
                self.game.setGameState("win","2")
            else:
                print "인간 승리"            
                self.game.setGameState("win","0")
        else:
            print "계속 진행"
            #self.game.setGameState("state","게임중")
        
        self.game.setGameState("day",self.game.day+1)
        
    def assaultByForecast(self,hamsterPlayer):
        #print "점습!!"
        forecastTarget={}
        seerPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.SEER)[0]    

        if(seerPlayer.alive == "생존"):
            #print "seerPlayer",seerPlayer        
            forecastTarget = seerPlayer.openEye()
            #print "forecastTarget", forecastTarget
                    
            if forecastTarget is not None:
                forecastTarget = self.game.entry.getCharacter(forecastTarget['mystery'])
                #print "forecastTarget", forecastTarget  

        #print "hamsterPlayer",hamsterPlayer   

        if(forecastTarget and hamsterPlayer.alive =="생존" and hamsterPlayer.id == forecastTarget.id):
            #print "점 습격  성공", hamsterPlayer
            hamsterPlayer.toDeath("습격")                        
        else:
            #print "점 습격 실패: " 
	    pass
            
    def assaultByWerewolfAndHamster(self,assaultVictim,victim,hamsterPlayer):
        self.game.entry.recordAssaultResult(assaultVictim)
            
        guard={}
        hunterPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.BODYGUARD)[0]    

        if(hunterPlayer.alive == "생존"):
            #print "hunterPlayer",hunterPlayer        
            guard = hunterPlayer.guard()
            if guard is not None:
                guard = self.game.entry.getCharacter(guard['purpose'])
                #print "guard", guard
                
        if assaultVictim.id == victim.id:
            #print "습격 실패: (고습실)"
            pass
        elif guard and assaultVictim.id == guard.id:
            #print "습격 실패: (선방)"
            pass
        elif assaultVictim.id == hamsterPlayer.id:
            #print "습격 실패: (햄습)"
	        pass
        else:
            #print "습격 성공", assaultVictim
            assaultVictim.toDeath("습격")       

        #print "guard: ",guard
        #print "assaultVictim: ", assaultVictim
        #print "victim: ", victim              

class ExpansionRule(WerewolfRule):
    min_players = 9
    max_players  = 17
    
    # 기본 세팅
    temp_truecharacter ={}
    temp_truecharacter[9] =  [2,3,6,11,15,4,5,9]
    temp_truecharacter[10] =  [1,2,3,6,11,12,4,5,9]
    temp_truecharacter[11] =  [1,1,15,2,3,6,13,4,5,10]
    temp_truecharacter[12] =  [1,1,1,1,2,3,6,13,4,5,10]
    temp_truecharacter[13] =  [1,15,2,3,6,11,12,13,4,5,9,10]
    temp_truecharacter[14] =  [1,1,1,2,3,6,11,12,13,4,5,9,10]
    temp_truecharacter[15] =  [1,1,15,2,3,6,11,12,13,4,5,5,9,10]
    temp_truecharacter[16] =  [1,1,1,1,2,3,6,11,12,13,4,5,5,9,10]
    temp_truecharacter[17] =  [1,1,1,1,2,3,6,11,12,13,4,5,5,9,10,14]

    def __init__(self,game):
        WerewolfRule.__init__(self, game)
        #print "ExpansionRule"    
    def initGame(self):
        print "init Expansion Rule"
        WerewolfRule.initGame(self)
    def nexeTurn_2day(self):
        print "2일째로 고고!"

        #일반 로그를 쓰지 않은 사람을 체크한다.
        self.game.entry.checkNoCommentPlayer()

        #희생양 NPC 습격
        victim =self.game.entry.getVictim()
        victim.toDeathByWerewolf()

        #돌연사 시킴 
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("돌연 ")       
        
        #코맨츠 초기화
        self.game.entry.initComment()

        #3. 게임 정보 업데이트
        self.game.setGameState("state","게임중")
        self.game.setGameState("day",self.game.day+1)

    def nextTurn_Xday(self):
        print "다음 날로 고고!"                
        #일반 로그를 쓰지 않은 사람을 체크한다.
        self.game.entry.checkNoCommentPlayer()
        
        #투표 -살아 있는 참가자가 투표를 했는지 체크, 않했다면 랜덤 투표
        victim = self.decideByMajority()
        if victim:
		if victim.truecharacter == Truecharacter.DIABLO:
			if victim.awaken():
				self.game.setGameState("win","3")
			        if(self.game.termOfDay == 60):
			                self.game.setGameState("state",GAME_STATE.TESTOVER)
				else:
			                self.game.setGameState("state",GAME_STATE.GAMEOVER)
				
				self.game.setGameState("day",self.game.day+1)
				return
		victim.toDeath("심판") 
        
        #돌연사 시킴 
        noMannerPlayers = self.game.entry.getNoMannerPlayers()
        for noMannerPlayer in noMannerPlayers:
            noMannerPlayer.toDeath("돌연 ")       
        
        #코맨츠 초기화
        self.game.entry.initComment()
        
        #습격!
        assaultVictim = self.decideByWerewolf()
        if assaultVictim:
            #print "assaultVictim",assaultVictim
            self.assaultByWerewolf(assaultVictim,victim)
            
        #종료 조건 확인
        #사람!
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        #for human in humanRace :
        #    print human
        
        #습격자!
        werewolfRace = self.game.entry.getEntryByRace(Race.WEREWOLF)
        #for werewolf in werewolfRace :
        #    print werewolf
        
        if((len(humanRace) <= len(werewolfRace)) or (len(humanRace) == 0)):
            print "인랑 승리"
            self.game.setGameState("win","1")
            if(self.game.termOfDay == 60):
                self.game.setGameState("state",GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state",GAME_STATE.GAMEOVER)
            
        elif(len(werewolfRace) == 0):
            print "인간 승리"
            self.game.setGameState("win","0")
            if(self.game.termOfDay == 60):
                self.game.setGameState("state",GAME_STATE.TESTOVER)
            else:
                self.game.setGameState("state",GAME_STATE.GAMEOVER)
        else:
            print "계속 진행"
            #self.game.setGameState("state","게임중")
        
        self.game.setGameState("day",self.game.day+1)

    def assaultByWerewolf(self,assaultVictim,victim):
        self.game.entry.recordAssaultResult(assaultVictim)
            
        guard={}
        hunterPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.BODYGUARD)[0]    

        if(hunterPlayer.alive == "생존"):
            #print "hunterPlayer",hunterPlayer        
            guard = hunterPlayer.guard()
            if guard is not None:
                guard = self.game.entry.getCharacter(guard['purpose'])
                #print "guard", guard
                
        
        if assaultVictim.id == victim.id:
            #print "습격 실패: (고습실)"
            pass
        elif guard and assaultVictim.id == guard.id:
            #print "습격 실패: " 
	        pass
        else:
            #print "습격 성공", assaultVictim
            assaultVictim.toDeath("습격")       

        #print "guard: ",guard
        #print "assaultVictim: ", assaultVictim
        #print "victim: ", victim     

    def decideByWerewolf(self):
        cursor = self.game.db.cursor
        
        #print "습격!!"
        #습격의 희생양들...
        humanRace = self.game.entry.getEntryByRace(Race.HUMAN)
        #print alivePlayers

        #습격자!
        werewolfPlayers = self.game.entry.getPlayersByTruecharacter(Truecharacter.WEREWOLF,"('생존')")
        readerwerewolfPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.READERWEREWOLF)
        lonelywerewolfPlayer = self.game.entry.getPlayersByTruecharacter(Truecharacter.LONELYWEREWOLF)
        #print werewolfPlayers 
	
	if(readerwerewolfPlayer):
		readerwerewolfPlayer = readerwerewolfPlayer[0]

	if(lonelywerewolfPlayer):
		lonelywerewolfPlayer = lonelywerewolfPlayer[0]
        
        #살아 있는 인랑이 있을 때만 습격을 진행한다.
        if(len(werewolfPlayers)==0 and (not readerwerewolfPlayer or readerwerewolfPlayer.alive=="사망") and (not lonelywerewolfPlayer or lonelywerewolfPlayer.alive =="사망")): 
            return        

        #인랑들이 습격을 결정했는지 확인한다.
	if(len(werewolfPlayers)>0):
	        for werewolfPlayer in werewolfPlayers:
		    #습격을 안했다면! 렌덤 습격 시작     
	            if werewolfPlayer.hasAssault() is False:
		        werewolfPlayer.assaultRandom(humanRace )

        #인랑들이 습격을 결정했는지 확인한다.
	if(readerwerewolfPlayer and readerwerewolfPlayer.alive=="생존"):
	    #습격을 안했다면! 렌덤 습격 시작     
            if readerwerewolfPlayer.hasAssault() is False:
	        readerwerewolfPlayer.assaultRandom(humanRace )

        #인랑들이 습격을 결정했는지 확인한다.
	if(lonelywerewolfPlayer and lonelywerewolfPlayer.alive=="생존"):
	    #습격을 안했다면! 렌덤 습격 시작     
            if lonelywerewolfPlayer.hasAssault() is False:
	        lonelywerewolfPlayer.assaultRandom(humanRace )

        #인랑들이 가장 좋아하는 사람을 찾는다.
        query = '''select `injured`, count(*)*2 as count from `zetyx_board_werewolf_deathNote` 
        where game = '%s' and day ='%s' 
        group by `injured` 
        order by `count`  DESC '''
        query%=(self.game.game,self.game.day)
        #print query
        
        cursor.execute(query)
        result = cursor.fetchall()
        #print result

	if(lonelywerewolfPlayer and lonelywerewolfPlayer.alive=="생존"):
	        query = '''select `injured`, count(*) as count from `zetyx_board_werewolf_deathnotehalf` 
	        where game = '%s' and day ='%s' 
	        group by `injured` 
	        order by `count`  DESC '''
	        query%=(self.game.game,self.game.day)
	        #print query
        
		cursor.execute(query)
	        result2 = cursor.fetchall()
	        #print result2

		if(len(result)>0):
			result2 = result2[0]
			resultList =[]
			for temp in result :
				if(temp['injured'] == result2['injured']):
					temp['count']+=1
				resultList.append(temp)

			result = resultList
		else:
			result = result2
        
        count = 0
        injured_list=[]

	#print result
	
        for temp in result :
            if count < temp['count']: 
	        injured_list=[]
                count =temp['count']
		injured_list.append(temp['injured'])
	    elif count == temp['count']: 
                #print "count", count
		injured_list.append(temp['injured'])
            else:
                break
        #print injured_list[random.randrange(0,len(injured_list))],injured_list
        return self.game.entry.getCharacter(injured_list[random.randrange(0,len(injured_list))])
    

class ConfidenceRule(BasicRule):
    min_players = 11
    max_players  = 16
    
    # 기본 세팅
    temp_truecharacter ={}
    temp_truecharacter[11] =  [1,1,1,1,16,3,4,17,17,6]
    temp_truecharacter[12] =  [1,1,1,1,1,16,3,4,17,17,6]
    temp_truecharacter[13] =  [1,1,1,1,1,1,16,3,4,17,17,6]
    temp_truecharacter[14] =  [1,1,1,1,1,16,3,4,17,17,17,6,12]
    temp_truecharacter[15] =  [1,1,1,1,1,1,16,3,4,17,17,17,6,12]
    temp_truecharacter[16] =  [1,1,1,1,1,1,1,16,3,4,17,17,17,6,12]    
    
    