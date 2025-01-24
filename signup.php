<?php
//signup.php
session_start();  
include 'connect.php';
include 'header.php';


echo '<div class="signForm">';
echo '<h2>Sign up</h2> </br> </br>';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    echo '<form method="post" action=""> 
<input type="text" name="user_name" placeholder="Username"/> 
<br><br>
<input type="password" name="user_pass" placeholder="Password"/> 
<br><br>
<input type="password" name="user_pass_check" placeholder="Confirm Password"/> 
<br><br>
<input type="email" name="user_email" placeholder="Email"/> 
<br><br><br><br>
<input type="submit" value="Create Account"/> 
<br><br>
</form>';
echo '</div>';
} else {

    $errors = array(); 
	if(isset($_POST['user_name']))
	{

		if(!ctype_alnum($_POST['user_name'])) {
			$errors[] = 'The username can only contain letters and digits.';
		}
		if(strlen($_POST['user_name']) > 15) {
			$errors[] = 'The username cannot be longer than 15 characters.';
		}
		
	} else {
		$errors[] = 'The username field must not be empty.';
	}
	if(isset($_POST['user_pass'])) {
		if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $_POST['user_pass'])) {
			$errors[] = "The password does not meet the requirements! Must be between 8 and 12 characters, Must have one of (!@#$%) and atleast 1 number and 1 letter";
			}
			
		if($_POST['user_pass'] != $_POST['user_pass_check']) {
			$errors[] = 'The two passwords did not match.';
		}
	} else {
		$errors[] = 'The password field cannot be empty.';
	}

	if (!empty($errors)) {
		echo "Uh-oh.. a couple of fields are not filled in correctly..";
		echo "<a href='signup.php'>Go back to sign up?</a>";
		echo "<ul>";
		foreach ($errors as $value) {
			echo "<li>" . htmlspecialchars($value) . "</li>";
		}
		echo "</ul>";
	
	} else {
		$hashed_password = password_hash($_POST['user_pass'], PASSWORD_DEFAULT);
		$sql = "INSERT INTO users(user_name, user_pass, user_email, user_date, user_level, user_bio) VALUES (?, ?, ?, NOW(), 0, '============' )";
        $stmt = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt, 'sss', $_POST['user_name'], $hashed_password, $_POST['user_email']);
        $result = mysqli_stmt_execute($stmt);
        if(!$result)
		{
			echo 'Something went wrong while registering. Please try again later.';
		} else {
			echo 'Successfully registered. You can now <a href="signin.php">sign in</a> and start posting! :-) <br><br><br><br>';
		}
	}
}
include 'footer.php';