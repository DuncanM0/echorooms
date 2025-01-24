<?php
//edit_password.php
session_start();
include 'connect.php';

if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] === true) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $original_password = trim($_POST['user_pass']);
        $new_password = trim($_POST['user_pass1']);
        $matching_pass = trim($_POST['user_pass2']);

        $stmt = $conn->prepare("SELECT user_pass FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($stored_password);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($original_password, $stored_password)) {
            if ($new_password === $matching_pass) {
                if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $new_password)) {
                    $error = "The password does not meet the requirements!";
                    header("Location: profile.php?error=" . urlencode($error));
                    exit;
                }
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET user_pass = ? WHERE user_id = ?");
                $stmt->bind_param("si", $hashed_new_password, $_SESSION['user_id']);
                if ($stmt->execute()) {
                    $_SESSION['user_pass'] = $hashed_new_password; 
                    session_regenerate_id(true);
                    header("Location: profile.php?success=Password updated successfully");
                    exit;
                } else {
                    $error = "Error updating password: " . $stmt->error;
                    header("Location: profile.php?error=" . urlencode($error));
                    exit;
                }
            } else {
                $error = "Passwords do not match.";
                header("Location: profile.php?error=" . urlencode($error));
                exit;
            }
        } else {
            $error = "Original password is incorrect.";
            header("Location: profile.php?error=" . urlencode($error));
            exit;
        }
    }
} else {
    echo '<a>You must be </a><a href="../PHP/signin.php">Signed In</a><a> to edit your profile :)</a>.';
}