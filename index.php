<!--This is the home page which is used to display all the blog posts created on the site as well as the comments
that were posted on each blog post-->
<?php
	//Includes the header php script in the file 
		//Displays the header content
	Include "header.php";
	
	//Checks if the post and comment variables have already been set
	$hiddenPostID = null;
	$comment = null;
	if(isset($_POST["comment"])){
		$comment = $_POST["comment"];
	}
	if (isset($_POST["hiddenPostID"])){
		$hiddenPostID = $_POST["hiddenPostID"];
	}
	
	//Checks if the comment is not null when a comment is posted
	if ($comment != null && $hiddenPostID != null) {
		$curr_date = date("Y-m-d");
		$comment = $_POST["comment"];
		
		//Inserts the comment into the database
		$sql = "INSERT INTO comments (id_post,id_user,post_date,comment)
		VALUES ('$hiddenPostID', '$ID', '$curr_date', '$comment')";
		if ($conn->query($sql) === True) {
			$hiddenPostID = null;
			$comment = null;
			$_SESSION["Success"] = "Comment successfully posted.";
		}else{
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
?>

<!--This is the main content of the page-->
<div class="container">
  <div class="row">
	<div class="col-lg-8 col-md-10 mx-auto">
		<div class="post-preview">
		<?php
			//Displays a success message when a comment is successfully posted
			if ($_SESSION["Success"] != null) {?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<?php echo $_SESSION["Success"] ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div><?php
				$_SESSION["Success"] = "";
			}
		?>
		</div>
	<?php
		//Used to fetch all blog posts from the database and display them with their corresponding comments
		$sql = "SELECT posts.id_post, posts.id_user, posts.publish_date, posts.title, 
		posts.post, user_data.first_name, user_data.last_name
		FROM posts
		INNER JOIN user_data ON user_data.id_user = posts.id_user";
		$posts = $conn->query($sql);
		if ($posts->num_rows > 0) {
			while($row = $posts->fetch_assoc()) {?>
				<!--Displays the blog post-->
				<div class="post-preview">
					<h1 class="post-title"><?php echo $row["title"] ?></h1>
					<p class="post-meta">By <?php echo $row["first_name"]." ".$row["last_name"]." on ".$row["publish_date"] ?></p>
					<p><?php echo $row["post"] ?></p>
					<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="<?php echo "#collapseComment".$row["id_post"] ?>" 
					aria-expanded="false" aria-controls="<?php echo "collapseComment".$row["id_post"] ?>">
						View Comments
					</button>
					<!--Displays a button that, when clicked, displays the comments for the corresponding blog post-->
					<div class="collapse" id="<?php echo "collapseComment".$row["id_post"] ?>">
						<div class="card card-body">
							<form name="<?php echo "postComment".$row["id_post"] ?>" method="post">
								<textarea name="comment"></textarea>
								<!--Hidden value that stores the post ID so that comments posted will be
								stored with the correct post ID-->
								<input type="hidden" name="hiddenPostID" value="<?php echo $row["id_post"] ?>"/>
								<button type="Submit" class="btn btn-secondary btn-sm">Post Comment</button>
							</form>
							<?php
								//Fetches the comments for the corresponding blog
								$postID = $row["id_post"];
							
								$sql = "SELECT comments.id_comment, comments.post_date, comments.comment,
								user_data.first_name, user_data.last_name
								FROM comments
								INNER JOIN user_data ON user_data.id_user = comments.id_user
								WHERE comments.id_post = '$postID'";
								if ($stmt = $conn->prepare($sql)) {
									$stmt->execute();
									$stmt->bind_result($commentID,$postDate,$comment,$name,$surname);
									while ($stmt->fetch()) {?>
										<div class="post-meta">
											<small class="text-muted"><?php echo $name." ".$surname." | (".$postDate.")"?></small><br>
											<?php echo $comment ?>
										</div>
										<?php
									}
								}
							?>
						</div>
					</div>
				</div>
				<hr>
				<?php
			}
		}?>
	  
	  <!--Button to take the user to the create blogs page-->
	  <div class="clearfix">
		<a class="btn btn-primary float-right" href="newBlog.php">Create a Post &rarr;</a>
	  </div>
	</div>
  </div>
</div>

<?php
	//Includes the footer php script content in the page
		//Displays the footer content
	Include "footer.php";
?>