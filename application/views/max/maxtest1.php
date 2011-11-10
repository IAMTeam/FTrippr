<?php 



#$max = new User(43); 

echo $max->username;

$this->locate->setLocation(41.86598147470959, -87.62420654296875);

#echo $this->locate->getLocation()->lat;

#echo $this->locate->getLocation()->lon;

#print_r($this->locate->nearbyVenues());

print_r($this->locate->geocode("673 W Wrightwood, Chicago IL 60647"));

print_r($this->locate->reverseGeocode(41.86598147470959, -87.62420654296875));
#try{
##
#}catch(exception $e){
#	print_r($e);
#};

#print_r($max->getBigdoorUser());

#print_r($_SESSION);

/*
            [id] => 1
            [title] => 0
            [latitude] => 41.884004
            [longitude] => -87.63176
            [street1] => 121 North La Salle Street
            [street2] => 
            [city] => Chicago
            [state] => IL
            [zip] => 60602
            [venuecat_id] => Green Roof
            [slug] => 
            [url] => http://www.greenroofs.com/projects/pview.php?id=21
*/

?>