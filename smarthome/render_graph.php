<?php
$Type = '';
$GraphLabel = '';
$SensorID = '';

if($argc>2){
	$Type = $argv[1];
	$SensorID = $argv[2];
}
require_once("globals.php");
include("pChart/class/pData.class.php");
include("pChart/class/pDraw.class.php");
include("pChart/class/pImage.class.php");
include_once('class/class.MySQL.php');


$oMySQL = new MySQL('temperature', $GLOBALS["dblogin"], $GLOBALS["dbpwd"], $GLOBALS["dbhost"], 3306);

$LibPath = __DIR__.'/pChart/';
$Graphwidth = 500;
$Graphheigth = 250;

$myData = new pData();
$myData->loadPalette($LibPath."palettes/psma.color", TRUE);


switch($Type){
	case "week":
		$SQLString = "
			call get_temp(unix_timestamp(NOW() - INTERVAL 1 WEEK),unix_timestamp(NOW()),86400,'$SensorID');
		";
		break;
	case "day":
		$SQLString = "
			call get_temp(unix_timestamp(NOW() - INTERVAL 1 DAY),unix_timestamp(NOW()),3600,'$SensorID');
		";
		break;
	case "hour":
		$SQLString = "
			";
		break;
	case "eighthour":
		$SQLString = "
			";
		break;
	case "month":
		$SQLString = "
			";
		break;
		
}

$oMySQL->ExecuteSQL($SQLString);
foreach ($oMySQL->arrayedResult as $row) {
	
	switch($Type){
	case "day":
		$time = date('h:m',$row["unixtime"]);
		break;
	case "week":
		$time = date('d.m.',$row["unixtime"]);
		break;
	}
	
	$myData->addPoints($time,"time");
	
	$venku = ($row["value"]==NULL)?VOID:$row["value"];
	$myData->addPoints($venku,"value");	
 }

$myData->setSerieWeight("value",1.5);
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

$myPicture->render(__DIR__.'/temp/'.$Type.'_'.$SensorID.'.png');