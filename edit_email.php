<?php
//edit_email.php
session_start();
include 'connect.php';

if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] === true) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_email = trim($_POST['user_email']);
        
        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $new_email)) {
            $error = "Invalid Email";
			header("location: profile.php");
        } else {
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("location: profile.php");
                exit;
            }
            $sql = "UPDATE users SET user_email = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_email, $_SESSION['user_id']);

            if ($stmt->execute()) {
                $_SESSION['user_email'] = $new_email; // Update session variable
                header("location: profile.php"); // Redirect back to profile page
                exit;
            } else {
                $error = "Error updating Email: " . $stmt->error;
				header("location: profile.php");
            } if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("location: profile.php");
                exit;
            }
        }
    }
} else {
    echo '<a>You must be </a><a href="../PHP/signin.php">Signed In</a><a> to edit your Profile :)</a>.';
}
