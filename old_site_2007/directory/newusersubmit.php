<?php

require "common.php";

$password2=$_REQUEST["password2"];

$query= "SELECT * FROM residents WHERE login = '".$login."'";
$result= mysql_query($query);
if ($result<=0)
{
  print "an error occured with query ".$query;
  return;
}

$num_hits=mysql_num_rows($result);

if ($num_hits>0)
{
  print "that username has already been taken (".$num_hits."). Please select another.";
  return;
}

if ($password!=$password2)
{
  print "The two passwords you entered did not match. Please try again.";
  return;
}

if ($password=="")
{
  print "You didn't enter a password! Please try again.";
  return;
}

$query="INSERT INTO residents SET login='".$login."', password='".$password."', status='0', watchdog='1'";
$result=mysql_query($query);
if ($result<=0)
{
  print "an error ".$result." occured with query ".$query;
  return;
}

$query= "SELECT * FROM residents where login = '". $login . "'";

$result= mysql_query($query);
if ($result<=0)
	print "error occured performing query ".$query;

setcookie("login",$_REQUEST["login"],time()+63072000);
setcookie("password",hash_password($_REQUEST["password"]),time()+63072000);

mail("5west-computers@mit.edu","New 5W Resident Database Submission","Received a submission for ".$login);

header("Location: editpersonal.php");
?>
