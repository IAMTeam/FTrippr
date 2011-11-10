<?php

require_once(DOMAIN_ROOT_PATH.'application/classes/lib/facebook.php');


$facebook = new Facebook(array(
  'appId'  => '294892627197166',
  'secret' => 'b70691d4417cdb656431f662e49cc126',
));

$logged_in = false;

// See if there is a user from a cookie
$user = $facebook->getUser();

if ($user) {
$logged_in = true;
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
    $user = null;
  }
}

$params = array(
  scope => 'read_stream, user_checkins, publish_actions',
  redirect_uri => 'http://stage.weareroot.org/loginmax/index.php'
);

$loginUrl = $facebook->getLoginUrl($params);

if(!$logged_in):

?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>    
    <title>FieldTrippr | Home</title>
    <meta id="vp" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="stylesheet" href="css/style.css" />

</head>
<body id="home">
    <figure class="logo"><img src="img/logo-front.png" alt="FieldTrippr" /></figure>
    <nav>
        <button target="_blank" type="button" id="login" class="blue">Log In</button>
        <button id="about">About</button>
        <button id="credits">Credits</button>
    </nav>
    <script type="text/javascript">
        document.getElementById('login').addEventListener('click', function(){
            window.location = '<?= $loginUrl ?>';
            return false;
        });
    </script>
  </body>
</html>

<?php else:

$params = "http://stage.weareroot.org/loginmax/index.php";

$logoutUrl = $facebook->getLogoutUrl($params);

 ?>

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
    <h1><?= $logoutUrl ?></h1>
    <a href="<?= $logoutUrl ?>">Log out</a>
  </body>
</html>

<?php endif; ?>