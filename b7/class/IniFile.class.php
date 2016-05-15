<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class IniFile{
	private $IniFilePath;
	public $iniFileArray = array();
	function __construct($IniFilePath){
		$this->IniFilePath = $IniFilePath;
		if(file_exists($IniFilePath)){
			$this->iniFileArray = parse_ini_file($IniFilePath);
		}else{
			echo "Soubor <b>$IniFilePath</b> nebyl nalezen <br> při uložení formuláře bude vytvořen.";
		}
	}
	
	public function write_php_ini($ini_array){
		$res = array();
		foreach($ini_array as $key => $val){
			if($key == 'b7' || $key == 'ftp' || $key == 'general' || $key == 'db' || $key == 'time'){
				$res[] = $val;
				continue;
			}
			if(is_array($val)){
				$res[] = "[$key]";
					foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
			}else{
				$res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
			}
		}
		return $this->safefilerewrite($this->IniFilePath, implode("\r\n", $res));
	}

	private function safefilerewrite($fileName, $dataToSave){
		if ($fp = fopen($fileName, 'w')){
			$startTime = microtime();
			do{
				$canWrite = flock($fp, LOCK_EX);
				// If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
				if(!$canWrite) usleep(round(rand(0, 100)*1000));
			}while((!$canWrite)and((microtime()-$startTime) < 1000));
	
			//file was locked so now we can store information
			if ($canWrite){
				fwrite($fp, $dataToSave);
				flock($fp, LOCK_UN);
			}
			fclose($fp);
			return true;
		}else{
			echo "Nelze zapsat do souboru <b>$fileName</b>";
			return false;
		}
			
	}
}

