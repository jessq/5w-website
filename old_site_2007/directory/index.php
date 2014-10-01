<?php
include "common.php";

?>
<title>Fifth-West Directory</title>

<?php
include "header.php";

$loginrow=verify_login($login,$password);
if (isset($loginrow))
{
  print "<P><center><I><FONT SIZE=-1>welcome back, ".$loginrow["login"]."!</FONT></I></center></P>";
  $privacyclearance=1;
}
else
{
  $privacyclearance=0;
}

if (isset($_REQUEST["querytype"]))
  $querytype=$_REQUEST["querytype"];
else
  $querytype="allres";

$basequery="SELECT name,room,email,major,year,role,url,id FROM residents WHERE status > 0";

switch ($querytype)
{
  // current residents sorted by name
case "resbyname":
  $query="$basequery AND privacylevel <= $privacyclearance AND currentresident=1 ORDER BY name";
  $title="Current Residents";
  break;

  // current officer residents sorted by name
case "curofficers":
  $query="$basequery AND privacylevel <= $privacyclearance AND currentresident=1 AND role!='' ORDER BY name";
  $title="Hall Officers";
  break;

  // current residents sorted by room #
case "resbyroom":
  $query="$basequery AND privacylevel <= $privacyclearance AND currentresident=1 ORDER BY room";
  $title="Current Residents";
  break;

  // alums only, sorted by name.
case "alums":
  $query="$basequery AND privacylevel <= $privacyclearance AND currentresident=0 ORDER BY name";
  $title="Alums";
  break;

  // all residents sorted by name
default:
    $query= "$basequery AND privacylevel <= $privacyclearance ORDER BY name";
    $title="Residents and Alums";
    break;

}

$result= mysql_query($query) or die("Query failed: $query");
?>
<form action="index.php" method=post>
<?php

$queryselectarray=array("resbyname" => "Current residents (by name)", 
			"resbyroom"=> "Current residents (by room)",
			"curofficers"=>"Current officers",
			"alums"=>"Alums",
			"allres"=>"Everyone");

doselect("querytype",$queryselectarray,$querytype,"");

?>

<input type=submit>

</form>
<?php
print "<H2>" . $title . "</H2>";

displayresults($result);

print "<FONT SIZE=-1><I>" .mysql_num_rows($result) . " members displayed.</I></FONT>";

include "footer.php";



?>
