<?php
//delete_category.php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cat_id = $_POST['cat_id'];

    $sql = "DELETE FROM categories WHERE cat_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cat_id);

    if ($stmt->execute()) {
        echo "Category deleted successfully";
        header("location: index.php");
        exit;
    } else {
        echo "Error deleting category: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}