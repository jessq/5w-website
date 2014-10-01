<?php
require "common.php";

$id=intval($_REQUEST["id"]);

$loginrow=verify_login($login,$password);
require_administrator_privileges($loginrow);

$adminsid=$loginrow["id"];

if ($adminsid==$id && $force!="yes")
{
  include "header.php";

  print "<P>Do you really want to unbless yourself? You will not be able to perform administrator functions any more!</P>";

  print "<TABLE width=100%><TR><TD align=center>";
  print "<A HREF=admin.php>No, never mind</A></TD><TD align=center> <A HREF=\"adminunbless.php?force=yes\">Yes, I can't take the pressue!</A></TD></TR></TABLE>";

  include "footer.php";
  return;
}

$query= "UPDATE residents SET status=1 WHERE id=".$id;
$results= mysql_query($query);

if ($results<=0)
{
  print "an error occured with query ".$query;
  return;
}

header("Location: admin.php");

?>

</html>
