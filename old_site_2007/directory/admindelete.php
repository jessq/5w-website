<?php
require "common.php";

$id=intval($_REQUEST["id"]);

$loginrow=verify_login($login,$password);
require_administrator_privileges($loginrow);

include "header.php";

$query= "SELECT name,login,email,id FROM residents WHERE id='".$id."'";
$results= mysql_query($query);

displayresults($results);

?>

<P>Do you <I>REALLY</I> want to delete the above record?</P>

<Table width=100%>
<TR><TD width=50%><A href="admin.php">What was I thinking?</A></TD>
<TD width=50%><A href="admindeletesubmit.php?id=<?php echo $id ?>">Delete the bastard!</A></TD></TR>
</table>

<?php

include("footer.php");

?>
