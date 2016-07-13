<?php 
	require_once("includes/session.php"); 
	require_once("includes/connection.php");
	require_once("includes/functions.php");
	require_once("includes/form_functions.php");
?>

<?php 
	confirm_logged_in();
?>

<?php

	$errors = form_validation();
	
	if( !empty($errors) ) {
		redirect_to("new_subject.php");
	}
?>

<?php

	$menu_name = mysql_prep($_POST['menu_name']);
	$position = mysql_prep($_POST['position']);
	$visible = mysql_prep($_POST['visible']);
?>

<?php
	$query = "INSERT INTO subjects (
				menu_name,position,visible
				) VALUES (
				 '{$menu_name}', {$position}, {$visible}
				 )";
	if( mysqli_query($connection,$query) ) {
		// Success!
		redirect_to("content.php");
	} else {
		// Display error message.
		echo "<p> Subject creation failed. </p>";
		echo "<p>".mysqli_error()."</p>";
	}
?>

<?php mysqli_close($connection); ?>