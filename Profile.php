<!--This is the profile page. It is used to display the users profile and their account details-->
<?php
	//Includes the header php script in the file 
		//Displays the header content
	Include "header.php";

	/*Displays a success message is the user successfully changed their details--
	on the change details page*/
	if ($_SESSION["Success"] != null) {?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo $_SESSION["Success"] ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div><?php
		$_SESSION["Success"] = "";
	}
	
	//Fetches and displays the user details
	$sql = "SELECT first_name, last_name, gender, date_of_birth, email, contact,
	photo, password, status FROM user_data WHERE email = '" . $username . "'";
	if ($stmt = $conn->prepare($sql)) {
		$stmt->execute();
		$stmt->bind_result($name,$surname,$gender,$date_of_birth,$email,$contact,
		$photo,$password,$status);
		$stmt->fetch();
		
		if ($gender == "M") {
			$genderText = "Male";
		}else{
			$genderText = "Female";
		} 
	}
?>
<!--This is the main content of the page-->
<div class="container">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<form>
				<fieldset>
					<legend style="text-align:center"><h1>Profile</h1></legend>
					<center>
						<!--Displays the user's profile picture-->
						<img src="<?php echo $photo ?>" class="img-thumbnail" style="border-radius:50%;max-width:30%"/>
						<p><?php echo $status ?></p>
					</center>
					<div class="form-group">
						<label for="FirstName">Name:</label>
						<input type="text" class="form-control" id="FirstName" name="FirstName" value= "<?php echo $name ?>" disabled>
					</div>
					<div class="form-group">
						<label for="LastName">Surname:</label>
						<input type="text" class="form-control" id="LastName" name="LastName" value= "<?php echo $surname ?>" disabled>
					</div>
					<div class="form-group">
						<label for=>Gender:</label>
						<input type="text" class="form-control" id="Gender" name="Gender" value= "<?php echo $genderText ?>" disabled>
					</div>
					<div class="form-group">
						<label for="DOB">Date of Birth:</label>
						<input type="text" class="form-control" id="DOB" name="DOB" value= "<?php echo $date_of_birth?>" disabled>
					</div>
					<div class="form-group">
						<label for="Email">Email:</label>
						<input type="email" class="form-control" id="Email" name="Email" value= "<?php echo $email ?>" disabled>
					</div>
					<div class="form-group">
						<label for="Contact">Contact:</label>
						<input type="text" class="form-control" id="Contact" name="Contact" value= "<?php echo $contact ?>" disabled>
					</div>
				</fieldset>
				<button type="button" class="btn btn-primary" name="ChangeDetails" onclick="window.location.href='ChangeDetails.php'"/>Change Details</button>
				<button type="button" class="btn btn-secondary" name="Return" onclick="window.location.href='index.php'"/>Return</button>
			</form>
		</div>
	</div>
</div>
<?php
	//Includes the footer php script content in the page
		//Displays the footer content
	Include "footer.php"; 
?>