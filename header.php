<!DOCTYPE html>

<!--header.php-->

<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>EchoRooms - Forum</title>
	<link rel="stylesheet" href="../CSS/mainStyle.css" type="text/css">
	
	
</head>

<body>

<h1>EchoRooms</h1>

	<div id="wrapper">
	<div id="menu" style="display: flex;">
	
	<?php

		if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] === true) {
			echo '<a style="" class="item"  href="../PHP/profile.php">Profile</a>';
		}

	?>

		<a class="item" href="../PHP/index.php">Home</a> 
		
		<a class="item" id="topic" href="../PHP/create_topic.php">Create a topic</a> 
		
		<a class="item" id="category" href="../PHP/create_cat.php">Create a category</a>
		

		
	
		<div id="userbar" style="float:right; display:inline; margin-left: auto;">
		<?php
		
		
			
			if(isset($_SESSION['signed_in']))
			{
				echo 'Hello <b>' . $_SESSION["user_name"] . '</b>. Not you? <a href="signout.php">Sign out</a>';
			}
			else
			{
				echo '<a href="../PHP/signin.php">Sign in</a> or <a href="signup.php">create an account</a>.';
			}
			
			
		
		
		
		?>
		
		</div>
		
	</div>
	
		<div id="content">