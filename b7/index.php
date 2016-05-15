<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
require 'class/nette.min.php';
require 'class/IniFile.class.php';
//$IniFile = new IniFile('/home/pi/b7/setting.ini');//cesta a nazev k ini souboru
$IniFile = new IniFile('setting.ini');//cesta a nazev k ini souboru
$ini_array = $IniFile->iniFileArray;
use Nette\Forms\Form;
?>
<html>
	<head>
		 <title>B7 setting</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/b7.css">
		<script src="js/jquery.js"></script>
		<script src="js/netteForms.js"></script>
		<script src="js/main.js"></script>
	</head>
	<body>
		
		<div style="width: 800px;">
			
		<?php
		echo "<h2>".date('d.m.Y H:i:s')."</h2>";
		$form = new Form;	
		
		$form->addHidden('general','[GENERAL]');
		$form->addHidden('file');
		
		$form->addGroup('B7 setting');
		$form->addHidden('b7','[B7]');
		$form->addText('stanoviste', 'Číslo stanoviště:')
			->setOption('description', 'Zadejte číslo stanoviště od 1 do 999')
			->setRequired('Zadejte číslo od 1 do 999')
			->addRule(Form::INTEGER, 'Stanoviště musí být číslo')
			->addRule(Form::RANGE, 'Číslo musí být od 1 do 99', array(1, 999))
			->setType('number');

		$form->addHidden('cards');
		
		$form->addHidden('ftp','[FTP]');
		$form->addHidden('server');
		$form->addHidden('user');
		$form->addHidden('password');
		$form->addHidden('dstdir');
    
    $form->addHidden('db','[DB]');
		$form->addHidden('script');
		$form->addHidden('dbserver');
		$form->addHidden('dbport');
		$form->addHidden('dbuser');
    $form->addHidden('dbpassword');
		$form->addHidden('dbname');
		$form->addHidden('dbtablename');

		$form->addSubmit('send', 'Uložit');
		$form->setDefaults($ini_array);
		echo $form; // vykreslí formulář
		
		if ($form->isSuccess()) {
			$values = $form->getValues(true);
			if($IniFile->write_php_ini($values))
				echo "	<script>
							alert('Formulář byl uložen');
							window.location.replace('index.php');
						</script>)";
		}
		?>
		</div>
	</body>
</html>
