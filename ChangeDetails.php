<!--This page is used to the change the user details-->
<?php
	//Includes the header php script in the file 
		//Displays the header content
	Include "header.php";
	
	//Fetches the user details from the database
	$sql = "SELECT id_user, first_name, last_name, gender, date_of_birth, email, contact,
	photo, password, status FROM user_data WHERE email = '" . $username . "'";
	if ($stmt = $conn->prepare($sql)) {
		$stmt->execute();
		$stmt->bind_result($userID,$name,$surname,$gender,$date_of_birth,$email,$contact,
		$photo,$password,$status);
		$stmt->fetch();
		//Sets the select form control to either male or female depending on user gender
		if ($gender == "M") {
			$genderMale = "selected";
			$genderFemale = "";
		}else{
			$genderMale = "";
			$genderFemale = "selected";
		}
	}
	
	$action = null;
	if(isset($_GET['action'])){
		$action = $_GET['action'];
	}
	/*If the user clicks submit, the newDetails php script validates the data and updates the
	user records in the database*/
	//User details are displayed again after changes
	if ($action == "submit") {
		include "newDetails.php";
		$sql = "SELECT id_user, first_name, last_name, gender, date_of_birth, email, contact,
		photo, password, status FROM user_data WHERE email = '" . $username . "'";
		if ($stmt = $conn->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($userID,$name,$surname,$gender,$date_of_birth,$email,$contact,
			$photo,$password,$status);
			$stmt->fetch();
		}
	}
?>
<!--This is the main content of the page-->
<div class="container">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<form method="post" enctype="multipart/form-data" action="ChangeDetails.php?action=submit">
				<fieldset>
					<legend style="text-align:center"><h1>Edit Profile</h1></legend>
						<div class="form-group">
							<label for="FirstName">Name:</label>
							<input type="text" class="form-control" id="FirstName" name="FirstName" value= "<?php echo $name ?>" required>
						</div>
						<div class="form-group">
							<label for="LastName">Surname:</label>
							<input type="text" class="form-control" id="LastName" name="LastName" value= "<?php echo $surname ?>" required>
						</div>
						<div class="form-group">
							<select name="Gender" id="Gender" tabindex="1" class="form-control" required>
							  <option value="M" <?php echo $genderMale ?>>Male</option>
							  <option value="F" <?php echo $genderFemale ?>>Female</option>
							</select>
						</div>
						<div class="form-group">
							<label for="DOB">Date of Birth:</label>
							<input type="date" class="form-control" id="DOB" name="DOB" value= "<?php echo $date_of_birth?>" required>
						</div>
						<div class="form-group">
							<label for="Email">Email:</label>
							<input type="email" class="form-control" id="Email" name="Email" value= "<?php echo $email ?>" required>
						</div>
						<div class="form-group">
							<label for="Contact">Contact:</label>
							<input type="tel" class="form-control" id="Contact" name="Contact" value= "<?php echo $contact ?>" required>
						</div>
						<div class="form-group">
							<label for="Photo">Photo:</label>
							<input type="file" id="Photo" name="Photo" value= "<?php echo $photo ?>" required>
						</div>
						<div class="form-group">
							<label for="Password">Password:</label>
							<input type="password" class="form-control" id="Password" name="Password" value= "<?php echo $password ?>" required>
						</div>
						<div class="form-group">
							<label for="Status">Status:</label>
						<input type="text" class="form-control" id="Status" name="Status" value= "<?php echo $status ?>" required>
					</div>
				</fieldset>
				<button type="submit" class="btn btn-primary" name="Submit">Apply</button>
				<button type="cancel" class="btn btn-secondary" name="Cancel" onclick="window.location.href='Profile.php'">Cancel</button>
			</form>
		</div>	
	</div>		
</div>
<?php
	//Includes the footer php script content in the page
		//Displays the footer content
	Include "footer.php";
?>