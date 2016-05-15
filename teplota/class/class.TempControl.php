<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TempControl
 *
 * @author uidv7359
 */
class TempControl {
	private $tempArray;
	public $sensorsArray;
	private $oMySQL;
	private $Mail;
	private $avgAKU;
	//put your code here
	function __construct($tempJson,$oMySQL,$Mail){
		$tempObj = json_decode($tempJson);
		$this->tempArray = get_object_vars($tempObj);
		$this->oMySQL = $oMySQL;
		$this->Mail = $Mail;
		$this->GetAllSensorsFomDb();

	}
	function InsertIntoTeplota(){
		foreach ($this->tempArray as $key => $temp) {
			$temp=round($temp,1);
			$res = $this->oMySQL->ExecuteSQL("CALL insert_value('','$key',$temp)");
			if(!$res){
				return $this->oMySQL->lastError;
			}
		}
	}
	function InsertIntoTemp(){
		$columns = "";
		$values = "";
		$first = true;
		foreach ($this->tempArray as $key => $temp) {
			$name=$this->GetRowByValue('sensorID', $key, 'name');//zjisteni nazvu sloupce podle sensoru
			$temp=round($temp,1);
			$this->ControlTempLimits($key, $temp);//kontrola na prekroceni limitu a odeslani emailu
			if($first){
				$columns.=$name;
				$values.=$temp;
			}else{
				$columns .= ",".$name;
				$values.= ",".$temp;
			}
			$first = false;
		}
		$this->avgAKU = round(($this->tempArray['2E40B4010000']+$this->tempArray['D94FB4010000']+$this->tempArray['FF6AB4010000'])/3,1);//average of aku
		$sql="INSERT INTO temp ($columns,temp11,timestamp,day) VALUES ($values,$this->avgAKU,NOW(),DATE_FORMAT(NOW(), '%Y-%m-%d'));";
		$this->oMySQL->ExecuteSQL($sql);
	}
	/*Function to get all sensors ids*/
	function GetAllSensorsFomDb(){
		//first get sensor info from db
		$sql="SELECT sensorID,name,description,limits_pos,limits_neg FROM sensors WHERE active = 1";
		$this->sensorsArray = $this->oMySQL->ExecuteSQL($sql,false);
		if(!$this->sensorsArray){
				return $this->oMySQL->lastError;
		}
		foreach ($this->sensorsArray as $row) {
			$dbSensorsKeys[]=$row['sensorID'];
		}
		//$dbSensorsKeys = array_column($this->sensorsArray, 'sensorID');
		$newSensorArray = array_diff(array_keys($this->tempArray),$dbSensorsKeys);
		if(sizeof($newSensorArray)>0){
			$sql="INSERT IGNORE INTO sensors (sensorID) VALUES ('".implode("'),('", $newSensorArray)."');";
			$this->oMySQL->ExecuteSQL($sql);
		}
	}
	private function GetRowByValue($SearcheKey,$SearcheValue,$GetValueKey){
		foreach ($this->sensorsArray as $row) {
			if($row[$SearcheKey] === $SearcheValue)
				return $row[$GetValueKey];
		}
	}
	private function ControlTempLimits($sensorID,$temp){
		foreach ($this->sensorsArray as $row) {
			if($row['sensorID']==$sensorID){
				if($temp > $row['limits_pos'] || $temp < $row['limits_neg']){
					return $this->sendMail($temp,$row['description']);
				}
			}
		}
	}
	private function smtpmailer($to, $from, $from_name, $subject, $body) {
		$this->Mail = new PHPMailer();
		$this->Mail->IsSMTP(true);
		$this->Mail->SMTPAuth = true;
		$this->Mail->SMTPSecure = "tls";
		$this->Mail->Host = "smtp.gmail.com";
		$this->Mail->Port = 587;
		$this->Mail->Username = $GLOBALS["email"];
		$this->Mail->Password = $GLOBALS["gpswd"];
		$this->Mail->From = $from;
		$this->Mail->FromName = $from_name;
		// Chceme email ve formátu HTML
		$this->Mail->IsHTML(true);
		$this->Mail->CharSet = "utf-8";

		// Přidání adresy zákazníka
		$this->Mail->AddAddress($to);
		$this->Mail->Subject = $subject;
		$this->Mail->Body = $body;
		if(!$this->Mail->Send()) {
			echo "Chybová hláška: " . $this->Mail->ErrorInfo;
		}
	}
	private function sendMail($temp,$SensorDesc){
		$subject="Dosažení teploty : $temp °C na zařízení $SensorDesc";
		$body="Dosažení teploty : $temp °C na zařízení $SensorDesc čas:".date('d.m.Y H:i');
		return $this->smtpmailer($GLOBALS["email"], $GLOBALS["from"], $GLOBALS["from_name"], $subject,$body);
	}
	public function Thermostat(){
		$sql="
			SELECT S.name,TT.Temp,S.sensorID FROM sensors S
			JOIN time_temp TT ON S.sensorID = TT.sensorID
			WHERE WEEKDAY(NOW())+1 = TT.Day
			AND TIME(NOW()) BETWEEN TT.TimeFrom AND TT.TimeTo;
		";
		$resultArray = $this->oMySQL->ExecuteSQL($sql);
      
		foreach ($resultArray as $result) {
			$temp = $result['Temp'];
			$sensorID = $result['sensorID'];
			//turn on gpio if temperature is lower then required temp
			if($this->tempArray[$sensorID] < $temp && $this->avgAKU > ($temp+3)){
				return 1;
			}
			//turn off gpio if temperature is higher then required temp
			//if($this->tempArray[$sensorID] >= $temp || $this->avgAKU < ($temp+3)){
			//	$res=0;
			//}
		}
		return 0;
	}
  public function Solar(){
	//turn on gpio if temperature is lower then required temp
	//echo $tempArr[$row["name"]];
	//echo "<br>";
	//echo $row["Temp"];
	$time=date('H:i');
    	$on_from='05:00';
    	$on_to='19:00';
    	if($time>=$on_from && $time<=$on_to)
    	{
      		return 1;
    	}
    	else return 0;
  }
}
