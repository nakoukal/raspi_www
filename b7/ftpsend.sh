#!/bin/bash
# This UNIX shell script FTPs all the files in the input directory to a remote directory
#

#read settin from ini file
SERV=$(/bin/sed -n 's/.*ftpserver *= "*\([^ ]*.*\)"/\1/p' < /home/pi/b7/setting.ini)
SERV=${SERV:0:-1}
LOGIN=$(/bin/sed -n 's/.*ftpuser *= "*\([^ ]*.*\)"/\1/p' < /home/pi/b7/setting.ini)
LOGIN=${LOGIN:0:-1}
PASSW=$(/bin/sed -n 's/.*ftppassword *= "*\([^ ]*.*\)"/\1/p' < /home/pi/b7/setting.ini)
PASSW=${PASSW:0:-1}
FILE=$(/bin/sed -n 's/.*file *= "*\([^ ]*.*\)"/\1/p' < /home/pi/b7/setting.ini)
FILE=${FILE:0:-1}
STNO=$(/bin/sed -n 's/.*stanoviste *= *\([^ ]*.*\)/\1/p' < /home/pi/b7/setting.ini)
STNO=${STNO:0:-1}
DSTDIR=$(/bin/sed -n 's/.*ftpdstdir *= "*\([^ ]*.*\)"/\1/p' < /home/pi/b7/setting.ini)
DSTDIR=${DSTDIR:0:-1} #dont cut if is end of file
DATUM=$(date +%y%m%d-%H%M%S)

SRCFILE=${FILE##*/} #cut file name from path
SRCDIR=${FILE%/*} #cut path from pathwith filename

FLASHDIR="/media/KINGSTON";
#test if file log exist
if [ ! -f $FILE ]
then
	echo "soubor $FILE neexistuje!" 
	exit 
fi

FILEFTP="$STNO-$DATUM-$SRCFILE"
#echo $SERV
#echo ${#SERV}


#rename and copy file from source log
cp $FILE "$SRCDIR/$FILEFTP"

#connet to ftp
cd $SRCDIR
#try connect and send via ftp
/usr/bin/ftp -inv $SERV >transfer.log 2>&1 <<END_SCRIPT
quote user $LOGIN
quote pass $PASSW
cd $DSTDIR
mput $FILEFTP
bye
END_SCRIPT

if ! grep -i "File received ok" transfer.log >/dev/null 
then 
	echo "$DATUM FTP failed" >> ftp.log
	rm -f "$SRCDIR/$FILEFTP" 
	rm -f "$FLASHDIR/$FILEFTP"
else 
	echo "$DATUM FTP complete" >> ftp.log
	cp $FILE "$FLASHDIR/$FILEFTP"
	rm -f $FILE
fi 
exit 0 
