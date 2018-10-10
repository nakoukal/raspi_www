<?php

namespace App\Presenters;

use Nette;
use Nette\Forms\Container;
use Nette\Application\UI;
use Nette\Application\UI\Presenter;
use Nette\Utils\Paginator;



class RelremotePresenter extends BasePresenter
{
	private $relremoteRepository;
	public $stateFormFactory;
	private $by;
	
	 /** @var \CategoryForm @inject */
    public $categoryFormFactory;

    protected function createComponentCategoryForm()
    {
        $form = $this->categoryFormFactory->create();
        $form->onSuccess[] = function (UI\Form $form) {
            $this->redirect('this');
        };

        return $form;
    }
	
	
	public function inject(\Temp\RelremoteRepository $relremoteRepository)						
    {
	    $this->relremoteRepository = $relremoteRepository;
    }
	
	
	public function renderDefault()
	{					
		if(!$this->user->isLoggedIn()){				
			$this->redirect('Sign:in');
		}
		$this->template->sensors = $this->relremoteRepository->GetAllSensors();
	}		
}
