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
		$by = array('sensorID'=>'100000000001','day'=>1);
		$this->template->hours = $this->TimetempRepository->GetHoursBy($by);
	}
	
	function actionDefault($sensorID,$day)
	{
		$this->by = array('sensorID'=>$sensorID,'day'=>$day);
	}
	
	protected function createComponentSensorsForm()
    {
		$dayOfWeek=array('1'=>'Po','2'=>'Ut');
        $form = new UI\Form;
        $form->addText('sensorID');
        $form->addSelect('day','den',$dayOfWeek);
        $form->addSubmit('send', 'Ulozit');
        $form->onSuccess[] = [$this, 'sensorsFormSucceeded'];
        return $form;
    }
	
	public function SensorsFormSucceeded(UI\Form $form)
    {
		$this->by = $form->getValues();
        $this->flashMessage('Byl jste úspěšně registrován.');
		$this->redirect('Timetemp:hours');
    }
	
	
}
