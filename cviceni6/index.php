
 <h2>Informace</h2>

    
    
    <?php
  require "funkceZobraz.php";
  require "funkceDB.php";
    hlavicka();
    menu();
  $con = pripoj();
  ?>
  
  <p>   PHP je programovací jazyk, který pracuje na straně serveru. S PHP můžete ukládat a měnit data
     webových stránek. Původní význam zkratky PHP byl Personal Home Page. Vzniklo v roce 1996, od té 
     doby prošlo velkými změnami a nyní tato zkratka znamená PHP: Hypertext Preprocessor.</p>
             
<h3>Možnosti PHP</h3>

<p>
PHP není nijak těžké pochopit a už se základy si lze vystačit. Umí ukládat, měnit a mazat data. 
Vše se odehrává na webovém serveru (kde jsou uloženy zdrojové kódy webových stránek). 
PHP skript se nejprve provede na serveru a potom odešle prohlížeči pouze výsledek 
(znamená to, že nejprve spočítá kolik je 300/30 a pak prohlížeči odešle jen číslo 10). 
Proto ve zdrojovém kódu najdete jen "10" (to je rozdíl oproti JavaScriptu, který počítá přímo v prohlížeči). 
Zdrojový kód PHP narozdíl od JavaScriptu a HTML v prohlížeči nezobrazíte.
Pomocí PHP je možné vytvořit diskuzní fórum, knihu návštěv, počítadlo, anketu, graf a 
dokonce si pomocí jednoduchého kódu můžete zlikvidovat celý obsah webu. 
Navíc máte možnost propojit vaše stránky s databázemi, např. MySQL. </p>

<h3>Předmět FPTWS</h3>

<p>
Tyto webové stránky slouží k zobrazení a editaci databáze, vytvořené pro korespondenční úkol
do předmětu Tworba webových stránek. Je zde možno zobrazit tři webové stránky, Klient, Vozidlo, Zapůjčení.
Každá stránka umožňuje zobrazení a editaci v jedné datové tabulce databáze. Mezi jednotlivými 
tabulkami jsou vazby.</p>
    <?php
     paticka();
    ?>
   
