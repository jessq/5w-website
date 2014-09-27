<?php

if (isset($_REQUEST["destination"]))
{
  $destination=$_REQUEST["destination"];
}
else
{
  $destination="index.php";
}

setcookie("login","");
setcookie("password","");

header("Location: ".$destination);
?>

