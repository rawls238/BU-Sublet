<?php
session_start();

/* Allow user to input interested locations */
include("include/header.php");
echo $header;



if ((isset($_COOKIE['cookid']) && $_COOKIE['cookstatus'] === true) || $_SESSION['logged_in'] == true) {

$mail;
$id;

if (isset($_SESSION['useremail']) && isset($_SESSION['userfacebookid'])) {
	$id = $_SESSION['userfacebookid'];
	$mail = $_SESSION['useremail'];
} else {
	$id = $_COOKIE['cookid'];
	$userinfo = $db->query("SELECT `name`, `email` FROM subletusers WHERE `facebookid` = '".$id."'");
	while ($col = $userinfo->fetch_assoc()) {
		$mail = $col['email'];
	}
}

$db = new mysqli(/*db information*/);
if($db->connect_error){
  die ('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}

$result = $db->query("SELECT `enteredinterest` FROM `subletusers` WHERE `facebookid` = '".$id."'");
$entered = 0;
$json;
$found;
//this code gets the users previous circles
if ($result) {
	while ($row = $result->fetch_assoc()) {
		$entered = $row['enteredinterest'];
	}
	if ($entered) {
		$circles = $db->query("SELECT `filled`, `range1lat1`, `range1lng1`, `range1lat2`, `range1lng2`, `range2lat1`, `range2lng1`, `range2lat2`, `range2lng2`, `range3lat1`, `range3lng1`, `range3lat2`, `range3lng2` FROM subletinterest WHERE `fbid` LIKE '".$id."'");
			while ($find = $circles->fetch_assoc()) {
				//echo "test";
				$found = $find;
			}
			
		
		}
}
$json = json_encode($found);

if (!$entered) {
echo<<<END
<!DOCTYPE html>
<html>
  <head>
  
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { }
    </style>
    <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=[key]&sensor=false">
    </script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false&language=en"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript">
    	var map;
    	var circlesArray = new Array();
    	
    	//initialize map and click listeners
      function initialize() {
        var myOptions = {
          center: new google.maps.LatLng(42.3525973, -71.1106078),
          zoom: 15,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map_canvas"),
            myOptions);
            	google.maps.event.addListener(map, 'click', function(event) {
  placeCircle(event.latLng);
  });
 /* if ($entered)
  	displayCircles();*/
  }
      
 	
 function findCircle(circle) {
 for (j = 0; j < circlesArray.length; j++) {
	if(circlesArray[j].getCenter() === circle.getCenter()) {
	if (j === 0)
		document.getElementById("bound1").value = circle.getBounds();
	else if (j === 1)
		document.getElementById("bound2").value = circle.getBounds();
	else if (j === 2)
		document.getElementById("bound3").value = circle.getBounds();
	}
	}
	
	}	
 
 
 
 //don't know why I can't get this to work :(
 function displayCircles() {
/* var range = $json;
 for (j = 0; j < range.filled; j++) {
	var curnum = j+1;
	var first = new google.maps.LatLng(range["range" + curnum + "lat1"], range["range" + curnum + "lng1"]);
	var second = new google.maps.LatLng(range["range" + curnum + "lat2"], range["range" + curnum + "lng2"]);
	var curbounds = new google.maps.LatLngBounds(first, second);
	var circcenter = curbounds.getCenter();
	var distance = google.maps.geometry.spherical.computeDistanceBetween(circcenter, first);
	var circleOptions = {
	center: circcenter,
	radius: distance,
	map: map, 
	editable: true
	};
	var circle = new google.maps.Circle(circleOptions);
	circlesArray.push(circle);
	findCircle(circle);
	google.maps.event.addListener(circle, 'radius_changed', function (event) {
	findCircle(circle);
	});
	google.maps.event.addListener(circle, 'center_changed', function (event) {
	findCircle(circle);
	});
	}
 */
 }
 
 
 
 //place circle, add listeners for circle events
 function placeCircle(location) {
 	if (circlesArray.length < 3) {
      var circleOptions = {
      	center: location,
      	radius: .5,
      	map: map,
      	editable: true
      };
	var circle = new google.maps.Circle(circleOptions);
	circlesArray.push(circle);
	findCircle(circle);
	google.maps.event.addListener(circle, 'radius_changed', function (event) {
	findCircle(circle);
	});
	google.maps.event.addListener(circle, 'center_changed', function (event) {
	findCircle(circle);
	});

	} else {
		alert('You can only create three boundaries!');
	}

  }
      
      $(document).ready(function(){
      	initialize();
	});
	
	function dumbcheck() {
	if (!($("#bound1").val())) {
		alert("Select at least one range");
		return false;
	} else {
		return true;
	}
	}
		
    </script>
  </head>
  <body>
<h3>How it works</h3><b>1.</b> Click on the map to create your range<br><b>2.</b> Adjust your range by clicking and dragging the marker that appears after clicking <br><b>3.</b> Submit<br><b>4.</b> We'll email you when an apartment in the range is submitted and you can view all of the apartments in the range on our site.<br> <b>You may select up to three locations.</b>
    <div id="map_canvas" style="width:950px; height:500px"></div>
    <form id = "interest" action = "interestformsub.php" method = "POST" onsubmit = "return dumbcheck();">
    <input id = "bound1" name = "bound1" style = "display: none">
    <input id = "bound2" name = "bound2" style = "display: none">
    <input id = "bound3" name = "bound3" style = "display: none">
    <input type = "text" name = "mail" style = "display: none" value = "$mail" id = "mail" />
   <input type = "text" id = "fbid" name = "fbid" style = "display: none" value = "$id" />
   <br />
  <center>  <input type = "submit" value = "Submit!"></center>

    
  </body>
</html>
END;
} else {
echo "You already set a location tracker. If you wish to reset it, please go to <a href = 'http://busublet.com/roominfo.php'>user settings</a>";
}
} else {
	 echo "<script> top.location.href = 'http://busublet.com/usersession.php?u=subletinterest.php' </script>";
}

include("include/footer.php");
echo $footer;


?>