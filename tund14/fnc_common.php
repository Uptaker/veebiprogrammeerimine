<?php
  function test_input($data) {
	  $data = filter_var($data, FILTER_SANITIZE_STRING);
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
  }
  
  function news_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	return $data;
}