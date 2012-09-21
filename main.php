<?php
session_start();

/* Homepage */

include("include/header.php");
echo $header;

if ($_COOKIE['cookstatus'] === true || $_SESSION['logged_in'] == true) {  
  
$db = new mysqli(/*db information*/);
if($db->connect_error){
  die ('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}


$id;

if (isset($_SESSION['useremail']) && isset($_SESSION['userfacebookid'])) {
	$id = $_SESSION['userfacebookid'];
} else {
	$id = $_COOKIE['cookid'];
}

$room;
$interest;
$result = $db->query("SELECT `enteredroom`, `enteredinterest` FROM subletusers WHERE `facebookid` = '".$id."'");
while ($row = $result->fetch_assoc()) {
	$room= $row['enteredroom'];
	$interest = $row['enteredinterest'];
}

if ($room && $interest) {
	echo "<center><a href = 'findsubletters.php'><img src='images/sublet_viewpotentials.png'></a><a href = 'subletmap.php'><img src='images/sublet_viewlistings.png'></a><a href = 'sublettrans.php'><img src='images/sublet_locationtrack.png'></a></center>";	
} else if ($interest) {
	echo "<center><a href = 'subletform.php'><img src='images/sublet_submitlisting.png'></a><a href = 'subletmap.php'><img src='images/sublet_viewlistings.png'></a><a href = 'sublettrans.php'><img src='images/sublet_locationtrack.png'></a></center>";	
} else if ($room) {
	echo "<center><a href = 'findsubletters.php'><img src='images/sublet_viewpotentials.png'></a><a href = 'subletmap.php'><img src='images/sublet_viewlistings.png'></a><a href = 'subletinterest.php'><img src='images/sublet_tracklocation.png'></a></center>";	
} else {
	echo "<center><a href = 'subletform.php'><img src='images/sublet_submitlisting.png'></a><a href = 'subletmap.php'><img src='images/sublet_viewlistings.png'></a><a href = 'subletinterest.php'><img src='images/sublet_tracklocation.png'></a></center>";	
}

} else{
	echo "<script> top.location.href = 'usersession.php' </script>";
}

include("include/footer.php");
echo $footer;
?>