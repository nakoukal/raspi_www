function kontrola() {

  var jmeno = document.getElementById("Jmeno");
  var prijmeni = document.getElementById("Prijmeni");
  var op = document.getElementById("OP");
  var rp = document.getElementById("RP");
  var platba = document.getElementById("Platba");

  // Conditions

  if (jmeno.value == '') {
    pozadiFalls(jmeno);//zmena barvy pozadi
    return false;
  }
  
  //kontrola na povinne prvni pismeno velke
  if (jmeno.value[0].toUpperCase() != jmeno.value[0]) {
        alert("Prvni pismeno jmena musi byt velke!!");
        pozadiFalls(jmeno);
        return false;
  } 

  if (prijmeni.value == '') {
    
    pozadiFalls(prijmeni);   
    return false;
  }
  
  if (op.value == '') {
    
    pozadiFalls(op);   
    return false;
  }
  
    if (rp.value == '') {
    
    pozadiFalls(rp);    
    return false;
  }
  
    if (platba.value == '') {
    
    pozadiFalls(platba);     
    return false;
  }  


}

function pozadiFalls(x) {
    x.style.background = "red";
}

function pozadiWhite(x) {
    x.style.background = "white";
}