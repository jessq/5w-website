<?php
require "common.php";

// don't require login yet!
//$result=verify_login($login,$password);

include "header.php";

?>

<P>Do you really want to delete the cookies created by this website? Without them, you will have to log back into the system with username and password next time you want to access it.</P>

<table width=100%>
<tr><td align=center><A href="advancedmenu.php">Never mind</A>
</td><td align=center>
<A href="deletecookiessubmit.php">Nuke 'em!</A>
</td></tr>
</table>
<?php
include "footer.php";
?>
