<?php
//profile.php
session_start();
include 'connect.php';
include 'header.php';


if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] === true) {
    $sql = "SELECT user_name, user_pass, user_email, user_pfp, user_level, user_date, user_bio FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $userLvl = $user['user_level'];
    $username = $user['user_name'];
    $userEmail = $user['user_email'];
    $userDate = new DateTime($user['user_date']);
    $userDate = $userDate->format('d-m-Y');
    $userBio = $user['user_bio'];
	$userPFP = $user['user_pfp'];

    echo '<h2 style="display:inline-block;">Profile</h2>';

    echo '<div class="pfpUpload">';
    echo '<form action="upload.php" method="post" enctype="multipart/form-data">';
    echo        '<b> Select file to upload: </b><br><br>';
    echo        '<input type="file" name="fileToUpload" id="fileToUpload"><br><br>';
    echo        '<input type="submit" value="Upload File" name="submit">';
    echo '</form>';

    echo '<img class="pfp" src="../Images/pfp/' . $userPFP . '" alt="' . $userPFP . '">';
    echo '</div>';

    if (isset($_SESSION['errors'])) {
        echo '<ul>';
        foreach ($_SESSION['errors'] as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        unset($_SESSION['errors']);
    }

    echo '<table border="1" id="profile">';
    echo '<tr><th>Name</th><td>';
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_username'])) {
        echo '<form method="post" action="edit_username.php">';
        echo '<input type="text" name="user_name" value="' . htmlspecialchars($username) . '"><br>';
        echo '<input type="submit" value="Update Username">';
        echo '</form>';
    } else {
        echo htmlspecialchars($username);
        echo '<form method="post" action="" style="display:inline;">';
        echo '<input type="hidden" name="edit_username" value="1">';
        echo '<input type="submit" value="Edit Username" style="float:right;">';
        echo '</form>';
    }
    echo '</td></tr>';

    echo '<tr><th>Email</th><td>';
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_email'])) {
        echo '<form method="post" action="edit_email.php">';
        echo '<input type="text" name="user_email" value="' . htmlspecialchars($userEmail) . '"><br>';
        echo '<input type="submit" value="Update Email">';
        echo '</form>';
    } else {
        echo htmlspecialchars($userEmail);
        echo '<form method="post" action="" style="display:inline;">';
        echo '<input type="hidden" name="edit_email" value="1">';
        echo '<input type="submit" value="Edit Email" style="float:right;">';
        echo '</form>';
    }
    echo '</td></tr>';

    echo '<tr><th>Password</th><td>';
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_password'])) {
        echo '<form method="post" action="edit_password.php">';
        echo '<input type="password" name="user_pass" placeholder="Enter Original Password: "><br>';
        echo '<input type="password" name="user_pass1" placeholder="Enter New Password:"><br>';
        echo '<input type="password" name="user_pass2" placeholder="Enter New Password Again:"><br>';
        echo '<input type="submit" value="Update Password">';
        echo '</form>';
    } else {
        echo '********';
        echo '<form method="post" action="" style="display:inline;">';
        echo '<input type="hidden" name="edit_password" value="1">';
        echo '<input type="submit" value="Edit Password" style="float:right;">';
        echo '</form>';
    }
    echo '</td></tr>';

    echo '<tr><th>Bio</th><td>';
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_bio'])) {
        echo '<form method="post" action="edit_bio.php">';
        echo '<textarea name="user_bio" rows="4" cols="50">' . htmlspecialchars($userBio) . '</textarea><br>';
        echo '<input type="submit" value="Update Bio">';
        echo '</form>';
    } else {
        echo htmlspecialchars($userBio);
        echo '<form method="post" action="" style="display:inline;">';
        echo '<input type="hidden" name="edit_bio" value="1">';
        echo '<input type="submit" value="Edit Bio" style="float:right;">';
        echo '</form>';
    }
    echo '</td></tr>';

    echo '<tr><th>Date Created</th><td>' . htmlspecialchars($userDate) . '</td></tr>';

    echo '<tr><th>Admin</th><td>';
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_admin'])) {
        echo '<form style="display:inline;" method="post" action="edit_admin.php">';
        echo '<input type="text" name="password" placeholder="PASSWORD"><br>';
        echo '<input type="submit"  value="Update Admin Rights">';
        echo '</form>';
    } else {
        echo htmlspecialchars($userLvl);
        echo '<form method="post" action="" style="display:inline;">';
        echo '<input type="hidden" name="edit_admin" value="1">';
        echo '<input type="submit" value="ADMIN" style="float:right;">';
        echo '</form>';
    }
    echo '</td></tr>';
    echo '</table>';
    echo '<form method="post" action="delete_account.php" style="display:inline;">';
    echo '<input type="hidden" name="user_id" value="' . $_SESSION["user_id"] . '"><hr>';
    echo '<div class="div1">';
    echo '<button class="Center" id="delete" type="submit">DELETE ACCOUNT</button>';
    echo '</div>';
    echo '</form>';
} else {
    echo '<a>You must be </a><a href="../PHP/signin.php">Signed In</a><a> to view your profile :)</a>.';
}




include 'footer.php';