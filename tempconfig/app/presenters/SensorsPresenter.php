<?php

namespace App\Presenters;

use Nette;
use Nette\Forms\Container;
use Nextras\Datagrid\Datagrid;
use Nette\Application\UI\Presenter;
use Nette\Utils\Paginator;


class SensorsPresenter extends BasePresenter
{
	private $SensorsRepository;
	
	public function inject(\Temp\SensorsRepository $sensorsRepository)						
    {
	    $this->SensorsRepository = $sensorsRepository;
    }

	
	public function createComponentDatagrid()
	{
		$grid = new Datagrid;
		$grid->addColumn('sensorID')->enableSort();
		$grid->addColumn('pozice')->enableSort($grid::ORDER_ASC);
		$grid->addColumn('description');
		$grid->addColumn('limits_pos');
		$grid->addColumn('limits_neg');
		$grid->addColumn('active')->enableSort();
		$grid->setRowPrimaryKey('sensorID');
		
		$grid->setDataSourceCallback([$this, 'getDataSource']);
		$grid->setPagination(15, [$this, 'getDataSourceSum']);
		$grid->setFilterFormFactory(function() {
			$form = new Container();
			$form->addText('sensorID');
			$form->addText('description');			
			return $form;
		});
		
		$grid->setEditFormFactory(function($row) {
			$form = new Container();
			$form->addInteger('pozice')->setRequired('Musite vlozit pozici')->addRule(\Nette\Forms\Form::RANGE,'Hodnota musi byt v rozzsahu mezi %d a %d.',[0,100]);
			$form->addText('description')->setRequired('Zapiste popis.');
			$form->addInteger('limits_pos')->setRequired('Musite vlozit pozici')->addRule(\Nette\Forms\Form::RANGE,'Hodnota musi byt v rozzsahu mezi %d a %d.',[-100,100]);
			$form->addInteger('limits_neg')->setRequired('Musite vlozit pozici')->addRule(\Nette\Forms\Form::RANGE,'Hodnota musi byt v rozzsahu mezi %d a %d.',[-100,100]);
			//$form->addDateTimePicker('description');
			$form->addText('active')->setRequired('Zapiste popis.');
			$form->addSubmit('save', 'Save')->getControlPrototype()->class = 'btn btn-primary';
			$form->addSubmit('cancel', 'Cancel')->getControlPrototype()->class = 'btn';
			!$row ?: $form->setDefaults($row);
			return $form;
		});
		
		
		$grid->setEditFormCallback([$this, 'saveData']);
		
		$grid->addCellsTemplate(__DIR__ . '/../../vendor/nextras/datagrid/bootstrap-style/@bootstrap3.datagrid.latte');
		//$grid->addCellsTemplate(__DIR__ . '/templates/Homepage/@cells.latte');
				
	
		return $grid;
	}
	
	public function getDataSource($filter, $order, Paginator $paginator = NULL)
	{
		$selection = $this->prepareDataSource($filter, $order);
		if ($paginator) {
			$selection->limit($paginator->getItemsPerPage(), $paginator->getOffset());
		}
		$selection = iterator_to_array($selection);
		foreach ($selection as $i => $row) {
			//if ($row->id === 15144) $row->update(['id' => 0]);
		}
		return $selection;
	}
	
	public function getDataSourceSum($filter, $order)
	{
		return $this->prepareDataSource($filter, $order)->count('*');
	}
	
	private function prepareDataSource($filter, $order)
	{
		$filters = array();
		foreach ($filter as $k => $v) {
			if ($k === 'gender' || is_array($v))
				$filters[$k] = $v;
			else
				$filters[$k. ' LIKE ?'] = "%$v%";
		}
		$selection = $this->SensorsRepository->findAll()->where($filters);
		if ($order) {
			$selection->order(implode(' ', $order));
		}
		return $selection;
	}
	
	public function saveData(Container $form)
	{
		$values = $form->getValues();		
		$user = $this->SensorsRepository->findAll()->get($values->sensorID);				
		$user->update($values);
	}
	
	
	
	
	
}
