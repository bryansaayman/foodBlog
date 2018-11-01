<!--This page is used to validate the details entered on the change details page-->
<?php
	//Connects the script to the database
	Include "connect.php";
	
	/*Used to validate the details entered on the registration form on submission.
	Error messages will be displayed if values are entered in the incorrect format 
	or the email and contact number entered do not already exist*/
	$errors = [];
	if(isset($_POST["FirstName"]) && 
		isset($_POST["LastName"]) &&
		isset($_POST["Gender"]) &&
		isset($_POST["DOB"]) &&
		isset($_POST["Email"]) &&
		isset($_POST["Contact"]) &&
		isset($_POST["Password"]) &&
		isset($_POST["Status"])
		){
			$NewName =  $_POST["FirstName"];
			$NewSurname = $_POST["LastName"];
			$NewGender = $_POST["Gender"];
			$NewDOB = $_POST["DOB"];
			$NewEmail = $_POST["Email"];
			$NewContact = $_POST["Contact"];
			$target_path = "Uploads/";
			$NewPhoto = $_FILES['Photo']['name'];
			$NewPassword = $_POST["Password"];
			$NewStatus = $_POST["Status"];
			validate_data($conn,$userID,$NewName,$NewSurname,$NewGender,$NewDOB,$NewEmail,$NewContact,$NewPhoto,$NewPassword);
			//If there are errors then they are displayed
			if (count($errors) != 0) {
				display_errors();
			}else{
				/*Sets the path for the uploaded photo and creates a copy
				of the photo and stores it in the Uploads file*/
				$target_path = $target_path.basename($_FILES['Photo']['name']);
				move_uploaded_file($_FILES['Photo']['tmp_name'],$target_path);
				//Updates the user's details in the database
				$sql = "UPDATE user_data SET first_name = '$NewName', last_name = '$NewSurname',gender = '$NewGender',date_of_birth = '$NewDOB',email = '$NewEmail',
				contact = '$NewContact', photo = '$target_path', password = '$NewPassword', status = '$NewStatus'
				WHERE id_user = '$userID'";
				if ($conn->query($sql) === True) {
					$_SESSION["Success"] = "Your details have been changed";
					header("Location: Profile.php");
				}else{
					$errors[] = "Error: " . $sql . "<br>" . $conn->error;
					$errors[] = "Failed to change profile details";
					display_errors();
					$errors = [];
				}

			}
	}
	
	//This function is used to validate the details that are entered on the registration form
	//Any details which are not valid get a corresponding error stored in an array
	function validate_data($conn,$userID,$name,$surname,$gender,$DOB,$email,$contact,$photo,$password) {
		global $errors;
		
		if ($name == "") {
			$errors[] = "<font color='red'>Please enter your name</font>";	
		}
		if (preg_match("/[0-9]/",$name) || preg_match("/!@#$%^&*~?=+-_|,/",$name)){
				$errors[] = "<font color='red'>Your name cannot contain digits</font>";
			}
		
		if ($surname == "") {
			$errors[] = "<font color='red'>Please enter your name</font>";	
		}
		if (preg_match("/[0-9]/",$surname) || preg_match("/!@#$%^&*~?=+-_|,/",$surname)){
			$errors[] = "<font color='red'>Your name cannot contain digits or special characters</font>";
		}
		
		if($gender = "") {
			$errors[] = "<font color='red'>Please select a gender</font>";
		}
		
		if($DOB = "") {
			$errors[] = "<font color='red'>Please enter a date of birth</font>";
		}
		
		if($email = "") {
			$errors[] = "<font color='red'>Please enter an email address</font>";
		}
		$sql = "SELECT email FROM user_data WHERE email = '" . $email . "' AND id_user <> '" . $userID . "'";
		if ($stmt = $conn->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($CheckEmail);
			$stmt->fetch();
			if (isset($CheckEmail) && $CheckEmail != null) {
				$errors[] = "<font color='red'>The email address entered already belongs to a registered account</font>";
			}
		}
		
		if($contact = "") {
			$errors[] = "<font color='red'>Please enter a contact number</font>";
		}
		if(substr($contact,0,1) != 0) {
			$errors[] = "<font color='red'>Enter a contact number that begins with 0</font>";
		}
		$sql = "SELECT contact FROM user_data WHERE contact = '" . $contact . "' AND id_user <> '" . $userID . "'";
		if ($stmt = $conn->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($CheckContact);
			$stmt->fetch();
			if (isset($CheckContact) && $CheckContact != null) {
				$errors[] = "<font color='red'>The contact number entered already exists</font>";
			}
		}
		if ($photo == null || $photo == "") {
			$errors[] = "<font color='red'>Please select a photo to upload</font>";
		}
		
		if ($password == null || $password == "") {
			$errors[] = "<font color='red'>Please enter a password</font>";
		}
		if (strlen($password) < 7) {
			$errors[] = "<font color='red'>Please enter a password that is at least 8 characters in length</font>";
		}
	}
	//This function is used to display error messages that are stored in the error array
	function display_errors() {
		global $errors;
		foreach ($errors as $values) {?>
			<div class="alert alert-warning alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <?php echo $values ?>
			</div><?php
		 }
	}
?>