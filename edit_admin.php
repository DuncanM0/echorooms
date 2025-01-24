<?php
//edit_admin.php
session_start();
include 'connect.php';

$hardcoded_password = "1234"; 

if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] === true) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $input_password = $_POST['password']; 
		
		if ($input_password === "rick"){
			header("location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
            exit();
		}

        if ($hardcoded_password === $input_password){
            $_SESSION['user_level'] =  true; 

            $sql = "UPDATE users SET user_level = 1 WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $stmt->close();

            header("location: profile.php");
            exit();
        } elseif ($hardcoded_password !== $input_password){
            $_SESSION['user_level'] =  false; 

            $sql = "UPDATE users SET user_level = 0 WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $stmt->close();

            header("location: profile.php");
            exit();
        } else {
            header("location: profile.php");
            exit();
        } 
    }
}