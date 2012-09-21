<?php 

/* Form for processing listing submission */

$db = new mysqli(/*db information*/);
if($db->connect_error){
  die ('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}



function bedsize($num) {
if ($num === "Studio")
	return 1;
else if ($num === "Two Bedroom")
	return 2;
else if ($num === "Three Bedroom")
	return 3;
else if ($num === "Four Bedroom")
	return 4;
else if ($num === "Five Bedroom")
	return 5;
else if ($num === "Six Bedroom")
	return 6;
}


$name = $db->real_escape_string($_POST['name']);
$email = $db->real_escape_string($_POST['email']);
$fbid = $db->real_escape_string($_POST['fbid']);
$address = $db->real_escape_string($_POST['address']);
$bedroom = $db->real_escape_string($_POST['bedroom']);
$bednum = bedsize($bedroom);
$bath = $db->real_escape_string($_POST['bath']);
$price = $db->real_escape_string($_POST['price']);
$start = $db->real_escape_string($_POST['start']);
$end = $db->real_escape_string($_POST['end']);
$gender = $db->real_escape_string($_POST['gender']);

$price = trim($price, '$');
//$comments = $db->real_escape_string($_POST['comments']);
      		
$entered;      		
$test = $db->query("SELECT `enteredroom` FROM `subletusers` WHERE `facebookid` = '".$fbid."'");
while ($gettest = $test->fetch_assoc()) {
	$entered = $gettest['enteredroom'];
}

if (!$entered && $fbid != 0) {      		
$db->query("INSERT INTO sublet (`name`, `email`, `fbid`, `address`, `bath`, `bedroom`, `bednum`, `start`, `end`, `price`, `gender`) VALUES ('".$name."', '".$email."', '".$fbid."', '".$address."', '".$bath."', '".$bedroom."', '".$bednum."', '".$start."', '".$end."', '".$price."', '".$gender."')");
$db->query("UPDATE `subletusers` SET `enteredroom` = 1 WHERE `facebookid` = '".$fbid."'"); 

//header("Location: subletmap.php");

$result = $db->query("SELECT `fbid`, `email`, `filled`, `range1lat1`, `range1lng1`, `range1lat2`, `range1lng2`, `range2lat1`, `range2lng1`, `range2lat2`, `range2lng2`, `range3lat1`, `range3lng1`, `range3lat2`, `range3lng2` FROM `subletinterest`");
$ranges;
$i = 0;

$curgender;
while ($row = $result->fetch_assoc())  {
if ($gender != "either") {
  $findgender = $db->query("SELECT `gender` FROM `subletusers` WHERE `facebookid` = '".$row['fbid']."'");
  $curgender;
  while ($getgen = $findgender->fetch_assoc()) {
    $curgender = $getgen['gender'];
  }
  if ($gender === $curgender)
    $ranges[$i++] = $row;
} else {
	$ranges[$i++] = $row;
}
}
$length = $i;
$json = json_encode($ranges);


echo<<<END
<html>
<head>
 <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyANHhEI6qKWiKuAXPHOGfcZWXjXCh1NV0c&sensor=false">
</script>
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type = "text/javascript">
var loc = $json;
var geocoder = new google.maps.Geocoder();
var BC = new google.maps.LatLng(42.3390925, -71.1812979); //west bounds set to BC
var East = new google.maps.LatLng(42.3655468, -71.0290155); //east bounds set to right outside of Logan
var bounds = new google.maps.LatLngBounds(BC, East);
var address = "$address";
$(document).ready(function() {
for (i = 0; i < $length; i++) {
    (function(range, i) {  geocoder.geocode( { 'address': address, 'bounds': bounds }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
      		var mail = range[i].email;
      		var id = range[i].fbid;
			var cur = results[0].geometry.location;
			for (j = 0; j < 3; j++) {
	var curnum = j+1;
	var first = new google.maps.LatLng(range[i]["range" + curnum + "lat1"], range[i]["range" + curnum + "lng1"]);
	var second = new google.maps.LatLng(range[i]["range" + curnum + "lat2"], range[i]["range" + curnum + "lng2"]);
	var curbounds = new google.maps.LatLngBounds(first, second);
	alert(curbounds);
	if (curbounds.contains(cur)) {
			$.ajax({
            type: "POST",
            url: "subletmatch.php",
            global: false,
            data: { address: "$address", interestid: id, hitid: "$fbid", tableid: mail, mail: "$email", bedroom: "$bedroom", price: $price, start: "$start", end: "$end" }
            });
	}
	}
	} else {
		alert('wrong');
	}
      		});
      		})(loc, i);
      }
      
      
});   		
</script>
      		</head>
      		<body>
      		      		</body>
      		</html>
END;

}

echo "<script> window.location.href = 'http://busublet.com/findsubletters.php' </script>";
?>