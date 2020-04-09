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

class RelremoteRepository extends Repository{
	
	public function GetAllSensors()
	{
		return $this->db->table('v_rel_sensors')->select('sensorID,releay_number,state_actual,name,act_temp,temp_needed,state_extra,state_extra_hour,state_extra_time');
	}
	
}
