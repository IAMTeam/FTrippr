<?php

$logged_in = false;

// See if there is a user from a cookie
$user = $this->facebook->getUser();

if ($user) {
$logged_in = true;
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $this->facebook->api('/me');
  } catch (FacebookApiException $e) {
    #echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
    $user = null;
  }
}

$loginUrl = $this->facebook->getLoginUrl(array(
  'scope' => 'read_stream, user_checkins, publish_actions',
  'redirect_uri' => site_url('fblogin?next_page='.urlencode(site_url('home')))
));


$logoutUrl = $this->facebook->getLogoutUrl(array(
	'next'=>"http://stage.weareroot.org/fieldtrippr/fbtest1"
));


if(!$logged_in):

?>
<!DOCTYPE html>
<html>
<head>    
    <title>FieldTrippr | Home</title>
    <meta id="vp" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="stylesheet" href="css/style.css" />

</head>
<body id="home">
    <figure class="logo"><img src="img/logo-front.png" alt="FieldTrippr" /></figure>
    <nav>
        <a href="<?php echo $loginUrl; ?>"><button target="_blank" type="button" id="login" class="blue">Log In</button></a>
        <button id="about">About</button>
        <button id="credits">Credits</button>
    </nav>
    <script type="text/javascript">
 //       document.getElementById('login').addEventListener('click', function(){
 //           window.location = '<?php echo $loginUrl ?>';
 //           return false;
 //       });
    </script>
  </body>
</html>

<?php else: ?>

<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <body>
    <?php if ($user_profile) { ?>
      Your user profile is 
      <pre>            
        <?php print htmlspecialchars(print_r($user_profile, true)) ?>
      </pre> 
    <?php } else { ?>
      <fb:login-button perms="user_checkins, publish_actions, publish_checkins"></fb:login-button>
    <?php } ?>
    <div id="fb-root"></div>
    <script>               

    </script>
    <a href="<?php echo $logoutUrl ?>">Log out</a>
  </body>
</html>

<?php endif; ?>