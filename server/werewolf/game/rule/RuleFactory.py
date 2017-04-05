#-*- coding:cp949 -*-
from werewolf.game.rule.Rule import *
#from werewolf.database.DATABASE import DATABASE
#import werewolf.game.Game
#from werewolf.game.entry.Entry import Entry

class RULE_NAME:
    BASIC = 1
    HAMSTER = 2
    EXPANSION = 3    
    CONFIDENCE= 4    

class RuleFactory:
        
    #@staticmethod
    def getRule(rule,game):
        #basic rule
        if(rule == RULE_NAME.BASIC):
            return BasicRule(game)
        #hamster rule
        elif(rule == RULE_NAME.HAMSTER):
            return HamsterRule(game)
        elif(rule == RULE_NAME.EXPANSION):
            return ExpansionRule(game)
        elif(rule == RULE_NAME.CONFIDENCE):
            return ConfidenceRule(game)

    getRule = staticmethod(getRule)

                