<?php

namespace App\Presenters;

use Nette;
use Nette\Forms\Container;
use Nextras\Datagrid\Datagrid;
use Nette\Application\UI\Presenter;
use Nette\Utils\Paginator;

//SERVER_ADDR=10.214.25.2 php5 /srv/www/vhosts/dpmo/www/index.php variant:pffcron

class SpeedPresenter extends BasePresenter
{
	private $speedRepository;
	
	public function inject(\Temp\SpeedRepository $speedRepository)						
    {
	    $this->speedRepository = $speedRepository;
    }

	public function actionSpeedcron($data) {
		
	}

	public function actionDefault($data){
		
	}
	

	
	
	
	
	
	
	
	
}
