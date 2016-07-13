<?php // this page is included by index.php ?>
<?php if( !isset($new_donor) ) { $new_donor = false; } ?>

<p> Name: <input type="text" name="donor_name" 
		value="" id="donor_name"/>
</p>
<p> Email: <input type="text" name="donor_email" 
		value="" id="donor_email"/>
</p>
<p> Age: <select name="donor_age" id = "donor_age">
	<?php
		
		for( $count = 10; $count <= 59; $count++) {
			echo "<option value=\"{$count}\"";
			echo ">{$count}</option>";
		}
	?>
</select></p>
<p> Gender: <input type="text" name="donor_gender" 
		value="" id="donor_gender"/>
</p>
<p> Contact: <input type="text" name="donor_contact" 
		value="" id="donor_contact"/>
</p>
<p> City: <input type="text" name="donor_city" 
		value="" id="donor_city"/>
</p>
<p> Country: <input type="text" name="donor_country" 
		value="" id="donor_country"/>
</p>
<p> Blood Group:
	<input type="radio" name = "donor_type" value="A"	/>A
		&nbsp;
	<input type="radio" name = "donor_type" value="B"	/>B
		&nbsp;
	<input type="radio" name = "donor_type" value="AB"	/>AB
		&nbsp;
	<input type="radio" name = "donor_type" value="O"	/>O
</p>

<p> Sign:
	<input type="radio" name = "donor_sign" value="+"	/>Positive
		&nbsp;
	<input type="radio" name = "donor_sign" value="-"	/>Negative
</p>

<p> Expiration:
	<input type="radio" name = "donor_expiration" value="1"	/>1 month
		&nbsp;
	<input type="radio" name = "donor_expiration" value="6"	/>6 months
		&nbsp;
	<input type="radio" name = "donor_expiration" value="12" />1 year
</p>

