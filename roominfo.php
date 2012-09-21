<?php 
session_start();

/* Display account information to user */ 

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

$result = $db->query("SELECT `address`, `price`, `bath`, `bedroom`, `start`, `gender`, `end` FROM `sublet` WHERE `fbid` LIKE '".$id."'");
	while ($row = $result->fetch_assoc()) {
		$address = $row['address'];
		$price = $row['price'];
		$bath = $row['bath'];
		$bed = $row['bedroom'];
		$gender = $row['gender'];
		$start = $row['start'];
		$end = $row['end'];
	}
	
$genderform;
if ($gender == "male")
	$genderform = "Male-Only";
else if ($gender == "female")
	$genderform = "Female-Only";
else 
	$genderform = "Male or Female";
	
$find = $db->query("SELECT `enteredroom`, `enteredinterest`, `email` FROM `subletusers` WHERE `facebookid` LIKE '".$id."'");
$enteredroom = 0;
$enteredinterest = 0;
while ($col = $find->fetch_assoc()) {
	$enteredroom = $col['enteredroom'];
	$email = $col['email'];
	$enteredinterest = $col['enteredinterest'];
}
if ($enteredroom) {

echo<<<END
<html>
<head>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
 <script type = "text/javascript">
  $(document).ready(function() {
    $("#editstart").datepicker({ 
    minDate: 0,
    onSelect: function( selectedDate ) {
				$( "#editend" ).datepicker( "option", "minDate", selectedDate );
			}
	 });
    $("#editend").datepicker({ 
    minDate: 0,
    onSelect: function( selectedDate ) {
				$( "#editstart" ).datepicker( "option", "maxDate", selectedDate );
			} 
	});
  });
  
  function edit(action) {
  if (action === 'edit') {
  $("#curinfo").hide();
  $("#edit").show();
  var gen = $("#gender");
  for (i = 0; i < gen.length; i++) {
  		if (gen[i].value === "$gender")
  			gen[i].checked = true;
  		
  }
}
}

function verify() {
	if (!($("#editprice").val())) {
		alert("Fill in a value for price!");
		$("#editprice").focus();
		return false;
	} else if (!($("#editaddress").val())) {
		alert("Fill in an address!");
		$("#editaddress").focus();
		return false;
	}  else if (!($("#editstart").val())) {
		alert("Fill in a starting sublet date for your apartment!");
		$("#editstart").focus();
		return false;
	}   else if (!($("#editend").val())) {
		alert("Fill in an end sublet date for your apartment!");
		$("#editend").focus();
		return false;
	} else if ($("#editstart").val().length != 10 || $("#editend").val().length != 10) {
		alert("Your start and end dates are not properly formatted!");
		return false;
	} else if (isNaN($("#editprice").val())) {
		alert("Price entered must be a number! This could be because you have an extra '$' in front of it.");
		return false;
	} else {
		return true;
	}
}

function verifydelete () {
	if (!(confirm("Are you sure you want to delete your apartment from our database?")))
		return false;
	else 
		return true;
}

function confirmlocdel () {
	if (!(confirm("Are you sure you want to reset your location tracker? This will stop your apartment tracker from updating any further and will stop sending you emails of new apartments.")))
		return false;
	else 
		return true;
}

function locationload() {
	if ($enteredinterest) {
		$("#tracker").show();
	} else {
		$("#tracker").hide();
	}
}
  
  </script>
</head>
<body onload = "locationload();">
<h1>Account and User Settings</h1><br>
<b>Name:</b> $name <br />
<div id = "curinfo" name = "curinfo">
<b>Email:</b> $email <br />
<b>Address:</b> $address<br />
<b>Rent/Month for Subletter: </b>$$price <br />
<b>Gender Allowed: </b> $genderform<br />
<b>Bedrooms:</b> $bed <br />
<b>Bathrooms:</b> $bath <br />
<b>Beginning of Sublet: </b>$start<br />
<b>End of Sublet:</b> $end<br /><br />
<div id = "tracker" name = "tracker"> 
<form method = "POST" action = "locationdelete.php" onsubmit = "confirmlocdel();">
<input type = "text" style = "display: none" id = "facebid" value = "$id" name = "facebid" />
<b>Location Tracker:</b> <input type = "submit" value = "Reset Location Tracker Settings"><br><br>
</form>
</div>
<input type = "button" value = "Edit Information" onclick = "edit('edit');">

</div>
<div id = "edit" name = "edit" style = "display: none"> 
<form name = "editing" id = "editing" method = "POST" action = "editroom.php" onsubmit = "return verify();">
<b>Email: </b><input type = "email" id = "editemail" value = "$email" name = "editemail" /><br /><br>
<input type = "text" style = "display: none" id = "editfbid" value = "$id" name = "editfbid" />
<b>Street Address</b> (just number + street): <input type = "text" value = "$address" id = "editaddress" name = "editaddress" /> <br /><br>
<b>Rent/Month for Subletter:</b> <input type = "text" value = "$price" id = "editprice" name = "editprice" /><br><br>
<b>Gender Allowed:</b> </b><input type = "radio" id = "gender" name = "gender" value = "male" /> Male-Only 
<input type = "radio" id = "gender" name = "gender" value = "female" /> Female-Only
<input type = "radio" id = "gender" name = "gender" value = "either" checked = "yes"/> Male or Female
<br><br>
<b>Bedrooms:</b> <select name = "editbedroom" id = "editbedroom">
<option value = "$bed">$bed (previous)</option>
<option value = "Studio">Studio</option>
<option value = "Two Bedroom">Two Bedroom</option>
<option value = "Three Bedroom">Three Bedroom</option>
<option value = "Four Bedroom">Four Bedroom </option>
<option value = "Five Bedroom">Five Bedroom </option>
<option value = "Six Bedroom">Six Bedroom</option>
</select> <br /><br>
<b>Bathrooms:</b> <select name = "editbath" id = "editbath">
<option value = "$bath">$bath (previous)</option>
<option value = "One Bathroom">One Bathroom</option>
<option value = "Two Bathroom">Two Bathrooms</option>
<option value = "Three Bathroom">Three Bathrooms</option>
<option value = "Four Bathroom">Four Bathrooms</option>
</select><br /><br>
<b>Start of Sublet: </b><input id = "editstart" value = "$start" name = "editstart" /><br /><br>
<b>End of Sublet:</b> <input id = "editend" value = "$end" name = "editend" /><br /><br><br>
<input type = "submit" value = "Done Editing" />
</form>
</div>
<br>
<form method = "POST" action = "subletdelete.php" onsubmit = "return verifydelete();">
<input type = "text" style = "display: none" id = "deletefbid" value = "$id" name = "deletefbid" />
<input type = "submit" value = "Delete Apartment">
</form>
</body>
</html>
END;

} else {
echo "<html><body>Name: ".$name." <br />Email: ".$email."<br /></body></html>";

}


} else {
 echo "<script> top.location.href = 'http://busublet.com/usersession.php?u=roominfo.php' </script>";
}

include("include/footer.php");
echo $footer;

?>
