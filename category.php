<?php
// create_cat.php 
session_start();
include 'connect.php';
include 'header.php';

if (!isset($_GET['id'])) {
    die('Category ID not provided.');
}

$cat_id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "SELECT 
cat_id, 
cat_name, 
cat_description 
FROM 
categories 
WHERE 
cat_id = '$cat_id'";

$result = mysqli_query($conn, $sql);
if (!$result) {
    echo 'The category could not be displayed, please try again later. ' . mysqli_error($conn);
} else {
    if (mysqli_num_rows($result) == 0) {
        echo 'This category does not exist.';
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<h2>Topics in ' . htmlspecialchars($row['cat_name']) . ' category</h2>';
        }

        $sql = "SELECT 
topic_id, 
topic_subject, 
topic_date, 
topic_cat 
FROM 
topics 
WHERE 
topic_cat = '$cat_id'";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            echo 'The topics could not be displayed, please try again later.';
        } else {
            if (mysqli_num_rows($result) == 0) {
                echo 'There are no topics in this category yet.';
            } else {
                echo '<table border="1"> 
<tr> 
<th>Topic</th> 
<th>Created at</th> 
</tr>';	
                while ($row = mysqli_fetch_assoc($result)) {				
                    echo '<tr>';
                    echo '<td class="leftpart">';
                    echo '<h3><a href="topic.php?id=' . htmlspecialchars($row['topic_id']) . '">' . htmlspecialchars($row['topic_subject']) . '</a></h3>';
                    echo '</td>';
                    echo '<td class="rightpart">';
                    echo '<p id="center">' . date('d/m/Y  H:i', strtotime($row['topic_date'])) . '</p>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
        }
    }
}
include 'footer.php';