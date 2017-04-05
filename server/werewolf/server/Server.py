#-*- coding:cp949 -*- 
from werewolf.database.DATABASE import DATABASE
from werewolf.game.Game import Game

import MySQLdb
import time
import os
import cStringIO
import math
import sys

import config
#from config import  progress,user,passwd,db

class Server:
    def start(self):    
        stdout = sys.stdout
        stderr = sys.stderr
        logfile = open("logfile.txt","w")
        sys.stdout = logfile
        sys.stderr = logfile
        
        print "PID:",os.getpid()

        old_time = 0
        while True :
            reload(config)
            
            if  config.progress == True:
                pass
                #print "true",config
            else:
                print "Server shutdown"
                break            
            
            new_time = math.floor(time.time()/60)
                
            if old_time  != new_time:
                old_time = new_time
                
                try:
                    database = DATABASE(config.user,config.passwd,config.db)
                    cursor = database.cursor
        
                    cursor.execute("select * from `zetyx_board_werewolf_gameinfo` where `state` ='게임중' or `state` ='준비중'  " )
                    #print cursor.rowcount
                    recs = cursor.fetchall()
        
                    for rec in recs:
                        game = Game(rec,database)
                        game.nextTurn()
            
                    cursor.close()
                    database.conn.close()
                    database=None
                except MySQLdb.Error, msg:
                    print "MySql Error %d: %s" % (msg.args[0], msg.args[1])                    
                except Exception,msg:
                    print "Exception!!:",msg

                logfile.flush()
            #time.sleep(30)    
            break


        logfile.close()
        sys.stdout = stdout
        sys.stderr = stderr
