<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
		<META HTTP-EQUIV="refresh" CONTENT="60">
        <meta charset="UTF-8">
        <title></title>
		<style>
			body {
				background-color: black;
				color:white;
			}
			
			table {
				font-family: sans-serif;
				border-collapse: collapse;
				width: 600px;
				margin-bottom: 20px;
				margin-top:  20px;
			}
			
			h1{
				font-weight: bold;
				font-family: sans-serif;
				font-size: 70px;
			}
			
			h2{
				font-weight: bold;
				font-family: sans-serif;
				font-size: 40px;
			}
			
			h3{
				font-weight: bold;
				font-family: sans-serif;
				font-size: 30px;
			}

			table, th, td {
				border: 1px solid black;
				text-align: left;
			}
			
			th, td {
				padding: 3px;
				text-align: center;
				font-weight: bold;
			}
			
			th{				
				color: white;
				font-size: 50px;
			}
			
			
			.akt{
				font-size: 70px;				
				text-align: left;
				width: 150px;
			}
			
			.akt_nadp{
				font-size: 60px;				
				width: 170px;
			}
			.image, img{
				width: 100px;
				height:80px;
				
			}
			
			.max{
				font-size: 30px;			
				background-color: #ff6633;
				color:black;
			}
			
			.min{
				font-size: 30px;		
				background-color: #66ccff;
				color:black;
			}
			
			.container {
				width:1280px;
				background:black;
				overflow:hidden; /* also used as a clearfix */
			}

			.contentA {
				float:left;
				background:black;
				width:600px;
			}
			.contentB {
				float:left;
				background:black;
				width:600px;
			}
			.contentC {
				float:left;
				background:black;
				width:100px;
			}
			.event {
				font-size: 25px;
			}
			
			.time{
				font-size: 20px;
			}
		</style>
		</head>
    <body>
		
		<?php                 
		require_once("class/class.MySQL.php");
		require_once("globals.php");
		require_once 'temp.php';
		require_once 'functions.php';
		require_once '../vendor/autoload.php';
		$oMySQL = new MySQL($dbname,$dblogin,$dbpwd,$dbhost);
		
		$dnes = date('H:i');
		if($dnes=='00:00' || $dnes == '00:01' || $dnes == '00:02' || $dnes == '00:03')
			SaveSun($oMySQL);
		
		$sun = GetSun($oMySQL);
		?>
		<div class="container">
		<div class="contentA">
			
		<?php
		$rows = GetSensorsTemp($oMySQL);
		foreach ($rows as $row) {
			$bgcolor=getColor($row['act_temp'],$row['limits_pos'],$row['limits_neg']);
			echo print_temp($row['description'], $row['act_temp'],$bgcolor,$row['image']);			
		}
		?>
		</div>
		<div class="contentB">
		<h1><?php echo date('d.m.y H:i:s'); ?></h1>
		
		<h2><?php echo "Dnes má svátek ".GetSvatekDnes().", zítra ".GetSvatekZitra(); ?></h2>
		<h2><img src="img/Weather-Sunrise-icon.png" width="10" alt=""/>&nbsp;<?php echo $sun['sunrise']; ?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img src="img/Weather-Sunset-icon.png" width="10" alt=""/>&nbsp;<?php echo $sun['sunset']; ?></h2>
		
		<hr>
		<h2>UDÁLOSTI</h2>
		<?php  GetItemsFromCalendar() ?>
		</div>
			<div class="contentC">
				
			</div>
		</div>
		<br>
    </body>
</html>
