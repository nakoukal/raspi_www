#!/bin/bash
#
#####################################################
# b7_mysql.sh
#
# 29.8.2014
# Radek Nakoukal
#
# Script to send b7 log data to mysql database
# Script require - 3 parameter 1-rfid 2-time 3-number of control station
# if is less then 3 parameter then do nothing
#####################################################
dbHostName=$(/bin/sed -n 's/.*dbserver *= "*\([^ ]*.*\)"/\1/p' < /home/pi/b7/setting.ini)
dbHostName=${dbHostName:0:-1} #dont cut if is end of file

dbPort=$(/bin/sed -n 's/.*dbport *= *\([^ ]*.*\)/\1/p' < /home/pi/b7/setting.ini)
dbPort=${dbPort:0:-1} #dont cut if is end of file

dbName=$(/bin/sed -n 's/.*dbname *= "*\([^ ]*.*\)"/\1/p' < /home/pi/b7/setting.ini)
dbName=${dbName:0:-1} #dont cut if is end of file

dbUserName=$(/bin/sed -n 's/.*dbuser *= "*\([^ ]*.*\)"/\1/p' < /home/pi/b7/setting.ini)
dbUserName=${dbUserName:0:-1} #dont cut if is end of file

dbPasswd=$(/bin/sed -n 's/.*dbpassword *= "*\([^ ]*.*\)"/\1/p' < /home/pi/b7/setting.ini)
dbPasswd=${dbPasswd:0:-1} #dont cut if is end of file

dbTableName=$(/bin/sed -n 's/.*dbtablename *= "*\([^ ]*.*\)"/\1/p' < /home/pi/b7/setting.ini)
#dbTableName=${dbTableName:0:-1} #dont cut is end of file



if [ "$#" -eq  3 ]; then
	rfid=$1
	cas=$2
	control=$3
	mysql -h$dbHostName -P$dbPort -u$dbUserName -p$dbPasswd << EOF
	use $dbName;
	INSERT IGNORE INTO $dbTableName (RFID,CAS,KONTROLA) VALUES ('$rfid','$cas',$control);
EOF
fi
