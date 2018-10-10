<?php

namespace App\Presenters;

use Nette,Nette\Application\UI;


/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{		
	
	/** @persistent */
	public $backlink = '';	
	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = new UI\Form;
		$form->addText('username','Username : ')
			->setRequired('Zapiste uzivatelske jmeno')->setAttribute('id','username')->setAttribute('class','hasDatepicker');

		$form->addPassword('password','Password : ')
			->setRequired('Vlozte heslo:');

		$form->addCheckbox('remember','Remember password');

		$form->addSubmit('send','Login');

		// call method signInFormSucceeded() on success
		$form->onSuccess[] = [$this,'signInFormSucceeded'];
		return $form;
	}


	public function signInFormSucceeded($form, $values)
	{
		if ($values->remember) {
			$this->getUser()->setExpiration('14 days', FALSE);
		} else {
			$this->getUser()->setExpiration('30 minutes', TRUE);
		}

		try {
			$this->getUser()->login($values->username, $values->password);
			$userInfo = $this->getUser()->getIdentity()->getData();						
			$this->flashMessage('Prihlaseni ok');
		} catch (Nette\Security\AuthenticationException $e) {
			$this->flashMessage($e->getMessage(),'error');
			$form->addError($e->getMessage());
			return;
		}
		$this->restoreRequest($this->backlink);
		$this->redirect('Relremote:');
	}


	public function actionOut()
	{
		$this->getUser()->logout();		
		unset($dpmo_session->UserLevel);
		$this->flashMessage('logout','success');
		$this->redirect('Relremote:');
	}

}
