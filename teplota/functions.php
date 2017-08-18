<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function GetSvatekDnes(){
	$d=getdate();
	$datum=date("d. m. Y");
	$yday=$d["yday"];
	
	if (($yday>58) && ((date("Y")%4)!=0)) 
		$yday++;	
	return $GLOBALS['svatky'][$yday];
}

function GetSvatekZitra(){
	$d=getdate();
	$datum=date("d. m. Y");
	$yday=$d["yday"];
	
	if (($yday>58) && ((date("Y")%4)!=0)) 
		$yday++;

	return $GLOBALS['svatky'][$yday%366+1];
}

function GetItemsFromCalendar(){
	$client = new Google_Client();
	$client->setApplicationName("calevents");
	$client->setDeveloperKey("AIzaSyDDdjzP310jllLPmZJsvnRt6cU4rnMjd6s");
	$service = new Google_Service_Calendar($client);

	$params = array(
	'singleEvents' => true,
	'orderBy' => 'startTime',
	'timeMin' => date(DateTime::ATOM),//ONLY PULL EVENTS STARTING TODAY
	'maxResults' => 4 //ONLY USE THIS IF YOU WANT TO LIMIT THE NUMBER
				  //OF EVENTS DISPLAYED

	);
	$events = $service->events->listEvents($GLOBALS['GoogleCalendarName'],$params);
	foreach ($events->getItems() as $key => $event) {
		$sdate = date('d.M.',strtotime($event->start->date));
		$sdateTime = $event->start->dateTime;
		$edateTime = $event->end->dateTime;

		if($sdateTime == NULL)
		{
			echo "<b class='event'>".date('d.m.',strtotime($sdate))." ".$event->summary."</b>";
			echo "<br><br>";
		}
		else
		{
			echo "<b class='event'>".date('d.m.',strtotime($sdateTime))." ".$event->summary."</b>";
			echo "<br>";
			echo "<i class='time'>".date('H:i',strtotime($sdateTime));
			echo " -> ";
			echo "".date('H:i',strtotime($edateTime));
			echo "    :   ";
			echo $event->location;
			echo "</i><br><br>";
		}
	}
}

function GetSensorsTemp($oMySQL){
	$query="select st.* , (select state_needed from v_rel_remote where sensorID=st.sensorID) image from v_sensors_temp st order by pozice";
		
	return $oMySQL->ExecuteSQL($query);
}

function GetColor($temperature,$limits_pos,$limits_neg){
	$colorArray = $GLOBALS["colorArray"];
	$limits = ($limits_pos - $limits_neg);
	$temp =  ($temperature - $limits_neg);
	$tempProc = 100/$limits*$temp;
	$colorNumber = round(count($colorArray)/100*$tempProc);
	if(sizeof($colorNumber)<=$colorNumber)
		return $colorArray[$colorNumber];
	else
		return $colorArray[0];
}

function GetSun($oMySQL)
{
	$query="select DATE_FORMAT(sunrise,'%H:%i') sunrise,DATE_FORMAT(sunset,'%H:%i') sunset from sun order by sunrise desc limit 1;";
		
	return $oMySQL->ExecuteSQL($query);
}

/*
 * Function to get actual sunset and sunrise by location and convert result to 
 * local time and save to database
 */
function SaveSun($oMySQL){
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'https://api.sunrise-sunset.org/json?lng=18.11188&lat=49.53617',
		CURLOPT_USERAGENT => 'Codular Sample cURL Request'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	$out = json_decode($resp);
	curl_close($curl);

	$mask='Y-m-d H:i';

	$tz_database = new DateTimeZone('GMT');
	$tz_user = new DateTimeZone('Europe/Prague');

	$date =  new DateTime($out->results->sunrise, $tz_database);
	$date->setTimezone($tz_user);
	$sunrise = $date->format($mask); 

	$date =  new DateTime($out->results->sunset, $tz_database);   
	$date->setTimezone($tz_user);
	$sunset = $date->format($mask); 
	
	$query="INSERT IGNORE INTO sun (sunrise,sunset) VALUES ('$sunrise','$sunset')";
		
	return $oMySQL->ExecuteSQL($query);
}
