<?php
// create_cat.php 
session_start();
include 'header.php';
include 'connect.php';

echo"<h2>Create Catogory</h2>";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<form method='post' action=''> 
<h4>Category name: </h4>
<input type='text' name='cat_name' /> 
<br>
<h4>Category description:</h4>
<textarea name='cat_description'></textarea> 
<br><br>
<input type='submit' value='Add category' /> 
</form>";
} else {
    $cat_name = $_POST['cat_name'];
    $cat_description = $_POST['cat_description'];
    $sql = "INSERT INTO categories (cat_name, cat_description) 
VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $cat_name, $cat_description);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        echo 'Error: ' . mysqli_error($conn);
    } else {
        echo 'New category successfully added.';
    }
    mysqli_stmt_close($stmt);
}

include 'footer.php';