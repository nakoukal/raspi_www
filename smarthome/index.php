<!DOCTYPE html> 
<html lang="cs"> 
<head> 
    <meta charset="UTF-8"> 
    <title>Ovladani vrat</title>
    <link rel="stylesheet" type="text/css"  href="style.css">
    <script type="text/javascript" src="common.js"></script>
	  <script type="text/javascript" src="http-request.js"></script>
	  <!--<script type="text/javascript" src="json2.js"></script>-->
    <script type="text/javascript">
      function setPin(bit,val){
        new HTTPRequest().post("hwrite.php", { "bit" : bit, "setval" : val},      
        function() {}, false);
      }
	  
		function switchOn(bit){
		var readXhr = new HTTPRequest();
		readXhr.get("read.php", [], { "end" : function(a) {
		obj=JSON.parse(a.responseText);
		data=obj['state'];
		
		for(var x = 0; x < data.length; x++) {
			if(data[x].bit == bit) {
				var val =  data[x].val;
				if(val ==1){
					setPin(bit,0);
					document.getElementById(bit).style.backgroundColor = "silver";	
				}else{
					setPin(bit,1);
					document.getElementById(bit).style.backgroundColor = "red";	
				}
				
			break;
			}
		}
		}}, false);
	  }
	
	  	function switchOnOff(bit){
			 switchOn(bit);
			 setTimeout("switchOn("+bit+")", 2 * 1000);
	  	}
      
      function allGatesOnOff(){
        switchOn(18);
			  setTimeout("switchOn(18)", 2 * 1000);
        switchOn(17);
        setTimeout("switchOn(17)", 2 * 1000);
      }
		
		window.onload = function() {
	  		var bitArray = [17,18];	
	  		var readXhr = new HTTPRequest();
	  		readXhr.get("read.php", [], { "end" : function(a) {
				obj=JSON.parse(a.responseText);
				data=obj['state'];
			
			for(var y = 0; y < bitArray.length; y++){
				for(var x = 0; x < data.length; x++){
					var bita = bitArray[y];
					var bit =  data[x].bit;
					if(bit === bita){
						if(data[x].val == 1){
							setPin(bit,0);
							document.getElementById(bit).style.backgroundColor = "silver";	
						}	
					}
				}
			}
			}}, false);
		}
	//
    </script>
    <style>
    img{
    height: 150px; 
    width: 200px;
    }
    button{
    height: 200px; 
    width: 250px;
    font-size:25px;
    }
    </style>
</head> 
<body>

    <div align="center" style="width:800px">
    <button type ="button" onclick="allGatesOnOff()">
    <img height="75" src="/picture/" alt="Vjezd vrata"/>
    <br /><b>VJEZD/GARAZ</b></button>
     
    <button type ="button" onclick="switchOnOff(18);" id="18">
    <img src="/picture/gate.jpg" alt="Vjezd vrata"/>
    <br /><b>VJEZD VRATA</b></button>
    
    <button type="button" onclick="switchOnOff(17);" id="17">
    <img src="/picture/garage.jpg" alt="Garaz vrata"/>
    <br /><b>GARAZ VRATA</b></button>
    </div>
	
	

</body> 
</html> 