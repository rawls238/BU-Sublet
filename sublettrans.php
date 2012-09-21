<?php
session_start();

/* Display suggested listings to user (based off location tracker) */

include("include/header.php");
echo $header;

if ($_COOKIE['cookstatus'] === true || $_SESSION['logged_in'] == true) {
$db = new mysqli(/*db information*/);
if($db->connect_error){
  die ('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}


if (isset($_SESSION['userfacebookid']))
	$id = $_SESSION['userfacebookid'];
else 
	$id = $_COOKIE['cookid'];

$interest;
$cur = $db->query("SELECT `enteredinterest` FROM subletusers WHERE `facebookid` = '".$id."'");
while ($gauss= $cur->fetch_assoc()) {
	$interest = $gauss['enteredinterest'];
}
if ($interest) {

$result = $db->query("SELECT `hitaddress`, `hitprice`, `hitstart`, `hitend`, `hitemail`, `hitfbid` FROM subinterestmatch WHERE `interestedfbid` LIKE '".$id."'");
$hits;
$i = 0;
if ($result){
while ($row = $result->fetch_assoc()) {
	$hits[$i++] = $row;
}
$length = $i;

echo "<h3>Apartments you may be interested in (based on your location tracker):</h3><br>";

if ($length == 0)
	echo "<b>Looks like there haven't been any apartments entered that fall in the location range you selected :(.</b>";

for ($i = 0; $i < $length; $i++) {
	$curnum = $i+1;
	$mailurl = "mailto:".$hits[$i]["hitemail"];
	$fbid = $hits[$i]["hitfbid"];
	$fbdialogurl ="https://www.facebook.com/dialog/send?app_id=[app_id]&name=BUSublet&link=http://busublet.com/basicsublethome.php&redirect_uri=http://busublet.com/subletmap.php&to=".$fbid;
	echo "<h4>Apartment ".$curnum.":</h4>";
	echo "<b>".$hits[$i]["hitaddress"]."</b>   <br>Rent/month: <b>$".$hits[$i]["hitprice"]."</b><br>Available from <b>".$hits[$i]["hitstart"]."</b> to <b>".$hits[$i]["hitend"]."</b><br><a href='".$mailurl."'>Email this person</a><br><a href='".$fbdialogurl."'>Send Facebook Message</a><br><br>";
	echo "<html><head><script type = 'text/javascript'>function test () { if (confirm('Are you sure you want to remove this listing?')) { return true; } else { return false; } }</script></head><body><form method = 'POST' action = 'manageinterest.php'><input type = 'text' style = 'display: none' name = 'myid' id = 'myid' value = '$id'><input type = 'text' style = 'display: none' id = 'id' name = 'id' value = '$fbid'><input type = 'submit' value = 'Remove this room from my list'></form></body></html>";
}
if ($length != 0)
echo "<br><html><head><script type = 'text/javascript'>function test () { if (confirm('Are you sure you want to clear your list?')) { return true; } else { return false; } }</script></head><body><form method = 'POST' action = 'manageinterest.php' onsubmit = 'return test();'><input type = 'text' style = 'display: none' name = 'myid' id = 'myid' value = '$id'><input type = 'text' style = 'display: none' id = 'id' name = 'id' value = 'all'><input type = 'submit' value = 'Clear List'></form><br></body></html>";
} else {
	echo "You must set a <a href = 'subletinterest.php'>location tracker</a> before you can use this.";
}
}

} else {
 echo "<script> top.location.href = 'http://busublet.com/usersession.php?u=sublettrans.php' </script>";
}

include("include/footer.php");
echo $footer;

?>