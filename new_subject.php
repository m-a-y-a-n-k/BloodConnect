<?php
	require_once("includes/session.php");
	require_once("includes/connection.php");
	require_once("includes/functions.php");
?>

<?php
	confirm_logged_in();
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
						<h2> Add Subject </h2>
							<form action="create_subject.php" method="post">
								<p> Subject Name:
									<input type="text" name="menu_name" value="" id="menu_name"/>
								</p>
								<p> Position:
									<select name="position">
										<?php
											$subject_set = get_all_subjects();
											$subject_count = mysqli_num_rows($subject_set);
											for( $count = 1; $count <= $subject_count + 1; $count++) {
												echo "<option value=\"{$count}\">{$count}</option>";
											}
										?>
										
									</select>
								</p>
								<p> Visible:
									<input type="radio" name = "visible" value="0" /> No
									&nbsp;
									<input type="radio" name = "visible" value="1" /> Yes
								</p>
								<input type="submit" value="Add Subject" />
							</form>
						<br />
						<a href="content.php"> Cancel </a>
					</td>
				</tr>
			</table>
<?php require_once("includes/footer.php"); ?>