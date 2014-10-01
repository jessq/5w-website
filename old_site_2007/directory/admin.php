<?php
require "common.php";

$loginrow=verify_login($login,$password);
require_administrator_privileges($loginrow);

include "header.php";
?>

<html>
<head>
<title>Administration</title>
</head>

<form method=post action="adminsubmit.php">

<?php

$query= "SELECT name,login,email,role,id,status,watchdog FROM residents ORDER BY status, name";
$result= mysql_query($query);

if ($result<=0)
{
  print "an error occured with query ".$query;
  return;
}

print "<table border=1 cellpadding=2 cellspacing=0 width=100%>";
print "<tr><td><B>status</B></Td><TD><B>Watchdog</B></TD>";

$n_records=mysql_num_rows($result);
$n_columns=mysql_num_fields($result);

for ($i=0;$i<$n_columns;$i++)
{
  $field_name = mysql_field_name($result,$i);

  // don't display these fields.
  if ($field_name=="id" || $field_name=="status" || $field_name=="watchdog")
    continue;
  
  print "<td><b>". $field_name . "</b>&nbsp;</td>";
}
print "</tr>";


while ($row=mysql_fetch_array($result))
{
  print "<tr>";

  $id=$row["id"];
  $status=$row["status"];
  $watchdog=$row["watchdog"];

  print "<td>";
  if ($status==0)
    {
      print("<A HREF=\"adminapprove.php?id=".$id."\">approve</A> ");
      print("<A HREF=\"admindelete.php?id=".$id."\">delete</A> ");
    }

  if ($status==1)
    {
      print("<A HREF=\"adminbless.php?id=".$id."\">bless</A> ");
      print("<A HREF=\"adminunapprove.php?id=".$id."\">unapprove</A> ");
    }
  if ($status==2)
    print("<A HREF=\"adminunbless.php?id=".$id."\">unbless</A> ");

  print "</TD><TD>";

  if ($watchdog==0)
    {
      print("<A HREF=\"adminwatchdog.php?id=".$id."\">no</A> ");
    }
  else
    {
      print("<A HREF=\"adminunwatchdog.php?id=".$id."\">yes</A> ");
    }

  print "</TD>";

  for ($i=0;$i<$n_columns;$i++) 
    {
      $field_name=mysql_field_name($result,$i);
      $data=t2h($row[$field_name]);
      
      // don't display these fields.
      if ($field_name=="id" || $field_name=="status" || $field_name=="watchdog")
	continue;

      print "<td><font size=-1>";
      
      if ($field_name=="email")
	{	
	  print "<a href=mailto:" . $data . ">" . $data . "</A>";
	}	
      elseif ($field_name=="url" || $field_name=="pictureurl")
	{
	  if ($data=="")
	    print "&nbsp;";
	  else
	    print "<a href=\"".$data."\">URL</A>";
	}
      elseif ($field_name=="name")
	{
	  if ($data=="")
	    {
	      $data="(unknown)";
	    }
	  
	  print "<a href=\"editpersonal.php?id=".urlencode($id)."\">".$data."</A>";
	}
      elseif ($field_name=="lastmodificationtime")
	{
	  if ($data!="")
	    {
	      print strftime("%c",$data);
	    }
	  else
	    print "&nbsp;";

	}
      else
	print $data."&nbsp";
            
      print "</font></td>";
    }
  print "</tr>";
}
print "</table>";

include("footer.php");
?>
