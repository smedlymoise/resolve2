<!DOCTYPE html>

<!-- Andy Estevez / Smedly Moise -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC Resolve Project -->
<!-- Student Class Viewer Page -->

<?php
    // PHP / Data Set Up
    session_start();

    include("config.php");
    include("functions.php");

    $user_data = check_student_login($conn);
    $classID = $_GET["cID"];
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <title>BMCC Resolve | Student | Class Viewer</title>
    </head>

    <body>
        <!-- Header / Navigation Bar -->
        <nav>
            <!-- Logo -->
            <a href="studentHome.php">
                <img class="BMCCLogo" src="Elements\bmcc-logo-resolve.png" alt="BMCC Logo" height="50px">
            </a>

            <!-- Buttons -->
            <div class="NavButtonsContainer">
                <button type="button" class="navButton" onclick="location.href='studentHome.php'">Home</button>
                <button type="button" class="navButton" onclick="location.href='studentConsole.php'">Console</button>
                <button type="button" class="navButton" onclick="location.href='studentProfile.php'">Profile</button>
                <button type="button" class="navButton" id="login" onclick="location.href='logout.php'">Log Out</button>
            </div>
        </nav>

        <!------------->
        <!-- Content -->
        <!------------->

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
                    <p class='classesBlockHeader'>
                        <strong>Class</strong>: $classInfo[name] // 
                        <strong>Grade</strong>: $classInfo[grade] // 
                        <strong>Faculty</strong>: $classInfo[username] // 
                        <strong>Email</strong>: $classInfo[email]
                    </p>
                </div>
            ");

            // Fetch Student's Assignments
            $assignmentsQuery = "SELECT *
                                 FROM stutoassignmentmap AS staMap
                                 LEFT JOIN assignments AS a
                                 ON staMap.assignmentID = a.assignmentID
                                 WHERE staMap.studentID = $user_data[studentID] AND staMap.classID = $classID
                                 ORDER BY dueDate";

            $assignmentsResult = mysqli_query($conn, $assignmentsQuery);

            // Verify Query & Results Exist
            if ($assignmentsResult && mysqli_num_rows($assignmentsResult) > 0) {
                // For Each Assignment
                while ($assignment = mysqli_fetch_assoc($assignmentsResult)) {
                    // Display Assignment
                    echo("
                        <div class='assignmentBlock'>
                            <h2 class='classBlockItemInfo'>$assignment[title]</h2>
                            
                            <div class='assignmentBlockBody'>
                                <h4 class='assignmentText'>Assignment Description:</h4>
                                <p class='assignmentText'>$assignment[description]</p>
                                <hr>

                                <p class='assignmentText'><strong>Due Date</strong>: $assignment[dueDate]</p>
                    ");

                    // Encode Assignment Title To Safeguard URL
                    $assignmentTitleEnc = urlencode($assignment["title"]);

                    // Display Assignment Completion Status, Set Up Submission Button, & Insert Remaining Information
                    if ($assignment["completionStatus"] == 0) {
                        echo("
                                    <p class='assignmentText'><strong>Status</strong>: Incomplete</p>
                                    <p class='assignmentText'><strong>Grade</strong>: $assignment[grade]</p>
                                </div>

                                <button type='button' class='assignmentButton' onclick='location.href=\"submitAssignmentConfirm.php?aT=$assignmentTitleEnc&aID=$assignment[assignmentID]&cID=$classID\"'>Mark As Complete</button>
                            </div>
                        ");
                    }
                    else if ($assignment["completionStatus"] == 1) {
                        echo("
                                    <p class='assignmentText'><strong>Status</strong>: Pending</p>
                                    <p class='assignmentText'><strong>Grade</strong>: $assignment[grade]</p>
                                </div>

                                <button type='button' class='assignmentButtonDis' disabled'>Turned In</button>
                            </div>
                        ");
                    }
                    else if ($assignment["completionStatus"] == 2) {
                        echo("
                                    <p class='assignmentText'><strong>Status</strong>: Complete</p>
                                    <p class='assignmentText'><strong>Grade</strong>: $assignment[grade]</p>
                                </div>

                                <button type='button' class='assignmentButtonDis' disabled'>Turned In</button>
                            </div>
                        ");
                    }
                    else {
                        die("ERROR: Invalid completion status.");
                    }
                }
            }
            else {
                // Display No Assignments Message
                echo("<h3 style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, 0)'>You do not have any assignments.</h3>");
            }
        ?>
    </body>
</html>