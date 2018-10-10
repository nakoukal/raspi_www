<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Utils\Paginator;
/**
 * Description of BasePresenter
 *
 * @author uidv7359
 */
abstract class BasePresenter extends Presenter {
	
		
	public function beforeRender()
	{		
		$this->template->globals = $this->presenter->context->parameters;
	}
}
