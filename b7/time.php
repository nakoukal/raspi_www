<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
require 'class/nette.min.php';
require 'class/IniFile.class.php';
$IniFile = new IniFile('/home/pi/b7/setting.ini');//cesta a nazev k ini souboru
//$IniFile = new IniFile('setting.ini');//cesta a nazev k ini souboru
$ini_array = $IniFile->iniFileArray;
use Nette\Forms\Form;
?>
<html>
	<head>
		 <title>B7 time setting</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/b7.css">
		<script src="js/jquery.js"></script>
		<script src="js/netteForms.js"></script>
		<script src="js/main.js"></script>
	</head>
	<body>
		<?php
		$form = new Form;	
		$form->addGroup('GENERAL setting');
		$form->addHidden('general','[GENERAL]');
		$form->addText('datetime', 'Datum a čas:')
				->setOption('description', 'Zadejte název souboru')
				->setRequired('Zadejte název souboru');
		
		$form->addSubmit('send', 'Uložit');
		$form->setDefaults($ini_array);
		echo $form; // vykreslí formulář
		
		if ($form->isSuccess()) {
			$values = $form->getValues(true);
			
				echo "	<script>
							alert('Formulář byl uložen');
							window.location.replace('time.php');
						</script>)";
		}
		?>		
	</body>
</html>