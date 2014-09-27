<?php
require "common.php";

// don't require login!
//$result=verify_login($login,$password);

include "header.php";

?>

<P>We will email your username and password to the email account listed in your database entry. If that email address no longer works, you'll need to contact one of the <A HREF="mailto:5west-computers@mit.edu">administrators</A>. They'll have to make a manual edit on the database, so give them some time to get back to you!</P>

<form method="post" action="lostpasswordsubmit.php">
<P>Your name, as it appears in the database: <input type="text" name="name" size=40></P>
<input type="submit" value="Generate reminder email">
</form>

<?php
include "footer.php";
?>
