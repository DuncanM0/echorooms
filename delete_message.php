<?php
//delete_message.php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = $_POST['post_id'];

    $sql = "DELETE FROM posts WHERE post_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);

    if ($stmt->execute()) {
        echo "topic deleted successfully";
        header("location: index.php");
        exit;
    } else {
        echo "Error deleting post: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}