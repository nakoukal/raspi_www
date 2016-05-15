<?php
function hlavicka()
   { // BEGIN function 
   	 echo 
        '<!DOCTYPE html>
      <html>
      <head>
      
        <link rel="stylesheet" href="L14012_style.css">
        <script type="text/javascript" src="validation.js" ></script>
      </head>
      <body>
        
        <div id="footer">
<h1>PHP: práce s databází</h1>
<img src="php.png";">
</div>';
   } // END function   
   
   
   
   function menu()
   { // BEGIN function menu
     
     if (empty($_REQUEST["menu"])) {
       $menu = "index";
     }
     else {
       $menu = $_REQUEST["menu"];
     }
     $klient = "";
     $vozidlo = "";
     $zapujceni = "";
     if ($menu == "klient") {
        $klient = 'class="aktivni"';
     }
     if ($menu == "Vozidlo") {
        $Vozidlo = 'class="aktivni"';
     }
     if ($menu == "Zapujceni") {
        $Zapujceni = 'class="aktivni"';
     } 
     echo
     '<div id="nav">


      <a '.$klient.'  href="klient.php?menu=klient"><img src="2_1.gif" style="width:150px;height:40px;"> </a>  
          <br>
      <a '.$vozidlo.'  href="vozidlo.php?menu=vozidlo"><img src="1_1.gif" style="width:150px;height:40px;"> </a>
          <br>
      <a '.$zapujceni.'   href="zapujceni.php?menu=zapujceni"><img src="3_1.gif" style="width:150px;height:40px;"> </a>
     </div>';
  
     } // END function menu
     
    function paticka()
   { // BEGIN function 
   	 echo 
        '        
        <div id="footer">
Jaroslav Ryšica - L14012 - FPTWS
</div>';
   } // END function 
   
     function menu2()
   { // BEGIN function menu
    
     if (empty($_REQUEST["menu"])) {
       $menu = "index";
     }
     else {
       $menu = $_REQUEST["menu"];
     }
     $index = "";
     if ($menu == "index") {
        $index = 'class="aktivni"';
     }
     echo
     '<div id="nav">
      <a '.$index.'  href="index.php?menu=klient"><img src="4_1.gif" style="width:150px;height:40px;"> </a> 
          </div>';
      
     } // END function menu
     
         