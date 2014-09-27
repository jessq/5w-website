<?php
require "common.php";

$loginrow=verify_login($login,$password);
if (!isset($loginrow))
  invalidlogin();

if (isset($_REQUEST["id"]))
  $id=$_REQUEST["id"];

// $id is a form variable passed in for who they want to edit, but it's optional!
if (!isset($id) || $id<1)
{
  // no $id provided, thus they're editing themselves
  $id=$loginrow["id"];
}

// who are we editing? if they gave us an id, let's edit them. Otherwise, edit the user.
if ($id!=$loginrow["id"])
{
  require_administrator_privileges($loginrow);
  
  $query="SELECT * from residents where id=".$id;
  $result=mysql_query($query);
  $row=mysql_fetch_array($result);
}
else
{
  $id=$loginrow["id"];
  $row=$loginrow;
}

if ($id!=$loginrow["id"])
{
  require_administrator_privileges($loginrow);
}

function doyesnoselect($selectname,$currentvalue)
{
  print "<SELECT name='".$selectname."'>";
  if ($currentvalue==1)
    {
      print "<OPTION value=1 selected>Yes";
      print "<OPTION value=0>No";
    }
  else
    {
      print "<OPTION value=1>Yes";
      print "<OPTION value=0 selected>No";
    }
  print "</SELECT>";
}
?>

<title>Edit Personal Data</title>

<?php include "header.php" ?>

<P>
<B>Edit your information:</B><BR>
<form method="post" action="editpersonalsubmit.php?id=<?php echo $id ?>">
<input type="hidden" name="newuser" value=<?php if ($newuser!="") print $newuser; else print "0"; ?>>

<table width=100%>
<TR><TD>Name: </TD><TD> <input size=40 type="text" name="name" value="<?php echo t2h($row["name"]) ?>"></TD></TR>


<TR><TD COLSPAN=2><FONT SIZE=-1><I>Enter your full name, e.g., "Abraham Lincoln"</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

<TR><TD>Room on 5W:  </TD><TD><input size=5 type="text" name="room" value='<?php echo t2h($row["room"]) ?>'></TD></TR>
<TR><TD COLSPAN=2><FONT SIZE=-1><I>What room do/did you live in? If you know that your room was a size B, you can type M509B.</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

<TR><TD>Current Resident:</TD><TD> <?php doyesnoselect("currentresident",$row["currentresident"]) ?></TD></TR>
<TR><TD COLSPAN=2><FONT SIZE=-1><I>Do you currently live on the hall?</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

<TR><TD>Email address:  </TD><TD><input size=40 type="text" name="email" value='<?php echo t2h($row["email"]) ?>'></TD></TR>
<TR><TD COLSPAN=2><FONT SIZE=-1><I>Where can you be reached via email?</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

<TR><TD>Birthday:  </TD><TD><input size=10 type="text" name="birthday" value='<?php echo t2h($row["birthday"]) ?>'></TD></TR>
<TR><TD COLSPAN=2><FONT SIZE=-1><I>What's your birthday? (FORMAT: YYYY-MM-DD)</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

<TR><TD>Hometown:  </TD><TD><input size=40 type="text" name="hometown" value='<?php echo t2h($row["hometown"]) ?>'></TD></TR>
<TR><TD COLSPAN=2><FONT SIZE=-1><I>Where are you from? (e.g., "Minneapolis, MN")</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

<TR><TD>Role:  </TD><TD><input size=40 type="text" name="role" value='<?php echo t2h($row["role"]) ?>'></TD></TR>
<TR><TD COLSPAN=2><FONT SIZE=-1><I>If you hold an official position on the hall (like Hall Chair, or a Comm position), what is it? (Please don't make up a position.)</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

<TR><TD>Major(s):  </TD><TD><input type="text" name="major" value='<?php echo t2h($row["major"]) ?>'></TD></TR>
<TR><TD COLSPAN=2><FONT SIZE=-1><I>What major are you? (Please use arabic numerals, e.g., "6-2")</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

<TR><TD>Class Year:  </TD><TD><input size=4 type="text" name="year" value='<?php echo t2h($row["year"]) ?>'></TD></TR>
<TR><TD COLSPAN=2><FONT SIZE=-1><I>What year did/will you get your undergraduate degree? Use four digits, e.g., 2001</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

<TR><TD>Webpage URL:  </TD><TD><input size=60 type="text" name="url" value='<?php echo t2h($row["url"]) ?>'></TD></TR>
<TR><TD COLSPAN=2><FONT SIZE=-1><I>Enter the fully qualified (include the http:// junk) URL of your personal webpage.</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

<TR><TD>Picture URL:  </TD><TD><input size=60 type="text" name="pictureurl" value='<?php echo t2h($row["pictureurl"]) ?>'></TD></TR>
<TR><TD COLSPAN=2><FONT SIZE=-1><I>Enter the fully qualified URL of a current picture of yourself! It will be visible when people click on your name in the database.</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

<TR><TD VALIGN=TOP>Current Address:  </TD><TD><textarea rows=4 cols=40 name="currentaddress"><?php echo ($row["currentaddress"]) ?></textarea></TD></TR>
<TR><TD COLSPAN=2><FONT SIZE=-1><I>We might want to send you some mail. Where should we send it to? Hall residents, please tell us your box number!</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

<TR><TD>Phone Number:  </TD><TD><input size=40 type="text" name="phone" value='<?php echo t2h($row["phone"]) ?>'></TD></TR>
<TR><TD COLSPAN=2><FONT SIZE=-1><I>What's your phone number? Hall residents too, please!</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

<TR><TD VALIGN=TOP>Tell us about yourself </TD><TD><textarea rows=15 cols=60 name="notes"><?php echo ($row["notes"]) ?></textarea></TD></TR>
<TR><TD COLSPAN=2><FONT SIZE=-1><I>What are you up to? Where are you working? What's interesting and/or exciting? Did you paint one of the murals on the hall? Feel free to be long-winded!</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

<TR><TD>Super Private Mode:</TD><TD> <?php doyesnoselect("privacymode",$row["privacylevel"]) ?>
</TD></TR>
<TR><TD COLSPAN=2><FONT SIZE=-1><I>If you select this option, your information will only be visible to other registered members of the database.</I></FONT></TD></TR>
<TR><TD COLSPAN=2>&nbsp;</TD></TR>

</TABLE>
<input value="Submit" type="submit">
</form>

<?php
include "footer.php";
?>
