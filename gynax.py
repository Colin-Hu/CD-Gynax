import MySQLdb
# Allows for access to database

import string
# Allows for cleaning up of sql output

import urllib2
# Allows for api pulls from eve-central

from xml.dom.minidom import parseString
# DOM allows for easy parsing of xml files

import time

def idsqlpull(typeName):
    #Pulls the id numbers from list of names
    namevalue = "' "+(typeName)+"\r'"
    db = MySQLdb.connect(host='localhost',user='read',passwd='',db='test')
    cur = db.cursor()
    cur.execute("SELECT typeID FROM typeid WHERE typeNAME = ' " + typeName  + "\r';")
    output =  string.translate(str(cur.fetchone()),None,string.punctuation+string.ascii_letters)
    db.close
    return output

def sqlpush(info):
    db = MySQLdb.connect(host='localhost',user='gynax',passwd='',db='gynax')
    cur = db.cursor()
    cur.execute("DELETE FROM piprices WHERE typeID="+info[1]+";")
    db.commit()
    cur.execute("INSERT INTO piprices VALUES ("+info[1]+ ",'"+info[0]+"',"+info[2][0]+","+info[2][1]+",NOW());")
    db.commit()
    db.close
    return

def evecentralpull(typeID):
    urlstart = "http://api.eve-central.com/api/marketstat?typeid="
    urlend = "&usesystem=30002187"
    url=urlstart+str(typeID)+urlend
    s = urllib2.urlopen(url)
    contents = s.read()
    file = open("item.xml", 'w')
    file.write(contents)
    file.close()

def xmlparse(filename):
    file = open(filename, 'r')
    contents = file.read()
    file.close()
    dom = parseString(contents)
    xmlTag = dom.getElementsByTagName('min')
    output = []
    output.append((xmlTag[1].childNodes[0].nodeValue))
    xmlTag = dom.getElementsByTagName('max')
    output.append((xmlTag[0].childNodes[0].nodeValue))
    return output

def fullinfo(itemname):
    info = []
    info.append(itemname)
    info.append(idsqlpull(info[0]))
    if info[1] == '':
        print "Invalid item around " + itemname
        return
    evecentralpull(info[1])
    info.append(xmlparse('item.xml'))
    sqlpush(info)
    print itemname + " Updated"

def fullupdate():
    items = []
    db = MySQLdb.connect(host='localhost',user='gynax',passwd='',db='gynax')
    cur = db.cursor()
    cur.execute("SELECT name FROM items where doupdate=1;")
    selection = cur.fetchone()
    while selection != None:
        items.append(selection[0])
        selection = cur.fetchone()
    for i in items:
        fullinfo(i)
    cur.execute("UPDATE items SET doupdate=0;")
    db.commit()
    print "Update complete"

#fullinfo("Enriche Uranium")
while 1==1:
    fullupdate()
    time.sleep(60)
