<?php
//edit_username.php
session_start();
include 'connect.php';

if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] === true) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_username = trim($_POST['user_name']);
        $errors = [];
        if (strlen($new_username) < 3 || strlen($new_username) > 20 || !preg_match('#^[A-Za-z0-9_-]{3,20}$#s', $new_username)) {
            $errors[] = "Invalid username. It should be 3-20 characters long and can only contain letters, numbers, and underscores.";
        }
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("location: profile.php");
            exit;
        } else {
            $sql = "UPDATE users SET user_name = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_username, $_SESSION['user_id']);
            if ($stmt->execute()) {
                $_SESSION['user_name'] = $new_username;
                header("location: profile.php"); 
                exit;
            } else {
                $_SESSION['errors'] = ["Error updating Username: " . $stmt->error];
                header("location: profile.php");
                exit;
            }
        }
    } else {
        echo '<a>You must be </a><a href="../PHP/signin.php">Signed In</a><a> to edit your username :)</a>.';
    }
} else {
    header("location: signin.php");
    exit;
}
?>
