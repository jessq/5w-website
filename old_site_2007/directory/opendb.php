<?php

$conn=mysql_pconnect("fw.mit.edu","php","resdb15");

if ($conn<=0) 
{
  print "an error occured connecting to localhost: " . $conn;
  die();
}
   
$result= mysql_select_db("residents");
if ($result<=0) {
  print "an error occured selecting database 'residents'\n".$result;
  die();
}

?>
