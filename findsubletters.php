<?php
session_start();

/* This page will find and display any location track matches with whomever just entered their listing */
include("include/header.php");
echo $header;

if ($_COOKIE['cookstatus'] == true || $_SESSION['logged_in'] == true) {

$db = new mysqli(/*db information*/);
if($db->connect_error){
  die ('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}

$mail;
$id;
$entered;

if (isset($_SESSION['useremail']) && isset($_SESSION['userfacebookid'])) {
	$id = $_SESSION['userfacebookid'];
	$mail = $_SESSION['useremail'];
} else {
	$id = $_COOKIE['cookid'];
	$userinfo = $db->query("SELECT `name`, `enteredroom`, `email` FROM subletusers WHERE `facebookid` = '".$id."'");
	while ($col = $userinfo->fetch_assoc()) {
		$mail = $row['email'];
	}
}

$enter = $db->query("SELECT `enteredroom` FROM subletusers WHERE `facebookid` = '".$id."'");
while ($col = $enter->fetch_assoc()) {
	$entered = $col['enteredroom'];
}

if ($entered) {
$address;
$wantedgender;
$result = $db->query("SELECT `address`, `gender` FROM `sublet` WHERE `fbid` = '".$id."'");
while ($row = $result->fetch_assoc()) {
	$address = $row['address'];
	$wantedgender = $row['gender'];
}


$locs;
$i = 0;
$current = $db->query("SELECT * FROM `subletinterest`");
while ($gauss = $current->fetch_assoc()) {
if ($wantedgender != "either") {
  $findgender = $db->query("SELECT `gender` FROM `subletusers` WHERE `facebookid` = '".$gauss['fbid']."'");
  $curgender;
  while ($getgen = $findgender->fetch_assoc()) {
    $curgender = $getgen['gender'];
  }
  if ($wantedgender === $curgender)
    $locs[$i++] = $gauss;
} else {
	$locs[$i++] = $gauss;
}
}
$length = $i;

$json = json_encode($locs);

echo<<<END
<html>
<head>
 <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=[key]&sensor=false">
</script>
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type = "text/javascript">
var loc = $json;
var hitvalues = [];
var geocoder = new google.maps.Geocoder();
var BC = new google.maps.LatLng(42.3390925, -71.1812979); //west bounds set to BC
var East = new google.maps.LatLng(42.3655468, -71.0290155); //east bounds set to right outside of Logan
var bounds = new google.maps.LatLngBounds(BC, East);
var address = "$address";
var hits = 0;

function findHits() {
if ($length == 0)
	$("#results").html("<br><h3>There are no matches right now =/</h3><br>");
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
	if (curbounds.contains(cur)) {
	if (hits == 0)
		$("#results").html(" ");
		hits++;
		 $.ajax({
            type: "POST",
            url: "addressmatch.php",
            global: false,
            data: { id: id }
            })
            .done(function(data){
                $("#results").append(data);
                });
    } else {
    	if (hits == 0)
    		$("#results").html("<br><h3>There are no matches right now =/</h3><br>");
    }
	}
	}
	});
      		})(loc, i);
      		}
      	}
    





$(document).ready(function() {
findHits();
});


        
</script>
</head>
<body>
<center><h1>Find Subletters</h1></center>
<center><div id = "explain">Here's a list of people who we think you might be interested in contacting. <b>Why?</b><br> Well, because they've stated that they're looking for apartments in the area where your apartment is located:</div></center>
<center><div id = "results"></div></center>
</body>
</html> 		
END;
} else {
	echo "You need to have entered a room to use this function!";
}
} else {
	 echo "<script> top.location.href = 'http://busublet.com/usersession.php?u=findsubletters.php' </script>";
}

include("include/footer.php");
echo $footer;
?>