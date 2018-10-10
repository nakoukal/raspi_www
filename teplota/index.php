<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>	
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="cache-control" content="no-cache">
		<link rel="stylesheet" type="text/css"  href="css/style.css">
        <script src="../scriptaculous/prototype.js" type="text/javascript"></script>
        <script src="../scriptaculous/scriptaculous.js" type="text/javascript"></script>
        <title></title>
		<script type="text/javascript" src="js/js_functions.js"></script>
        </head>
    <body OnLoad="timeout_init()">
		
		<?php                 
		require_once("class/class.MySQL.php");
		require_once("globals.php");
		require_once 'functions.php';		
		$oMySQL = new MySQL($dbname,$dblogin,$dbpwd,$dbhost);
		
		$dnes = date('H:i');
		if($dnes=='00:00' || $dnes == '00:01' || $dnes == '00:02' || $dnes == '00:03')
			SaveSun($oMySQL);
		
		$sun = GetSun($oMySQL);
		?>
		<div class="container">
			<div class="contentA" id="sensory"></div>
            <div class="contentB">
				<h1 id="clock"></h1>
		
        		<h2><?php echo "Dnes má svátek ".GetSvatekDnes().", zítra ".GetSvatekZitra(); ?></h2>
                        <h2><img src="img/Weather-Sunrise-icon.png" width="10" alt=""/>&nbsp;<?php echo $sun['sunrise']; ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <img src="img/Weather-Sunset-icon.png" width="10" alt=""/>&nbsp;<?php echo $sun['sunset']; ?></h2>
		
                        <hr>
                        <h2>UDÁLOSTI</h2>
                        <div id="events"></div>		
                    </div>
                    <div class="contentC"></div>
		</div>
		<br>
                
                
        <script type="text/javascript">
            new Ajax.Updater('sensory', 'control/act_temp.php', {asynchronous:true,evalScripts:true});
            new Ajax.Updater('events', 'control/act_events.php', {asynchronous:true,evalScripts:true});
        </script>
    </body>
</html>
