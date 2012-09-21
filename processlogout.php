<?php
session_start();


      if(isset($_COOKIE['cookid']) && isset($_COOKIE['cookstatus'])){
         setcookie("cookid", "", time()-(60*60*24*100), "/");
         setcookie("cookstatus",   "", time()-(60*60*24*100), "/");
         // setcookie("cookname", "", time()-(60*60*24*100), "/");
        // setcookie("cookmail",   "", time()-(60*60*24*100), "/");
      }

$_SESSION['logged_in'] = false;
unset($_SESSION['fullname']);
unset($_SESSION['useremail']);
unset($_SESSION['userfacebookid']);

header("Location: index.php");


?>