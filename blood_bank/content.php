<?php
	require_once("includes/session.php");
?>

<?php
	require_once("includes/connection.php");
?>

<?php
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
						<?php echo staff_navigation($sel_subject,$sel_page,false); ?>
						<br />
						<a href = "new_subject.php"> + Add a new subject </a>
					</td>
					<td id="page">
						<h2>
						<?php
							if( !is_null($sel_subject) ) {	?>
								<h2><?php echo $sel_subject['menu_name']; ?></h2>
						<?php	} elseif ( !is_null($sel_page) ) {	?>
								<h2><?php echo $sel_page['menu_name']; ?></h2>
								<div class="page-content">
									<?php echo $sel_page['content']; ?>
								</div>
								<br />
								<a href="edit_page.php?page=
									<?php 
										echo urlencode($sel_page['id']);
									?>">Edit page
								</a>
						<?php	} else {	?>
								<h2> Select a page or subject to edit </h2>
						<?php	} 	?>
						</h2>
						
					</td>
				</tr>
			</table>
<?php require_once("includes/footer.php"); ?>

