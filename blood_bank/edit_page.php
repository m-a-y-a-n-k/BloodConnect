<?php
	require_once("includes/session.php");
	require_once("includes/connection.php");
	require_once("includes/functions.php");
?>
<?php confirm_logged_in(); ?>
<?php
	
	if( intval($_GET['page']) == 0 )
	{
		redirect_to("content.php");
	}

	include_once("includes/form_functions.php");
	
	// START FORM PROCESSING
	// only execute the form processing if the form has been submitted
	
	if( isset($_POST['submit'] ) ) {
		
		// initialize the array to hold our errors
		//$errors = array()
		
		// Perform validation on the form data
		
		//array_merge($errors, check_required_fields(array('menu_name','position','visible','content')));
		
		//array_merge($errors,check_max_field_lengths(array('menu_name' => 30 )));
		
		// Clean up the form before putting it in the database 
		$id = mysql_prep($_GET['page']);
		$menu_name = trim(mysql_prep($_POST['menu_name']));
		$position = mysql_prep($_POST['position']);
		$visible = mysql_prep($_POST['visible']);
		$content = mysql_prep($_POST['content']);

		// Database submission only proceeds if there were NO errors
		
		if( empty($errors)){
			$query = "UPDATE pages SET
						menu_name = '{$menu_name}',
						position = {$position},
						visible = {$visible},
						content = '{$content}'
						WHERE id = {$id}";
			
			$result = mysqli_query($connection,$query);
			
			// test to see if the update occurred
			if( mysqli_affected_rows($connection) == 1 ){
				// Success
				$message = "The page was succesfully updated";
			}	else {
				// Failed
				$message = "The page update failed.";
				$message .= "<br />".mysqli_error($connection);
			}
		} else {
			if( count($errors) == 1 ) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}
		}
		// END FORM PROCESSING
	}
	
?>
<?php
	find_selected_page();
?>
<?php
	include_once("includes/header.php");
?>
			<table id="structure">
				<tr>
					<td id="navigation">
						<?php echo staff_navigation($sel_subject,$sel_page); ?>
						<br />
						<a href="new_subject.php">+ Add a new subject</a>
					</td>
					<td id="page">
						<h2> Edit Page: <?php echo $sel_page['menu_name']; ?></h2>
							<?php 
								if( !empty($message) ) {
									echo "<p class=\"message\">".$message."</p>";
								}
							?>
							<?php
								// output a list of the fields that had errors
								if( !empty($errors) ) {
									display_errors($errors);
								}	
							?>
							<form action="edit_page.php?page=
							<?php
								echo $sel_page['id']; 
								?>" method="post">
								<?php include "page_form.php" ?>
								
								<input type="submit" name = "submit" value="Update Page" />
								&nbsp;&nbsp;
								<a href = "delete_page.php?page=
									<?php 
										echo $sel_page['id'];
									?>" onclick="return confirm('Are you sure you want to delete this page?');">Delete Page</a>
							</form>
						<br />
						<a href="content.php?page=
							<?php
								echo $sel_page['id']; 
							?>"> Cancel 
						</a>
						<br />
					</td>
				</tr>
			</table>
<?php require_once("includes/footer.php"); ?>