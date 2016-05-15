<h2>KLIENT</h2>
<?php
  require "funkceZobraz.php";
  require "funkceDB.php";
  
  hlavicka();
  menu2();
  $con = pripoj();
  
// empty vraci ano, pokud je pole request prazdne
  // vykricnik "!" provadi negaci, tj. true prevede na false a false prevede na true
  if (!empty($_REQUEST["Platba"])) {
  
    $Jmeno = $_REQUEST["Jmeno"];
    $Prijmeni = $_REQUEST["Prijmeni"];
    $OP = $_REQUEST["OP"];
    $RP = $_REQUEST["RP"];
    $Platba = $_REQUEST["Platba"];
    $id_ida = $_REQUEST["id_ida"];
   
    $sloupce = "Jmeno, Prijmeni, OP, RP, Platba, id_ida";
    $hodnoty = "'$Jmeno', '$Prijmeni', '$OP', '$RP', '$Platba', '$id_ida'";
    vloz($con, "Klient",$sloupce, $hodnoty);

  } 
  
  // empty vraci ano, pokud je pole request prazdne
  // vykricnik "!" provadi negaci, tj. true prevede na false a false prevede na true
  if (!empty($_REQUEST["id"])) {
    $id = $_REQUEST["id"];
    smaz($con,"Klient","id_idk=$id");
  }
  
  if (!empty($_REQUEST["id_idk"])) {
    $idEdit = $_REQUEST["id_idk"];
    $Jmeno = $_REQUEST["JmenoEdit"];
    $Prijmeni = $_REQUEST["PrijmeniEdit"];
    $OP = $_REQUEST["OPEdit"];
    $RP = $_REQUEST["RPEdit"];
    $Platba = $_REQUEST["PlatbaEdit"];
    $id_ida = $_REQUEST["id_ida"];
    
    $hodnoty = array(
        "Jmeno"=>$Jmeno,
        "Prijmeni"=>$Prijmeni,
        "OP"=>$OP,
        "RP"=>$RP,
        "Platba"=>$Platba,        
    );
    $where = "id_idk = $idEdit";    
    uprav($con, "Klient", $hodnoty, $where);
    
    // vypíše databázovou chybu, pokud k ní došlo
    $chyba = mysqli_error($con);
    if ($chyba) {
      echo "chyba v mysql: $chyba\n<br>";
    }
  }
  
  
  $result = mysqli_query($con, "SELECT * FROM Klient");
  
  /*
  echo "\n\n<table border = 1>";
  while ($radek = mysqli_fetch_assoc($result)) {
     echo "<tr>";
     echo "<td>".$radek["idUcebna"]."</td> <td>".$radek["nazev"]."</td> <td>".$radek["kapacita"]."</td> <td>".$radek["projektor"]."</td>";
     echo "</tr>";
  }
  echo "</table>\n\n";   
  */
?>

<h3> Vyhledávání </h3>
<form action="klient.php" method="post">
  <table>
  <tr> 
    <td>Příjmení klienta: </td><td><input type="text" name="nazevHledej""></td><td><input type="submit" value="Vyhledat"></td>
  </tr>
  </table>
  
</form>

<br>

<?php
$result = array();

  if (empty($_REQUEST["nazevHledej"])) {                 
      $return = dQuery($con,"Klient");
      if($return){
          $result = $return;
      }      
  }
  else {
    $hledej = $_REQUEST["nazevHledej"];  
    $return = dQuery($con,"Klient","Prijmeni LIKE '%$hledej%'");
    if($return){
        $result = $return;
    }
  }
  
  echo "\n\n<table border = 1>";
  echo "<tr><th>ID</th><th>Jméno</th><th>Příjmení</th><th>Číslo OP</th><th>Číslo ŘP</th><th>Platba</th><th>Číslo vozu</th></tr>";
  while ($radek = mysqli_fetch_assoc($result)) {
     echo "<tr>";
       foreach ($radek as $bunka) {
         echo "<td>";
         echo $bunka;
         echo "</td>";
       }
       echo "<td>";
       echo '<a href="klient.php?idEdit='.$radek["id_idk"].'"><img src="edit.png" width="20" onclick="return confirm('."'".'Opravdu chcete editovat tuto položku?'."'".')"></a>';
       echo "</td>";
       echo "<td>";                                                                             
       echo '<a href="klient.php?id='.$radek["id_idk"].'"><img src="smaz.png" width="20" onclick="return confirm('."'".'Opravdu chcete smazat tuto položku?'."'".')"> </a> </td>';
       echo "</td>";
     echo "</tr>";
  }
  echo "</table>\n\n";  



  
?>
<h3> Vložení </h3>
<form action="klient.php" method="post" onsubmit="return kontrola();">
  <table>
  <tr> 
    <td>Jméno: </td><td><input id="Jmeno" type="text" name="Jmeno" value="" onfocus="pozadiWhite(this);"></td>
  </tr>
  <tr> 
    <td>Příjmení: </td><td><input id="Prijmeni" type="text" name="Prijmeni" value="" onfocus="pozadiWhite(this);"></td>
  </tr>
  <tr> 
    <td>Číslo OP: </td><td><input id="OP" type="text" name="OP" value="" onfocus="pozadiWhite(this);"></td>
  </tr>
  <tr> 
    <td>Číslo ŘP: </td><td><input id="RP" type="text" name="RP" value="" onfocus="pozadiWhite(this);"></td>
  </tr>
  <tr> 
    <td>Platba klienta: </td><td><input id="Platba" type="number" name="Platba" value="" onfocus="pozadiWhite(this);"></td>
  </tr>
  <tr> 
    <td>SPZ vozidla: </td>
    <td>
      <select name="id_ida">
        <?php
        $result = array();
        $return = dQuery($con,"Vozidlo");
        if($return){
            $result = $return;
        }                
        while ($radek = mysqli_fetch_assoc($result)) { ?>
        <option value="<?php echo $radek["id_ida"]; ?>"> <?php echo $radek["SPZ"]; ?> </option>
        <?php } ?>
      </select>
    </td>
  </tr>
  </table>
  <input type="submit" value="odeslat">
</form>

<h3> Mazání </h3>
<form action="klient.php" method="post">
  <table>
  <tr> 
    <td>ID klienta: </td><td><input type="text" name="id" value=""></td>
  </tr>
  </table>
  <input type="submit" value="odeslat">
</form>


<?php
  if (!empty($_REQUEST["idEdit"])) {
    $idEdit = $_REQUEST['idEdit'];
    $result = mysqli_query($con, "SELECT * FROM Klient WHERE id_idk = $idEdit");
    $radek = mysqli_fetch_assoc($result);
    
    
    $Jmeno = $radek["Jmeno"];
    $Prijmeni = $radek["Prijmeni"];
    $OP = $radek["OP"];
    $RP = $radek["RP"];
    $Platba = $radek["Platba"];
    $id_ida = $radek["id_ida"];
?>

<h3> Editace </h3>
<form action="klient.php" method="post">
  <table>
  <tr> 
    <td>Jméno: </td><td><input type="text" name="JmenoEdit" value="<?php echo $Jmeno; ?>"></td>
  </tr>
  <tr> 
    <td>Příjmení: </td><td><input type="text" name="PrijmeniEdit" value="<?php echo $Prijmeni; ?>"></td>
  </tr>
  <tr> 
    <td>OP: </td><td><input type="text" name="OPEdit" value="<?php echo $OP; ?>"></td>
  </tr>
  <tr> 
    <td>RP: </td><td><input type="text" name="RPEdit" value="<?php echo $RP; ?>"></td>
  </tr>
  <tr> 
    <td>Platba: </td><td><input type="text" name="PlatbaEdit" value="<?php echo $Platba; ?>"></td>
  </tr>
  <tr> 
    <td>SPZ vozidla: </td>
  <td>
    <?php
      $result = mysqli_query($con, "SELECT * FROM Vozidlo");
      // vypíše databázovou chybu, pokud k ní došlo
      $chyba = mysqli_error($con);
      if ($chyba) {
        echo "chyba v mysql: $chyba\n<br>";
      }
      ?>
      
        <select name="id_ida">
          <?php while ($radek = mysqli_fetch_assoc($result)) { ?>
          <option value="<?php echo $radek["id_ida"]; ?>" <?php if ($radek["id_ida"] == $id_ida) {  echo 'selected="selected"'; } ?> > <?php echo $radek["SPZ"]; ?> </option>
          <?php } ?>
        </select>
    
    </td>
  </tr>
  </table>
  <input type="submit" value="odeslat">
</form>

<?php
  }
    

  paticka();

?>
</body>
</html> 
