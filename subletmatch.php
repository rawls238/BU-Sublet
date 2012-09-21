<?php

/* Process the adding of a suggested match between subletter and subletee */

$db = new mysqli(/*db information*/);
if($db->connect_error){
  die ('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}

$address = $db->real_escape_string($_POST['address']);
$interestemail = $db->real_escape_string($_POST['tableid']);
$interestid = $db->real_escape_string($_POST['interestid']);
$hitid = $db->real_escape_string($_POST['hitid']);
$email = $db->real_escape_string($_POST['mail']);
$bedroom = $db->real_escape_string($_POST['bedroom']);
$price = $db->real_escape_string($_POST['price']);
$start = $db->real_escape_string($_POST['start']);
$end = $db->real_escape_string($_POST['end']);


$query = "INSERT INTO subinterestmatch (`interestedfbid`, `interestedemail`, `hitaddress`, `hitprice`, `hitstart`, `hitend`, `hitemail`, `hitfbid` ) VALUES ('".$interestid."', '".$interestemail."', '".$address."', '".$price."', '".$start."', '".$end."', '".$email."', '".$hitid."')"; 
$db->query($query);

mailApt();

function mailApt() {
global $interestemail, $email, $hitid;
	$from = "From: busublet@gmail.com";
	$to = $interestemail.", ".$email;
   $headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
	$headers .= $from. "\r\n"; 
	$unsub = "http://busublet.com/no_email?id=".$hitid;
	
	$subject = "Listing within your location tracker posted!";
	$body = "Hey, <br> Someone just posted a listing on our site that is within the location you expressed interest in. Go <a href = 'http://busublet.com/sublettrans.php'>here</a> to check them out!<br><br>Cheers!<br><br> Disable your location tracker (in your user settings on the site) if you no longer wish to receive this emails";
	
	return mail($interestemail, $subject, $body, $headers);
}
?>