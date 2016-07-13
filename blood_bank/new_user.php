<?php 
	require_once("includes/session.php");
	require_once("includes/connection.php");
	require_once("includes/functions.php");
?>
<?php
	include_once("includes/form_functions.php");
	
	// START FORM PROCESSING
	if( isset($_POST['submit']) ) {
	
		$errors = array();
		
		$required_fields = array('username','password');
		$errors = array_merge($errors,check_required_fields($required_fields,$_POST));
		
		$fields_with_lengths = array('username' => 30, 'password' => 30);
		$errors = array_merge($errors,check_max_field_lengths($fields_with_lengths,$_POST));
		
		$username = trim(mysql_prep($_POST['username']));
		$password = trim(mysql_prep($_POST['password']));
		$hashed_password = sha1($password);
		
		if( empty($errors) ){
			$query = "INSERT INTO users (
						user_name, hashed_password
						) VALUES (
							'{$username}','{$hashed_password}'
							)";
			$result = mysqli_query($connection,$query);
			if( $result ) {
				$message = "The user was successfully created.";
			} else {
				$message = "The user could not be created.";
				$message .= "<br />" . mysqli_error($connection);
			}
		} else {
			
			if(count($errors) == 1 ) {
				$message = "There was 1 error in the form";
			} else {
				$message = "There were ". count($errors) . "errorrs in the form";
			}
		}
		
	} else {
		$username = "";
		$password = "";
	}
?>

<?php include("includes/header.php"); ?>
<table id = "structure" >
	<tr>
		<td id="navigation">
			<a href="staff.php">Return to the Menu</a><br />
		</td>
		<td id="page">
			<h2>Create new user</h2>
			<?php if(!empty($message)){ echo "<p class=\"message\">".$message."</p>";} ?>
			<?php if(!empty($errors)) {display_errors($errors);} ?>
			<form action="new_user.php" method="post">
			<table>
				<tr>
					<td> UserName: </td>
					<td> <input type="text" name="username" maxlength="30" value="
						<?php
							echo htmlentities($username); 
						?>" />
					</td>
				<tr>
					<td> Password: </td>
					<td><input type="password" name="password" maxlength="30" value="
						<?php
							echo htmlentities($password);
						?>"	/>
					</td>
				</tr>
				<tr>
					<td colspan="2"><input type = "submit" name="submit" value="Create User"	/>
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table> 