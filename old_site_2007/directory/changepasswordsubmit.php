<?php
require "common.php";

$oldpassword=$_REQUEST["oldpassword"];
$newpassword1=$_REQUEST["newpassword1"];
$newpassword2=$_REQUEST["newpassword2"];

$loginrow=verify_login($login,$oldpassword);
var_dump($loginrow);
print $oldpassword;
if ($loginrow==null)
{
  include "header.php";
  print "<h2>That is not your current password.</h2>";
  include "footer.php";
  die();
}

if ($newpassword1!=$newpassword2)
{
  include "header.php";
  print "You must enter your new password twice, exactly the same. It is cAsE sensitive, by the way!";
  include "footer.php";
  die();
}
else
{
  $query="UPDATE residents SET password=\"".$newpassword1."\" WHERE login=\"".$login."\"";
  $result=mysql_query($query);

  if ($result<=0)
    {
      include "header.php";
      print "database request ".$query." failed!";
      include "footer.php";
      die();
    }
  else
    {
      // reset their cookies.
      setcookie("password",hash_password($_REQUEST["newpassword1"]),time()+63072000);
      
      include "header.php";
      print "<h2>Your password has been changed.</h2>";
    }
}

?>

<?php
include "footer.php";

?>

</P>
</html>

