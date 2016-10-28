<?php
   // funkce pro nastavení lokálního připojení a připojení na hosting a připojení databáze
   function pripoj()
   { // BEGIN function pripoj
   if (1) { 
   
    //$con = mysqli_connect("localhost","root","", "l14012_webz_8087");
    $con = mysqli_connect("localhost","test","test", "test");
    }
    else {
      
      $con = mysqli_connect("sql2.webzdarma.cz","l14012.webz.8087","AAaa1234", "l14012.webz.8087");
    }

      
    if (!$con) {
      echo "Error: Unable to connect to MySQL." . PHP_EOL;
      echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
      echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
      exit;
    }
    
  
    // vypíše databázovou chybu, pokud k ní došlo
       // vypíše databázovou chybu, pokud k ní došlo
    $chyba = mysqli_error($con);
    if ($chyba) {
      echo "chyba v mysql: $chyba\n<br>";
    }
  
    // nastaví znakovou sadu na UTF8, aby se správně zobrazovala čeština
    // Celkem musí kódování souhlasit na těchto místech:
    // 1. V databázi v kódování sloupců s textem každé tabulky
    // 2. V hlavičce stránky - charset
    // 3. V kódování editoru. V PsPadu se přepíná v menu formát.
    //    V poznámkovém bloku u uložit jako, pod příponou
    mysqli_set_charset($con, "utf8");
    return $con;
   } // END function pripoj
   
  // funkce pro vyhledávání pomocí id klienta
   function hledej() { ?>
<h3> Vyhledávání </h3>
<form action="index.php" method="post">
  <table>
  <tr> 
    <td>ID klienta: </td><td><input type="text" name="nazevHledej"></td><td><input type="submit" value="Vyhledat"></td>
  </tr>
  </table>
  
</form>

<br>

<?php
}
// vypíše chybu připojení a vrati false
function chyba($con,$message="") {
  $chyba = mysqli_error($con);
  if ($chyba) {
    echo "<b class='error'>$message chyba v mysql: $chyba\n<br></b>";
    return false;
  }
  return true;
}

function info($message)
{
    echo "<div class='info'><b> $message </b></div>";
}
// nacte radek z tabulky
function nacti($con,$tabulka, $idName, $idValue) {
  $query = "SELECT * FROM $tabulka WHERE $idName = '$idValue'";
  $result = mysqli_query($con,$query);
  
  
  if(!chyba($con,"Funkce nacti query: ")){    
      return false;
  }
  
  
  $radek = mysqli_fetch_assoc($result);
  
    
  if(!chyba($con,"Funkce nacti fetch: ")){
      return false;
  }
  
  return $radek;
}

/**
 * Jednoducha funkce na vraceni result z tabulky neni mozno puzit slozitejsi 
 * sql dotaz
 * @param type $con - identifikator pripojeni k db
 * @param type $tabulka - nazev tabulky 
 * @param type $where - podminka dotazy (slopec = 'dotaz')
 * @param type $order - ptrideni podle sloupce (slouped DESC nebo ASC)
 * @return boolean - vraci false pokud je chyba a result pokud je vse ok;
 */
function dQuery($con,$tabulka,$where="",$order=""){
    //Slozeni sql dotazu podle parametru funkce
    $query = "SELECT * FROM $tabulka ";
    
    if($where){
        $query .= " WHERE $where ";
    }
    
    if($order){
        $query .= " ORDER BY $order ";
    }
    
    $query .= ";";
        
    $result = mysqli_query($con, $query);
    if(!chyba($con)){
        return false;
    }    
    return $result;
}
/**
 * Funkce pro vykonani definovaneho sql dotazu
 * provede kontrolu na chyby a vrati result
 * 
 * @param type $con
 * @param type $query
 * @return boolean
 */
function eQuery($con,$query)
{
    $result = mysqli_query($con, $query);
    if(!chyba($con)){
        return false;
    }
    return $result;
}

function smaz($con,$tabulka,$where) {
    if(sizeof($where)==0)
        return false;
    //Slozeni sql dotazu podle parametru funkce
    $query = "DELETE FROM $tabulka WHERE ";
    
    //sestaveni podminky podle obsahu parametru pole where
    foreach ($where as $key => $value) {
        $query .= "$key = '".$value."'";
        // pridani AND na konec pokud se nejedna o posledni item v poli
        if( next( $where ) ) {            
            $query .= " AND ";
        }        
    }    
    $result = mysqli_query($con,$query);
    if(!chyba($con,"Funkce smaz :")){
        return false;
    }
    info("Zaznam v tabulce $tabulka byl odstranen.");
    return $result;
}

function vloz($con,$tabulka, $sloupce, $hodnoty) {
    //Slozeni sql dotazu podle parametru funkce
    $query = "INSERT INTO $tabulka ($sloupce) VALUES ($hodnoty);";
    $result = mysqli_query($con,$query);
    if(!chyba($con)){
        return false;
    }
    info("Zaznam v tabulce $tabulka byl vytvoren.");
    return $result;
}

function uprav($con,$tabulka, $hodnoty,$where){
    //Slozeni sql dotazu podle parametru funkce
    $query = "UPDATE $tabulka SET ";
    foreach ($hodnoty as $key => $value) {
        $query .= "$key = '".$value."'";
        // pridani carky na konec pokud se nejedna o posledni item v poli
        if( next( $hodnoty ) ) {            
            $query .= " , ";
        }        
    }
    
    $query .= " WHERE $where ;";    
    
    $result = mysqli_query($con,$query);
    if(!chyba($con)){
        return false;
    }
    info("Zaznam v tabulce $tabulka byl upraven.");
    return $result;
}