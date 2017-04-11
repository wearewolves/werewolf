#-*- coding:cp949 -*-
from werewolf.game.rule.Rule import *
from werewolf.game.rule.BasicRule import *
from werewolf.game.rule.HamsterRule import *
from werewolf.game.rule.ExpensionRule import *
from werewolf.game.rule.ConfidenceRule import *

class RULE_NAME:
    BASIC = 1
    HAMSTER = 2
    EXPANSION = 3
    CONFIDENCE = 4

class RuleFactory:

    #@staticmethod
    def getRule(rule, game):
        if rule == RULE_NAME.BASIC:
            return BasicRule(game)
        elif rule == RULE_NAME.HAMSTER:
            return HamsterRule(game)
        elif rule == RULE_NAME.EXPANSION:
            return ExpansionRule(game)
        elif rule == RULE_NAME.CONFIDENCE:
            return ConfidenceRule(game)

    getRule = staticmethod(getRule)

                