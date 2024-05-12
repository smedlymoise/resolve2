<!DOCTYPE html>

<!-- Andy Estevez / Smedly Moise -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC Resolve Project -->
<!-- Student Removal Confirmation Page -->

<?php
    // PHP / Data Set Up
    session_start();

    include("config.php");
    include("functions.php");

    $user_data = check_faculty_login($conn);
    $classID = $_GET["cID"];
    $studentID = $_GET["sID"];
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <title>BMCC Resolve | Faculty | Student Removal Confirmation</title>
    </head>

    <body>
        <!-- Header / Navigation Bar -->
        <nav>
            <!-- Logo -->
            <a href="facultyHome.php">
                <img class="BMCCLogo" src="Elements\bmcc-logo-resolve.png" alt="BMCC Logo" height="50px">
            </a>

            <!-- Button -->
            <div class="NavButtonsContainer">
                <button type="button" class="navButton" onclick="location.href='facultyEditClass.php?cID=<?php echo($classID)?>'">Return</button>
            </div>
        </nav>

        <!------------->
        <!-- Content -->
        <!------------->

        <?php 
            // Fetch Class Info
            $classQuery = "SELECT * 
                           FROM classes AS c
                           WHERE $classID = c.classID";

            $classResult = mysqli_query($conn, $classQuery);

            // Verify Query
            if (!$classResult)
                die("ERROR: Could not acquire class & faculty data for info banner of class with ID " + $classID);

            $classInfo = mysqli_fetch_assoc($classResult);

            $classSemester = $classInfo["semester"];

            // Fetch Class' Student Count
            $studentCountQuery = "SELECT COUNT(*) AS count
                                  FROM stutoclassmap AS stcMap
                                  WHERE stcMap.classID = $classID";

            $studentCountResult = mysqli_query($conn, $studentCountQuery);

            // Verify Query
            if (!$studentCountResult)
                die("ERROR: Could not acquire student count for class " + $classInfo[name]);

            $studentCount = mysqli_fetch_assoc($studentCountResult);

            // Display Class Info Banner
            echo("
                <div class='classInfo student'>
                    <p class='classesBlockHeader'>
                        <strong>Class</strong>: $classInfo[name] // 
                        <strong>Section</strong>: $classInfo[section] // 
                        <strong>Semester</strong>: $classInfo[semester] // 
                        <strong>Year</strong>: $classInfo[year] // 
                        <strong>Students</strong>: $studentCount[count]
                    </p>
                </div>
            ");

            // Fetch Student Info
            $studentQuery = "SELECT *
                             FROM students AS s
                             WHERE s.studentID = $studentID;";

            $studentResult = mysqli_query($conn, $studentQuery);

            // Verify Query & Results Exist
            if ($studentResult && mysqli_num_rows($studentResult) > 0) {
                // Fetch Student Name
                $studentName = mysqli_fetch_assoc($studentResult)["username"];

                // Display Confirmation Form
                echo("
                    <div class='confDiv'>
                        <p class='confHeader'>Remove Student</p>
                        
                        <div class='confBody'>
                            <p class='confText'>Are you sure you want to remove the following student:</p>
                            <p class='confText'><strong>Name</strong>: $studentName</p>
                            <p class='confText'>... From the following class:</p>
                            <p class='confText'><strong>Class</strong>: $classInfo[name]</p>
                            <p class='confText'>... The student will be notified.</p>

                            <div class='confButtonHolder'>
                                <button type='button' class='confButton' onclick='location.href=\"facultyEditClass.php?cID=$classID\"'>Cancel</button>
                                <button type='button' class='confButton' onclick='location.href=\"removeClassStudent.php?cID=$classID&sID=$studentID\"'>Confirm</button>
                            </div>
                        </div>
                    </div>
                ");
            }
            else {
                die("ERROR: Could not find student information for student of ID " + $studentID);
            }
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