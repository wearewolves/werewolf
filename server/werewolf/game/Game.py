#-*- coding:cp949 -*-
from werewolf.game.entry.Entry      import Entry
from werewolf.game.rule.RuleFactory import RuleFactory
from werewolf.database.DATABASE     import DATABASE
from werewolf.game.GAME_STATE       import GAME_STATE
import time
import logging
import config

class Game:
    def __init__(self, rec, db):
        self.game = rec['game']
        self.day = rec['day']
        self.deathTime = rec['deathtime']
        self.players = rec['players']
        self.result = rec['result']
        self.state = rec['state']
        self.termOfDay = rec['termOfDay']
        self.characterSet = rec['characterSet']
        self.useTimetable = rec['useTimetable']
        self.win = rec['win']
        self.good = rec['good']
        self.bad = rec['bad']
        self.seal = rec['seal']
        self.seal_yes = rec['seal_yes']
        self.seal_no = rec['seal_no']
        self.delayAfter = rec['delayAfter']
        self.delayBefore = rec['delayBefore']
        self.delayAfterUsed = rec['delayAfterUsed']
        self.delayBeforeUsed = rec['delayBeforeUsed']
        self.rule = RuleFactory.getRule(rec['rule'], self)
        self.entry = Entry(self)
        self.db = db

    def nextTurn(self):
        # 1일 마을에서 시간이 지난 경우. 
        if self.useTimetable == 0 and time.time() >= (self.deathTime + self.termOfDay * self.day):
            if self.seal == "논의":
                logging.debug("%s: 봉인 논의중..", self.game)
                suddenPlayerCount = self.entry.getSuddenPlayerCount()
                self.setGameState("seal", "종료")
                if (self.seal_yes > self.seal_no) and (self.seal_yes >= (self.players - suddenPlayerCount - 1)/2):
                    logging.info("%s: 봉인 조건 충족 - 봉인", self.game)
                    self.setGameState("state", GAME_STATE.SEAL)
                    return
            logging.info("%s: 다음 날로..", self.game)
            self.rule.nextTurn()
        # 30분 마을의 경우
        elif self.useTimetable == 1:
            AllAlivePlayerCounter = self.entry.getAllAlivePlayerCounter()
            AllConfirmCounter = self.entry.getAllConfirmCounter()
            logging.debug("준비 완료: %s within %s", AllConfirmCounter, AllAlivePlayerCounter)
            logging.debug("Type of AllConfirmCounter: %s", type(AllConfirmCounter))

            if self.day == 0:
                deathtime = self.deathTime
            else:
                deathtime = self.getTimetable()['reg_date'] + self.termOfDay
            
            # 디버그 서버일 경우엔 준비완료를 같게 한다
            if config.server == "test group":
                AllConfirmCounter = AllAlivePlayerCounter
                
            # 준비 완료 OR 시간이 지남
            if((self.state == "게임중" and AllAlivePlayerCounter == AllConfirmCounter) or time.time() >= deathtime):
                logging.debug("Alive players: %s", AllAlivePlayerCounter)
                logging.debug("Confirm players: %s", AllConfirmCounter)
                logging.debug("Death time: %s", deathtime)
                if self.seal == "논의":
                    logging.debug("%s: 봉인 논의중..", self.game)
                    suddenPlayerCount = self.entry.getSuddenPlayerCount()
                    self.setGameState("seal", "종료")
                    if (self.seal_yes > self.seal_no) and (self.seal_yes >= (self.players - suddenPlayerCount - 1)/2):
                        logging.info("%s: 봉인 조건 충족 - 봉인", self.game)
                        self.setGameState("state", GAME_STATE.SEAL)
                        return
                logging.info("%s: 다음 날로..", self.getName())
                self.setTimetable()
                self.rule.nextTurn()

    def checkDelay(self):
        # 코멘트 충전
        if self.state == "게임중" and self.delayAfterUsed == 0:
            if self.day == 1 and self.delayAfter > 0:
                if time.time() >= (self.deathTime + self.delayAfter):
                    logging.info("%s: 로그 충전!", self.game)
                    self.rule.checkDelayToAllocComment()
            else:
                logging.info("%s: 로그 충전!", self.game)
                self.rule.checkDelayToAllocComment()
		
        # 코멘트 해제
        if self.state == "게임중" and self.delayBeforeUsed == 0 and self.delayBefore > 0:
            # 1일 서버
            if self.useTimetable == 0 and time.time() >= (self.deathTime + self.termOfDay * self.day - self.delayBefore):
                logging.info("%s: 로그 해제!", self.game)
                self.rule.checkDelayToFreeComment()
            # 30분 서버
            elif self.useTimetable == 1:
                deathtime = self.getTimetable()['reg_date'] + self.termOfDay
                if time.time() >= (deathtime - self.delayBefore):
                    logging.info("%s: 로그 해제!", self.game)
                    self.rule.checkDelayToFreeComment()

    def minus_division(self, division):
        cursor = self.db.cursor
        cursor.execute("update `zetyx_division_werewolf` set num=num-1 where division='"+str(division)+"'")
        #print "division: " ,division

    def getName(self):
        cursor = self.db.cursor

    def deleteGame(self):
        cursor = self.db.cursor
        cursor.execute("select * from `zetyx_board_werewolf` where no='"+str(self.game)+"'")
        boardData = cursor.fetchone()
        logging.info("%s: 게임 삭제", boardData['subject'])

        cursor.execute("delete from `zetyx_board_werewolf` where no='"+str(self.game)+"'")
        self.minus_division(boardData['division'])

        logging.info("depth: %s", boardData['depth'])
        if boardData['depth'] == 0:
            if boardData['prev_no']:
                logging.debug("prev_no: %s", boardData['prev_no'])
                cursor.execute("update `zetyx_board_werewolf` set next_no='"+str(boardData['next_no'])+"' where next_no='"+str(self.game)+"'")
            if boardData['next_no']:
                logging.debug("next_no: %s", boardData['next_no'])
                cursor.execute("update `zetyx_board_werewolf` set prev_no='"+str(boardData['prev_no'])+"' where prev_no='"+str(self.game)+"'")
        else:
            cursor.execute("select count(*) from `zetyx_board_werewolf` where father='"+str(boardData['father'])+"'")
            fatherData = cursor.fetchone()
            logging.info("fatherData: %s", fatherData['count(*)'])
            if fatherData['count(*)'] == 0:
                cursor.execute("update `zetyx_board_werewolf` set child='0' where no='"+boardData['father']+"'")

        cursor.execute("delete from `zetyx_board_comment_werewolf`  where parent='"+str(self.game)+"'")

        cursor.execute("delete from `zetyx_board_werewolf_gameinfo`  where game='"+str(self.game)+"'")
        cursor.execute("delete from `zetyx_board_werewolf_entry` where game='"+str(self.game)+"'")
        cursor.execute("delete from `zetyx_board_werewolf_vote` where game='"+str(self.game)+"'")
        cursor.execute("delete from `zetyx_board_comment_werewolf_commentType` where game='"+str(self.game)+"'")
        cursor.execute("delete from `zetyx_board_werewolf_revelation` where game='"+str(self.game)+"'")
        cursor.execute("delete from `zetyx_board_werewolf_deathNote` where game='"+str(self.game)+"'")
        cursor.execute("delete from `zetyx_board_werewolf_deathNote_result` where game='" + str(self.game) + "'")
        cursor.execute("delete from `zetyx_board_werewolf_guard` where game="+str(self.game)+"")

        cursor.execute("select count(*) from `zetyx_board_werewolf`")
        total = cursor.fetchone()
        logging.info("total: %s", total['count(*)'])

        cursor.execute("update `zetyx_admin_table`  set total_article='"+str(total['count(*)'])+"' where name='werewolf'")
        cursor.execute("update `zetyx_board_category_werewolf` set num=num-1 where no='"+str(boardData['category'])+"'")
        cursor.execute("update `zetyx_member_table`  set point1=point1-1 where no='"+str(boardData['ismember'])+"'")

    def setGameState(self, key, value):
        cursor = self.db.cursor

        #self.deathTime += self.termOfDay

        query = "update `zetyx_board_werewolf_gameinfo` set `%s` = '%s'  where game = '%s'"
        query %= (key, value, self.game)

        cursor.execute(query)

    def setTimetable(self):
        cursor = self.db.cursor
        #self.deathTime += self.termOfDay

        query = """insert into `zetyx_board_werewolf_timetable`
        (`game`,`day`,`reg_date`) 
        values(%s,%s,%s)"""
        query %= (self.game, self.day, time.time())

        cursor.execute(query)

    def getTimetable(self):
        cursor = self.db.cursor
        #self.deathTime += self.termOfDay

        query = """select * from `zetyx_board_werewolf_timetable`
        where `game` =%s and `day`=%s """
        query %= (self.game, self.day-1)

        cursor.execute(query)
        return cursor.fetchone()

    def writeComment(self, userID, userName, userPasswd, comment, ip, commentType, character, time):
        cursor = self.db.cursor

        import re
        #userID = re.escape(userID)
        userName = re.escape(userName)
        comment = re.escape(comment)

        query = """insert into `zetyx_board_comment_werewolf`
        (`parent`,`ismember`,`name`,`password`,`memo`,`reg_date`,`ip`) """
        #query += "values ('"+str(self.game)+"','"+str(userID)+"','"+userName+"','"+userPasswd+"','"+comment+"','"+str(int(time.time()))+"','"+ip+"')"
        query += "values ('"+str(self.game)+"','"+str(userID)+"','"+userName+"','"+userPasswd+"','"+comment+"','"+str(time)+"','"+ip+"')"
        cursor.execute(query)

        query = "insert into `zetyx_board_comment_werewolf_commentType` (game,comment,type,`character`)"
        query += "values ('"+str(self.game)+"',"+str(cursor.lastrowid)+",'"+commentType+"','"+str(character)+"')"
        cursor.execute(query)
