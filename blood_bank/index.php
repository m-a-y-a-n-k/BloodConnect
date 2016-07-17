<?php
	require_once("includes/connection.php");
?>

<?php
	require_once("includes/functions.php");
	require_once("includes/form_functions.php");
?>

<?php
	register_donor();
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
						<?php echo public_navigation($sel_subject,$sel_page); ?>	
					</td>
					<td id="page">
						<?php
							if( $sel_page ) {	?>
								<h2><?php echo htmlentities($sel_page['menu_name']); ?></h2>
								<div class="page-content">
									<?php echo htmlentities(strip_tags(nl2br($sel_page['content']),"<b><br><p><a>")); ?>
									<br />
									<?php
										echo display_page_content($sel_page);
									?>
									<?php
										if( $sel_page['menu_name'] == "Donate") { ?>
											<form action="index.php?" method="post">
										<?php $new_donor=true; ?>
										<?php include "donor_form.php" ?>
								<input type="submit" name = "submit" value="Register" />
							</form>
									<?php	}
									?>
									<?php
										if( $sel_page['menu_name'] == "Staff Login" ) {
											redirect_to("staff.php");
										}
									?>
								</div>
						<?php	} else {	?>
								<h2> Welcome Guest !!!! <br /><br /> </h2>
								<h3>--Keep Supporting For Us For The Noble Cause </h3>		
						<?php	} 	?>	
					</td>
				</tr>
			</table>
<?php 
	remove_expired_donors();
	require_once("includes/footer.php"); 
?>

