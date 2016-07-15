<?php
	// This file is the place to store all basic functions
	
	function mysql_prep( $value ) {
		global $connection;
		
		$magic_quotes_active = get_magic_quotes_gpc();
		$new_enough_php = function_exists("mysql_real_escape_string");
		
		if( $new_enough_php ) {
			if( $magic_quotes_active ) {
				$value = stripslashes( $value );
			}
			$value = mysqli_real_escape_string($connection,$value);
		}else {
				if( !$magic_quotes_active ) {
					$value = addslashes( $value );
				}
		}
		return $value;
	}
	
	function redirect_to($location = NULL) {
		if( $location != NULL )
		{
			header("Location: {$location}");
			exit;
		}
	}
	
	function confirm_query($result_set){
		if( !$result_set) {
			die("Database query failed: ".mysql_error());
		}
	}
	
	function get_all_subjects($public = true) {
		global $connection;
		$query_subject = "SELECT * 
						FROM subjects ";
		if( $public) {
			$query_subject .= "WHERE visible = 1 ";
		}
		$query_subject .= "ORDER BY position ASC";
		$subject_set = mysqli_query($connection,$query_subject);
		confirm_query( $subject_set);
		return $subject_set;
	}
	
	function get_default_page($subject_id) {
		// Get all visible pages
		$page_set = get_pages_for_subject($subject_id,true);
		if( $first_page = mysqli_fetch_array($page_set) ) {
			return $first_page;
		} else {
			return NULL;
		}
	}
	
	function get_pages_for_subject($subject_id,$public = true) {
		global $connection;
		$query_pages = "SELECT * 
						FROM pages ";
		$query_pages .= "WHERE subject_id = {$subject_id} ";
		if( $public ) {
			$query_pages .= "AND visible = 1 ";
		}
		$query_pages .= "ORDER BY position ASC";
		$page_set = mysqli_query($connection,$query_pages);
		confirm_query($page_set);
							
		return $page_set;
	}
	
	function get_subject_by_id($subject_id) {
		global $connection;
		
		$query = "SELECT * 
					FROM subjects 
					WHERE id = {$subject_id}";
		
		$result_set = mysqli_query($connection,$query);
		confirm_query($result_set);
		// if no rows are returned fetch array will return false
		if($subject = mysqli_fetch_array($result_set)){
			return $subject;
		}else {
			return NULL;
		}
	}
	
	function get_page_by_id($page_id) {
		global $connection;
		$query = "SELECT *
					FROM pages
					WHERE id={$page_id}";
		$result_set = mysqli_query($connection,$query);
		confirm_query($result_set);
		
		if( $page = mysqli_fetch_array($result_set)) {
			return $page;
		} else {
			return NULL;
		}
	}
	
	function find_selected_page() {
		global $sel_subject;
		global $sel_page;
		
		if(isset($_GET['subj']) ){
		
			$sel_subject = get_subject_by_id($_GET['subj']);
			$sel_page = get_default_page($sel_subject['id']);
		
		} elseif( isset($_GET['page']) ) {
		
			$sel_subject = NULL;
			$sel_page = get_page_by_id($_GET['page']);
	
		} else {
		
			$sel_page = NULL;
			$sel_subject = NULL;
		}
	}

	function staff_navigation($sel_subject,$sel_page,$public = false) {
		$output = "<ul class=\"subjects\">";
			// 3.Perform database query
			$subject_set = get_all_subjects($public);
			
			// 4. Use returned data
			while($subject = mysqli_fetch_array($subject_set)){
				$output .= "<li";
				if( $subject["id"] == $sel_subject['id'])
					$output .= " class=\"selected\"";
				$output .= "><a href=\"edit_subject.php?subj=".urlencode($subject["id"]).
				"\">{$subject["menu_name"]}</a></li>";
							
				$page_set = get_pages_for_subject($subject["id"]);
				$output .= "<ul class=\"pages\">";
				// 4. Use returned data
				while($page = mysqli_fetch_array($page_set)){
					$output .= "<li";
					if(  $page["id"] == $sel_page['id'])
						$output .= " class=\"selected\"";
					$output .= "><a href=\"content.php?page=".urlencode($page["id"]).
					"\">{$page["menu_name"]}</a></li>";
				}
				$output .= "</ul>";
			}
			$output .= "</ul>";
			return $output;
	}

	function public_navigation($sel_subject,$sel_page,$public = true) {
	
		$output = "<ul class=\"subjects\">";
		$subject_set = get_all_subjects($public);
		
		while( $subject = mysqli_fetch_array($subject_set) ) {
			$output .= "<li";
			if( $subject['id'] == $sel_subject['id']) {
				$output .= " class = \"selected\""; 
			}
			$output .= "><a href=\"index.php?subj=" . urlencode($subject["id"])
			."\">{$subject["menu_name"]}</a></li>";
			if( $subject['id'] == $sel_subject['id']) {
				$page_set = get_pages_for_subject($subject["id"]);
				$output .= "<ul class=\"pages\">";
				while( $page = mysqli_fetch_array($page_set) ) {
					$output .= "<li";
					if( $page["id"] == $sel_page['id'] ) {
						$output .= " class=\"selected\""; 
					}
					$output .= "><a href=\"index.php?page=" . urlencode($page["id"]).
						"\">{$page["menu_name"]}</a></li>";
				}
				$output .= "</ul>";
			}
		}
		$output .= "</ul>";
		return $output;
	}

	function find_blood_type_from_selected_page($sel_page) {
		
		if( $sel_page['menu_name'] == "Blood Group A") {
			return 'A';
		} elseif ($sel_page['menu_name'] == "Blood Group B") {
			return 'B';
		}elseif( $sel_page['menu_name'] == "Blood Group AB") {
			return 'AB';
		} elseif( $sel_page['menu_name'] == "Blood Group O" ) {
			return 'O';
		} else {
			return 'X';
		}
 	}

	function get_donors() {
		global $connection;
		
		$query = "SELECT *
					FROM donors";
		$result_set = mysqli_query($connection,$query);
		confirm_query($result_set);
		return $result_set;
	}
	
	function display_page_content($sel_page) {
		
		$type = find_blood_type_from_selected_page($sel_page);
		$result_set = get_donors();
		$output = "<ul class = \"donors\">";
		
		while( $row = mysqli_fetch_array($result_set) ) {
			if( $row['type'] == $type )
			$output .= "<li> Name = {$row['name']} <br /> 
							Email = {$row['email']} <br />
							Age = {$row['age']} <br />
							Gender = {$row['gender']} <br />
							Contact = {$row['contact']} <br />
							City = {$row['city']} <br />
							Country = {$row['country']} <br />
 							Sign = {$row['sign']} <br />
					   </li>";
		}
		
		$output .= "</ul>";
		return $output;
	}

	function remove_expired_donors() {
		global $connection;
		$all_donors_set = get_donors();
		
		while( $donor_row = mysqli_fetch_array($all_donors_set) ) {
			if( !isset($_COOKIE[$donor_row['name']]) ){
				// delete cookie from database
				$query = "DELETE FROM donors WHERE id = {$_COOKIE[$donor_row['name']]}";
				$result_set = mysqli_query($connection,$query);
				confirm_query($result_set);
			}
		}
	}
	
	function register_donor() {
		global $connection;
	// START FORM PROCESSING
	// only execute the form processing if the form has been submitted
	if (isset($_POST['submit'])) {
		// initialize an array to hold our errors
		$errors = array();
	
		// perform validations on the form data
		$required_fields = array('donor_name', 'donor_email', 'donor_age', 'donor_gender', 'donor_contact', 'donor_city', 'donor_country', 'donor_type','donor_sign','donor_expiration');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));
	
		// Database submission only proceeds if there were NO errors.
		if (empty($errors)) {
			// clean up the form data before putting it in the database
			$fields_with_lengths = array('donor_name' => 30,'donor_email' => 50,'donor_age' => 3,'donor_gender' => 10,'donor_contact' => 12,'donor_city'=>50,'donor_country' => 50,'donor_type' => 3,'donor_sign' => 1,'donor_expiration' => 12);
			$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths, $_POST));
		} 
		if (empty($errors)) {
			// clean up the form data before putting it in the database
			
			$donor_name = trim(mysql_prep($_POST['donor_name']));
			$donor_email = mysql_prep($_POST['donor_email']);
			$donor_age = intval(mysql_prep($_POST['donor_age']));
			$donor_gender = mysql_prep($_POST['donor_gender']);
			$donor_contact = mysql_prep($_POST['donor_contact']);
			$donor_city = mysql_prep($_POST['donor_city']);
			$donor_country = mysql_prep($_POST['donor_country']);
			$donor_type = mysql_prep($_POST['donor_type']);
			$donor_sign = mysql_prep($_POST['donor_sign']);
		
			$expiration_time = time() + mysql_prep($_POST['donor_expiration']) * 24 * 60 * 60 * 31;
			
			$query = "INSERT INTO donors (
						name, email, age, gender, contact, city, country, type,sign,expiration
					) VALUES (
						'{$donor_name}', '{$donor_email}', {$donor_age}, '{$donor_gender}', '{$donor_contact}', '{$donor_city}', '{$donor_country}', '{$donor_type}', '{$donor_sign}', {$expiration_time}
					)";
			if ($result = mysqli_query($connection,$query)) {
				// as is, $message will still be discarded on the redirect
				$message = "Your response has been submitted.";
				$new_donor_id = mysqli_insert_id($connection);
				setcookie($donor_name,$new_donor_id,$expiration_time);
			} else {
				$message = "Processing request error";
				$message .= "<br />" . mysqli_error($connection);
			}
		} else {
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}
		}
		
		// END FORM PROCESSING
	}
	}
	
	function save_data_from_app() {
		
		global $connection;
			// START APP PROCESSING
			// only execute the form processing if the form has been submitted
		if (isset($_POST['submit'])) {
				// initialize an array to hold our errors
			$errors = array();
	
				// perform validations on the form data
			$required_fields = array('iname', 'iemail', 'iage', 'igender', 'icontact', 'icity', 'icountry', 'itype','isign','iexpiration');
			$errors = array_merge($errors, check_required_fields($required_fields, $_POST));
	
				// Database submission only proceeds if there were NO errors.
			if (empty($errors)) {
				// clean up the form data before putting it in the database
				$fields_with_lengths = array('iname' => 30,'iemail' => 50,'iage' => 3,'igender' => 10,'icontact' => 12,'icity'=>50,'icountry' => 50,'itype' => 3,'isign' => 1,'donor_expiration' => 12);
				$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths, $_POST));
			} 
			if (empty($errors)) {
				// clean up the form data before putting it in the database
			
				$donor_name = trim(mysql_prep($_POST['iname']));
				$donor_email = mysql_prep($_POST['iemail']);
				$donor_age = intval(mysql_prep($_POST['iage']));
				$donor_gender = mysql_prep($_POST['igender']);
				$donor_contact = mysql_prep($_POST['icontact']);
				$donor_city = mysql_prep($_POST['icity']);
				$donor_country = mysql_prep($_POST['icountry']);
				$donor_type = mysql_prep($_POST['itype']);
				$donor_sign = mysql_prep($_POST['isign']);
		
				$expiration_time = time() + mysql_prep($_POST['donor_expiration']) * 24 * 60 * 60 * 31;
			
				$query = "INSERT INTO donors (
							name, email, age, gender, contact, city, country, type,sign,expiration
						) VALUES (
							'{$donor_name}', '{$donor_email}', {$donor_age}, '{$donor_gender}', '{$donor_contact}', '{$donor_city}', '{$donor_country}', '{$donor_type}', '{$donor_sign}', {$expiration_time}
						)";
				if ($result = mysqli_query($connection,$query)) {
					// as is, $message will still be discarded on the redirect
					$message = "Your response has been submitted.";
					$new_donor_id = mysqli_insert_id($connection);
					setcookie($donor_name,$new_donor_id,$expiration_time);
				} else {
					$message = "Processing request error";
					$message .= "<br />" . mysqli_error($connection);
				}
			} else {
				if (count($errors) == 1) {
					$message = "There was 1 error in the form.";
				} else {
					$message = "There were " . count($errors) . " errors in the form.";
				}
			}
		
			// END FORM PROCESSING AND CLOSE DB CONNECTION
			
			mysqli_close($connection);
	}
	}

	function send_all_data_to_app() {
		global $connection;
		$result = get_donors();
		if( mysqli_num_rows($result) > 0 ) {
			while( $row[] = mysqli_fetch_array($result) ) {
				$json = json_encode($row);
			}
		} else {
			echo "0 results";
		}
		echo $json;
		mysqli_close($connection);
	}
?>