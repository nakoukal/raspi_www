<?php
$Type = '';
$GraphLabel = '';
if(isset($_GET["type"]))$Type = filter_var($_GET["type"], FILTER_SANITIZE_STRING);
if(isset($_GET["nadpis"]))$GraphLabel = filter_var($_GET["nadpis"], FILTER_SANITIZE_STRING);
require_once("globals.php");
include("pChart/class/pData.class.php");
include("pChart/class/pDraw.class.php");
include("pChart/class/pImage.class.php");
include_once('class/class.MySQL.php');


$oMySQL = new MySQL('temperature', $GLOBALS["dblogin"], $GLOBALS["dbpwd"], $GLOBALS["dbhost"], 3306);
$LibPath = 'pChart/';
$Graphwidth = 500;
$Graphheigth = 250;

$myData = new pData();
$myData->loadPalette($LibPath."palettes/psma.color", TRUE);

switch($Type){
	case "week":
		$SQLString = "
			SELECT DATE_FORMAT(day,'%w')AS date,
				ROUND(AVG(temp02),1) AS venku,
				ROUND(AVG(temp01),1) AS obyvak,
				ROUND(AVG(temp04),1) AS podlaha,
				ROUND(AVG(temp03),1) AS krb,
				ROUND(AVG(temp08),1) AS tom,
				ROUND(AVG(temp09),1) AS nela,
				ROUND(AVG(temp11),1) AS aku
			FROM temp 
			WHERE day >= now() - INTERVAL 1 WEEK
			AND temp11 IS NOT NULL
			GROUP BY day 
			ORDER BY day;";
		break;
	case "day":
		$SQLString = "
			SELECT DATE_FORMAT(timestamp,'%H:00')AS date,
				ROUND(AVG(temp02),1) AS venku,
				ROUND(AVG(temp01),1) AS obyvak,
				ROUND(AVG(temp04),1) AS podlaha,
				ROUND(AVG(temp03),1) AS krb,
				ROUND(AVG(temp08),1) AS tom,
				ROUND(AVG(temp09),1) AS nela,
				ROUND(AVG(temp11),1) AS aku,
				ROUND(UNIX_TIMESTAMP(timestamp)/3600) AS timekey
			FROM temp 
			WHERE timestamp >= now() - INTERVAL 1 DAY
			AND temp11 IS NOT NULL
			GROUP BY timekey
			ORDER BY timestamp;";
		break;
	case "hour":
		$SQLString = "
			SELECT DATE_FORMAT(timestamp,'%H:%i')AS date,
				ROUND(AVG(temp02),1) AS venku,
				ROUND(AVG(temp01),1) AS obyvak,
				ROUND(AVG(temp04),1) AS podlaha,
				ROUND(AVG(temp03),1) AS krb,
				ROUND(AVG(temp08),1) AS tom,
				ROUND(AVG(temp09),1) AS nela,
				ROUND(AVG(temp11),1) AS aku,
				ROUND(UNIX_TIMESTAMP(timestamp)/150) AS timekey
			FROM temp 
			WHERE timestamp >= now() - INTERVAL 1 HOUR
			AND temp11 IS NOT NULL
			GROUP BY timekey
			ORDER BY timestamp;";
		break;
	case "eighthour":
		$SQLString = "
			SELECT DATE_FORMAT(timestamp,'%H:%i')AS date,
				ROUND(AVG(temp02),1) AS venku,
				ROUND(AVG(temp01),1) AS obyvak,
				ROUND(AVG(temp04),1) AS podlaha,
				ROUND(AVG(temp03),1) AS krb,
				ROUND(AVG(temp08),1) AS tom,
				ROUND(AVG(temp09),1) AS nela,
				ROUND(AVG(temp11),1) AS aku,
				ROUND(UNIX_TIMESTAMP(timestamp)/900) AS timekey
			FROM temp 
			WHERE timestamp >= now() - INTERVAL 8 HOUR
			AND temp11 IS NOT NULL
			GROUP BY timekey
			ORDER BY timestamp;";
		break;
	case "month":
		$SQLString = "
			SELECT DATE_FORMAT(timestamp,'%e.%c')AS date,
				ROUND(AVG(temp02),1) AS venku,
				ROUND(AVG(temp01),1) AS obyvak,
				ROUND(AVG(temp04),1) AS podlaha,
				ROUND(AVG(temp03),1) AS krb,
				ROUND(AVG(temp08),1) AS tom,
				ROUND(AVG(temp09),1) AS nela,
				ROUND(AVG(temp11),1) AS aku,
				ROUND(UNIX_TIMESTAMP(timestamp)/86400) AS timekey
			FROM temp 
			WHERE timestamp >= now() - INTERVAL 1 MONTH
			AND temp11 IS NOT NULL
			GROUP BY timekey
			ORDER BY timestamp;";
		break;
		
}

$oMySQL->ExecuteSQL($SQLString);
foreach ($oMySQL->arrayedResult as $row) {
	$time  = ($Type === "week")?$DayArray[$row["date"]]:$row["date"];
	$myData->addPoints($time,"time");
	
	$venku = $row["venku"];
	$myData->addPoints($venku,"venku");
	
	$obyvak = $row["obyvak"];
	$myData->addPoints($obyvak,"obyvak");
	
	$podlaha = $row["podlaha"];
	$myData->addPoints($podlaha,"podlaha");
	
	$krb = $row["krb"];
	$myData->addPoints($krb,"krb");
	
	$krb = $row["tom"];
	$myData->addPoints($krb,"tom");
	
	$krb = $row["nela"];
	$myData->addPoints($krb,"nela");
	
	$aku = $row["aku"];
	$myData->addPoints($aku,"aku");
    
	
 }

$myData->setSerieWeight("venku",1.5);
$myData->setSerieWeight("obyvak",1);
$myData->setSerieWeight("podlaha",1);
$myData->setSerieWeight("krb",1);
$myData->setSerieWeight("tom",1);
$myData->setSerieWeight("nela",1);
$myData->setSerieWeight("aku",1);
$myData->setAxisName(0,"Teplota");
$myData->setAxisUnit(0,"°C");


/* Create the abscissa serie */
$myData->setAbscissa("time");//set the x line
//$myData->setAbscissaName("Den");
//$myData->setXAxisDisplay(AXIS_FORMAT_DATE);
$myPicture = new pImage($Graphwidth,$Graphheigth,$myData);
 
$Settings = array("R"=>240, "G"=>240, "B"=>240);
$myPicture->setFontProperties(array("FontName"=>$LibPath."fonts/verdana.ttf","FontSize"=>8));
$myPicture->drawFilledRectangle(0,0,$Graphwidth,$Graphheigth,$Settings);
$myPicture->drawRectangle(0,0,$Graphwidth,$Graphheigth,array("R"=>0,"G"=>0,"B"=>0));
$myPicture->drawRectangle(0,0,$Graphwidth-1,$Graphheigth-1,array("R"=>0,"G"=>0,"B"=>0)); 
$myPicture->setShadow(FALSE);
$myPicture->setGraphArea(50,30,$Graphwidth-70,$Graphheigth-40);

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));
$myPicture->setFontProperties(array("FontName"=>$LibPath."fonts/verdana.ttf","FontSize"=>8));

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

$Settings = array("Mode"=>SCALE_MODE_FLOATING
		//,"ManualScale"=>$AxisBoundaries
		,"DrawSubTicks"=>TRUE
		,"DrawArrows"=>TRUE
		,"ArrowSize"=>6
		,"Pos"=>SCALE_POS_LEFTRIGHT
		, "LabelSkip"=>0
		, "SkippedInnerTickWidth"=>10
		, "LabelingMethod"=>LABELING_ALL
		, "GridR"=>0, "GridG"=>0, "GridB"=>0, "GridAlpha"=>20
		, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>20
		, "LabelRotation"=>90, "DrawXLines"=>1, "DrawSubTicks"=>0, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>20, "DrawYLines"=>ALL);


$myPicture->drawScale($Settings);

$Config = array("DisplayValues"=>0, "AroundZero"=>0,"BreakVoid"=>1,"RecordImageMap"=>FALSE);

$GraphType = 'SplineChart';
switch ($GraphType) {
    case 'AreaChart':
	$myPicture->drawAreaChart($Config);
	break;
    case 'FilledSplineChart':
	$myPicture->drawFilledSplineChart($Config);
	break;
    case 'SplineChart':
	$myPicture->drawSplineChart($Config);
	break;
    case 'BarChart':
	$myPicture->drawBarChart($Config,'tetimes');
	break;
    case 'StackedBarChart':
	$myPicture->drawStackedBarChart($Config);
	break;
    case 'PlotChart':
	$myPicture->drawPlotChart($Config);
	break;

    default:
	break;
}
$Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>$LibPath."fonts/verdana.ttf", "FontSize"=>8, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER, "Mode"=>LEGEND_VERTICAL);
$myPicture->drawLegend($Graphwidth-65,40,$Config);

$myPicture->setFontProperties(array("FontName"=>$LibPath."fonts/verdana.ttf","FontSize"=>10));
$TextSettings = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE, "R"=>0, "G"=>0, "B"=>0);
$myPicture->drawText(200,15,$GraphLabel,$TextSettings);

$myPicture->stroke();