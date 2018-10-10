<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SensorIDRepository
 *
 * @author uidv7359
 */

namespace Temp;

class TimetempRepository extends Repository{
	
	
	public function GetSensors()
	{
		return $this->db->table('time_temp')->select('DISTINCT sensorID');
	}
	
	public function GetHoursBy(array $by)
	{
		return $this->db->table('time_temp')->where($by)->select('TimetempID,sensorID,Day,Temp,TimeFrom,TimeTo');
	}
	
	public function GetDaysBy(array $by)
	{
		return $this->db->table('time_temp')->where($by)->select('day');
	}
	
	public function IncreaseTemp(array $values)
	{
		foreach ($values as $value) {			
			$this->db->table('time_temp')->where(array('TimetempID'=>$value))->update(['Temp+=' => 0.5]);
		}		
	}
	
	public function DegreaseTemp(array $values)
	{
		foreach ($values as $value) {			
			$this->db->table('time_temp')->where(array('TimetempID'=>$value))->update(['Temp-=' => 0.5]);
		}		
	}
}
