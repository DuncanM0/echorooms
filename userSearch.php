<?php
session_start();
include 'connect.php';
include 'header.php';

$username = mysqli_real_escape_string($conn, $_GET['search']);

$sql = "SELECT * FROM users WHERE user_name LIKE '%" . $username . "%'";

$result = mysqli_query($conn, $sql);

echo "<h2 style='text-align: center;'>Search results</h2>";

echo '<br><form style="text-align:center;" action="userSearch.php" method="GET">';
echo '<input id="search" name="search" type="text" placeholder="User Search">';
echo '<input id="submit" type="submit" value="Search">';
echo '</form><br><br>';

echo '<div class="profileContainer">';

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='profileBox'>";
        echo '<img style="float: left; margin-top: 20px; margin-right: 15px" class="pfp" src="../Images/pfp/' . $row["user_pfp"] . '" alt="' . $row["user_pfp"] . '">';
        echo "<b>" . $row["user_name"] . "<br></b>";
        echo "<p>" . $row["user_bio"] . "</p>";
        echo "</div>";
    }
} else {
    echo "0 results";
}

echo '</div>';

include 'footer.php';