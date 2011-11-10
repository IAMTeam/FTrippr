<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link rel="stylesheet" href="" />
<script type="text/javascript" src="/js/jquery-1.6.2.min.js"></script>

<script type="text/javascript">
var doAskLocation = true;
var global1 = 'x';
function askLocation(){

	var location = false;
	var locationType = false;
	var gotLocation = false;
	var browserHasLocationSupport = false;;
	var userGrantedLocationAccess = false;
	if (navigator.geolocation && doAskLocation){
	
		
			browserHasLocationSupport = true;
	    	
	    	
	    	global1 = navigator.geolocation.getCurrentPosition(		
	    	
	    	function( position ){
	    	  	
	    	userGrantedLocationAccess = true;
			
	    	//console.log(position);
	    		try{
	    			location = position.coords.latitude + ',' + position.coords.longitude;
	    			locationType = 'latlon';
	    			gotLocation = true;
	    		}catch(e){
	    			location = position.address.streetNumber + ' ' + position.address.street + ' ' + position.address.city + ' ' + position.address.region + ' ' + position.address.postalCode;
	       			locationType = 'street';
					gotLocation = false;
	    		}
	    		if(gotLocation){
	    			alert(locationType+': '+location);
	    		}
	    	
	    		//alert(location)
	    		//alert(location); 
	    	},
	    	function( error ){
	    		console.log(error);
	    		
				switch(error.code) 
				{
					case error.TIMEOUT:
						alert ('Timeout');
						break;
					case error.POSITION_UNAVAILABLE:
						alert ('Position unavailable');
						break;
					case error.PERMISSION_DENIED:
						alert ('Permission denied');
						break;
					case error.UNKNOWN_ERROR:
						alert ('Unknown error');
						break;
				}
	    		
	    		//alert('There was a problem getting your address, please enter it manually');
	    		//alert(dump(error))
	    
	    		//console.log(error);
	    		//$('#saddr').focus();
	    	},
	    	{
	    		timeout: (20 * 1000),
	    		maximumAge: (1000 * 60 * 15),
	    		enableHighAccuracy: true
	    	}
	    )
	    
	}else{
		browserHasLocationSupport = false;
		alert('NO!');
	}

}

</script>
</head>
<body onload="askLocation()">
HURRRR
</body>
</html>	