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