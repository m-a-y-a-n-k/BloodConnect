<?php
	require_once("includes/session.php");
	require_once("includes/connection.php");
	require_once("includes/functions.php");
	confirm_logged_in();
?>

<?php
	// make sure the subject id sent is an integer
	if( intval($_GET['subj']) == 0 )
	{
		redirect_to("content.php");
	}

	if( isset($_POST['submit'] ) ) {
		
		$errors = form_validation();
		if( !empty($errors) ) {
			$message = "There were ".count($errors)." errors in the form.";
			redirect_to("edit_subject.php");
		} else {
			
			// Perform update
			$id = mysql_prep($_GET['subj']);
			$menu_name = mysql_prep($_POST['menu_name']);
			$position = mysql_prep($_POST['position']);
			$visible = mysql_prep($_POST['visible']);
			
			$query = "UPDATE subjects SET
						menu_name = '{$menu_name}',
						position = {$position},
						visible = {$visible}
						WHERE id = {$id}";
						
			$result = mysqli_query($connection,$query);
			// affect of most recent query
			if( mysqli_affected_rows($connection) == 1 ){
				// Success
				$message = "The subject was succesfully updated";
			}	else {
				// Failed
				$message = "The subject update failed.";
				$message .= "<br />".mysqli_error($connection);
			}
			
		}
		
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
					</td>
					<td id="page">
						<h2> Edit Subject: <?php echo $sel_subject['menu_name']; ?></h2>
							<?php 
								if( !empty($message) ) {
									echo "<p class=\"message\">".$message."</p>";
								}
							?>
							<?php
								// output a list of the fields that had errors
								if( !empty($errors) ) {
									echo "<p class=\"errors\">";
									echo "Please review the following fields: <br />";
									foreach($errors as $error) {
										echo " - ". $error . "<br />";
									}
									echo "</p>";
								} 
							?>
							<form action="edit_subject.php?subj=
							<?php
								echo urlencode($sel_subject['id']); 
								?>" method="post">
								<p> Subject Name:
									<input type="text" name="menu_name" value="
									<?php
										echo $sel_subject['menu_name'];
									?>" id="menu_name"/>
								</p>
								<p> Position:
									<select name="position">
										<?php
											$subject_set = get_all_subjects();
											$subject_count = mysqli_num_rows($subject_set);
											for( $count = 1; $count <= $subject_count + 1; $count++) {
												echo "<option value=\"{$count}\"";
												if( $sel_subject['position'] == $count) {
													echo " selected";
												}
												echo ">{$count}</option>";
											}
										?>
										
									</select>
								</p>
								<p> Visible:
									<input type="radio" name = "visible" value="0"
										<?php
											if( $sel_subject['visible'] == 0 ) {
												echo " checked";
											}
										?>	/> No
									&nbsp;
									<input type="radio" name = "visible" value="1" 
										<?php
											if( $sel_subject['visible'] == 1 ) {
												echo " checked";
											} ?>
									/> Yes
								</p>
								<input type="submit" name = "submit" value="Edit Subject" />
							
								&nbsp;&nbsp;
								<a href = "delete_subject.php?subj=
									<?php 
										echo urlencode($sel_subject['id']);
									?>" onClick="return confirm('Are you sure?');">Delete Subject</a>
							</form>
						<br />
						<a href="content.php"> Cancel </a>
						<div style = "margin:top 2em; border-top: 1px solid #000000;">
							<h3>Pages in this subject: </h3>
							<ul>
								<?php
									$subject_pages = get_pages_for_subject($sel_subject['id']);
									while( $page = mysqli_fetch_array($subject_pages) ) {
										echo "<li><a href=\"content.php?page={$page['id']}\">
											{$page['menu_name']}</a></li>";
									}
								?>
							</ul>
							<br />
							+ <a href="new_page.php?subj=
								<?php	
									echo $sel_subject['id'];
									?>">
								Add a new page to this subject
							  </a>
						</div>
					</td>
				</tr>
			</table>
<?php require_once("includes/footer.php"); ?>