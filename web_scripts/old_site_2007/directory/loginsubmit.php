<?php

include "common.php";

// get form data
$destination=$_REQUEST["destination"];
if ($destination=="")
  $destination="index.php";

$loginrow=verify_login($_REQUEST["newlogin"],hash_password($_REQUEST["newpassword"]));
if (!isset($loginrow))
{
  include "header.php";
  print "Login failed. <a href=login.php>Try again</a>";
  include "footer.php";
  die();
}

setcookie("login",$_REQUEST["newlogin"],time()+63072000);
setcookie("password",hash_password($_REQUEST["newpassword"]),time()+63072000);
header("Location: ".$destination);

?>

<html>
<head>
<title>Resident Login</title>
</head>

You have successfully logged in. Click <A href="<?php echo $destination ?>">here</A> to go to continue.
</P>

</html>

