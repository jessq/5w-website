<?php

function doselect($name, $options, $selected, $otherdata)
{
  print "<select name=\"".$name."\" ".$otherdata.">";
  $size=sizeof($options);
  
  reset($options);
  while (list($value,$name)=each($options))
  {
    print "<option value=\"".$value."\"";
    if ($value==$selected)
      print " selected";
    print ">".$name."</option>";
  }
  print "</select>";
}

function displayresults($result)
{
  print "<table border=1 cellpadding=2 cellspacing=0 width=100%>";
  print "<tr>";
  
  $n_records=mysql_num_rows($result);
  $n_columns=mysql_num_fields($result);
  for ($i=0;$i<$n_columns;$i++)
    {
      $field_name = mysql_field_name($result,$i);

      if ($field_name=="id")
	continue;

      print "<td><b>". $field_name . "</b>&nbsp;</td>";
    }
  print "</tr>";
  
  while ($row=mysql_fetch_array($result))
    {
      $id=$row["id"];

      print"<tr>";
      for ($i=0;$i<$n_columns;$i++) 
	{
	  $field_name = mysql_field_name($result,$i);

	  if ($field_name=="id")
	    continue;

	  $data=$row[$field_name];

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
		print "&nbsp;";
	      else
		print "<a href=\"userdetails.php?id=".urlencode($id)."\">".$data."</A>";
	    }
	  else
	    print $data."&nbsp";

	  print "</td>";
	}
      print "</tr>";
    }
  print "</table>";
}

// make hashed password. a function of IP address so other users can't (trivially)
// sniff the wire and log in. They'd have to do IP spoofing too.
function hash_password($password)
{
  $clientip=getenv("REMOTE_ADDR");

  return md5($clientip.$password);
}

// returns the row in the database for the user, or null if it doesn't exist.
// (i.e., login incorrect)
// $login and $password should be the COOKIES stored on the client, not the
// actually password.
function verify_login($login, $password)
{
  $query= "SELECT * FROM residents where login = '".u2q($login)."'";

  $result= mysql_query($query) or die("unable to check login information");
  
  if (mysql_num_rows($result)!=1)
    {
      return null;
    }

  // check the password
  $row=mysql_fetch_array($result);

  if (hash_password($row["password"])==$password)
    return $row;

  // password is wrong!
  return null;
}

function invalidlogin()
{
  header("Location: login.php?destination=".getenv("SCRIPT_NAME"));
  die();
}

function is_administrator($loginrow)
{
  if (!isset($loginrow))
    return false;

  return ($loginrow["status"]>=2);
}

function require_administrator_privileges($loginrow)
{
  if (is_administrator($loginrow))
    return;

  include "header.php";
  print "You must be an administrator to use this feature. (If you need to log in again, click <a href=login.php>here</a>.)";
  include "footer.php";

  die();
}

function t2h($in)
{
return (nl2br(htmlentities($in)));
}

// convert user supplied data into mysql-safe query data
function u2q($in)
{
  return mysql_escape_string($in);
}

function generateemail($subject, $body)
{
return mail("goodevil@mit.edu",$subject,$body);
}

function formaturl($url)
{
  return "<a href='$url'>$url</a>";
}

function formatemail($email)
{
  return "<a href=mailto:$email>$email</a>";
}

?>
