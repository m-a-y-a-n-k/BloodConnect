<?php

function check_required_fields($required_array) {

	$field_errors = array();
	
	foreach($required_array as $fieldname ) {
		if( !isset($_POST[$fieldname]) || 
				(empty($_POST[$fieldname]) && $_POST[$fieldname] != 0) ) {
				$field_errors[] = $fieldname;
		}
	}
	return $field_errors;
}

function check_max_field_lengths($field_length_array) {
	$field_errors = array();
	foreach($field_length_array as $fieldname => $maxlength ) {
		if( strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength ) {
			$field_errors[] = $fieldname;
		}
	}
	return $field_errors;
}

function display_errors($error_array) {
	echo "<p class=\"errors\">";
	echo "Please review the following fields:<br />";
	foreach($error_array as $error) {
		echo " - ". $error. "<br />";
	}
	echo "</p>";
}

function form_validation() {
		$errors = array();
	
		// Form validation
		$required_fields = array('menu_name','position','visible');
	
		foreach($required_fields as $fieldname ) {
			if( !isset($_POST[$fieldname]) || 
				(empty($_POST[$fieldname]) && $_POST[$fieldname] != 0) ) {
				$errors[] = $fieldname;
			}
		}
	
		$fields_with_lengths = array('menu_name'=>30);
	
		foreach($fields_with_lengths as $fieldname => $max_length ) {
		
			if( strlen(trim(mysql_prep($_POST[$fieldname]))) > $max_length ) {
				$errors[] = $fieldname;
			}
		}
		return $errors;
	}
?>
