<?php

namespace App\Presenters;

use Nette;
use Nette\Forms\Container;
use Nextras\Datagrid\Datagrid;
use Nette\Application\UI\Presenter;
use Nette\Utils\Paginator;


class HomepagePresenter extends BasePresenter
{
	private $SensorsRepository;
	
	public function inject(\Temp\SensorsRepository $sensorsRepository)						
    {
	    $this->SensorsRepository = $sensorsRepository;
    }
	
	protected function createComponentSensorsGrid(){
		return new SensorsGrid($this->SensorsRepository);
	}
	
	
	
}
