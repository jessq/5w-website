<?php

include "common.php";

$loginrow=verify_login($login,$password);

$id=intval($_REQUEST["id"]);

// $id is a form variable passed in for who they want to edit, but it's optional!
if ($id<1)
{
  // no $id provided, thus they're editing themselves
  $id=$loginrow["id"];
}

// is an administrator editing?
if ($id!=$loginrow["id"])
{
  require_administrator_privileges($loginrow);
  $administrator_editing=1;
}
else
{
  $administrator_editing=0;
}

// now suck in the form data
$name=$_REQUEST["name"];
$room=$_REQUEST["room"];
$email=$_REQUEST["email"];
$birthday=$_REQUEST["birthday"];
$role=$_REQUEST["role"];
$major=$_REQUEST["major"];
$year=$_REQUEST["year"];
$url=$_REQUEST["url"];
$pictureurl=$_REQUEST["pictureurl"];
$currentaddress=$_REQUEST["currentaddress"];
$hometown=$_REQUEST["hometown"];
$phone=$_REQUEST["phone"];
$notes=$_REQUEST["notes"];
$currentresident=$_REQUEST["currentresident"];
$privacylevel=$_REQUEST["privacylevel"];


// now, on to the actual editing. Grab the old data.
$query= "SELECT * FROM residents where id = '". $id . "'";
$result= mysql_query($query) or die("query error: $query");

if (mysql_num_rows($result)!=1)
  die("Did not get exactly one row.");

$oldrow=mysql_fetch_array($result);

// validate their data
$error="";

// check email:
if (!ereg("^[^ ]+@[^ ].[^ ]",$email))
     $error=$error."<LI>Your email address appears to be malformed. It should look something like: myname@somewhere.com.";

if ($year<100)
     $error=$error."<LI>You should enter your year as a four digit number, e.g., 2001.";

$room=strtoupper($room);
if (!ereg("[WMH][0123456789]{3}[ABCDT]?",$room) && $room!="")
     $error=$error."<LI>Your room number should be of the form M509.";

if ($name=="")
     $error=$error."<LI>You did not enter your name!";

if (!ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$birthday) && $birthday!="")
     $error=$error."<LI>The date you entered for your birthday isn't in the form YYYY-MM-DD.";

// generate error message
if ($error!="")
{
  include "header.php";

  print "<P>There is a problem with your submission:</P>";

  print "<UL>";
  print $error;
  print "</UL>";

  print "You need to go back and correct these errors!";
  include "footer.php";
  return;
}

// print $currentresident . "  " . $privacymode;


// save these results before we perform the new query and lose the data we're aftr.
//$id=getfield($result,"id");
$watchdog=$oldrow["watchdog"];

$query="UPDATE residents SET "
  ."name='" .u2q($name)
  ."', room='" . u2q($room)
  ."', email='".u2q($email)
  ."', birthday='".u2q($birthday)
  ."', role='".u2q($role)
  ."', major='".u2q($major)
  ."', year='".u2q($year)
  ."', url='".u2q($url)
  ."', pictureurl='".u2q($pictureurl)
  ."', currentaddress='".u2q($currentaddress)
  ."', hometown='".u2q($hometown)
  ."', phone='".u2q($phone)
  ."', notes='".u2q($notes)
  ."', lastmodificationtime='".time()
  ."', lastmodificationip='".getenv("REMOTE_ADDR")
  ."', lastmodificationid='".$loginrow["id"]
  ."', currentresident='".u2q($currentresident)
  ."', privacylevel='".u2q($privacymode)
  ."', watchdog='".$watchdog
  ."' where id=".($id);

$result=mysql_query($query);
if ($result<=0)
  print "update failed for query ".$query;
else
{
  if ($watchdog==1)
    {
      generateemail("5W Resident database: WATCHDOG UPDATE","Update for ".$name." (login=".$login.")");
      
      if ($status!=2)
	{
	  // automatically unapprove their entry.
	  $query= "UPDATE residents SET status=0 WHERE id=".$id;
	  $results= mysql_query($query);
	}
    }

  if ($newuser==1)
    {
      generateemail("New 5W Resident Database Submission","Received a submission for ".$login);
    }

  if (($watchdog==1 || $newuser==1) && administrator_editing==0)
    {
      include "header.php";

      print "<P>Your entry will become visible once it is approved by an administrator. They have just been notified of your submission.</P>";

      print "<P>Click <A HREF=userdetails.php?id=".$id.">here</A> to review your record.</P>";

      print "<P>Thanks!</P>";

      include "footer.php";
      return;
    }

  header("Location: userdetails.php?id=".$id);
}

?>

</P>
</html>




