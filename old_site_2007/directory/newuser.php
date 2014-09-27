<?php
include("common.php");

if ($login!="" || $password!="")
{
  include "header.php";
  print "<h2>You have already registered, you cannot register twice.</h2>";
  include "footer.php";
  return;
}

include "header.php";
?>
<title>New User</title>

<B>Choose a username and password (<I>NOT your Athena password!</I>):</B><BR>
<form method="post" action="newusersubmit.php">
<table>
<Tr><td>username:</td><td> <INPUT type="text" name="login"></td></tr>
<Tr><td>password:</td><td> <input type="password" name="password"></td></tr>
<Tr><td>password again:</td><td> <input type="password" name="password2"></td></tr>
</Table>

<P><B>NOTE:</B> Your information will not be visible on the main database until it has been verified by an administrator. They will be notified automatically of your submission.</P>

<input value="  Next  " type="submit">
</form>

<?php

include "footer.php";

?>

</html>
