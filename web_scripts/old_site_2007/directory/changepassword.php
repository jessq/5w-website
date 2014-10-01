<?php
require "common.php";

$loginrow=verify_login($login,$password);

include "header.php";
?>
<title>Change Password</title>

<form method="post" action="changepasswordsubmit.php">
<table>
<tr><td>Your current password: </td><td><input type="password" name="oldpassword" size=40></td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td>New password: </td><td><input type="password" name="newpassword1" size=40></td></tr>
<tr><td>New password again: </td><td><input type="password" name="newpassword2" size=40></td></tr>
</table>
<P>
<input type="submit" value="Change Password"></P>

<?php
include "footer.php";

?>

</P>
</html>
