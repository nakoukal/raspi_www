<?php

namespace App\Presenters;

use Nette;
use Nette\Forms\Container;
use Nextras\Datagrid\Datagrid;
use Nette\Application\UI;
use Nette\Application\UI\Presenter;
use Nette\Utils\Paginator;



class TimetempPresenter extends BasePresenter
{
	private $TimetempRepository;
	private $by;
	
	public function inject(\Temp\TimetempRepository $timetempRepository)						
    {
	    $this->TimetempRepository = $timetempRepository;
    }
	
	public function renderDefault()
	{					
		$this->template->sensors = $this->TimetempRepository->GetSensors();
	}
	
	public function renderHours()
	{
		//$by = array('sensorID'=>'100000000001','day'=>1);				
		$this->template->hours = $this->TimetempRepository->GetHoursBy($this->by);
	}
	
	function actionHours($sensorID,$day)
	{
		$this->by = array('sensorID'=>$sensorID);		
	}
	
	protected function createComponentTempForm()
    {		
        $form = new UI\Form;		
        $form->addCheckboxList('toedit');		
        $form->addHidden('sensorID');
		$form->addSubmit('plus', '+ 0.5')->onClick[] = [$this,'processForm1'];
		$form->addSubmit('minus', '- 0.5')->onClick[] = [$this,'processForm2'];        
        return $form;
    }
	
	public function processForm1(\Nette\Forms\Controls\SubmitButton $button)
	{
		$form = $button->getForm();        
        $values = $form->getHttpData($form::DATA_TEXT, 'sel[]');		
		$this->TimetempRepository->IncreaseTemp($values);
		//$this->flashMessage('Byl jste úspěšně registrován.');
		$this->redirect('Timetemp:hours',$this->by);	
	}
	
		public function processForm2(\Nette\Forms\Controls\SubmitButton $button)
	{				
        $form = $button->getForm();        
        $values = $form->getHttpData($form::DATA_TEXT, 'sel[]');		
		$this->TimetempRepository->DegreaseTemp($values);
        //$this->flashMessage('Byl jste úspěšně registrován.');
		$this->redirect('Timetemp:hours',$this->by);
	}
	
	
}
