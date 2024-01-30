<?php
 	include('../session/session.php');
    include '../session/connection.php';
	$id=$_GET['deleteupdateid'];
	$sql = "DELETE FROM service_calls_updates WHERE `ID` = $id;";

	if (mysqli_query($con, $sql)) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
	} 

			
?>