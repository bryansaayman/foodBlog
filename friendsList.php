<?php
	Include "header.php";
	
	$action = null;
	$friendID = null;
	if(isset($_GET['action'])){
		$action = $_GET['action'];
	}
	
	if(isset($_GET['userid'])){
		$friendID = $_GET['userid'];
	}
	if ($action == "addfriend") {
		
		$sql = "SELECT first_name, last_name FROM user_data WHERE id_user = '".$friendID."'";
		if ($stmt = $conn->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($name,$surname);
			$stmt->fetch();
			$stmt->close();
		}
		$sql = "INSERT INTO friends (id_user, id_user_friend)
		VALUES ('$ID', '$friendID')";
		if ($conn->query($sql) === True) {
			$sql  = "INSERT INTO friends (id_user, id_user_friend)
			VALUES ('$friendID', '$ID')";
			if ($conn->query($sql) === True) {
				$_SESSION["Success"] = $name." ".$surname." is now your friend";
			}else{
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}else{
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
?>
<div class="container">
	<div class="row">
		<div class="col-lg-8 col-md-10 mx-auto">
			<div class="post-preview"><?php
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
			<form method="post" action="friendsList.php?action=search">
				<fieldset>
				<div class="form-group">
					<label for="Friend">Search for friends</label>
					<input type="text" class="form-control" id="Friend" name="Friend">
				</div>
				<fieldset>
				<button type="submit" class="btn btn-primary" name="Search">Search</button>
			</form>
			<br><br><hr>
			<div class="post-preview">
				<?php
					if ($action == "search"){
							$search = $_POST["Friend"];
							$sql = "SELECT id_user, status, first_name, last_name, CONCAT(first_name,' ',last_name) 
							FROM user_data 
							WHERE CONCAT(first_name,' ',last_name) LIKE '%".$search."%' AND id_user <> '".$ID."'";
					}else{
						$sql = "SELECT user_data.id_user, user_data.first_name, user_data.last_name, 
						user_data.status FROM user_data WHERE id_user <> '".$ID."'";
					}
					$users = $conn->query($sql);
					if ($users->num_rows > 0) {
							while ($row = $users->fetch_assoc()){?>
								<form method="post" action="friendsList.php?action=addfriend&userid=<?php echo $row['id_user'] ?>">
									<fieldset>
										<div class="media">
											<div class="media-body">
												<h5>
												  <?php echo $row["first_name"]." ".$row["last_name"] ?><br>
												  <small class="text-muted"><?php echo $row["status"] ?></small>
												  <input type="hidden" name="hiddenUserID" value="<?php echo $row["id_user"] ?>"/> 
												</h5>	
											</div><?php
											$sql = "SELECT id_user, id_user_friend FROM friends WHERE id_user = '".$ID."' AND id_user_friend = '".$row['id_user']."'";
											$friend = $conn->query($sql);
											if ($friend->num_rows <> 0) {?>
												<button type="submit" class="btn btn-secondary" disabled>Add Friend</button><?php
											}else{?>
												<button type="submit" class="btn btn-success">Add Friend</button><?php
											}?>
										</div>
									</fieldset>
								</form>
								<br><?php
							}
					}else{
						echo "no results found";
					}
				?>
			</div>
		</div>
	</div>
</div>
<?php
	Include "footer.php";
?>