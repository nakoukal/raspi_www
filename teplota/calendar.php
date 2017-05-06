<?php
require_once '../vendor/autoload.php';
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

$events = $service->events->listEvents('nakoukal.com_o0q2gns1rmvc385lv5f1ejjr20@group.calendar.google.com',$params);


echo "<pre>";
foreach ($events->getItems() as $key => $event) {
	$sdate = date('d.M.',strtotime($event->start->date));
	$sdateTime = $event->start->dateTime;
	$edateTime = $event->end->dateTime;
	
	if($sdateTime == NULL)
	{
		echo date('d.m.',strtotime($sdate))." ".$event->summary;
		echo "<br>";
	}
	else
	{
		echo $event->summary;
		echo "<br>";
		echo "".date('H:i',strtotime($sdateTime));
		echo " -> ";
		echo "".date('H:i',strtotime($edateTime));
		echo "<br>";
		echo $event->location;
		echo "<br><br>";
	}
}
                
