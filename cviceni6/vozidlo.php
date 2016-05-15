<h2>VOZIDLO</h2>
<?php
  require "funkceZobraz.php";
  require "funkceDB.php";
  
  hlavicka();
  menu2();
  $con = pripoj();
    
  // empty vraci ano, pokud je pole request prazdne
  // vykricnik "!" provadi negaci, tj. true prevede na false a false prevede na true
  if (!empty($_REQUEST["Barva"])) {
  
    $SPZ = $_REQUEST["SPZ"];
    $EC = $_REQUEST["EC"];
    $Barva = $_REQUEST["Barva"];
    $KM = $_REQUEST["KM"];
    $Vyrobce = $_REQUEST["Vyrobce"];
    $RV = $_REQUEST["RV"];
    $id_ida = $_REQUEST["id_ida"];
    //mysqli_query($con, "INSERT INTO `ucebny`.`ucebna` (`idUcebna`, `nazev`, `kapacita`, `projektor`) VALUES (NULL, '$nazev', '$kapacita', '$projektor');");
    mysqli_query($con, "INSERT INTO `Vozidlo` (`SPZ`, `EC`, `Barva`, `KM`, `Vyrobce`, `RV`, `id_ida`) VALUES ('$SPZ', '$EC', '$Barva', '$KM', '$Vyrobce', '$RV', '$id_ida');");
    
    // vypíše databázovou chybu, pokud k ní došlo
    $chyba = mysqli_error($con);
    if ($chyba) {
      echo "chyba v mysql: $chyba\n<br>";
    }
  } 
  
  // empty vraci ano, pokud je pole request prazdne
  // vykricnik "!" provadi negaci, tj. true prevede na false a false prevede na true
  if (!empty($_REQUEST["id"])) {
    $id = $_REQUEST["id"];
    mysqli_query($con, "DELETE FROM `Vozidlo` WHERE `id_idv` = $id");
  
    // vypíše databázovou chybu, pokud k ní došlo
    $chyba = mysqli_error($con);
    if ($chyba) {
      echo "chyba v mysql: $chyba\n<br>";
    }
  }
  
  if (!empty($_REQUEST["id_idv"])) {
    $idEdit = $_REQUEST["id_idv"];
    $SPZ = $_REQUEST["SPZEdit"];
    $EC = $_REQUEST["ECEdit"];
    $Barva = $_REQUEST["BarvaEdit"];
    $KM = $_REQUEST["KMEdit"];
    $Vyrobce = $_REQUEST["VyrobceEdit"];
    $RV = $_REQUEST["RVEdit"];
    $id_ida = $_REQUEST["id_idaEdit"];
    mysqli_query($con, "UPDATE Vozidlo SET `SPZ` = '$SPZ', `EC` = '$EC', `Barva` = '$Barva', `KM` = '$KM', `Vyrobce` = '$Vyrobce', `RV` = '$RV', `id_ida` = '$id_ida' WHERE `id_idv` = $idEdit;");
    // vypíše databázovou chybu, pokud k ní došlo
    $chyba = mysqli_error($con);
    if ($chyba) {
      echo "chyba v mysql: $chyba\n<br>";
    }
  }
  
  
  $result = mysqli_query($con, "SELECT * FROM Vozidlo");
  
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
<form action="vozidlo.php" method="post">
  <table>
  <tr> 
    <td>SPZ vozidla: </td><td><input type="text" name="nazevHledej""></td><td><input type="submit" value="Vyhledat"></td>
  </tr>
  </table>
  
</form>

<br>

<?php
  if (empty($_REQUEST["nazevHledej"])) {
    $result = mysqli_query($con, "SELECT * FROM Vozidlo");
  }
  else {
    $hledej = $_REQUEST["nazevHledej"];  
    $result = mysqli_query($con, "SELECT * FROM Vozidlo WHERE SPZ LIKE '%$hledej%' ");
  }
  
  echo "\n\n<table border = 1>";
  echo "<tr><th>ID</th><th>SPZ</th><th>EČ</th><th>Barva</th><th>najeto KM</th><th>Výrobce</th><th>Rok výroby</th><th>Číslo vozu</th></tr>";
  while ($radek = mysqli_fetch_assoc($result)) {
     echo "<tr>";
       foreach ($radek as $bunka) {
         echo "<td>";
         echo $bunka;
         echo "</td>";
       }
       echo "<td>";
       echo '<a href="vozidlo.php?idEdit='.$radek["id_idv"].'"><img src="edit.png" width="20" onclick="return confirm('."'".'Opravdu chcete editovat tuto položku?'."'".')"></a>';
       echo "</td>";
       echo "<td>";
       echo '<a href="vozidlo.php?id='.$radek["id_idv"].'"><img src="smaz.png" width="20" onclick="return confirm('."'".'Opravdu chcete smazat tuto položku?'."'".')"> </a> </td>';
       echo "</td>";
     echo "</tr>";
  }
  echo "</table>\n\n";  



  
?>
<h3> Vložení </h3>
<form action="vozidlo.php" method="post">
  <table>
  <tr> 
    <td>SPZ: </td><td><input type="text" name="SPZ" value=""></td>
  </tr>
  <tr> 
    <td>EČ: </td><td><input type="text" name="EC" value=""></td>
  </tr>
  <tr> 
    <td>Barva: </td><td><input type="text" name="Barva" value=""></td>
  </tr>
  <tr> 
    <td>Najeto KM: </td><td><input type="text" name="KM" value=""></td>
  </tr>
  <tr> 
    <td>Výrobce: </td><td><input type="text" name="Vyrobce" value=""></td>
  </tr>
  <tr> 
    <td>Rok výroby: </td><td><input type="text" name="RV" value=""></td>
  </tr>
  <tr> 
    <td>IČ: </td><td><input type="text" name="id_ida" value=""></td>
  </tr>
  </table>
  <input type="submit" value="odeslat">
</form>

<h3> Mazání </h3>
<form action="vozidlo.php" method="post">
  <table>
  <tr> 
    <td>ID vozidla: </td><td><input type="text" name="id" value=""></td>
  </tr>
  </table>
  <input type="submit" value="odeslat">
</form>


<?php
  if (!empty($_REQUEST["idEdit"])) {
    $idEdit = $_REQUEST['idEdit'];
    $result = mysqli_query($con, "SELECT * FROM Vozidlo WHERE id_idv = $idEdit");
    $radek = mysqli_fetch_assoc($result);
    
    $idVozidla = $radek["id_idv"];
    $SPZ = $radek["SPZ"];
    $EC = $radek["EC"];
    $Barva = $radek["Barva"];
    $KM = $radek["KM"];
    $Vyrobce = $radek["Vyrobce"];
    $RV = $radek["RV"];
    $id_ida = $radek["id_ida"];
?>

<h3> Editace </h3>
<form action="vozidlo.php" method="post">
  <table>
  <tr> 
    <td>ID vozidla: </td><td><input type="hidden" name="id_idv" value="<?php echo $idVozidla; ?>"></td>
  </tr>
  <tr> 
    <td>SPZ: </td><td><input type="text" name="SPZEdit" value="<?php echo $SPZ; ?>"></td>
  </tr>
  <tr> 
    <td>EČ: </td><td><input type="text" name="ECEdit" value="<?php echo $EC; ?>"></td>
  </tr>
  <tr> 
    <td>Barva: </td><td><input type="text" name="BarvaEdit" value="<?php echo $Barva; ?>"></td>
  </tr>
  <tr> 
    <td>Najeto KM: </td><td><input type="text" name="KMEdit" value="<?php echo $KM; ?>"></td>
  </tr>
  <tr> 
    <td>Výrobce: </td><td><input type="text" name="VyrobceEdit" value="<?php echo $Vyrobce; ?>"></td>
  </tr>
  <tr> 
    <td>Rok výroby: </td><td><input type="text" name="RVEdit" value="<?php echo $RV; ?>"></td>
  </tr>
  <tr> 
    <td>IČ: </td><td><input type="text" name="id_idaEdit" value="<?php echo $id_ida; ?>"></td>
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
