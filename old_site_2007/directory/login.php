<html>
<head>
<title>Resident Login</title>
</head>
<?php

include "common.php";

include "header.php";

// get form data
$destination=$_REQUEST["destination"];
?>

<P>
<B>Login:</B><BR>
<form method="post" action="loginsubmit.php">
<table>
<tr><td>username:</Td><td><INPUT type="text" name="newlogin"></TD></TR>
<TR><TD>password:</TD><TD> <input type="password" name="newpassword"> <input value="Go!" type="submit"></TD></TR>
</TABLE>

<P><input type="hidden" name="destination" value="<?php echo $destination ?>">
</form>
</P>

</html>

<?php

include "footer.php";
?>
