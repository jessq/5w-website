<?php
require "common.php";

$loginrow=verify_login($login,$password);
require_administrator_privileges($loginrow);

$status=getfield($results,"status");

if ($status==2)
{
  include "header.php";

  print "You cannot watchdog an administrator.";
  include "footer.php";
  return;
}
	
$query= "UPDATE residents SET watchdog=1 WHERE id=".$id;
$results= mysql_query($query);

if ($results<=0)
{
  print "an error occured with query ".$query;
  return;
}

header("Location: admin.php");

?>

</html>
