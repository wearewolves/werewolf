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
    def start(self):
        stdout = sys.stdout
        stderr = sys.stderr
        logfile = open("logfile_cout.txt", "w")
        sys.stdout = logfile
        sys.stderr = logfile
        loggerLevel = logging.DEBUG
        loggingFormat = "%(asctime)s [%(filename)-12s:%(lineno)-3s] %(levelname)-8s %(message)s"
        try:
            logging.basicConfig(filename="./logfile.txt", filemode='w', level=loggerLevel,
                                format=loggingFormat)
        except TypeError: # python 2.3
            logger = logging.getLogger()
            logger.setLevel(loggerLevel)
            logger.removeHandler(logger.handlers[0])
            formatter = logging.Formatter(loggingFormat)
            ch = logging.FileHandler(filename="./logfile.txt", mode='w')
            ch.setFormatter(formatter)
            logger.addHandler(ch)
        logging.info('PID: %d', os.getpid())
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
        logging.shutdown()
        logfile.close()
        sys.stdout = stdout
        sys.stderr = stderr
