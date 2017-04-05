#!/usr/bin/python
##-*- coding:cp949 -*-     
import time

print "Content-type:text/html\n"
from werewolf.server.Server import Server

server = Server()
server.start()


werewolfLatestWorking = open("werewolfLatestWorking.txt","w")
werewolfLatestWorking.seek(0)
werewolfLatestWorking.write(str(time.time()) + "\n")
werewolfLatestWorking.close()

