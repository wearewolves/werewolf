#-*- coding:cp949 -*-
from werewolf.database.DATABASE import DATABASE
from werewolf.game.GAME_STATE import GAME_STATE
from werewolf.game.entry.Entry import Truecharacter
from werewolf.game.entry.Entry import Race
from werewolf.game.rule.BasicRule import BasicRule
import random
import copy

class ConfidenceRule(BasicRule):
    min_players = 11
    max_players = 16
    
    # 기본 세팅
    temp_truecharacter = {}
    temp_truecharacter[11] = [1,1,1,1,16,3,4,17,17,6]
    temp_truecharacter[12] = [1,1,1,1,1,16,3,4,17,17,6]
    temp_truecharacter[13] = [1,1,1,1,1,1,16,3,4,17,17,6]
    temp_truecharacter[14] = [1,1,1,1,1,16,3,4,17,17,17,6,12]
    temp_truecharacter[15] = [1,1,1,1,1,1,16,3,4,17,17,17,6,12]
    temp_truecharacter[16] = [1,1,1,1,1,1,1,16,3,4,17,17,17,6,12]