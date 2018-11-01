<!--This is the login page. It is used to allow the user to login to their account so that
they can access the webblog. Alternatively, if a user does not have an account then they can
register for an account by clicking register-->
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link href="css/login.css" rel="stylesheet" id="bootstrap-css">
<script src="js/login.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!--The links above are used to link bootstrap styling and javascripts to the file-->

<?php
	/*Session is started and checks whether the user has logged out so that
	the session can be destroyed*/
	session_start();
	$action = null;
	if(isset($_GET['action'])){
		$action = $_GET['action'];
	}
	if ($action == "logout") {
		session_destroy();
	}
	//Include the connect php script to connect to the database
	Include "connect.php";
	
	/*Used to validate the username and password entered on login.
	Checks that a username and password has been entered and if the
	the username and password belong to a registered account in the database*/
	$errors = [];
	if (isset($_POST["Username"]) && $_POST["Username"] != null) {
		if (isset($_POST["Password"]) && $_POST["Password"] != null) {
			//Fetches details from database to verify account details entered
			$sql = "SELECT id_user,email FROM user_data WHERE email = '" . $_POST["Username"] .
			"' AND password = '" . $_POST["Password"] . "'";
			if($stmt = $conn->prepare($sql)) {
				$stmt->execute();
				$stmt->bind_result($userID,$username);
				$stmt->fetch();
				/*If the account exists then the session is started and the user's 
				username and user ID are stored in the respectable session variables*/
				if (isset($username) && $username != null) {
					session_start();
					$_SESSION['userID'] = $userID;
					$_SESSION['username'] = $username;
					$_SESSION["Success"] = "";
					$_SESSION["Comment"] = "";
					header('Location: index.php');
				}else{
					$errors[] = "Account does not exist";
					display_errors();
					$errors = [];
				}
			}
		}
	}
	
	/*Used to validate the details entered on the registration form on submission.
	Error messages will be displayed if values are entered in the incorrect format 
	or the email and contact number entered do not already exist*/
	$errors = [];
	if(isset($_POST["FirstName"]) && 
		isset($_POST["LastName"]) &&
		isset($_POST["Gender"]) &&
		isset($_POST["DOB"]) &&
		isset($_POST["Email"]) &&
		isset($_POST["Contact"])
		){
			$name =  $_POST["FirstName"];
			$surname = $_POST["LastName"];
			$gender = $_POST["Gender"];
			$DOB = $_POST["DOB"];
			$email = $_POST["Email"];
			$contact = $_POST["Contact"];
			$target_path = "Uploads/";
			$photo = $_FILES['Photo']['name'];
			validate_data($conn,$name,$surname,$gender,$DOB,$email,$contact,$photo);
			//If there are errors then they are displayed
			if (count($errors) != 0) {
				display_errors();

			}else{
				$target_path = $target_path.basename($_FILES['Photo']['name']);
				move_uploaded_file($_FILES['Photo']['tmp_name'],$target_path);
				
				//Generates a random password for the user's new account
				function rand_string( $length ) {
					$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
					return substr(str_shuffle($chars),0,$length);
					return rand_string($length);
				}
				
				$password = rand_string(8);
				
				//Inserts the new account into the database and emails the user the password to their new account
				$sql = "INSERT INTO user_data (first_name, last_name, gender, 
					date_of_birth, email, contact, photo, password)
					VALUES ('$name', '$surname', '$gender', '$DOB','$email', '$contact', '$target_path', '$password')";
				if ($conn->query($sql) === True) {
					echo "Account has been created successfully", "<br/>";
					$to = $email;
					$from = "student@webota.co.za";
					$result = mail($to, "New Account", "This is the password for your new account: " . $password, $from);
					if(!$result) {
						echo "Failed to send activation email", "<br/>";
					}else{
						echo "Check your email to view your new account password", "<br/>";
					}
				}else{
					echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}
	}
	
	//This function is used to validate the details that are entered on the registration form
	//Any details which are not valid get a corresponding error stored in an array
	function validate_data($conn,$name,$surname,$gender,$DOB,$email,$contact,$photo) {
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
		$sql = "SELECT email FROM user_data WHERE email = '" . $email . "'";
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
		$sql = "SELECT contact FROM user_data WHERE contact = '" . $contact . "'";
		if ($stmt = $conn->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($CheckContact);
			$stmt->fetch();
			if (isset($CheckContact) && $CheckContact != null) {
				$errors[] = "<font color='red'>The contact number entered already exists</font>";
			}
		}
		
		if ($photo = null || $photo = "") {
			$errors[] = "<font color='red'>Please select a photo to upload</font>";
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
<!--This is the main content of the page-->
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-login">
				<div class="panel-heading">
					<!--Provides buttons to switch between the login and registration form-->
					<div class="row">
						<div class="col-xs-6">
							<a href="#" class="active" id="login-form-link">Login</a>
						</div>
						<div class="col-xs-6">
							<a href="#" id="register-form-link">Register</a>
						</div>
					</div>
					<hr>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<!--Displays the login form-->
							<form id="login-form" action="" method="post" role="form" style="display: block;">
								<div class="form-group">
									<input type="text" name="Username" id="username" tabindex="1" class="form-control" placeholder="Username" required>
								</div>
								<div class="form-group">
									<input type="password" name="Password" id="password" tabindex="2" class="form-control" placeholder="Password" required>
								</div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-6 col-sm-offset-3">
											<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
										</div>
									</div>
								</div>
							</form>
							<?php
								//Gets the current date to set the default value in the date input control
								$currentDate = date("Y-m-d");
							?>
							<!--Displays the registration form-->
							<form enctype="multipart/form-data" id="register-form" method="post" role="form" style="display: none;">
								<div class="form-group">
									<input type="text" name="FirstName" id="FirstName" tabindex="1" class="form-control" placeholder="First Name" required>
								</div>
								<div class="form-group">
									<input type="text" name="LastName" id="LastName" tabindex="1" class="form-control" placeholder="Last Name" required>
								</div>
								<div class="form-group">
									<select name="Gender" id="Gender" tabindex="1" class="form-control" required>
									  <option value="" disabled selected hidden>Select a Gender</option>
									  <option value="M">Male</option>
									  <option value="F">Female</option>
									</select>
								</div>
								<div class="form-group">
									<input type="date" name="DOB" id="DOB" tabindex="1" class="form-control" value="<?php echo $currentDate ?>" required>
								</div>
								<div class="form-group">
									<input type="email" name="Email" id="Email" tabindex="1" class="form-control" placeholder="Email Address" required>
								</div>
								<div class="form-group">
									<input type="tel" name="Contact" id="Contact" tabindex="1" class="form-control" placeholder="Contact Number" required>
								</div>
								<div class="custom-file">
									<label class="custom-file-label" for="Photo">Choose file</label>
									<input type="file" name="Photo" id="Photo" tabindex="1" class="custom-file-input" required>
								</div><br>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-6 col-sm-offset-3">
											<input type="submit" name="Submit" id="Submit" tabindex="4" class="form-control btn btn-register" value="Register">
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>