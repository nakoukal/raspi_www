function startTime() 
{
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1;//January is 0!`
	var yyyy = today.getFullYear().toString().substr(2,2);
	if(dd<10){dd='0'+dd}
	if(mm<10){mm='0'+mm}
	var h = today.getHours();
	var m = today.getMinutes();
	var s = today.getSeconds();
	m = checkTime(m);
	s = checkTime(s);
	document.getElementById('clock').innerHTML =
	dd+"."+mm+"."+yyyy+" " + h + ":" + m + ":" + s;               
}

function checkTime(i) {
	if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
	return i;
}
function timeout_1() {
	new Ajax.Updater('sensory', 'control/act_temp.php', {asynchronous:true,evalScripts:true});
}
function timeout_2() {
	new Ajax.Updater('events', 'control/act_events.php', {asynchronous:true,evalScripts:true});
}

function timeout_init() {
	var in0 = setInterval("startTime()", 500);    	
	var in1 = setInterval("timeout_1()", 60000);    	
	var in2 = setInterval("timeout_2()", 600000);    	                
}


