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
        if config.server == "test group":
            loggerLevel = logging.DEBUG
        else:
            loggerLevel = logging.INFO
        loggingFormat = "%(asctime)s [%(filename)-25s:%(lineno)-3s]\t%(levelname)-8s\t%(message)s"

        try:
            logging.basicConfig(filename="logfile.txt", filemode='w',
                                format=loggingFormat, level=loggerLevel)
        except TypeError:
            logger = logging.getLogger()
            logger.setLevel(loggerLevel)
            for headler in logger.handlers:
                logger.removeHandler(headler)
            formatter = logging.Formatter(loggingFormat)
            ch = logging.FileHandler(filename="logfile.txt", mode='w')
            ch.setFormatter(formatter)
            logger.addHandler(ch)
        except Exception:
            logging.exception("logging initalize error!!")
        try:
            logging.info('server.py made')
        except Exception:
            pass

    def __del__(self):
        try:
            logging.info('Free server.py')
        except Exception:
            pass
        logging.shutdown()

    def start(self):
        logging.info('PID: %d (server.py start [start])', os.getpid())
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

                    # nextTurn
                    cursor.execute("select * from `zetyx_board_werewolf_gameinfo` where `state` = '게임중' or `state` = '준비중'")
                    logging.debug("Cursor's rowcount: %d", cursor.rowcount)
                    recs = cursor.fetchall()
                    for rec in recs:
                        game = Game(rec, database)
                        game.nextTurn()

                    # checkDelay
                    cursor.execute("select * from `zetyx_board_werewolf_gameinfo` where `state` = '게임중' or `state` = '준비중'")
                    logging.debug("Cursor's rowcount: %d", cursor.rowcount)
                    recs = cursor.fetchall()
                    for rec in recs:
                        game = Game(rec, database)
                        game.checkDelay()

                    cursor.close()
                    database.conn.close()
                    database = None
                except MySQLdb.Error, msg:
                    logging.exception("MySql Error %d: %s", msg.args[0], msg.args[1])
                except Exception, msg:
                    logging.exception("Exception: %s", msg)
            break
        logging.info('PID: %d (server.py start [end])', os.getpid())
