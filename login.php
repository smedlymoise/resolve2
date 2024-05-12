<!DOCTYPE html>

<!-- Andy Estevez / Smedly Moise -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC Resolve Project -->
<!-- Log In Page -->

<?php
    // PHP / Data Set Up
    session_start();

    include("config.php");
    include("functions.php");

    // When Log In Form Is Submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $accountType = $_POST["accountType"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Verify Inputs Not Empty
        if (!empty($email) && !empty($password)) {
            // Check For Admin Log In
            $query = "select * from admins where email = '$email' limit 1";  
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $accountType = "admin";
            }
            else if ($accountType == "student") {
                // If Not Admin, Check For Student
                $query = "select * from students where email = '$email' limit 1";
                $result = mysqli_query($conn, $query);
            }
            else if ($accountType == "faculty") {
                // If Not Admin, Check For Faculty
                $query = "select * from faculty where email = '$email' limit 1";
                $result = mysqli_query($conn, $query);
            }
            else {
                die("ERROR: Invalid account type during account log in.");
            }

            // Verify Query & Results Exist
            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
                    
                // If Password Matches
                if ($user_data["password"] === $password) {
                    // Init Session Data & Redirect User To Appropriate Console
                    if ($accountType == "student") {
                        $_SESSION["studentID"] = $user_data["studentID"];
                        header("Location: studentHome.php");
                        die;
                    }
                    else if ($accountType == "faculty") {
                        $_SESSION["facultyID"] = $user_data["facultyID"];
                        header("Location: facultyHome.php");
                        die;
                    }
                    else if ($accountType == "admin") {
                        $_SESSION["adminID"] = $user_data["adminID"];
                        header("Location: adminHome.php");
                        die;
                    }
                    else {
                        die("ERROR: Invalid account type during account log in.");
                    }
                }
            }
            
            /* Handle Incorrect Input Here */
        }
        else {
            /* Handle Invalid Input Here */
        }
    }
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <title>BMCC Resolve | Log In</title>
    </head>

    <body class="loginBody">
        <!-- Header / Navigation Bar -->
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

        <!-- Log In Div -->
        <div class="loginDiv">
            <p class="loginHeader">Log In</p>

            <!-- Log In Form -->
            <form class="loginForm" method="post">
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

                <input type="submit" value="Log In" class="loginFormButton">

                <a class="registerLink" href="signup.php">Don't have an account? Register here.</a>
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