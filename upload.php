<?php
session_start();
include 'connect.php';
include 'header.php';

$target_dir = "../Images/pfp/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if(isset($_POST["submit"])) {
    if(empty($_POST["submit"])) {
        echo "No file Selected.";
        exit();
    }
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}


if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

$allowed_types = ["jpg", "jpeg", "png", "gif"];
if (!in_array($imageFileType, $allowed_types)) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded.";
        
        $new_upload = basename($_FILES["fileToUpload"]["name"]);
        $sql = "UPDATE users SET user_pfp = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_upload, $_SESSION['user_id']);

        if ($stmt->execute()) {
            $_SESSION['user_pfp'] = $new_upload; 
            header("location: profile.php"); 
            exit;
        } else {
            echo "Error updating profile picture: " . $stmt->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

include 'footer.php';