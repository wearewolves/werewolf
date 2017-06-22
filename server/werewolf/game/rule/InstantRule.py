#-*- coding:cp949 -*-
import random
import copy
import logging
from werewolf.game.GAME_STATE import GAME_STATE
from werewolf.game.entry.Role import Truecharacter
from werewolf.game.entry.Role import Race
from werewolf.game.rule.Rule import WerewolfRule

class InstantRule(WerewolfRule):
    def __init__(self, game):
        super(InstantRule, self).__init__(game)
        self.min_players = 8
        self.max_players = 9
        logging.debug("instant rule")