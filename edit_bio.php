<?php
//edit_bio.php
session_start();
include 'connect.php';

if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] === true) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_bio = trim($_POST['user_bio']);
        
        $sql = "UPDATE users SET user_bio = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_bio, $_SESSION['user_id']);

        if ($stmt->execute()) {
            $_SESSION['user_bio'] = $new_bio; // Update session variable
            header("location: profile.php"); // Redirect back to profile page
            exit;
        } else {
            $error = "Error updating Bio: " . $stmt->error;
        }
    }
} else {
    echo '<a>You must be </a><a href="../PHP/signin.php">Signed In</a><a> to edit your bio :)</a>.';
}
