<!DOCTYPE html>

<!-- Andy Estevez / Smedly Moise -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC Resolve Project -->
<!-- Assignment Submission Confirmation Page -->

<?php
    // PHP / Data Set Up
    session_start();

    include("config.php");
    include("functions.php");

    $user_data = check_student_login($conn);
    $classID = $_GET["cID"];
    $assignmentID = $_GET["aID"];
    $assignmentTitle = urldecode($_GET["aT"]);
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <title>BMCC Resolve | Student | Assignment Submission Confirmation</title>
    </head>

    <body>
        <!-- Header / Navigation Bar -->
        <nav>
            <!-- Logo -->
            <a href="studentHome.php">
                <img class="BMCCLogo" src="Elements\bmcc-logo-resolve.png" alt="BMCC Logo" height="50px">
            </a>

            <!-- Button -->
            <div class="NavButtonsContainer">
                <button type="button" class="navButton" onclick="location.href='studentClass.php?cID=<?php echo($classID)?>'">Return</button>
            </div>
        </nav>

        <?php 
            // Fetch Class & Faculty Info
            $classQuery = "SELECT * 
                           FROM classes AS c
                           LEFT JOIN stutoclassmap as stcMap
                           ON $user_data[studentID] = stcMap.studentID
                           LEFT JOIN faculty AS f
                           ON c.facultyID = f.facultyID
                           WHERE $classID = c.classID";

            $classResult = mysqli_query($conn, $classQuery);
            
            // Verify Query
            if (!$classResult)
                die("ERROR: Could not acquire class & faculty data for info banner of class with ID " + $classID);
        
            $classInfo = mysqli_fetch_assoc($classResult);

            // Display Class Info Banner
            echo("
                <div class='classInfo student'>
                    <p class='classesBlockHeader'><strong>Class</strong>: $classInfo[name] // <strong>Grade</strong>: $classInfo[grade] // <strong>Faculty</strong>: $classInfo[username] // <strong>Email</strong>: $classInfo[email]</p>
                </div>
            ");

            // Display Confirmation Form
            echo("
                <div class='confDiv'>
                    <p class='confHeader'>Turn In Assignment</p>
                    
                    <div class='confBody'>
                        <p class='confText'>Are you sure you want to turn in the following assignment:</p>
                        <p class='confText'><strong>Title</strong>: $assignmentTitle</p>
                        <p class='confText'>... For the following class:</p>
                        <p class='confText'><strong>Class</strong>: $classInfo[name]</p>
                        <p class='confText'>... This CANNOT be undone.</p>
                        <div class='confButtonHolder'>
                            <button type='button' class='confButton' onclick='location.href=\"studentClass.php?cID=$classID\"'>Cancel</button>
                            <button type='button' class='confButton' onclick='location.href=\"submitAssignment.php?aID=$assignmentID&cID=$classID\"'>Confirm</button>
                        </div>
                    </div>
                </div>
            ");
        ?>

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