<?php
// topic.php
session_start();
include 'connect.php';
include 'header.php';

$errors = array(); 

$topic_id = $_GET['id'];

$sql = "SELECT topic_subject, topic_id, topic_by, topic_date FROM topics WHERE topic_id = '$topic_id'";
$result = mysqli_query($conn, $sql);


     
if (!$result) {
    echo 'The topic could not be displayed, please try again later.';
} else {
    if (mysqli_num_rows($result) == 0) {
        echo 'This topic does not exist.';
    } else {
        $topic = mysqli_fetch_assoc($result);
        echo '<h2>' . htmlspecialchars($topic['topic_subject']) . '</h2>';
        if (isset($_SESSION['user_id'])){
            if ($_SESSION['user_level']) {
                echo '<form method="post" action="delete_topic.php">';
                echo '<input type="hidden" name="topic_id" value="' . $topic['topic_id'] . '">';
                echo '<input id="delete" type="submit" name="delete_topic" value="Delete Topic">';
                echo '</form><hr>';
            }
        }
        $sql = "SELECT
            posts.post_content,
            posts.post_date,
            posts.post_by,
			posts.post_id,
            users.user_name,
            users.user_id,
            users.user_pfp

        FROM
            posts
        LEFT JOIN
            users
        ON
            posts.post_by = users.user_id
        WHERE
            posts.post_topic = '" . mysqli_real_escape_string($conn, $_GET['id']) . "'
        ORDER BY
            posts.post_date ASC";
        $posts_result = mysqli_query($conn, $sql);

        if (!$posts_result) {
            echo 'The posts could not be displayed, please try again later.';
        } else {
            if (mysqli_num_rows($posts_result) == 0) {
                echo 'No posts in this topic yet.';
            } else {
                while ($post = mysqli_fetch_assoc($posts_result)) {
                    echo '<div class="messageBox">';
                    echo '<p>' . htmlspecialchars($post['post_content']) . '</p>';
                    echo '<img style="float:right;" class="pfp" src="../Images/pfp/' . $post['user_pfp'] . '" alt="' . $post['user_pfp'] . '">';
                    echo '<b>Posted by: ' . htmlspecialchars($post['user_name']) . ' on ' . date('Y-m-d G:i', strtotime(($post['post_date'])))  .  '</b><br><br>';
                    if (isset($_SESSION['user_id'])){
                        $user_id = $_SESSION['user_id'];
                        if ($post['post_by'] == $user_id OR $_SESSION['user_level']){
                            echo '<form method="post" action="delete_message.php">';
                            echo '<input id="delete" type="submit" name="delete_message" value="Delete">';
							echo '<input type="hidden" name="post_id" value="' . $post['post_id'] . '">';
                            echo '</form>';
                        }
                    }

                    echo '</div><hr>';
                }
            }
        }

        if (isset($_SESSION['user_id'])) {
            echo '<h3>Post a Reply</h3>';
            echo '<form method="post" action="topic.php?id=' . $topic_id . '">';
            echo '<textarea name="reply_content" required></textarea><br>';
            echo '<button type="submit" class="item">Post Reply</button>';
            echo '</form>';
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply_content'])) {
                $reply_content = mysqli_real_escape_string($conn, $_POST['reply_content']);
                $user_id = $_SESSION['user_id'];

                $sql = "INSERT INTO posts (post_content, post_date, post_topic, post_by) VALUES (?, NOW(), ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sii", $reply_content, $topic_id, $user_id);
                $stmt->execute();

                header("Location: topic.php?id=" . $topic_id);
                exit;
            }
        } else {
            echo 'You must be <a href="signin.php">Logged in</a> to post a message';
        }
    }
}



include 'footer.php';