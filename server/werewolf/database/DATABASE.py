import MySQLdb

class DATABASE:
    def __init__(self,user,passwd,db):
        self.conn = MySQLdb.connect(user=user,passwd=passwd,db=db)
        self.cursor = self.conn.cursor(MySQLdb.cursors.DictCursor)
        