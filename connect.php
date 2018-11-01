<?php
	/*This php script is used to connect the site to the database*/
	$conn = @mysqli_connect("localhost","ODBC",  "", "db_blog");
	//$conn = @mysqli_connect("localhost","ayshghxo_bryan",  "P@55word", "ayshghxo_bryan");
	
	if(mysqli_connect_errno()){
		echo "Unable to connect ". mysqli_connect_error();
		exit;
	}
?>