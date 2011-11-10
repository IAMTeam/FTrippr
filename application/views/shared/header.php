<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <meta id="vp" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" />

<?php if($this->mobile_detect->isIphone()): ?>
<meta name="apple-mobile-web-app-capable" content="yes" />
<?php endif; ?>
<?php if($this->mobile_detect->isIphone()): ?>

<?php endif; ?>
<?php if($this->mobile_detect->isIpad()): ?>

<?php endif; ?><!--[if !IE]>-->
<!--<![endif]-->

	<link rel="stylesheet" type="text/css" href="/css/reset.css" />
  	<link rel="stylesheet" type="text/css" href="/css/max.css" />
	<link rel="stylesheet" type="text/css" href="/css/jeremy.css" />
	<link rel="stylesheet" type="text/css" href="/css/iphone.css" />
	
	<script src="/js/jquery-1.6.2.min.js"></script>
	<script src="/js/max.js" type="text/javascript"></script>
	<script src="/js/jeremy.js" type="text/javascript"></script>
	<title><?php echo $title; ?></title>
</head>


<body class="<?php echo $page_slug; ?>" <?php if($this->mobile_detect->isIphone()): ?>onload="setTimeout(function() { window.scrollTo(0, 1) }, 100);"<?php endif; ?>>

<?php if(isset($showmenu) && $showmenu != false){ $this->load->view('shared/menu'); } ?>