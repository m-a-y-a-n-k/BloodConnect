<?php
	require_once("includes/session.php");
	require_once("includes/connection.php");
	require_once("includes/functions.php");
?>

<?php
	
	if( intval($_GET['subj']) == 0 )
	{
		redirect_to("content.php");
	}
	
	$id = mysql_prep($_GET['subj']);
	
	if( $subject =  get_subject_by_id($id) ) {
		
		$query = "DELETE FROM subjects WHERE id = {$id} LIMIT 1";
		$result = mysqli_query($connection,$query);
	
		if( mysqli_affected_rows($connection) == 1 ) {
				redirect_to("content.php");
		} else {
				// Deletion failed
			echo "<p> Subject deletion failed. </p>";
			echo "<p>".mysqli_error($connection)."</p>";
			echo "a href=\"content.php\">Return to Main Page</a>";
		}
	} else {
		
		// subject didn't exist in database
		redirect_to("content.php");
	}

?>

<?php mysqli_close($connection); ?>