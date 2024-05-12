<!DOCTYPE html>

<!-- Andy Estevez / Smedly Moise -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC Resolve Project -->
<!-- Faculty Assignment Submissions Page -->

<?php
    // PHP / Data Set Up
    session_start();

    include("config.php");
    include("functions.php");

    $user_data = check_faculty_login($conn);
    $classID = $_GET["cID"];
    $assignmentID = $_GET["aID"];

    // When Grade Update Form Is Submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $studentID = $_POST["studentID"];
        $grade = $_POST["grade"];

        // Verify Inputs Not Empty
        if (!empty($studentID) && !empty($grade)) {
            $updateQuery = "UPDATE stutoassignmentmap AS staMap
                            SET staMap.grade = '$grade', staMap.completionStatus = 2
                            WHERE staMap.assignmentID = $assignmentID AND staMap.studentID = $studentID;";

            $updateResult = mysqli_query($conn, $updateQuery);

            if ($updateResult) {
                header("Location: assignmentSubmissions.php?cID=$classID&aID=$assignmentID");
                die;
            }
            else {
                die("ERROR: Could not update grade.");
            }
        }
    }
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <title>BMCC Resolve | Faculty | Assignment Grader</title>
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
                <button type="button" class="navButton" onclick="location.href='facultyClass.php?cID=<?php echo($classID)?>'">Return</button>
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
                                  WHERE stcMap.classID = $classID;";

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

            // Fetch Assignment Title
            $assignmentTitleQuery = "SELECT title
                                     FROM assignments AS a
                                     WHERE a.assignmentID = $assignmentID;";

            $assignmentTitleResult = mysqli_query($conn, $assignmentTitleQuery);

            if (!$assignmentTitleResult) {
                die("ERROR: Could not get assignment title.");
            }

            $assignmentTitle = mysqli_fetch_assoc($assignmentTitleResult)["title"];

            echo("<h3 style='text-align: center; height: 25px; margin: 10px 0;'>$assignmentTitle</h3>");
        ?>

        <!-- Forms -->
        <div class="assignmentGraderBodyDiv">
            <!-- Unsubmitted List -->
            <div class="classesBlock assignmentGrader">
                <!-- Header -->
                <div class="classesBlockHead">
                    <h2 class="classesBlockHeader">Unsubmitted</h2>
                </div>
    
                <!-- Body -->
                <div class="classesBlockBody">
                    <?php
                        // Fetch Student Entries
                        $submissionQuery = "SELECT *
                                            FROM stutoassignmentmap AS staMap
                                            WHERE staMap.assignmentID = $assignmentID AND staMap.completionStatus = 0;";
    
                        $submissionResult = mysqli_query($conn, $submissionQuery);
    
                        // Verify Query & Results Exist
                        if ($submissionResult && mysqli_num_rows($submissionResult) > 0) {
                            // For Each Submission
                            while ($submission = mysqli_fetch_assoc($submissionResult)) {
                                // Fetch Student Info
                                $studentInfoQuery = "SELECT *
                                                     FROM students AS s
                                                     WHERE s.studentID = $submission[studentID];";
    
                                $studentInfoResult = mysqli_query($conn, $studentInfoQuery);
    
                                if (!$studentInfoResult) {
                                    die("ERROR: Could not get info for student of ID " + $submission[studentID]);
                                }
    
                                $student = mysqli_fetch_assoc($studentInfoResult);
    
                                // Append Submission To List
                                echo("
                                    <div class='classBlockItem assignmentGrader unsubmitted'>
                                        <h4 class='classBlockItemInfo'><strong>$student[username]</strong> ($student[studentID])</h4>
                                    </div>
                                    <hr>
                                ");
                            }
    
                        }
                        else {
                            // Handle No Submissions Here
                            echo("<h4 style='position: relative; top: 45%; left: 50%; transform: translate(-50%, 0); margin: 0 10px; color: white;'>No pending students at this time.</h4>");
                        }
                    ?>
                </div>
            </div>

            <!-- Submitted List -->
            <div class="classesBlock assignmentGrader">
                <!-- Header -->
                <div class="classesBlockHead">
                    <h2 class="classesBlockHeader">Submitted</h2>
                </div>
    
                <!-- Body -->
                <div class="classesBlockBody">
                    <?php
                        // Fetch Submissions
                        $submissionQuery = "SELECT *
                                            FROM stutoassignmentmap AS staMap
                                            WHERE staMap.assignmentID = $assignmentID AND staMap.completionStatus = 1;";
    
                        $submissionResult = mysqli_query($conn, $submissionQuery);
    
                        // Verify Query & Results Exist
                        if ($submissionResult && mysqli_num_rows($submissionResult) > 0) {
                            // For Each Submission
                            while ($submission = mysqli_fetch_assoc($submissionResult)) {
                                // Fetch Student Info
                                $studentInfoQuery = "SELECT *
                                                     FROM students AS s
                                                     WHERE s.studentID = $submission[studentID];";
    
                                $studentInfoResult = mysqli_query($conn, $studentInfoQuery);
    
                                if (!$studentInfoResult) {
                                    die("ERROR: Could not get info for student of ID " + $submission[studentID]);
                                }
    
                                $student = mysqli_fetch_assoc($studentInfoResult);
    
                                // Append Submission To List
                                echo("
                                    <div class='classBlockItem assignmentGrader'>
                                        <h4 class='classBlockItemInfo'><strong>$student[username]</strong> ($student[studentID])</h4>
                                        <form method='post' style='display: flex; flex-direction: column; align-items: center;'>
                                            <input type='hidden' name='studentID' value='$student[studentID]'>
                                            <input type='text' name='grade' class='loginFormElement assignmentGrader' placeholder='Enter Grade'>
                                            <input type='submit' value='Assign Grade' class='loginFormButton assignmentGrader'>
                                        </form>
                                    </div>
                                    <hr>
                                ");
                            }
    
                        }
                        else {
                            // Handle No Submissions Here
                            echo("<h4 style='position: relative; top: 45%; left: 50%; transform: translate(-50%, 0); margin: 0 10px; color: white;'>No submissions at this time.</h4>");
                        }
                    ?>
                </div>
            </div>

            <!-- Graded List -->
            <div class="classesBlock assignmentGrader">
                <!-- Header -->
                <div class="classesBlockHead">
                    <h2 class="classesBlockHeader">Graded</h2>
                </div>
    
                <!-- Body -->
                <div class="classesBlockBody">
                    <?php
                        // Fetch Submissions
                        $submissionQuery = "SELECT *
                                            FROM stutoassignmentmap AS staMap
                                            WHERE staMap.assignmentID = $assignmentID AND staMap.completionStatus = 2;";
    
                        $submissionResult = mysqli_query($conn, $submissionQuery);
    
                        // Verify Query & Results Exist
                        if ($submissionResult && mysqli_num_rows($submissionResult) > 0) {
                            // For Each Submission
                            while ($submission = mysqli_fetch_assoc($submissionResult)) {
                                // Fetch Student Info
                                $studentInfoQuery = "SELECT *
                                                     FROM students AS s
                                                     WHERE s.studentID = $submission[studentID];";
    
                                $studentInfoResult = mysqli_query($conn, $studentInfoQuery);
    
                                if (!$studentInfoResult) {
                                    die("ERROR: Could not get info for student of ID " + $submission[studentID]);
                                }
    
                                $student = mysqli_fetch_assoc($studentInfoResult);
    
                                // Append Submission To List
                                echo("
                                    <div class='classBlockItem assignmentGrader graded'>
                                        <h4 class='classBlockItemInfo'><strong>$student[username]</strong> ($student[studentID])</h4>
                                        <h4 class='classBlockItemInfo'><strong>Grade: </strong> ($submission[grade])</h4>
                                    </div>
                                    <hr>
                                ");
                            }
    
                        }
                        else {
                            // Handle No Submissions Here
                            echo("<h4 style='position: relative; top: 45%; left: 50%; transform: translate(-50%, 0); margin: 0 10px; color: white;'>No available grades at this time.</h4>");
                        }
                    ?>
                </div>
            </div>
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