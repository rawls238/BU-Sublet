<?
$header = <<<END
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>BU Sublet</title>
<!-- Stylesheet -->
<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
<link rel="stylesheet" type="text/css" media="all" href="css/responsive.css" />
<!-- Google Font -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
<!--[if lt IE 9]>
<script src="js/html5.js" type="text/javascript"></script>
<![endif]-->
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-34182771-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body id="body" class="contact">

<!-- #site-container -->
<div id="site-container"> 
  
  <!-- #header-top -->
  <div id="header-top" class="clearfix">
    <div class="left">  E. busublet@gmail.com</div>
    <!-- #social -->
    <ul id="social" class="clearfix">
      <li class="twitter"><a href="https://twitter.com/BURoomSwap">Twitter</a></li>
    </ul>
    <!-- /#social --> 
  </div>
  <!-- /#header-top --> 
  
  
END;
if ($_COOKIE['cookstatus'] === true || $_SESSION['logged_in'] == true) {
	$header .='
	<!-- #header -->
  <header id="header" class="clearfix"> 
    <!-- #logo -->
    <div id="logo">
      <h1> <a href="main.php"> <img alt="Logo" src="images/logo.png"> </a> </h1>
    </div>
    <!-- /#logo --> 
    <!-- #primary-nav -->
    <nav id="primary-nav" role="navigation" class="clearfix">
      <ul id="menu-primary-nav" class="nav sf-menu clearfix">
	<li><a href="roominfo.php" >Account & User Settings</a></li>
	<li><a id="stronglog" href="processlogout.php">LOG OUT</a></li>';
}
else {
	$header.='
	<!-- #header -->
  <header id="header" class="clearfix"> 
    <!-- #logo -->
    <div id="logo">
      <h1> <a href="index.php"> <img alt="Logo" src="images/logo.png"> </a> </h1>
    </div>
    <!-- /#logo --> 
    <!-- #primary-nav -->
    <nav id="primary-nav" role="navigation" class="clearfix">
      <ul id="menu-primary-nav" class="nav sf-menu clearfix">
        <li><a href="aboutus.php" >About US</a></li>				
        <li><a id="stronglog" href="usersession.php">LOG IN</a></li>';
}
$header .='		
      </ul>
      
    </nav>
    <!-- #primary-nav --> 
    
  </header>
  <div>';
?>