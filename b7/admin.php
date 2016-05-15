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
use Nette\Utils\Html;
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
		
		<div style="width: 900px;">
		<?php
		echo "<h2>".date('d.m.Y H:i:s')."</h2>";
		$form = new Form;	
		$form->addGroup('GENERAL setting');
		$form->addHidden('general','[GENERAL]');
		$form->addText('file', 'soubor:')
				->setOption('description',Html::el('b')->setHtml('/home/pi/b7/vysledky.log Cesta pro umisteni souboru !!'))
				->setRequired('Zadejte název souboru');
		
		$form->addGroup('B7 setting');
		$form->addHidden('b7','[B7]');
		$form->addText('stanoviste', 'Číslo stanoviště:')
			->setOption('description', 'Zadejte číslo stanoviště od 1 do 999')
			->setRequired('Zadejte číslo od 1 do 999')
			->addRule(Form::INTEGER, 'Stanoviště musí být číslo')
			->addRule(Form::RANGE, 'Číslo musí být od 1 do 99', array(1, 999))
			->setType('number');
		
		$form->addTextArea('cards', 'Service cards:')
			->setOption('description', 'Zadejte čísla karet v hexadecimalnim formatu oddělené "|"')
			->setRequired('Zadejte číslo karty ve formátu hexa|hexa')
			->addRule(Form::PATTERN, 'cards neplatný formát hexa|hexa', '^(([a-fA-F0-9]){8})(\|(([a-fA-F0-9]){8}))*$');
				
		$form->addGroup('FTP setting');
		$form->addHidden('ftp','[FTP]');
		$form->addText('ftpserver', 'server:')
				->setOption('description', 'Zadejte adresu ftp serveru')
				->setRequired('Zadejte adresu ftp serveru');
		$form->addText('ftpuser', 'user:')
				->setOption('description', 'Zadejte uživatelské jméno')
				->setRequired('Zadejte uživatelské jméno');
		$form->addText('ftppassword', 'heslo:')
				->setOption('description', 'Zadejte heslo')
				->setRequired('Zadejte heslo');
		$form->addText('ftpdstdir', 'Cílový adresář:')
				->setOption('description', 'Zadejte název cílového adresáře kde bude uložen soubor s logy /log')
				->setRequired('Zadejte název cílového adresáře');
		
		$form->addGroup('MYSQL setting');
		$form->addHidden('db','[DB]');
		$form->addText('script', 'Ceata ke skriptu: ')
			->setOption('description',Html::el('b')->setHtml('/home/pi/b7/b7_mysql.sh Nevyplněno znamená neodesílat data přímo do databáze !!'));
		$form->addText('dbserver', 'server:')
				->setOption('description', 'Zadejte adresu mysql serveru');
		$form->addText('dbport', 'port:')
				->setOption('description', 'Zadejte port mysql serveru obykle 3306')
				->addRule(Form::INTEGER, 'Port musí být číslo')
				->setType('number');
		$form->addText('dbuser', 'user:')
				->setOption('description', 'Zadejte uživatelské jméno');
				
		$form->addText('dbpassword', 'heslo:')
				->setOption('description', 'Zadejte heslo');
				
		$form->addText('dbname', 'Název databáze:')
				->setOption('description', 'Zadejte název databáze');
				
		$form->addText('dbtablename', 'Název tabulky:')
				->setOption('description', 'Zadejte název tabulky');
		
		$form->addSubmit('send', 'Uložit');
		$form->setDefaults($ini_array);
		echo $form; // vykreslí formulář
		
		if ($form->isSuccess()) {
			$values = $form->getValues(true);
			if($IniFile->write_php_ini($values))
				echo "	<script>
							alert('Formulář byl uložen');
							window.location.replace('admin.php');
						</script>)";
		}
		?>
		</div>
	</body>
</html>
