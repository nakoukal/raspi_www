 <h2>ZAPŮJČENÍ</h2>
 <?php
  require "funkceZobraz.php";
  require "funkceDB.php";
  
  hlavicka();
  menu2();
  $con = pripoj(); 

  // empty vraci ano, pokud je pole request prazdne
  // vykricnik "!" provadi negaci, tj. true prevede na false a false prevede na true
  if (!empty($_REQUEST["Datum"])) {
  
    $Datum = $_REQUEST["Datum"];
    $Cena = $_REQUEST["Cena"];
    $idKlienta = $_REQUEST["id_idk"];
    
   
    //mysqli_query($con, "INSERT INTO `ucebny`.`ucebna` (`idUcebna`, `nazev`, `kapacita`, `projektor`) VALUES (NULL, '$nazev', '$kapacita', '$projektor');");
    mysqli_query($con, "INSERT INTO Zapujceni (`Datum`, `Cena`, `id_idk`) VALUES ('$Datum', '$Cena', '$idKlienta');");
    
    // vypíše databázovou chybu, pokud k ní došlo
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
    mysqli_query($con, "DELETE FROM `Zapujceni` WHERE `Zapujceni`.`id_idz` = $id");
  
    // vypíše databázovou chybu, pokud k ní došlo
       // vypíše databázovou chybu, pokud k ní došlo
    $chyba = mysqli_error($con);
    if ($chyba) {
      echo "chyba v mysql: $chyba\n<br>";
    }
  }
  
  if (!empty($_REQUEST["nazevEdit"])) {
    $idEdit = $_REQUEST["id_idz"];
    $Datum = $_REQUEST["nazevEdit"];
    $Cena = $_REQUEST["nazevEdit2"];
    $idKlienta = $_REQUEST["id_idk"];
   
    mysqli_query($con, "UPDATE Zapujceni SET `Datum` = '$Datum', `Cena` = '$Cena', `id_idk` = '$idKlienta' WHERE `id_idz` = $idEdit;");
    // vypíše databázovou chybu, pokud k ní došlo
       // vypíše databázovou chybu, pokud k ní došlo
    $chyba = mysqli_error($con);
    if ($chyba) {
      echo "chyba v mysql: $chyba\n<br>";
    }
  }
  
  
  $result = mysqli_query($con, "SELECT * FROM `Klient`");
  
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

<?php
 hledej();
?>

<?php
  if (empty($_REQUEST["nazevHledej"])) {
    $result = mysqli_query($con, "SELECT * FROM `Zapujceni`, `Klient` WHERE Zapujceni.id_idk = Klient.id_idk");
  }
  else {
    $hledej = $_REQUEST["nazevHledej"];  
    $result = mysqli_query($con, "SELECT * FROM `Zapujceni` WHERE id_idk LIKE '%$hledej%' ");
    // vypíše databázovou chybu, pokud k ní došlo
    $chyba = mysqli_error($con);
    if ($chyba) {
      echo "chyba v mysql: $chyba\n<br>";
    }
  }
  echo "\n\n<table border = 1>";
  echo "<tr> <th> ID zapůjčení </th><th> ID klienta </th> <th> Datum </th> <th> Cena </th></tr>";
  while ($radek = mysqli_fetch_assoc($result)) {
     echo "<tr>";
       echo "<td>";
       echo $radek["id_idz"];
       echo "</td>";
       echo "<td>";
       echo $radek["id_idk"];
       echo "</td>";
       echo "<td>";
       echo $radek["Datum"];
       echo "</td>";
       echo "<td>";
       echo $radek["Cena"];
       echo "</td>";
       echo "<td>";
       echo '<a href="Zapujceni.php?idEdit='.$radek["id_idz"].'"><img src="edit.png" width="20" onclick="return confirm('."'".'Opravdu chcete editovat tuto položku?'."'".')"></a>';
       echo "</td>";
       echo "<td>";
       echo '<a href="Zapujceni.php?id='.$radek["id_idz"].'"><img src="smaz.png" width="20" onclick="return confirm('."'".'Opravdu chcete smazat tuto položku?'."'".')"> </a> </td>';
       echo "</td>";
     echo "</tr>";
  }
  echo "</table>\n\n";  



  
?>
<h3> Vložení </h3>
<form action="Zapujceni.php" method="post">
  <table>
  <tr> 
    <td>Datum: </td><td><input type="text" name="Datum" value=""></td>
  </tr>
  <tr> 
    <td>Cena: </td><td><input type="text" name="Cena" value=""></td>
  </tr>
  <tr> 
    <td>ID klienta: </td>
    <td>
      <select name="id_idk">
        <?php
        $result = mysqli_query($con, "SELECT * FROM `Klient`");
        
        // vypíše databázovou chybu, pokud k ní došlo
    $chyba = mysqli_error($con);
    if ($chyba) {
      echo "chyba v mysql: $chyba\n<br>";
    }
  
        while ($radek = mysqli_fetch_assoc($result)) { ?>
        <option value="<?php echo $radek["id_idk"]; ?>"> <?php echo $radek["Prijmeni"]; ?> </option>
        <?php } ?>
      </select>
    </td>
  </tr>
  </table>
  <input type="submit" value="odeslat">
</form>
 
<h3> Mazání </h3>
<form action="Zapujceni.php" method="post">
  <table>
  <tr> 
    <td>ID Zapůjčení: </td><td><input type="text" name="id" value=""></td>
  </tr>
  </table>
  <input type="submit" value="odeslat">
</form>


<?php
  if (!empty($_REQUEST["idEdit"])) {
    $idEdit = $_REQUEST['idEdit'];
    $result = mysqli_query($con, "SELECT * FROM Zapujceni WHERE id_idz = $idEdit");
    $radek = mysqli_fetch_assoc($result);
    
    $idZapujceni = $radek["id_idz"];
    $Datum = $radek["Datum"];
    $Cena = $radek["Cena"];
    $idKlienta = $radek["id_idk"];
    
?>

<h3> Editace </h3>
<form action="Zapujceni.php" method="post">
  <table>
  <tr> 
    <td>ID Zapujceni: </td><td><input type="hidden" name="id_idz" value="<?php echo $idZapujceni; ?>"></td>
  </tr>
  <tr> 
    <td>Datum: </td><td><input type="text" name="nazevEdit" value="<?php echo $Datum; ?>"></td>
  </tr>
  <tr> 
    <td>Cena: </td><td><input type="text" name="nazevEdit2" value="<?php echo $Cena; ?>"></td>
  </tr>
  <tr> 
    <td>ID klienta: </td>
  <td>
    <?php
      $result = mysqli_query($con, "SELECT * FROM Klient");
      // vypíše databázovou chybu, pokud k ní došlo
      
      ?>
      
        <select name="id_idk">
          <?php while ($radek = mysqli_fetch_assoc($result)) { ?>
          <option value="<?php echo $radek["id_idk"]; ?>" <?php if ($radek["id_idk"] == $idKlienta) {  echo 'selected="selected"'; } ?> > <?php echo $radek["Prijmeni"]; ?> </option>
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

