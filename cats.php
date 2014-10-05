<?php

function getCatzDataz($url){
  $ch = curl_init($url);
  $result = curl_exec($ch);
  return $result;
}

var_dump(getCatzDataz("http://catfacts-api.appspot.com/api/facts?number=1"));
?>