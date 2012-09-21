<?php
session_start();
require_once("facebook.php");

/* File for processing registration and logins */

$db = "";//connect to database


  define('APP_ID','app_id');
define('APP_SECRET','app_secret');
define('REDIRECT_URI',"redirect");
define('PERMISSIONS_REQUIRED', "email, user_education_history, publish_stream");

$next = "http://busublet.com/";
if (isset($_GET['u']))  {
	$add = $_GET['u'];
	$next .= $add;
} else {
	$next .= "main.php";
}

$facebook = new Facebook(array(
    'appId' => APP_ID,
    'secret' => APP_SECRET,
    'cookie' => true,
));

// Get User ID

$fbuser = $facebook->getUser();
// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($fbuser) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
$registered = $db->query("SELECT * FROM `subletusers` WHERE `facebookid` = '".$fbuser."'");
$k = 0;
while ($user = $registered->fetch_assoc()) {
$k++;
}

    if ($k == 0) { //proceed if no ID matches in the database -> new user!
    $user_profile = $facebook->api('/me', 'GET');
   // $access = $facebook->getAccessToken();
    //print_r($user_profile['education']);
    $bu = false; //boolean variable to see if user is a member of Boston University
   for ($j = 0; $j < count($user_profile['education']); $j++) { 
   if ($user_profile['education'][$j]['school']['name'] === "Boston University") {
   	$bu = true;
   	}
   }
   if (!$bu) {
   $db->close();
   	echo ("<script> top.location.href = 'otherschool.php'</script>");
   } else {
   
   //store everything as a session variable, then place into the database
   $_SESSION['gender'] = $user_profile['gender'];
   $_SESSION['fullname'] = $user_profile['name'];
   $_SESSION['useremail'] = $user_profile['email'];
   $_SESSION['userfacebookid'] = $user_profile['id'];
   $_SESSION['logged_in'] = true;
   //$_SESSION['logouturl'] = $logouturl;
   $curid = $user_profile['id'];
   setcookie("cookid", $user_profile['id'], time()+(60*60*24*100), "/", "www.busublet.com"); //remember user ID and logged in status
   setcookie("cookstatus", true,   time()+(60*60*24*100), "/", "www.busublet.com");
//   setcookie("cookname", $user_profile['name'], time()+(60*60*24*100), "/");
 //  setcookie("cookmail", $user_profile['email'], time()+(60*60*24*100), "/");
   
   $db->query("INSERT INTO subletusers (`name`, `email`, `facebookid`, `gender`) VALUES ('".$user_profile['name']."', '".$user_profile['email']."', '".$user_profile['id']."', '".$user_profile['gender']."')");
   	/*header("Location: basicsublethome.php");
   	$next .= "?/id=".$user_profile['id'];
   	echo $next;*/
   	$db->close();
   echo ("<script> top.location.href = '".$next."'</script>");
   }
   } else {
   
   //we already have a logged in user so just read from DB (rather than from fb again) and set logged_in to true
   	$_SESSION['logged_in'] = true;
   	//$_SESSION['logouturl'] = $logouturl;
   $reg = $db->query("SELECT `name`, `facebookid`, `gender`, `email` FROM subletusers WHERE `facebookid` LIKE '".$fbuser."'");
   	while ($row = $reg->fetch_assoc()) {
      $_SESSION['gender'] = $row['gender'];
   		$_SESSION['fullname'] = $row['name'];
   		$_SESSION['useremail'] = $row['email'];
   		$_SESSION['userfacebookid'] = $row['facebookid'];
      $curid = $row['facebookid'];
   		/*$next .= "?/id=".$user['facebookid'];
   		echo $next;*/
   	}
   setcookie("cookid", $_SESSION['userfacebookid'], time()+(60*60*24*100), "/"); //remember user ID and logged in status
   setcookie("cookstatus", true,   time()+(60*60*24*100), "/");
  // setcookie("cookname", $_SESSION['fullname'], time()+(60*60*24*100), "/");
   // setcookie("cookmail", $_SESSION['email'], time()+(60*60*24*100), "/");
   	$db->close();
   	//echo $logouturl;
   	//echo $next;
   echo ("<script> top.location.href = '".$next."'</script>");
    }
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
}
  
} else {
//don't know why but this will always be where it goes first and then it gets redirected back to this page and SHOULD have user info from fb
	$login_url = $facebook->getLoginUrl($params = array('redirect_uri' => REDIRECT_URI,'scope' => PERMISSIONS_REQUIRED));
		 echo ("<script> top.location.href='".$login_url."'</script>");
}


//echo "<a href = '".$next."'>Go back</a>";

?>