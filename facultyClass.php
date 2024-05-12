<!DOCTYPE html>

<!-- Andy Estevez / Smedly Moise -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC Resolve Project -->
<!-- Faculty Class Page -->

<?php
    // PHP / Data Set Up
    session_start();

    include("config.php");
    include("functions.php");

    $user_data = check_faculty_login($conn);
    $classID = $_GET["cID"];
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <title>BMCC Resolve | Faculty | Class Viewer</title>
    </head>

    <body>
        <!-- Header / Navigation Bar -->
        <nav>
            <!-- Logo -->
            <a href="facultyHome.php">
                <img class="BMCCLogo" src="Elements\bmcc-logo-resolve.png" alt="BMCC Logo" height="50px">
            </a>

            <!-- Buttons -->
            <div class="NavButtonsContainer">
                <button type="button" class="navButton" onclick="location.href='facultyHome.php'">Home</button>
                <button type="button" class="navButton" onclick="location.href='facultyConsoleClasses.php'">Console</button>
                <button type="button" class="navButton" onclick="location.href='facultyProfile.php'">Profile</button>
                <button type="button" class="navButton" id="login" onclick="location.href='logout.php'">Log Out</button>
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
                <div class='classInfo'>
                    <!-- Class Announcements Button -->
                    <button class='assignmentCornerButton classAnnouncements' onclick='location.href=\"facultyClassAnnouncements.php?cID=$classID\"'></button>
                    
                    <!-- Class Information -->
                    <p class='classesBlockHeader'>
                        <strong>Class</strong>: $classInfo[name] // 
                        <strong>Section</strong>: $classInfo[section] // 
                        <strong>Semester</strong>: $classInfo[semester] // 
                        <strong>Year</strong>: $classInfo[year] // 
                        <strong>Students</strong>: $studentCount[count]
                    </p>

                    <!-- Class Settings Button -->
                    <button class='assignmentCornerButton classSettings' onclick='location.href=\"facultyEditClass.php?cID=$classID\"'></button>
                </div>
            ");

            // Assignment Creator Button
            echo("<button class='createAssignmentButton' onclick='location.href=\"createAssignment.php?cID=$classID\"'>Create Assignment</button>");

            // Fetch Class' Assignments Query
            $assignmentsQuery = "SELECT *
                                 FROM assignments AS a
                                 WHERE a.classID = $classID
                                 ORDER BY dueDate";

            $assignmentsResult = mysqli_query($conn, $assignmentsQuery);

            // Verify Query & Results Exist
            if ($assignmentsResult && mysqli_num_rows($assignmentsResult) > 0) {
                // For Each Assignment
                while ($assignment = mysqli_fetch_assoc($assignmentsResult)) {
                    // Display Assignment
                    echo("
                        <div class='assignmentBlock'>
                            <div class='assignmentBlockHead'>
                                <!-- Assignment Reminder Button -->
                                <button class='assignmentCornerButton assignmentNotifications' onclick='location.href=\"facultyAssignmentReminder.php?cID=$classID&aID=$assignment[assignmentID]\"'></button>
                                
                                <h2 class='classBlockItemInfo'>$assignment[title]</h2>

                                <!-- Assignment Editor Button -->
                                <button class='assignmentCornerButton assignmentSettings' onclick='location.href=\"facultyEditAssignment.php?cID=$classID&aID=$assignment[assignmentID]\"'></button>
                            </div>
                            
                            <div class='assignmentBlockBody'>
                                <h4 class='assignmentText'>Assignment Description:</h4>
                                <p class='assignmentText'>$assignment[description]</p>
                                <hr>
                                <p class='assignmentText'><strong>Due Date</strong>: $assignment[dueDate]</p>
                    ");

                    // Fetch Assignment's Student Count
                    $assignmentStudentCountQuery = "SELECT COUNT(*) AS count
                                                    FROM stutoassignmentmap AS staMap
                                                    WHERE staMap.assignmentID = $assignment[assignmentID]";

                    $assignmentStudentCountResult = mysqli_query($conn, $assignmentStudentCountQuery);

                    // Verify Query
                    if (!$assignmentStudentCountResult)
                        die("ERROR: Could not acquire student count for assignment " + $assignment[name]);

                    $assignmentStudentCount = mysqli_fetch_assoc($assignmentStudentCountResult);

                    // Fetch Assignment's Pending Submission Count
                    $pendingSubmissionCountQuery = "SELECT COUNT(*) as count
                                                    FROM stutoassignmentmap AS staMap
                                                    WHERE staMap.assignmentID = $assignment[assignmentID]
                                                    AND staMap.completionStatus = 1";

                    $pendingSubmissionCountResult = mysqli_query($conn, $pendingSubmissionCountQuery);

                    // Verify Query
                    if (!$pendingSubmissionCountResult)
                        die("ERROR: Could not acquire pending submission count for assignment " + $assignment[name]);

                    $pendingSubmissionCount = mysqli_fetch_assoc($pendingSubmissionCountResult);

                    // Display Student / Submission Statistics
                    echo("
                                <p class='assignmentText'><strong>Students</strong>: $assignmentStudentCount[count]</p>
                                <p class='assignmentText'><strong>Pending Submissions</strong>: $pendingSubmissionCount[count]</p>
                            </div>

                            <!-- Submission Viewer Button -->
                            <button type='button' class='assignmentButton' onclick='location.href=\"assignmentSubmissions.php?cID=$classID&aID=$assignment[assignmentID]\"'>See Submissions</button>
                        </div>
                    ");
                }
            }
            else {
                // Display No Assignments Message
                echo("<h3 style='position: absolute; top: 55%; left: 50%; transform: translate(-50%, 0)'>You have not created any assignments.</h3>");
            }
        ?>
    </body>
</html>