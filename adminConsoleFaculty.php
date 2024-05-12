
<?php
    session_start();
    
    include("config.php");
    include("functions.php");
    
    $user_data = check_admin_login($conn);
?>

<?php
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
   
        // NOTIFACATION//
// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $Title = $_POST["Title"];
    $message = $_POST["Message"];
   

    // Insert data into the table
    $sql = "INSERT INTO notifications (Title,  Message) VALUES ('$Title', '$message')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Announcement sent successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>


<!DOCTYPE html>

<!-- Andy Estevez / Smedly Moise -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC Resolve Project -->
<!-- Admin Console (Faculty) Page -->


<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <title>BMCC Resolve | Admin Console</title>
    </head>

    <body>
    <nav>
            <a href="https://www.bmcc.cuny.edu" target="_blank" onclick="return confirm('This will take you to the main BMCC page')">
                <img class="BMCCLogo" src="Elements\bmcc-logo-resolve.png" alt="BMCC Logo" height="50px">
            </a>
            <div class="NavButtonsContainer">
                <button type="button" class="navButton" onclick="location.href='adminHome.php'">Home</button>
                <button type="button" class="navButton" onclick="location.href='adminConsoleClasses.php'">Console</button>
                <button type="button" class="navButton" onclick="location.href='adminProfile.php'">Profile</button>
                <button type="button" class="navButton" id="login" onclick="location.href='logout.php'">Log Out</button>
            </div>
        </nav>
            <!-- block -->
            <div class="classesBlock" id="leftAligned">
            <!-- View Select Buttons -->
            <div class="facultyClassesBlockHead">
            <button type="button" class="adminConsoleButton" onclick="location.href='adminConsoleClasses.php'">Classes</button>
                 <button type="button" class="adminConsoleButton" onclick="location.href='adminConsoleFaculty.php'" >Faculty</button>
                 <button type="button" class="adminConsoleButton" onclick="location.href='adminConsoleStudent.php'" >Students</button>
            </div>
            <!-- Search Bar -->
            <input type="text" class="searchBar" placeholder="Search">

            <?php
                    $professorsQuery = "SELECT DISTINCT u.*
                                        FROM faculty AS u
                                        INNER JOIN classes AS c ON u.facultyID = c.facultyID
                                        ORDER BY u.username ASC";

                    $professorsResult = mysqli_query($conn, $professorsQuery);

                    if ($professorsResult && mysqli_num_rows($professorsResult) > 0) {
                        if (mysqli_num_rows($professorsResult) > 3)
                            echo("<div class='classesBlockBody' style='border-radius: 15px 0 0 15px'>");
                        else
                            echo("<div class='classesBlockBody'>");

                        while ($professor = mysqli_fetch_assoc($professorsResult)) {
                            echo("
                                <div class='professorBlockItem'>
                                    <h4 class='classBlockItemInfo'>Professor: $professor[username]</h4>
                                </div>
                                <hr>
                            ");
                        }

                        echo("</div>");
                    }
                    else {
                        echo("
                            <div class='professorsBlockBody'>
                                <p style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%)' class='professorBlockItemInfo'>No professors found.</p>
                            </div>
                        ");
                    }
                ?>

        </div>

        <div class="addClassFormDiv">
        <p class="loginHeader">Send Notification</p>

            <form class="loginForm" method="post">
                <input type="text" name="Title" class="loginFormElement" placeholder="Enter Title"><br>

                <input type="text" name="Message" class="loginFormElement" placeholder="Message" ><br>

                <input type="submit" value="Send" class="loginFormButton">

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