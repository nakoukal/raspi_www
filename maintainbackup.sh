#!/bin/bash
#
#  Script name : maintainbackup.sh
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
#                05.03.2008, Kalina P.
#                - added checking if is not this script still running 
##
## DESCRIPTION :
##
##   This script maintanins depth of backup history
##   created using 'makebackup.sh' script. Call it before
##   the makebackup is called
##
## MANDATORY ARGUMENTS:
##
##   DestinationDir	backup dir to maintain
##   YearsHistory	how many year backups to store
##   MonthsHistory	how many month backups to store
##   DaysHistory	how many day backups to store
##   HoursHistory	how many hour backups to store
## 
##
## OPTIONS:
##
##   -v         prints out messages what is being done
##   --help     display this help
##
##
## EXAMPLE:
##
##   Make maintenance of backup dir /backup/fren006 with last 24 hours history,
##   7 days, 12 months and 3 years backup history depth:
##
##      maintainbackup.sh /backup/fren006 3 12 7 24
##
##
## EXIT STATUS:
##
##    0                 Successful program execution
##
##    1                 Too few arguments given, illegal option
##                      or missing option argument
##
##    2                 Getarguments script file not installed. This file
##                      is included to accomplish a check of command line
##                      options and arguments the script is called with
##                      and to initialize variables from them
##
##    3                 Destination directory doesn't exist
##
##    4                 Couldn't delete directory
##
##
## SEE ALSO:
##    makebackup.sh
##

echo "Start script"

# Define and check if all needed arguments are given and for options
arguments="DestinationDir YearsHistory MonthsHistory DaysHistory HoursHistory"
#        Name   Option argument
options="v      - "
GETARGUMENTSFILE=/usr/local/bin/getarguments

if [ $(ps ax | grep "$0 $1 $2 $3 $4 $5"| grep -v grep | wc -l) -gt 4 ]; then 
#if [ $(ps ax | grep "$0 $1"| grep -v grep | wc -l) -gt 2 ]; then
		echo "Dont start script:" $0 $1",because is still running .........."
	echo "Time:" `date '+%H:%M:%S %y.%m.%d. '`
	echo "$(ps ax | grep "$0 $1"| grep -v grep | wc -l)"
	echo "$(ps ax | grep "$0 $1"| grep -v grep )"	
	echo "0"
	exit 0
fi

# check if all above defined arguments and options are given well
# and initialize variables from them
if [ -e $GETARGUMENTSFILE ]; then
   . $GETARGUMENTSFILE
else
   echo "$(basename $0): can not find included file $GETARGUMENTSFILE !" >&2
   echo "$(basename $0): please install it first to use this script" >&2
	echo "2"
   exit 2
fi
echo "next"
# check prerequisite conditions
if [ ! -d $DestinationDir ];then
  echo "$(basename $0): destination directory $DestinationDir doesn't exist !!!" >&2
  echo "3"	
  exit 3
fi

CurrentYearDirs=0
CurrentMonthDirs=0
CurrentDayDirs=0
CurrentHourDirs=0
for YearDir in $(ls -Ar $DestinationDir); do
  if [ "$YearDir" == "last" ]; then continue; fi
  YearPath=${DestinationDir}/$YearDir
  for MonthDir in $(ls -Ar $YearPath); do
    MonthPath=${YearPath}/$MonthDir
    for DayDir in $(ls -Ar $MonthPath); do
      DayPath=${MonthPath}/$DayDir
      # deletes older subdirectories if count of subdirectories
      # in directory is greater than needed history depth
      while ([ $(ls -A $DayPath |wc -l) -gt $(($HoursHistory-$CurrentHourDirs)) ] && [ $(ls -A $DayPath |wc -l) -gt 1 ]); do
	# deletes first(the oldest) direcory
        Dir=${DayPath}/$(ls -A $DayPath |head -1)
        if [ $v -eq 1 ]; then echo "Deleting directory: $Dir"; fi
	rm -r $Dir || exit 4
      done 
      if [ $(ls -A $DayPath |wc -l) -eq 1 ] && [ $(($HoursHistory-$CurrentHourDirs)) -eq 0  ]; then
        :	
      else
        CurrentHourDirs=$(($CurrentHourDirs + $(ls -A $DayPath |wc -l)))
      fi 
    done
    # deletes older subdirectories if count of subdirectories
    # in directory is greater than needed history depth
    while ([ $(ls -A $MonthPath |wc -l) -gt $(($DaysHistory-$CurrentDayDirs)) ] && [ $(ls -A $MonthPath |wc -l) -gt 1 ]); do
      # deletes first(the oldest) direcory
      Dir=${MonthPath}/$(ls -A $MonthPath |head -1)
      if [ $v -eq 1 ]; then echo "Deleting directory: $Dir"; fi
      rm -r $Dir || exit 4
    done
    if [ $(ls -A $MonthPath |wc -l) -eq 1 ] && [ $(($DaysHistory-$CurrentDayDirs)) -eq 0  ]; then
      :
    else
      CurrentDayDirs=$(($CurrentDayDirs + $(ls -A $MonthPath |wc -l)))
    fi
  done
  # deletes older subdirectories if count of subdirectories
  # in directory is greater than needed history depth
  while ([ $(ls -A $YearPath |wc -l) -gt $(($MonthsHistory-$CurrentMonthDirs)) ] && [ $(ls -A $YearPath |wc -l) -gt 1 ]); do
    # deletes first(the oldest) direcory
    Dir=${YearPath}/$(ls -A $YearPath |head -1)
    if [ $v -eq 1 ]; then echo "Deleting directory: $Dir"; fi
    rm -r $Dir || exit 4
  done
  if [ $(ls -A $YearPath |wc -l) -eq 1 ] && [ $(($MonthsHistory-$CurrentMonthDirs)) -eq 0  ]; then
    :
  else
    CurrentMonthDirs=$(($CurrentMonthDirs + $(ls -A $YearPath |wc -l)))
  fi
done
# deletes older subdirectories if count of subdirectories
# in directory is greater than needed history depth
while ([ $(ls -A $DestinationDir |wc -l) -gt $(($YearsHistory+1)) ]); do
  # deletes first(the oldest) direcory
  Dir=${DestinationDir}/$(ls -A $DestinationDir |head -1)
  if [ $v -eq 1 ]; then echo "Deleting directory: $Dir"; fi
  rm -r $Dir || exit 4
done
echo "0:"
exit 0
