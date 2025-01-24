<?php
// create_topic.php
session_start();
include 'connect.php';
include 'header.php';

echo '<h2>Create a topic</h2>';

if (!isset($_SESSION['signed_in']) || $_SESSION['signed_in'] !== true) {
    echo 'Sorry, you have to be <a href="../PHP/signin.php">signed in</a> to create a topic.';
    include 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $sql = "SELECT cat_id, cat_name, cat_description FROM categories";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        echo 'Error while selecting from database. Please try again later.';
    } else if (mysqli_num_rows($result) == 0) {
        echo $_SESSION['user_level'] == 1 ? 'You have not created categories yet.' : 'Before you can post a topic, you must wait for an admin to create some categories.';
    } else {
        echo '<form method="post" action=""> 
              <h4>Subject:</h4>
              <input type="text" name="topic_subject" required /> 
              <h4>Category:</h4>
              <select name="topic_cat" required>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . htmlspecialchars($row['cat_id']) . '">' . htmlspecialchars($row['cat_name']) . '</option>';
        }
        echo '</select> 
              <h4>Message:</h4>
              <textarea name="post_content" required></textarea> <br><br>
              <input type="submit" value="Create topic" /> 
              </form>';
    }
} else {
    mysqli_begin_transaction($conn);

    $topic_subject = mysqli_real_escape_string($conn, $_POST['topic_subject']);
    $topic_cat = mysqli_real_escape_string($conn, $_POST['topic_cat']);
    $topic_by = $_SESSION['user_id'];
    
    $sql = "INSERT INTO topics(topic_subject, topic_date, topic_cat, topic_by) VALUES('$topic_subject', NOW(), '$topic_cat', '$topic_by')";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo 'An error occurred while inserting your data. Please try again later.' . mysqli_error($conn);
        mysqli_rollback($conn);
    } else {
        $topicid = mysqli_insert_id($conn);
        $post_content = mysqli_real_escape_string($conn, $_POST['post_content']);
        $post_topic = $topicid;
        $post_by = $_SESSION['user_id'];

        $sql = "INSERT INTO posts(post_content, post_date, post_by, post_topic) VALUES ('$post_content', NOW(), '$post_by', '$post_topic')";
        $result = mysqli_query($conn, $sql);
        
        if (!$result) {
            echo 'An error occurred while inserting your post. Please try again later.' . mysqli_error($conn);
            mysqli_rollback($conn);
        } else {
            mysqli_commit($conn);
            echo 'You have successfully created <a href="topic.php?id=' . $topicid . '">your new topic</a>.';
        }
    }
}

include 'footer.php';