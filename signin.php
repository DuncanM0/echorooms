<?php
//signin.php
session_start();
include "connect.php";
include "header.php";

echo '<div class="signForm">';
echo "<h2>Sign in</h2><br><br>";


if (isset($_SESSION["signed_in"]) && $_SESSION["signed_in"] == true) {
    echo 'You are already signed in, you can <a href="signout.php">sign out</a> if you want. <br><br><br><br>';
} else {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        echo '<form method="post" action="signin.php" style="float: center;"> 
                <input type="text" name="user_name" placeholder="Username"/> 
                <br><br>
                <input type="password" name="user_pass" placeholder="Password"/> 
                <br><br><br><br>
                <input type="submit" value="Sign in" /> 
				<br><br>
              </form>';
		echo '</div>';
    } else {
        $errors = [];

        if (empty($_POST["user_name"])) {
            $errors[] = "The username field must not be empty.";
        }

        if (empty($_POST["user_pass"])) {
            $errors[] = "The password field must not be empty.";
        }

        if (!empty($errors)) {
            echo "Uh-oh.. a couple of fields are not filled in correctly..";
            echo "<a href='signin.php'>Go back to sign in?</a>";
            echo "<ul>";
            foreach ($errors as $value) {
                echo "<li>" . htmlspecialchars($value) . "</li>";
            }
            echo "</ul>";
        } else {
            $sql = "SELECT user_id, user_name, user_level, user_pass, user_pfp FROM users WHERE user_name = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $_POST["user_name"]);
            $result = mysqli_stmt_execute($stmt);

            if (!$result) {
                echo "Something went wrong while signing in. Please try again later. <br><br><br><br>";
            } else {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 0) {
                    echo "You have supplied a wrong user/password combination. Please try again. <br><br><br><br>";
                } else {
                    $row = mysqli_fetch_assoc($result);
                    if (password_verify($_POST["user_pass"], $row["user_pass"])) {
                        $_SESSION["signed_in"] = true;
                        $_SESSION["user_id"] = $row["user_id"];
                        $_SESSION["user_name"] = $row["user_name"];
                        $_SESSION["user_level"] = $row["user_level"];
                        $_SESSION["user_pfp"] = $row["user_pfp"];
                        echo "Welcome, " . htmlspecialchars($_SESSION["user_name"]) . '. <a href="index.php">Proceed to the forum overview</a>. <br><br><br><br>';
                    } else {
                        echo "You have supplied a wrong user/password combination. Please try again. <br><br><br><br>";
                    }
                }
            }
        }
    }
}

include "footer.php";