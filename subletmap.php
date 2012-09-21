<?php
session_start();

/* Display listings to user */

include("include/header.php");
echo $header;

if ($_COOKIE['cookstatus'] === true || $_SESSION['logged_in'] == true) {  
  
$db = new mysqli(/*db information*/););
if($db->connect_error){
  die ('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}

$gender;
$id;

if (isset($_SESSION['useremail']) && isset($_SESSION['gender'])) {
	$id = $_SESSION['userfacebookid'];
	$gender = $_SESSION['gender'];
} else {
	$id = $_COOKIE['cookid'];
	$userinfo = $db->query("SELECT `gender`, `email` FROM subletusers WHERE `facebookid` = '".$id."'");
	while ($col = $userinfo->fetch_assoc()) {
		$gender = $col['gender'];
	}
}



function cmp($a, $b) {
if ($a["price"] > $b["price"])
	return $a;
else 
	return $b;
}

function cmp2($a, $b) {
if ($a["bednum"] > $b["bednum"])
	return $a;
else 
	return $b;
}

function MaxMin($arr, $param, $type, $leng) {
$arr = bubble_sort($arr, $type);
if (count($arr) === 0)
	return 0;
if ($type === "price") {
if ($param === "min") {
	if ($arr[0]["price"] != null) 
		return $arr[0]["price"];
	else if ($arr[0]["price"] < 0)
		return 0;
	else
		return 0;
} else {
	if ($arr[$leng-1]["price"] != null) 
		return $arr[$leng-1]["price"];
	else
		return 3000;
}
} else {
if ($param === "min") 
	return $arr[0]["bednum"];
else 
	return $arr[$leng-1]["bednum"];
	}
}

function bubble_sort($arr, $param) {
    $size = count($arr);
    for ($i=0; $i<$size; $i++) {
        for ($j=0; $j<$size-1-$i; $j++) {
            if (($arr[$j+1][$param] < $arr[$j][$param])) {
            $tmp = $arr[$j];
    		$arr[$j] = $arr[$j+1];
    		$arr[$j+1] = $tmp;
            }
        }
    }
    return $arr;
}

$result = $db->query("SELECT `address`, `bedroom`, `bath`, `bednum`, `price`, `start`, `end`, `fbid`, `email` FROM sublet WHERE `gender` = '".$gender."' OR `gender` = 'either'");
$i= 0;
$apts;
if ($result) {
while ($row = $result->fetch_assoc()) {
	$apts[$i++] = $row;
}
}
$length = $i;
/*$j = 0;
$duplicates = $db->query("SELECT `address` FROM `sublet` GROUP BY `address` HAVING  (COUNT(`address`) > 1)");
while ($dup = $duplicates->fetch_assoc()) {
	$duplicate[$j++] = $dup['address'];
}
$jlength = $j;*/
$maxprice = MaxMin($apts, "max", "price", $length);
$minprice = MaxMin($apts, "min", "price", $length);
$minbed = MaxMin($apts, "min", "bednum", $length);
$maxbed = MaxMin($apts, "max", "bednum", $length);
$json = json_encode($apts);
//$dups = json_encode($duplicate);





/*echo '<script type = "text/javscript">';
echo 'var address = $address';
echo '</script>';*/
echo <<<END
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #bedroom { margin: 10px; width: 200px }
      #price { margin: 10px; width: 250px}
      #map_canvas { }
    </style>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>
    <script src="http://connect.facebook.net/en_US/all.js"></script>
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
      <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type = "text/javascript">

var map;
var geocoder;
var markersArray= new Array();
var objectsArray = new Array();
var infowindow = new google.maps.InfoWindow({});
var directionsService = new google.maps.DirectionsService();
var service;

$(document).ready(function() {

/* Start Google Maps Code */


initialize();
initializeObjectsArray();


function initialize() {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(42.3525973, -71.1106078);
    var myOptions = {
      zoom: 14,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
  }
  
  
 function hasDuplicates(cur) {
 var curlength = objectsArray.length;
 var curpos = cur.position;
for (var i = 0; i < curlength; i++) {
	if (curpos.equals(objectsArray[i].position)) {
			objectsArray[i].hasDuplicates = true;	
		return true;
	}
}
return false;
} 


function findAllDuplicates(cur) {
 var curlength = objectsArray.length;
 var curpos = cur.position;
 var content = cur.content;
for (var i = 0; i < curlength; i++) {
	if (curpos.equals(objectsArray[i].position)) {
		objectsArray[i].duplicates.push(cur);
		cur.duplicates.push(objectsArray[i]);
		content = content + "<br><br>" + objectsArray[i].content;
	}
}
return content;
} 

  

function initializeObjectsArray () {
		var json = $json;
		for (i = 0; i < $length; i++) {
		var cur = new Object();
		cur.address = json[i].address;
  		cur.price = json[i].price;
  		cur.bath = json[i].bath;
  		cur.email = json[i].email;
  		var email = "mailto:" + cur.email;
  		cur.bedroom = json[i].bedroom;
  		cur.bednum = json[i].bednum;
  		cur.start = json[i].start;
  		cur.end = json[i].end;
  		cur.fbid = json[i].fbid;
  		cur.show = true;
  		var fbdialogurl ="https://www.facebook.com/dialog/send?app_id=[app_id]&name=BUSublet&link=http://busublet.com&redirect_uri=http://busublet.com/subletmap.php&to=" +cur.fbid;
  		cur.content =  cur.address + '<br><b>' + cur.bedroom + '</b>, ' + cur.bath + '<br><b>Rent:</b> $' + cur.price + '/month<br><b>From </b>' + cur.start + '<b> to</b> ' + cur.end + '<br><a href='+fbdialogurl+'>Send Message</a><br><a href ='+email+'>Email this Person</a>';	
		var BC = new google.maps.LatLng(42.3390925, -71.1812979); //west bounds set to BC
    	var East = new google.maps.LatLng(42.3655468, -71.0290155); //east bounds set to right outside of Logan
    	var bounds = new google.maps.LatLngBounds(BC, East);
    	 (function(curobject) { geocoder.geocode( { 'address': curobject.address, 'bounds': bounds }, function(results, status) {
      		if (status == google.maps.GeocoderStatus.OK) {
      		
        var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
        });
        curobject.position = results[0].geometry.location;
        curobject.marker = marker;
        curobject.hasDuplicates = hasDuplicates(curobject);
        curobject.duplicates = new Array();
        if (curobject.hasDuplicates) {
        	curobject.dupcontent = findAllDuplicates(curobject);
        	makeInfoWindow(curobject.marker, curobject.dupcontent);
        } else {
        makeInfoWindow(curobject.marker, curobject.content);
        }
        objectsArray.push(curobject);
	}
	  });
	  })(cur);
	}
}



function makeInfoWindow(marker, content){ 
  google.maps.event.addListener(marker, 'click', function () { 
    infowindow.setContent(content);
    infowindow.open(map, marker); 
  }); 
} 




    	


   
   /* End Google Maps Code */
   /* Start filter function code */
var minbed = $minbed;
var maxbed = $maxbed;
var minprice = $minprice;
var tempminp = minprice;
var maxprice = $maxprice;
var tempmaxp = maxprice;
    $("#bedroom").slider( { 
    min: minbed, 
    max: maxbed,
    values: [ minbed, maxbed ],
    step: 1, 
    range: true,
    change: function( event, ui ) {
    		if (ui.values[0] !== null) {
				$("#minbed").val(ui.values[ 0 ]);
			} else if (ui.values[0] < minbed)
				$("#minbed").val(minbed);
			if (ui.values[1] != null) {
				$("#maxbed").val(ui.values [1 ]);
			} else if (ui.values[1] > maxbed)
				$("#maxbed").val(maxbed);
			showAndTell();
			}
    });

    $("#price").slider({ 
    min: minprice, 
    max: maxprice,
    values: [ minprice, maxprice ], 
    step: 5, 
    range: true,
    change: function( event, ui ) {
    		if (ui.values[0] !== null) {
				$("#minprice").val(ui.values[ 0 ]);
			} else if (ui.values[0] < minprice)
				$("#minprice").val(minprice);
			if (ui.values[1] != null) {
				$("#maxprice").val(ui.values [1 ]);
			} else if (ui.values[1] > maxprice)
				$("#maxprice").val(maxprice);
			showAndTell();
			}

	});


 	//$("#startsearch").datepicker();
 	//$("#endsearch").datepicker();
    $("#startsearch").datepicker({ 
    minDate: 0,
    onSelect: function( selectedDate ) {
				$( "#endsearch" ).datepicker( "option", "minDate", selectedDate );
				showAndTell();
				}
	});
		
    $("#endsearch").datepicker({
    minDate: 0,
    onSelect: function( selectedDate ) {
				$( "#startsearch" ).datepicker( "option", "maxDate", selectedDate );
				showAndTell();
			}
		});
			});
			
    /* End Filter Code */
    

function makeInfoWindows(marker, content){ 
  google.maps.event.addListener(marker, 'click', function () { 
    infowindow.setContent(content);
    infowindow.open(map, marker); 
  }); 
} 

function showAndTell () {
	var minp = Number($("#minprice").val());
	var maxp = Number($("#maxprice").val());
	$("#pricerange").html("&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>Price Range:</b> $"+ minp + " to $" + maxp);
	var minb = Number($("#minbed").val());	
	var maxb = Number($("#maxbed").val());
	$("#bedrange").html("&nbsp&nbsp&nbsp&nbsp<b>Number of Bedrooms:</b>  " + minb + " to " + maxb);
	var start = $("#startsearch").val();
	var end = $("#endsearch").val();
	//var exactdate = $("exactdate:checked").val();
	//var exactmonth = $("exactmonth:checked").val();
	var length = objectsArray.length;
	var pricebool = false;
	var bedbool = false;
	var datebool = false;
	var curcon = "";
	var current_content = "";
	var curplace = $("#place").val();
	var curtime = $("#time").val();
	
	
    	for (i = 0; i < length; i++) {
		price = Number(objectsArray[i].price);
		pricebool = false;
		
			if (!curplace || !curtime)
				objectsArray[i].show = true;
		
		if (price >= minp && price <= maxp)
			pricebool = true;
		
		bednum = objectsArray[i].bednum;
		bedbool = false;
		if (bednum >= minb && bednum <= maxb) 
			bedbool = true;
			
		
		begin = objectsArray[i].start;
		stop = objectsArray[i].end;
		datebool = true;

		if (start !== "" || end !== "")
		datebool = datecmp (start, end, begin, stop);
		
		
		if (objectsArray[i].hasDuplicates) {
		  current_content = "";
		  curcon = "";
			if (bedbool && pricebool && datebool)
				curcon = objectsArray[i].content + "<br><br>";
			current_content = duplicateMatches(objectsArray[i], minp, maxp, minb, maxb, start, end, curcon);
			if (infowindow)
				infowindow.close();
			if (current_content == "" || !(objectsArray[i].show)) {
				objectsArray[i].marker.setVisible(false);
			 } else {
				makeInfoWindows(objectsArray[i].marker, current_content);
				objectsArray[i].marker.setVisible(true);
			}
		} else {


    	if (bedbool && pricebool && datebool && objectsArray[i].show) {
    		objectsArray[i].marker.setVisible(true);
    	} else {
    		if(infowindow)
    			infowindow.close();
    		objectsArray[i].marker.setVisible(false);
    	}
    	}
    }
}

function duplicateMatches(obj, minprice, maxprice, minbed, maxbed, start_date, end_date, curcontent) {
	var curlength = obj.duplicates.length;
	var curpos = obj.position;
	var con = curcontent;
for (var i = 0; i < curlength; i++) {
		if (obj.duplicates[i].price >= minprice && obj.duplicates[i].price <= maxprice) {
			if (obj.duplicates[i].bednum >= minbed && obj.duplicates[i].bednum <= maxbed) {
				if (start_date == "" && end_date == "") {
					con += obj.duplicates[i].content + "<br><br>";
				} else if (datecmp(start_date, end_date, objectsArray[i].start, objectsArray[i].end)) {
					con += obj.duplicates[i].content + "<br><br>";
				}
			}
	}

}
return con;
}

function datecmp (searchstart, searchend, begin, stop) {
if (searchstart !== "") {
	var startarr = searchstart.split('/');
	var searchstartmonth = parseInt(startarr[0], 10);
	var searchstartday = parseInt(startarr[1], 10);
	var searchstartyear = parseInt(startarr[2], 10);
	}
	if (searchend !== "") {
	var endarr = searchend.split('/');
	var searchendmonth = parseInt(endarr[0], 10);
	var searchendday = parseInt(endarr[1], 10);
	var searchendyear = parseInt(endarr[2], 10);
	}
	
	var beginarr = begin.split('/');
		var beginmonth = parseInt(beginarr[0], 10);
		var beginday = parseInt(beginarr[1], 10);
		var beginyear = parseInt(beginarr[2], 10);
		
		var stoparr = stop.split('/');
		var stopmonth = parseInt(stoparr[0], 10);
		var stopday = parseInt(stoparr[1], 10);
		var stopyear = parseInt(stoparr[2], 10);
		
		var beginbool = false;
		var endbool = false;
	
		
	if (beginyear === searchstartyear) {
		if (beginmonth > searchstartmonth) {
			beginbool = true;
		} else if (beginmonth === searchstartmonth) {
			if (beginday >= searchstartday)
				beginbool = true;
		}
	} else if (searchstartyear < beginyear) 
		beginbool = true;
		
	if (searchendyear === stopyear) {
		if (searchendmonth < stopmonth)
			endbool = true;
		else if (stopmonth === searchendmonth) {
			if (stopday >= searchendday)
				endbool = true;
			}
		} else if (stopyear < searchendyear) {
			endbool = true;	
		}
		
	if (searchend !== "" && searchstart !== "") {
		if (beginbool && endbool)
			return true;
	} else if (searchend === "" && searchstart !== "") {
		if (beginbool)
			return true;
	} else if (searchend !== "" && searchstart === "") {
		if (endbool)
			return true;
	} else {
		return false;
	}
	}



	function places() {
		var query = $("#place").val();
		var temp = $("#time").val();
		if (!query || !temp) {
			alert("If you're going to use the place search, you fill in a query AND a walking time");
		 } else if (isNaN(temp)) {
		 	alert("You can only input numbers for walking time!");
		 } else {
			
		var request;
		for (i = 0; i < objectsArray.length; i++) {
		request = {
    		location: objectsArray[i].position,
    		rankBy: google.maps.places.RankBy.DISTANCE,
    		radius: '500',
    		// types: ['bus_station']
    		query: query
  		};
  		 
  		 service = new google.maps.places.PlacesService(map);
  		 
  	(function(cur) { service.textSearch(request, function(response, status) {
  		
 	 if (status == google.maps.places.PlacesServiceStatus.OK) {
 	 $("#placeresult").html(response[0].name);
  	var loc = response[0].geometry.location;
  	var directions = {
   			origin:objectsArray[cur].position,
    		destination: loc,
    		travelMode: google.maps.TravelMode.WALKING
  		};
  		
  	(function(cur) { directionsService.route(directions, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
		var legs= response.routes[0].legs;
		var time = 0;
		var maxtime = $("#time").val()*60;
	for(var j=0; j<legs.length; j++) {
        time += legs[j].duration.value;
    }
    	if (time < maxtime)  {
    		objectsArray[cur].show = true;
    	} else {
    	objectsArray[cur].show = false;
    	}
    showAndTell();

	}
});
})(cur);


} else {
alert(status);
}
});
})(i);
}
}
}





  
   
  </script>
  </head>
  <body>
   <div id = "bedroomamount" style = "float: left"><div id = "bedrange" style = "margin: 0">&nbsp&nbsp&nbsp&nbsp<b>Number of Bedrooms:</b> &nbsp&nbsp$minbed to $maxbed </div><input size = "2" style = "display: none" id = "minbed" name = "minbed" value = "$minbed"/><input style = "display: none" id = "maxbed" value = "$maxbed" size = "2"/></div>

  <div id = "priceamount" style = "float: left"><div id = "pricerange" style = "margin: 0">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>Price Range:</b> $$minprice to $$maxprice</div><input size = "5" style = "display: none" id = "minprice" value = "$minprice"><input size = "5" id = "maxprice" style = "display: none" value = "$maxprice"></div>
  <br><div id = "bedroom" style = "float: left"></div>
  <div id = "price" style = "float: left"></div>
&nbsp&nbsp<b>Start:</b> <input id = "startsearch" name = "startsearch" id = "startsearch"  style = "text-align: center"/> &nbsp&nbsp
 <b>End: </b> <input id = "endsearch" name = "endsearch" id = "endsearch" style = "text-align: center" /><br>
  <br>
 <div id="map_canvas" style="width:950px; height:500px"></div>
<br><center><b>Use this to find apartments that are within walking distance of the things you find essential in life.</b></center> 
 <br>
<center><b>Search for...&nbsp&nbsp&nbsp&nbsp&nbsp  </b><input type = "text" id = "place" name = "place">&nbsp&nbsp&nbspwithin&nbsp&nbsp&nbsp   <input type = "text" id = "time" name = "time"> minutes walking&nbsp&nbsp&nbsp&nbsp&nbsp<input type = "button" value = "Filter" onclick = "places();"></center>
<center><b>Suggested Queries (clickable):&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</b><span onclick = "place.value = '915 Commonwealth Avenue 02215'; time.value = 15; places();">Fit Rec</span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<span onclick = "place.value = '775 Commonwealth Avenue 02215'; time.value = 15; places();">GSU / Mugar Library&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span><span onclick = "place.value = '725 Commonwealth Avenue 02215';time.value = 15; places();">College of Arts and Sciences (CAS)&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span><span onclick = "place.value = 'Bus Stations'; time.value = 15; places();">Bus Stations&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span><span onclick = "place.value = 'Fenway Park'; time.value = 15; places();">Fenway Park&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span></center>
<br><center>Note: T subway stops aren't provided by Google and thus you can't filter via them. If you think this should be changed, email <a href = 'mail:busublet@gmail.com'>busublet@gmail.com</a></center></body>
 </html>
END;


} else {
	 echo "<script> top.location.href = 'http://busublet.com/usersession.php?u=subletmap.php' </script>";
}
include("include/footer.php");
echo $footer;
?>