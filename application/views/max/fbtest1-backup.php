<?php

require_once(DOMAIN_ROOT_PATH.'application/classes/lib/facebook.php');

$logged_in = false;

$facebook = new Facebook(array(
  'appId'  => FACEBOOK_APPID,
  'secret' => FACEBOOK_SECRET
));

// See if there is a user from a cookie
$user = $facebook->getUser();

if ($user) {

	$logged_in = true;
	
	echo "<script>alert('USER!!!!')</script>";

  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
  	
    echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
    $user = null;
  }
}

if($logged_in){
	$logoutUrl = $facebook->getLogoutUrl(array(
		'next'=>current_url()
	));
}else{
	$loginUrl = $facebook->getLoginUrl(array(
	  'scope' => 'read_stream, user_checkins, publish_actions',
	  'redirect_uri' => current_url()
	));
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>Login</title>
</head>
  <body>
    <?php if ($logged_in) { ?>
      Your user profile is 
      <pre>            
        <?php print htmlspecialchars(print_r($user_profile, true)) ?>
      </pre> 
      
      <a href="<?= $logoutUrl ?>">Log out</a>
    <?php } else { ?>
		<a href="<?php echo $loginUrl; ?>">Log In</a>
    <?php } ?>
   </body>
</html>