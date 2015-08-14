<?php
/*
 *  Copyright (C) 2014
 *  Radek Nakoukal Class to comunicate with gpio sensors on Raspberry PI
 *   
*/

// MySQL Class v0.8.1
class GPIO {
  /* bit 17 : Garage gate
   * bit 18 : Entry gate
   * bit 21 : Heating termostat
   * bit 22 : free
   * bit 24 : free
   * bit 25 : free            
   */  
  private $allowed_bits;
  private $return_value = array();
  private $last_json_result;
  private $direction;
  public $value;
  
  
  function __construct()
  {
    $this->allowed_bits = array(17, 18, 21, 22, 24, 25);  
  }
  
  private function readAllBits()
  {
    for($x = 0; $x < count($this->allowed_bits); $x++) 
    {
      $bit = $this->allowed_bits[$x];
      $val = trim(shell_exec("cat /sys/class/gpio/gpio".$bit."/value"));
      $dir = trim(shell_exec("cat /sys/class/gpio/gpio".$bit."/direction"));
	
      $val = $val == "1" ? 1 : 0;
      $dir = $dir == "out" ? "o" : "i";
	
      $this->return_value['state'][] = array("bit" => $bit, "val" => $val, "dir" => $dir); 
    }
    return true;
  }
  
  public function getAllBitsOnJson()
  {
    $this->readAllBits();
    $this->encodeToJson();
    echo $this->last_json_result; 
  }
  
  public function readBitBy($bit)
  {
    if(in_array($bit, $this->allowed_bits))
    {
      $this->value = trim(shell_exec("cat /sys/class/gpio/gpio".$bit."/value"));
      $this->direction = trim(shell_exec("cat /sys/class/gpio/gpio".$bit."/direction"));
      $this->return_value['state'][] = array("bit" => $bit, "val" => $this->value, "dir" => $this->direction);
      return true;   
    }
    return false;
  }
  
  public function getBitByOnJson($bit)
  {
    if($this->readBitBy($bit))
    {
      $this->encodeToJson();
      echo $this->last_json_result;
    } 
  }
  
  function writeValueByBit($bit,$val)
  {
    if(in_array($bit, $this->allowed_bits))
    {
      @shell_exec("echo \"$val\" > /sys/class/gpio/gpio$bit/value");
      return true;  
    }
    return false;
  }
  
  function setDirection()
  {
  }
  
  private function encodeToJson()
  {
    $this->last_json_result = json_encode($this->return_value);
  }
  
  
  
  
}