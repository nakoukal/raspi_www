#!/bin/bash
#
#  Script name : makebackup.sh                                                             
#                                                                                       
#       Author : Pavel Srubar, SV C BC FST PS PE/TE, tel. 327                           
#                                                                                       
#         Date : 25.10.2005                                                             
#                                                                                       
#      Changes : 08.12.2005, Srubar P.                                                  
#                - added 'e' and 'p' options, see below    
#
#                13.12.2005, Srubar P.
#                - added possibility to use rsync remote server as source
#                  in first 'DirToBackup' parameter 
#                - added 'v' option, see below
#                                                                                       
#                15.03.2006, Srubar P.
#                - added checking of included getarguments file and exit 
#                  this script if file missing. Missing included
#                  getarguments file and continuing running this script 
#                  may cause serious system destroy
#                - error messages redirected to stderr
#
##
## DESCRIPTION :                                                                       
##
##   This script creates incremental backup of DirToBackup to
##   DestinationDir. Created directory structure includes whole path 
##   of backuped directory or defined prefix. It creates directory called 'last'
##   with last backup and, in the same directory as 'last', time 
##   history directory structure containing previous versions of 'last'
##   directory. Use script 'maintainbackup.sh' to maintain and define history depth
##   and call it e.g. in crontab before calling this script.
##
##
## MANDATORY ARGUMENTS:
##
##   DirToBackup        dir to be backuped. Local path or rsync remote server
##                      with exported module can be defined as source of data.
##                      To define rsync server as source, use one of two possible
##                      syntax in this parameter:
##                           rsync://[rsync_username@]hostname/modulename
##                      or
##                           [rsync_username@]hostname::modulename
##                      To use rsync authentication(rsync_username) during backup
##                      in scripts without user intervention, environment variable 
##                      RSYNC_PASSWORD must be set to required password  
##   DestinationDir     dir to store backup in. Only local path can be defined
##                      here
##
##
## OPTIONS:
##
##   -e                 exclude dirs or files which matches this pattern,
##                      to define more patterns, separate them with colon
##   -p                 prefix to create directory path inside DestinationDir
##                      If given, original backuped directory path is replaced 
##                      with this prefix
##   -v                 be verbose, prints out what is being done
##   --help             display this help
##
##
## EXAMPLE:
##
## To make backup of directory /etc to the backup directory /backup/fren006:
##
##    makebackup.sh /etc /backup/fren006
##
## this command will backup whole content of /etc and result in following
## directory structure:
##    /backup/fren006/last/etc/....
##    /backup/fren006/2005/09/12_Monday/11_59/etc/.....
##
##    makebackup.sh -v /etc /backup/fren006
##
## this command will make the same as above and prints out what is being done
##
## To make backup of directory /etc to the backup directory /backup/fren006
## and create different directory path inside destination dir:
##
##    makebackup.sh -p /old/config /etc /backup/fren006
##
## this command will backup whole content of /etc and result in following
## directory structure:
##    /backup/fren006/last/old/config/....
##    /backup/fren006/2005/09/12_Monday/11_59/old/config/.....
##
## To make backup of directory /etc except directories or files
## named 'skel' and 'security' to the backup directory /backup/fren006:
##
##    makebackup.sh -e skel,security /etc /backup/fren005
##
## this command will backup whole content of /etc except directories or files
## named 'skel' and 'security' and result in following
## directory structure:
##    /backup/fren005/last/etc/....
##    /backup/fren005/2005/09/12_Monday/11_59/etc/.....
##
## To make backup of remote fren004 rsync server etc module, using 
## rsync server authentication as 'rsync_user' username
##
##    makebackup.sh rsync://rsync_user@fren004/etc /backup/fren004
##            or
##    makebackup.sh rsync_user@fren004::etc /backup/fren004
##
## this command will backup whole content of etc module and result in
## following directory structure:
##    /backup/fren004/last/etc/....
##    /backup/fren004/2005/09/12_Monday/11_59/etc/.....
##
##
## EXIT STATUS:
##
##    0                 Successful program execution
##
##    1                 too few arguments given, illegal option
##                      or missing option argument
##
##    2                 Getarguments script file not installed. This file
##                      is included to accomplish a check of command line
##                      options and arguments the script is called with
##                      and to initialize variables from them
##
##    3                 second mandatory argument 'DestinationDir' contains 
##                      remote server definition which is not allowed              
##
##    4                 local source directory doesn't exist
##
##     
##
## SEE ALSO:
##    maintainbackup.sh
##
##

# Define and check if all needed arguments are given and for options
arguments="DirToBackup DestinationDir"
#        Name   Option argument
options="e      ExcludeString \
         p      Prefix        \
         v      -             "
GETARGUMENTSFILE=/usr/local/bin/getarguments
# check if all above defined arguments and options are given well
# and initialize variables from them
if [ -e $GETARGUMENTSFILE ]; then
   . $GETARGUMENTSFILE
else
   echo "$(basename $0): can not find included file $GETARGUMENTSFILE !" >&2
   echo "$(basename $0): please install it first to use this script" >&2
   exit 2
fi

# Check if DestinationDir does not containt remote server path
if [ -z "${DestinationDir##*:*}" ]; then
   echo "$(basename $0): second mandatory argument 'DestinationDir' can't contain remote server definition !" >&2
   echo "Try '$(basename $0) --help' for more information." >&2
   exit 3
fi

# Check if option v was given
if [ $v -eq 1 ]; then
	v=v
else
	v=""
fi

Source=$DirToBackup
if [ -z "${DirToBackup##rsync://*/*}" ]; then  # if rsync server used as source
   DirToBackup=/${DirToBackup#rsync://*/}
elif [ -z "${DirToBackup##*::*}" ]; then
   DirToBackup=/${DirToBackup#*::}             # if rsync server used as source
elif [ ! -d $DirToBackup ]; then # if source is local directory and doecn't exist
   echo "$(basename $0): local source directory $DirToBackup doesn't exist !" >&2
   exit 4
fi

#Path to last backup directory
LastBackupPath=${DestinationDir}/last

#if exists last copy it to its time's dir
if [ -d ${LastBackupPath}/${Prefix:-$DirToBackup} ]; then
	[ -n "$v" ] && echo "COPYING 'last' TO IT'S TIME DIR..." 
   YearDir=$(ls -ld --time-style="+%Y" $LastBackupPath |
   awk '{print $6;}')
   MonthDir=$(ls -ld --time-style="+%m" $LastBackupPath |
   awk '{print $6;}')
   DayDir=$(ls -ld --time-style="+%d" $LastBackupPath |
   awk '{print $6;}')
   HourMinuteDir=$(ls -ld --time-style="+%H_%M" $LastBackupPath |
   awk '{print $6;}')
   YearPath=${DestinationDir}/$YearDir
   MonthPath=${YearPath}/$MonthDir
   DayPath=${MonthPath}/$DayDir
   HourMinutePath=${DayPath}/$HourMinuteDir
   mkdir -p$v ${HourMinutePath}/${Prefix:-$DirToBackup}
	if [ -n "$(ls -A ${LastBackupPath}/${Prefix:-$DirToBackup})" ]; then
		cp -al$v ${LastBackupPath}/${Prefix:-$DirToBackup}/* ${HourMinutePath}/${Prefix:-$DirToBackup}
	fi
fi

#Do rsync
if [ -n "$ExcludeString" ]; then # if exclude option given
	Exclude="--delete-excluded --exclude=${ExcludeString//,/ --exclude=}"
fi
mkdir -p$v ${LastBackupPath}/${Prefix:-$DirToBackup}
[ -n "$v" ] && echo && echo "DOING rsync..."
rsync -aH$v --delete $Exclude $Source/  $LastBackupPath/${Prefix:-$DirToBackup}
touch $LastBackupPath

exit 0
