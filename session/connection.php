<?php
	$con=mysqli_connect("127.0.0.1","projects","Hvi@2021!","db") or die($con);;
	// Check connection
	if (mysqli_connect_errno())
	{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
?>