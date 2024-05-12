<!DOCTYPE html>

<!-- Andy Estevez / Smedly Moise -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC Resolve Project -->
<!-- Sign Up Page -->

<?php
    // PHP / Data Set Up
    session_start();

    include("config.php");
    include("functions.php");

    // When Sign Up Form Is Submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $accountType = $_POST["accountType"];
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Verify Inputs Not Empty
        if (!empty($username) && !empty($email) && !empty($password)) {
            // Generate User ID
            $user_id = random_num(10);
            
            // Construct Appropriate Query
            if ($accountType == "student") {
                $query = "insert into students (studentID, username, email, password) values ('$user_id', '$username', '$email', '$password')";
            }
            else if ($accountType == "faculty") {
                $query = "insert into faculty (facultyID, username, email, password) values ('$user_id', '$username', '$email', '$password')";
            }
            else {
                die("ERROR: Invalid account type during account registration.");
            }

            // Verify Query Successful
            if (mysqli_query($conn, $query)) {
                header("Location: login.php");
                die;
            }
            else {
                die("ERROR: Account sign up failed.");
            }
        }
        else {
            // Handle Empty Inputs Here
        }
    }
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <title>BMCC Resolve | Sign Up</title>
    </head>

    <body class="loginBody">
        <!-- Header / Navigation Bar-->
        <nav>
            <!-- Logo -->
            <a href="index.html">
                <img class="BMCCLogo" src="Elements\bmcc-logo-resolve.png" alt="BMCC Logo" height="50px">
            </a>

            <!-- Button -->
            <div class="NavButtonsContainer">
                <button type="button" class="navButton" onclick="location.href='index.html'">Home</button>
            </div>
        </nav>

        <!------------->
        <!-- Content -->
        <!------------->

        <!-- Sign Up Div -->
        <div class="loginDiv">
            <p class="loginHeader">Sign Up</p>

            <!-- Registration Form -->
            <form class="loginForm" method="post">
                <input type="text" name="username" class="loginFormElement" placeholder="Enter Name" autocomplete="name">
                <input type="email" name="email" class="loginFormElement" placeholder="Enter Email" autocomplete="email">
                <input type="password" name="password" class="loginFormElement" placeholder="Enter Password">

                <div>
                    <label>
                        <input type="radio" name="accountType" value="student" checked>Student
                    </label>
                    <label>
                        <input type="radio" name="accountType" value="faculty">Faculty
                    </label>
                </div>

                <input type="submit" value="Register" class="loginFormButton">

                <a class="registerLink" href="login.php">Already have an account? Log in here.</a>
            </form>
        </div>

        <!-- Footer -->
        <footer>
            <a class="footerLink" href="https://maps.app.goo.gl/87HcM8tEhsrWe9wH6" target="_blank">
                <p class="footerText"><u>
                    Borough of Manhattan Community College <br>
                    The City University of New York <br>
                    199 Chambers Street <br>
                    New York, NY 10007
                </u></p>
            </a>
        </footer>
    </body>
</html>