<?php
require "common.php";

$id=intval($_REQUEST["id"]);

$loginrow=verify_login($login,$password);
require_administrator_privileges($loginrow);

$query= "UPDATE residents SET status=2 WHERE id=".$id;
$results= mysql_query($query);

if ($results<=0)
{
  print "an error occured with query ".$query;
  return;
}

header("Location: admin.php");

?>

</html>
