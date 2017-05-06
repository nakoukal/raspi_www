<?php
namespace App\Presenters;
use NiftyGrid\Grid;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FirmaGrid
 *
 * @author uidv7359
 */
class SensorsGrid extends Grid{
	public $SensorsRepository;
	
	public function __construct($SensorsRepository)
    {
        parent::__construct();
		$this->SensorsRepository = $SensorsRepository;
    }
	
	protected function configure($presenter)
    {
		
        //Vytvoříme si zdroj dat pro Grid
        //Při výběru dat vždy vybereme id
        $source = new \NiftyGrid\DataSource\NDataSource($this->SensorsRepository->findAll());
        //Předáme zdroj
        $this->setDataSource($source);
		$this->setDefaultOrder("pozice ASC");
		$this->gridName = "SENSORS";
		$this->addGlobalButton(Grid::ADD_ROW, 'Přidat nový záznam');
		$this->addColumn('TimetempID', 'TimetempID');
		$this->addColumn('sensorID', 'SID')
				->setTextEditable()
				->setTextFilter()
				->setAutocomplete();
		$this->addColumn('pozice', 'POS')
				->setTextEditable()
				->setNumericFilter();
		$this->addColumn('description', 'DES')
				->setTextEditable()
				->setTextFilter()
				->setAutocomplete();
		$this->addColumn('name', 'NAME')
				->setTextEditable()
				->setTextFilter()
				->setAutocomplete();
		$this->addColumn('limits_pos', 'LIM+')
				->setTextEditable()
				->setNumericFilter();
		$this->addColumn('limits_neg', 'LIM-')
				->setTextEditable()
				->setNumericFilter();
		$this->addColumn('active', 'ACTIVE')
				->setTextEditable()
				->setNumericFilter();
				
		
		$self = $this;
		
		$this->setRowFormCallback(function($values) use($self){
			$data = array(
					"sensorID" => $values["sensorID"],
					"pozice" => $values["pozice"],
					"description" => $values["description"],
					"name" => $values["description"],
					"limits_pos" => $values["limits_pos"],
					"limits_neg" => $values["limits_neg"],
					"active" => $values["active"],
					);
			if(isset($values['TimetempID'])){
				//update
				$by = array('TimetempID'=>$values["TimetempID"]);
				$self->SensorsRepository->updateTable($data,$by);
				$self->flashMessage("Nastaveni bylo zmeneno.", "grid-successful");
			}
			else{
				$self->SensorsRepository->insertData($data);
				$this->flashMessage("Zázna byl uložen","grid-successful");
			}
        }
		);
		$this->addButton(Grid::ROW_FORM, "Rychlá editace")->setClass("fast-edit");
		$this->addButton("delete", "Smazat")
				->setClass("delete")
				->setLink(function($row) use ($self){return $self->link("delete!", $row['TimetempID']);})
				->setConfirmationDialog(function($row){return "Určitě chcete odstranit logy ".$row['TimetempID']."?";});
		
		 }
	
	
	public function handleDelete($id)
    {
		$this->SensorsRepository->findAll()->where('TimetempID',$id)->delete();
		if(count($id) > 1){
			$this->flashMessage("Vybrané logy byly úspěšně smazány.","grid-successful");
		}else{
			$this->flashMessage("Vybraný log byl úspěšně smazán","grid-successful");
		}        
        $this->redirect("this");
    }
		
		
	
}
