<?php

/* 
 * Application
 * Copyright (C) 2017 Continental Automotive Systems Czech Republic s.r.o.
 * Radek Nakoukal
 */
use Nette\Application\UI;

class CategoryForm
{
    private $database;

    public function __construct(Nette\Database\Connection $database)
    {
        $this->database = $database;
    }

    public function create()
    {
        $form = new UI\Form;
        // mohu použít $this->database
		$form->addHidden('sensorID');
		$form->addInteger('state')->addRule($form::RANGE, 'Úroveň musí být v rozsahu mezi %d a %d.', [0,1])->setRequired(true);
        $form->addText('date')->setType('datetime-local');
		$form->addSubmit('plus', 'Save')->onClick[] = [$this,'processForm1'];		
        return $form;
    }

    public function processForm1(\Nette\Forms\Controls\SubmitButton $button)
	{		
		$form = $button->getForm();
		$values = $form->getValues();		
		$this->database->query('update rel_remote set state_extra='.$values['state'].' , state_extra_time="'.$values['date'].'" where sensorID="'.$values['sensorID'].'"');				
	}
	
}
