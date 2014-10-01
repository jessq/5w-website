<?php
require "common.php";

include "header.php";

$name=$_REQUEST["name"];

$query="SELECT * from residents where name=\"".u2q($name)."\"";
$result=mysql_query($query);

$n_records=mysql_num_rows($result);

if ($n_records==0)
{
  print "I didn't find anyone with that name. Did you enter it EXACTLY as it appears in the database?";
  include "footer.php";
  die();
}

if ($n_records>1)
{
  print "A problem has arisen-- you seem to be in the database twice. You'll need to get an administrator to clear up this mess!";
  include "footer.php";
  die();
}

$row=mysql_fetch_array($result);

$to=$row["email"];
$subject="5West Residents Database login/password reminder";
$message="Your login information for the 5West resident database is:\n\nlogin: "
.$row["login"]."\npassword: ".$row["password"]
."\n\nIf you did not request this information, it means that someone else did. However, ONLY YOU have received this information.";

if (!mail($to,$subject,$message,""))
{
  print "The email failed for some reason. Please contact the administrators!";
}
else
{
  print "The email should be on its way to ".$to.". Depending on how slow the 'net is being today, it should arrive in the next few minutes.";
}

include "footer.php";

?>
