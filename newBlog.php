<!--This page is used to allow the user to create his/her own blog post-->
<?php
	//Includes the header php script in the file 
		//Displays the header content
	Include "header.php";
	
	/*Checks if the user has enterd a title and content attempting to
	post a blog*/
	if(isset($_POST["Title"]) && isset($_POST["Blog"])){
		$title =  $_POST["Title"];
		$blog = $_POST["Blog"];
		$curr_date = date("Y-m-d");
		//Inserts the blog into the database and returns to the home page
		$sql = "INSERT INTO posts (id_user,publish_date,title,post)
		VALUES ('$ID','$curr_date','$title','$blog')";
		if ($conn->query($sql) === True) {
			$_SESSION["Success"] = "Blog has been successfully published";
			header("Location: index.php");
		}else{
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
?>
<!--This is the main content of the page-->
<div class="container">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
		<form method="post">
			<fieldset>
				<legend style="text-align:center">Create a Blog</legend>
				<div class="form-group">
					<label for="Title">Title</label>
					<input type="text" class="form-control" id="Title" name="Title" required />
				</div>
				<div class="form-group">
					<label for="Blog">Blog</label> 
					<textarea class="form-control" id="Blog" rows="3" name="Blog" required></textarea>
				</div>
			</fieldset>
			<button type="submit" class="btn btn-primary"/>Post Blog</button>
		</form>
		</div>
	</div>
</div>
<?php
	//Includes the footer php script content in the page
		//Displays the footer content
	Include "footer.php";
?>