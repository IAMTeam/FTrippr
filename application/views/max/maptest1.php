<?php


#$this->locate->setLocation(41.870711290525584, -87.62540817260742); //CAMPUS
#$this->locate->setLocation(41.920097769316804, -87.68712043762207); //BUCKTOWN
#$this->locate->setLocation(42.49842801732155, -87.93182373046875); //WI

#42.047253079589595, -88.26141357421875

$nearbyVenues = ($this->locate->nearbyVenues(false,false,$numResults));

/*
           [id] => 2
            [title] => 0
            [latitude] => 41.861725
            [longitude] => -87.61484
            [street1] => 1410 South Museum Campus Drive
            [street2] => 
            [city] => Chicago
            [state] => IL
            [zip] => 60605
            [venuecat_id] => Green Roof
            [slug] => 
            [url] => http://www.greenroofs.org/index.php/grhccommittees/289
            [distance] => 50.972206977853
            */


#if(isset($textAddress)){
#	echo "<script>alert('$textAddress')</script>";
#}


?><!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
/*  html { height: 100% }
  body { height: 100%; margin: 0; padding: 0 }
  #map_canvas { height: 100% }
*/
#map_canvas{width:400px; height:400px;}
</style>
<script type="text/javascript" src="/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript"
    src="http://maps.googleapis.com/maps/api/js?sensor=false">
</script>
<script type="text/javascript">
  function initialize() {
  
//  var blueIcon = new GIcon(G_DEFAULT_ICON);
//blueIcon.image = "http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png";

  
    var latlng = new google.maps.LatLng(<?php echo $this->locate->currentLat.','.$this->locate->currentLon; ?>);
    var myOptions = {
      zoom: 11,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"),
        myOptions);

 var latlngbounds = new google.maps.LatLngBounds( );
 
 latlngbounds.extend(latlng);

	  var marker = new google.maps.Marker({
      position: latlng, 
      map: map, 
      title:"My Locationper",
      icon:"http://stage.weareroot.org/img/user.png"
  	   });   

<?php foreach ($nearbyVenues as $venue): ?>

		//map.addOverlay(new GMarker(new google.maps.LatLng(<?php echo $venue->latitude.','.$venue->longitude; ?>), {icon:blueIcon}));
latlngbounds.extend(new google.maps.LatLng(<?php echo $venue->latitude.','.$venue->longitude; ?>));

	  var marker = new google.maps.Marker({
      position: new google.maps.LatLng(<?php echo $venue->latitude.','.$venue->longitude; ?>), 
      map: map, 
      title:"<?php echo $venue->title; ?>",
      icon:"http://stage.weareroot.org/img/marker.png"
  	   });   
 <?php endforeach; ?>
 
 	console.log(latlngbounds);
 
		map.setCenter( latlngbounds.getCenter( ), map.fitBounds( latlngbounds ) );
 


//LOCATION SCRIPT

var doAskLocation = false;
	if (navigator.geolocation && doAskLocation){
			navigator.geolocation.getCurrentPosition(		
		function( position ){
		//console.log(position);
			try{
				var addy = position.address.streetNumber + ' ' + position.address.street + ' ' + position.address.city + ' ' + position.address.region + ' ' + position.address.postalCode;
			}catch(e){
				var addy = position.coords.latitude + ', ' + position.coords.longitude;
			}
			//alert(addy)
			alert(addy); 
		},
		function( error ){
			alert('There was a problem getting your address, please enter it manually');
			//alert(dump(error))
	
//	console.log(error);
			//$('#saddr').focus();
		},
		{
			timeout: (20 * 1000),
			maximumAge: (1000 * 60 * 15),
			enableHighAccuracy: true
		})	
    }
}

  


</script>
</head>
<body onload="initialize()">
	<form method="get">
	
<label>Please enter your address</label>
<input name="textAddress" type="text" value="<?php if(isset($textAddress)) echo $textAddress; ?>"/>
<select name="numResults">
	<option value="5">5 results</option>
	<option value="10">10 results</option>
	<option value="15">15 results</option>
	<option value="20">20 results</option>
</select>
<input type="submit" value="go" />
	</form>
  <div id="map_canvas" style=""></div>

<?php 
/*
    [id] => 2
    [title] => 0
    [latitude] => 41.861725
    [longitude] => -87.61484
    [street1] => 1410 South Museum Campus Drive
    [street2] => 
    [city] => Chicago
    [state] => IL
    [zip] => 60605
    [venuecat_id] => Green Roof
    [slug] => 
    [url] => http://www.greenroofs.org/index.php/grhccommittees/289
    [distance] => 50.972206977853
*/
?>
<?php if($doSearch): ?>
<p>Nearest Locations</p>
<ol>
    <?php foreach ($nearbyVenues as $venue): ?>
    <li><?php echo $venue->title; ?>: <?php echo $venue->distance; ?>mi.
    <?php endforeach; ?>
</ol>
<?php endif; ?>
</body>
</html>
