<?php $this->load->view('shared/header',array('title'=>$title,'showmenu'=>true)); ?>
<style type="text/css">
	body{width:100%;height:100%;margin:0;padding:0;}
	#main-nav{
	z-index:2;
	display:block;
	height:20%;
	position:absolute;
	width:100%;
	}
	#mymap{
	position:absolute;
	top:20%;
	height:80%;
	width:100%;
	z-index:1;
	}
</style>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	$(document).ready(initializemap);
  function initializemap() {  
  <?php $latlon = $this->locate->geocode('1 N State, Chicago IL 60602'); ?>
    
    var latlng = new google.maps.LatLng(<?php echo $latlon->lat.','.$latlon->lon; ?>);
    var myOptions = {
      zoom: 11,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("mymap"),
        myOptions);
	}
</script>
<div id="mymap" style="height:80%;width:100%;"></div>

<?php $this->load->view('shared/footer'); ?>