#!/bin/bash
###############################################################################
#10.12.2014 Radek Nakoukal
#Read temperature from sensors with owread and send json request to database
##############################################################################

insert_url="http://192.168.1.97/teplota/process.php"
temp_json_out="temp={"
separator=""
first=0
declare -A temp_arr

#function for switching relay 
#firs parametr is gpio number
#second parameter is value returned from php script
function switch_relay(){
	pinStatus=$(cat "/sys/class/gpio/gpio$1/value")
	if [ "$2" = '1' ] 
	then
                if [ "$pinStatus" = '0' ] 
		then
                        echo $2 > "/sys/class/gpio/gpio$1/value"
                fi
        else
                if [ "$pinStatus" = '1' ] 
		then
                        echo $2 > "/sys/class/gpio/gpio$1/value"
                fi
        fi
}

#create array with temerature
for sensors in $(owdir | grep /28*); do 
	device_id=$(owread "$sensors/id" | sed 's/ *$//')
	temp=$(owread "$sensors/temperature" | sed -e 's/^ *//' -e 's/ *$//')
	temp_arr[$device_id]=$temp

	#/usr/bin/rrdtool update "/home/pi/temp/$device_id.rrd" N:"${temp[@]}"
done

#generate json string and send with request to db
#json structure temp={sensorid:value}
for i in "${!temp_arr[@]}"
do
  	if [ $first -ne 0 ]; then
                separator=","
        fi

	temp_json_out+="$separator\"$i\":${temp_arr[$i]}"
	((first++))
done
temp_json_out+="}"
#echo $temp_json_out
response=$(curl --silent   -XPOST $insert_url --data-urlencode "$temp_json_out")


heating=$(echo $response | /usr/bin/jq '.heating');
solar=$(echo $response | /usr/bin/jq '.solar');

switch_relay 21 $heating;
switch_relay 22 $solar;
