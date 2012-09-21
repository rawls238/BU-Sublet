<?php
session_start();

/* Form for listing submission */
include("include/header.php");
echo $header;


if ($_COOKIE['cookstatus'] === true || $_SESSION['logged_in'] == true) {

$db = new mysqli(/*db information*/);
if($db->connect_error){
  die ('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}

$name;
$email;
$id;

if (isset($_SESSION['fullname']) && isset($_SESSION['useremail']) && isset($_SESSION['userfacebookid'])) {
	$name = $_SESSION['fullname'];
	$email = $_SESSION['useremail'];
	$id = $_SESSION['userfacebookid'];
} else {
	$id = $_COOKIE['cookid'];
	$userinfo = $db->query("SELECT `name`, `email` FROM subletusers WHERE `facebookid` = '".$id."'");
	while ($col = $userinfo->fetch_assoc()) {
		$name = $row['name'];
		$email = $row['email'];
	}
}
	



$result = $db->query("SELECT `enteredroom` FROM `subletusers` WHERE `facebookid` = '".$id."'");
$allow = 0;
if ($result) {
	while ($row = $result->fetch_assoc()) {
		$allow = $row['enteredroom'];
	}
}
if (!$allow) {
echo<<<END

<!DOCTYPE html>
<html>
<head>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
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
 <script type = "text/javascript">
  $(document).ready(function() {
    $("#start").datepicker({ 
    minDate: 0,
    onSelect: function( selectedDate ) {
				$( "#end" ).datepicker( "option", "minDate", selectedDate );
			}
	 });
    $("#end").datepicker({ 
    minDate: 0,
    onSelect: function( selectedDate ) {
				$( "#start" ).datepicker( "option", "maxDate", selectedDate );
			} 
	});
  });
  
  function verify() {
  var price = $("#price").val();
  price = price.replace('$', '');
  if (!($("#address").val())) {
  	alert('Please fill out your address.');
  	$("#address").focus();
  	return false;
  } else if ($("#bedroom").val() === "null") {
    alert('Please fill out the number of bedrooms in your apartment.');
  	$("#bedroom").focus();
  	return false;
  } else if ($("#bath").val() === "null") {
    alert('Please fill out the number of bathrooms in your apartment.');
  	$("#bath").focus();
  	return false;
  } else if (!($("#start").val())) {
    alert('Please fill out the start date for your sublet.');
  	$("#start").focus();
  	return false;
  } else if (!($("#end").val())) {
    alert('Please fill out the end date for your sublet.');
  	$("#end").focus();
  	return false;
  } else if (!($("#price").val())) {
    alert('Please fill out the rental price you will be charging the subletter.');
  	$("#price").focus();
  	return false;
  } else if ($("#start").val().length != 10 || $("#end").val().length != 10) {
		alert("Your start and end dates are not properly formatted!");
		return false;
	} else if (isNaN(price)) {
		alert("Price entered must be a number! This could be because you have an extra '$' in front of it.");
		return false;
	} else {
  	return true;
  }
  }
  </script>

</head>
<body>
<form action = "subletinfo.php" method = "POST" onsubmit = "return verify();">
<input type = "text" style = "display: none" value = "$name" id = "name" name = "name" />
<input type = "text" style = "display: none" id = "email" value = "$email" name = "email" />
<input type = "text" style = "display: none" id = "fbid" value = "$id" name = "fbid" />
<b>Street Address (just number + street): </b><input type = "text" id = "address" name = "address" /> <br /><br />
<b>Bedrooms: </b><select name = "bedroom" id = "bedroom"> <br />
<option value = "null">Please Select</option>
<option value = "Studio">Studio</option>
<option value = "Two Bedroom">Two Bedroom</option>
<option value = "Three Bedroom">Three Bedroom</option>
<option value = "Four Bedroom">Four Bedroom </option>
<option value = "Five Bedroom">Five Bedroom </option>
<option value = "Six Bedroom">Six Bedroom</option>
</select> <br /><br />
<b>Bathrooms: </b><select name = "bath" id = "bath"> <br /> <br />
<option value = "null">Please Select</option>
<option value = "One Bathroom">One Bathroom</option>
<option value = "Two Bathroom">Two Bathrooms</option>
<option value = "Three Bathroom">Three Bathrooms</option>
<option value = "Four Bathroom">Four Bathrooms</option>
</select><br /><br />
<b>Start of Sublet: </b><input id = "start" name = "start" />
<b>End of Sublet: </b><input id = "end" name = "end" /><br /><br />
<b>Rent/Month for Subletter: </b><input type = "text" id = "price" name = "price" /><br /><br />
<b>Gender Allowed: </b><input type = "radio" id = "gender" name = "gender" value = "male" /> Male-Only 
<input type = "radio" id = "gender" name = "gender" value = "female" /> Female-Only
<input type = "radio" id = "gender" name = "gender" value = "either" checked = "yes"/> Male or Female
<br /><br />

<input type = "submit" value = "Submit" />
</body>
</html> 
END;
} else {
echo "You already entered your room!";
}
} else {
 echo "<script> top.location.href = 'http://busublet.com/usersession.php?u=subletform.php' </script>";
}
include("include/footer.php");
echo $footer;

?>