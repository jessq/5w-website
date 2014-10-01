<?php
require "common.php";

include "header.php";

?>

<html>
<head>
<title>Detailed User Information</title>
</head>

<?php

$loginrow=verify_login($login,$password);
if (isset($loginrrow))
{
  $privacyclearance=1;
}
else
{
  $privacyclearance=0;
}

$id=$_REQUEST["id"];

$query= "SELECT * FROM residents where id = '". $id . "'";

$result= mysql_query($query);
if ($result<=0)
	print "error occured performing query ".$query;

$row=mysql_fetch_array($result);

// don't let random people see info for people by guessing their id #
if ($privacyclearance<$row["privacylevel"] && 
    !($privacyclearance==1 && $login==$row["login"]))
{
  print "You do not have permission to access this page.";
  die();
}

?>
<STYLE type="text/css">
 TD.navbar {text-align: left; background-color: #000050; border:0; padding: 4; spacing: 0; margin :0; font-size: 20pt; color: #ffffff }
</STYLE>

<TABLE width=100%><TR><TD class=navbar>&nbsp;<?php echo t2h($row["name"]) ?></TD></TR></TABLE>

<table width=100%>
<tr>
<td valign=top>
<table>
<tr><td><B>Email:</B> </td><td><?php echo formatemail($row["email"]) ?></TD></TR>
<tr><td><B>Year: </B></td><td><?php echo t2h($row["year"]) ?></TD></TR>
<tr><td><B>Room: </B></td><td><?php echo t2h($row["room"]) ?></TD></TR>
<tr><td><B>Major: </B></td><td><?php echo t2h($row["major"]) ?></TD></TR>
<tr><td><B>Hometown: </B></td><td><?php echo t2h($row["hometown"]) ?></TD></TR>
<tr><td><B>Birthday: </B></td><td><?php echo t2h($row["birthday"]) ?></TD></TR>
<tr><td><B>Role: </B></td><td><?php echo t2h($row["role"]) ?></TD></TR>
<tr><td><B>Homepage: </B> </td><td><?php echo formaturl($row["url"]) ?></TD></TR>

</table>
<P><B>Current mailing address: </B><BR>
<?php echo t2h($row["currentaddress"]) ?></P>

<P><B>Phone: </B><BR>
<?php echo t2h($row["phone"]) ?></P>

<P><B>Where are they now?<BR> </B>
<?php echo t2h($row["notes"]) ?></P>

</TD><TD valign=top>

<img src=
<?php $source=t2h($row["pictureurl"]);
if ($source=="") 
  $source="images/nopicture.gif"; 
print $source; 
?>
>
</Td></TR>

</table>

</P>

<?php
$editorid=$row["lastmodificationid"];

$query="select name from residents where id=".$editorid;
$editorresult=mysql_query($query);
$editorrow=mysql_fetch_array($editorresult);

if ($editorresult<=0)
  $editorname="(unknown)";
else
   $editorname=$editorrow["name"];

?>

<I><FONT SIZE=-1><P>This record last modified by <?php print $editorname ?> on <?php print strftime("%c",$row["lastmodificationtime"]) ?> 

<?php if (is_administrator($loginrow))
  print "from IP address ". $row["lastmodificationip"] .".</P></FONT></I> " ?>

<?php

include "footer.php";
?>







