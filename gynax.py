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
    db = MySQLdb.connect(host='localhost',user='webbot',passwd='',db='planetary_interaction')
    cur = db.cursor()
    cur.execute("SELECT typeID FROM typeid WHERE typeNAME = ' " + typeName  + "\r';")
    output =  string.translate(str(cur.fetchone()),None,string.punctuation+string.ascii_letters)
    db.close
    return output

def sqlpush(info):
    #Pushes data into database while deleting old data
    db = MySQLdb.connect(host='localhost',user='webbot',passwd='',db='planetary_interaction')
    cur = db.cursor()
    cur.execute("DELETE FROM piprices WHERE typeID="+info[1]+";")
    db.commit()
    cur.execute("INSERT INTO piprices VALUES ("+info[1]+ ",'"+info[0]+"',"+info[2][0]+","+info[2][1]+",NOW());")
    db.commit()
    db.close
    return

def evecentralpull(typeID):
    #Parses api for info about a single item
    urlstart = "http://api.eve-central.com/api/marketstat?typeid="
    urlend = "&usesystem=30000142"
    url=urlstart+str(typeID)+urlend
    s = urllib2.urlopen(url)
    contents = s.read()
    file = open("item.xml", 'w')
    file.write(contents)
    file.close()

def xmlparse(filename):
    #Sifts through the downloaded xml file from api to pull out relevant data
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
    #Updates info on a single item by calling previous functions
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
    #Sifts through total item list and updates all
    print "Starting item update"
    items = []
    db = MySQLdb.connect(host='localhost',user='webbot',passwd='',db='planetary_interaction')
    cur = db.cursor()
    cur.execute("SELECT name FROM items;")
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
print "Test Test"
fullupdate()
