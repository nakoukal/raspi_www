<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//SELECT CONCAT("INSERT INTO teplota (sensorID,value,timekey) VALUES ('",sensorID,"',",value,",",timekey,");") FROM teplota WHERE timekey > UNIX_TIMESTAMP(NOW() - INTERVAL 60 MINUTE);
/**
 * Description of Repository
 *
 * @author uidv7359
 */
namespace Temp;
use Nette;

abstract class Repository extends Nette\Object {
	
	/** @var Nette\Database\Connection */
    public $context;

    public function __construct(\Nette\Database\Context $db)
    {
        $this->context = $db;
		//dump($this->context);
    }

    /**
     * Vrací objekt reprezentující databázovou tabulku.
     * @return Nette\Database\Table\Selection
     */
    protected function getTable()
    {
        // název tabulky odvodíme z názvu třídy
        preg_match('#(\w+)Repository$#', get_class($this), $m);
        return $this->context->table(lcfirst($m[1]));
    }

    /**
     * Vrací všechny řádky z tabulky.
     * @return Nette\Database\Table\Selection
     */
    public function findAll()
    {
        return $this->getTable();
    }

    /**
     * Vrací řádky podle filtru, např. array('name' => 'John').
     * @return Nette\Database\Table\Selection
     */
    public function findBy(array $by)
    {
        return $this->getTable()->where($by);
    }
    
    public function findState()
    {
	return $this->getTable()->fetchPairs('country_2_code');
    }
    
	/**
	 * Aktualizuje tabbulku podle parametru pole
	 * @param type $data
	 * @param type $by
	 * @return type
	 */
	public function updateTable($data,$by){		
		return $this->getTable()->where($by)->update($data);
	}
  
  public function insertData($data){	
		return $this->getTable()->insert($data);
	}
}
