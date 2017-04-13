#-*- coding:cp949 -*- 
from werewolf.database.DATABASE import DATABASE
from werewolf.game.Game import Game

import MySQLdb
import time
import os
import cStringIO
import math
import sys
import logging
import config

class Server:
    def __init__(self):
        loggerLevel = logging.DEBUG
        loggingFormat = "%(asctime)s [%(filename)-20s:%(lineno)-3s]\t%(levelname)-8s\t%(message)s"

        self.stdout = sys.stdout
        self.stderr = sys.stderr
        self.logfile = open("logfile_cout.txt", "w")
        sys.stdout = self.logfile
        sys.stderr = self.logfile
        print 'PID: %d (server.py made)'%(os.getpid())

        logger = logging.getLogger()
        logger.setLevel(loggerLevel)
        for headler in logger.handlers:
            logger.removeHandler(headler)
        formatter = logging.Formatter(loggingFormat)
        ch = logging.FileHandler(filename="logfile.txt", mode='w')
        ch.setFormatter(formatter)
        logger.addHandler(ch)

    def __del__(self):
        logging.info('Free server.py')
        print 'Free server.py'
        sys.stdout = self.stdout
        sys.stderr = self.stderr
        self.logfile.close()
        logging.shutdown()

    def start(self):
        logging.info('PID: %d (server.py called)', os.getpid())
        old_time = 0
        while True:
            reload(config)

            if config.progress:
                #logging.debug('Config progress %s', config)
                pass
            else:
                logging.warn('Server shutdown')
                break

            new_time = math.floor(time.time()/60)

            if old_time != new_time:
                old_time = new_time

                try:
                    database = DATABASE(config.user, config.passwd, config.db)
                    cursor = database.cursor

                    cursor.execute("select * from `zetyx_board_werewolf_gameinfo` where `state` ='게임중' or `state` ='준비중'  ")
                    logging.debug("Cursor's rowcount: %d", cursor.rowcount)
                    recs = cursor.fetchall()

                    for rec in recs:
                        game = Game(rec, database)
                        game.nextTurn()

                    cursor.close()
                    database.conn.close()
                    database = None
                except MySQLdb.Error, msg:
                    logging.error("MySql Error %d: %s", msg.args[0], msg.args[1])
                except Exception, msg:
                    logging.error("Exception: %s", msg)
            break
