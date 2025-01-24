<?php
//index.php
session_start();
include 'connect.php';
include 'header.php';

$errors = array(); 

if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] === true) {
    $user_level = $_SESSION['user_level'];
}

$sql_categories = "SELECT cat_id, cat_name, cat_description FROM categories ORDER BY cat_name ASC";
$result_categories = mysqli_query($conn, $sql_categories);


?>

<br><form style="text-align:center;" action="userSearch.php" method="GET">
	<input id="search" name="search" type="text" placeholder="User Search">
	<input id="submit" type="submit" value="Search">
</form><br><br>

<?php

if (!$result_categories) {
    echo 'The categories could not be displayed, please try again later.';
} else {
    if (mysqli_num_rows($result_categories) == 0) {
        echo 'No categories defined yet.';
    } else {
        echo '<table border="1">
                <tr>
                    <th>Category</th>
                    <th id="center">Last Topic</th>';
        
        if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] === true && $user_level) {
            echo '<th>Actions</th>';
        }
        echo '</tr>';
        
        while ($category = mysqli_fetch_assoc($result_categories)) {
            echo '<tr>';
            echo '<td class="leftpart">';
            echo '<h3><a href="category.php?id=' . $category['cat_id'] . '">' . htmlspecialchars($category['cat_name']) . '</a></h3>';
            echo htmlspecialchars($category['cat_description']);
            echo '</td>';

            $sql_topic = "SELECT topic_id, topic_subject, topic_date FROM topics 
                          WHERE topic_cat = " . $category['cat_id'] . " 
                          ORDER BY topic_date DESC LIMIT 1";
            $result_topic = mysqli_query($conn, $sql_topic);

            echo '<td class="middlepart">';
            if ($result_topic && mysqli_num_rows($result_topic) > 0) {
                $topic = mysqli_fetch_assoc($result_topic);
                echo '<a id="center" href="topic.php?id=' . $topic['topic_id'] . '">' . htmlspecialchars($topic['topic_subject']) . '</a>';
                echo "<p style='border: 2px; text-align: center;'>" . date('d/m/Y', strtotime($topic['topic_date'])) . "</p>";
            } else {
                echo '<p id="center">No topics yet</p>';
            }
            echo '</td>';

            if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] === true && $user_level) {
                echo '<td class="rightpart">';
                echo '<form method="post" action="delete_topic.php" style="display:inline;">';
                echo '<input type="hidden" name="topic_id" value="' . ($topic['topic_id'] ?? '') . '">';
                echo '<button type="submit" id="delete">Delete Topic</button>';
                echo '</form>';
                echo '<hr>';
                echo '<form method="post" action="delete_category.php" style="display:inline;">';
                echo '<input type="hidden" name="cat_id" value="' . $category['cat_id'] . '">';
                echo '<button type="submit" id="delete">Delete Category</button>';
                echo '</form>';
                echo '</td>';
            }

            echo '</tr>';
        }
        echo '</table>';
    }
}

include 'footer.php';