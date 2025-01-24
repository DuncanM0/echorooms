<?php
//delete_topic.php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $topic_id = $_POST['topic_id'];

    $sql = "DELETE FROM topics WHERE topic_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $topic_id);

    if ($stmt->execute()) {
        echo "topic deleted successfully";
        header("location: index.php");
        exit;
    } else {
        echo "Error deleting topic: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}