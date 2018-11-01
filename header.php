<!--This php script is used to display the header and navigation content in other pages,
link pages to bootstrap style and javascript files to allow for bootsrap as well as connect
pages to the database-->
<?php
	//Includes the connect php script
		//Connects to the database
	Include "connect.php";
	//Checks if the user is logged in
	/*If the user is not logged in then they are redirected to the login page.
	Otherwise, the session variables are called to retrieve user data*/
	session_start();
	if ($_SESSION["userID"] == null){
		header("Location: login.php");
	}else{
		$ID = $_SESSION['userID'];
		$username = $_SESSION["username"];
		$success = $_SESSION["Success"];
	}
?>
	<!--Links the file to the bootstrap style and javascript files-->
	<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Foodblog</title>

	<!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="css/clean-blog.min.css" rel="stylesheet">
	
	</head>
	<body>
	 
	<!--Displays the naviagtion bar-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Profile.php">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="newBlog.php">Create Post</a>
            </li>
			<li class="nav-item">
              <a class="nav-link" href="friendsList.php">Friends</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php?action=logout">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!--Displays the page header-->
    <header class="masthead" style="background-image: url('img/FoodBlog.jpg')">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
            <div class="site-heading">
              <h1>Food Blogs</h1>
              <span class="subheading">A Blog Site All About Food</span>
            </div>
          </div>
        </div>
      </div>
    </header>