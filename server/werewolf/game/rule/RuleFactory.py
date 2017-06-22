#-*- coding:cp949 -*-
#from werewolf.game.rule.Rule import *
import logging

class RULE_NAME:
    BASIC = 1
    HAMSTER = 2
    EXPENSION = 3
    CONFIDENCE = 4
    INSTANT = 5

class SUBRULE_NAME:
    ASSAULT_ONESELF = 1
    NPC_ALLOCATION = 2 #dummy rule
    TELEPATHY_NONE = 3
    SECRET_VOTE = 4

def getSubrule(rule, game):
    cursor = game.db.cursor
    query = "select subRule from `zetyx_board_werewolf_gameinfo` where game = '%s'"
    query %= game.game
    logging.debug(query)
    cursor.execute(query)
    subrule_dec = cursor.fetchone()['subRule']
    logging.debug('subrule index: %d', subrule_dec)
    return bool(subrule_dec/2**(rule-1)%2)

class RuleFactory:
    #@staticmethod
    def getRule(rule, game):
        if rule == RULE_NAME.BASIC:
            from werewolf.game.rule.BasicRule import BasicRule
            return BasicRule(game)
        elif rule == RULE_NAME.HAMSTER:
            from werewolf.game.rule.HamsterRule import HamsterRule
            return HamsterRule(game)
        elif rule == RULE_NAME.EXPENSION:
            from werewolf.game.rule.ExpensionRule import ExpensionRule
            return ExpensionRule(game)
        elif rule == RULE_NAME.CONFIDENCE:
            from werewolf.game.rule.ConfidenceRule import ConfidenceRule
            return ConfidenceRule(game)
		elif rule == RULE_NAME.INSTANT:
            from werewolf.game.rule.InstantRule import InstantRule
            return InstantRule(game)
    getRule = staticmethod(getRule)
