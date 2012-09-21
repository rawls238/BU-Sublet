<?php

/* Process interest form submission */
include("include/header.php");
echo $header;

$db = new mysqli(/*db information*/);
if($db->connect_error){
  die ('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}


$email = $db->real_escape_string($_POST['mail']);
$id = $db->real_escape_string($_POST['fbid']);

$entered;
$result = $db->query("SELECT `enteredinterest` FROM `subletusers` WHERE `facebookid` ='".$id."'");
while ($row = $result->fetch_assoc()) {
	$entered = $row['enteredinterest'];
}

$loc1 = $db->real_escape_string($_POST['bound1']);
$loc2 = $db->real_escape_string($_POST['bound2']);
$loc3 = $db->real_escape_string($_POST['bound3']);
$loc = array (
	"0" => $loc1,
	"1" => $loc2,
	"2" => $loc3,
	);
$locarray;
$fill = 0; //number of circles filled

//all this is doing is trimming the boundaries to its lat and long coordinates
for ($i = 0; $i < count($loc); $i++) {
if($loc[$i] !== "") {
$temp = explode("(", $loc[$i]);
$first = explode(",", $temp[2]);
$firstlat = $first[0];
$firstlong = trim($first[1], ')');
$firstlng = trim($firstlong, ' ');
$second = explode(",", $temp[3]);
$secondlat = $second[0];
$secondlong = trim($second[1], ')');
$secondlng = trim($secondlong, ' ');
$locarray[$i]["firstlat"] = $firstlat;
$locarray[$i]["firstlng"] = $firstlng;
$locarray[$i]["secondlat"] = $secondlat;
$locarray[$i]["secondlng"] = $secondlng;
$fill++;
}
}
//if ($update) {
/*if ($fill === 1)
$db->query("UPDATE `subletinterest` SET `filled` = '".$fill."', `range1lat1` = '".$locarray[0]["firstlat"]."', `range1lng1` = '".$locarray[0]["firstlng"]."', `range1lat2` = '".$locarray[0]["secondlat"]."', `range1lng2` = '".$locarray[0]["secondlng"]."'  WHERE `fbid` = '".$id."'");
else if ($fill === 2) {
$db->query("UPDATE `subletinterest` SET `filled` = '".$fill."', `range1lat1` = '".$locarray[0]["firstlat"]."', `range1lng1` = '".$locarray[0]["firstlng"]."', `range1lat2` = '".$locarray[0]["secondlat"]."', `range1lng2` = '".$locarray[0]["secondlng"]."', `range2lat1` = '".$locarray[1]["firstlat"]."', `range2lng1` = '".$locarray[1]["firstlng"]."', `range2lat2` = '".$locarray[1]["secondlat"]."', `range2lng2` = '".$locarray[1]["secondlng"]."'  WHERE `fbid` = '".$id."'");
}else*/
//$db->query("UPDATE `subletinterest` SET `filled` = '".$fill."', `range1lat1` = '".$locarray[0]["firstlat"]."', `range1lng1` = '".$locarray[0]["firstlng"]."', `range1lat2` = '".$locarray[0]["secondlat"]."', `range1lng2` = '".$locarray[0]["secondlng"]."', `range2lat1` = '".$locarray[1]["firstlat"]."', `range2lng1` = '".$locarray[1]["firstlng"]."', `range2lat2` = '".$locarray[1]["secondlat"]."', `range2lng2` = '".$locarray[1]["secondlng"]."', `range3lat1` = '".$locarray[2]["firstlat"]."', `range3lng1` = '".$locarray[2]["firstlng"]."', `range3lat2` = '".$locarray[2]["secondlat"]."', '".$locarray[2]["secondlng"]."'  WHERE `fbid` = '".$id."'");
//$db->query("DELETE FROM `subletinterest` WHERE `facebookid` = '".$id."'");
//} else { 

if (!$entered && $id != 0) {
$db->query("INSERT INTO subletinterest (`fbid`, `email`, `filled`, `range1lat1`, `range1lng1`, `range1lat2`, `range1lng2`, `range2lat1`, `range2lng1`, `range2lat2`, `range2lng2`, `range3lat1`, `range3lng1`, `range3lat2`, `range3lng2`) VALUES ('".$id."', '".$email."', '".$fill."', '".$locarray[0]["firstlat"]."', '".$locarray[0]["firstlng"]."', '".$locarray[0]["secondlat"]."', '".$locarray[0]["secondlng"]."', '".$locarray[1]["firstlat"]."', '".$locarray[1]["firstlng"]."', '".$locarray[1]["secondlat"]."', '".$locarray[1]["secondlng"]."', '".$locarray[2]["firstlat"]."', '".$locarray[2]["firstlng"]."', '".$locarray[2]["secondlat"]."', '".$locarray[2]["secondlng"]."')");
$db->query("UPDATE `subletusers` SET `enteredinterest` = 1 WHERE `facebookid` = '".$id."'"); 

//$db->query("INSERT INTO subletinterest (`locrange1`, `locrange2`, `locrange3`) VALUES ('".$loc1."', '".$loc2."', '".$loc3."')");
//$tableform = " (id int, address varchar(50), price int(11), startdate varchar(15), enddate varchar(15), email varchar(50))";
//$db->query("INSERT INTO subletinterest (`locrange1`, `locrange2`, `locrange3`) VALUES ('".$loc1."', '".$loc2."', '".$loc3."')");
//$db->query("CREATE TABLE ".$tableid.$tableform);

$locrange = json_encode($locarray);
$result = $db->query("SELECT `address` FROM sublet");
$available;
$i = 0;
while ($row = $result->fetch_assoc()) {
	$available[$i++] = $row;
}
$length = $i;

$json = json_encode($available); 


echo "Thanks for submitting a location tracker! You'll now be receiving emails from us every time a new apartment in your tracking zone is added to our database. <br><br><b>You can view the compilation of all the matches for you <a href = 'sublettrans.php'>here</a></b>";

} else {

echo "You've already submitted a location tracker!";
}
include("include/footer.php");
echo $footer;

?>
