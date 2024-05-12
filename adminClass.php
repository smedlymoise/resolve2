<?php
// PHP / Data Set Up
session_start();
include("config.php");
include("functions.php");
$user_data = check_admin_login($conn);

// Retrieve classID from URL parameter
$classID = isset($_GET['cID']) ? $_GET['cID'] : null;

// Verify if classID is provided
if (!$classID) {
    die("Error: Class ID is missing.");
}

// Fetch Class Info
$classQuery = "SELECT * 
               FROM classes AS c
               WHERE classID = $classID";

$classResult = mysqli_query($conn, $classQuery);

// Verify Query
if (!$classResult) {
    die("ERROR: Could not acquire class & faculty data for info banner of class with ID " . $classID);
}

$classInfo = mysqli_fetch_assoc($classResult);

// Fetch Class' Student Count
$studentCountQuery = "SELECT COUNT(*) AS count
                      FROM stutoclassmap AS stcMap
                      WHERE stcMap.classID = $classID";

$studentCountResult = mysqli_query($conn, $studentCountQuery);

// Verify Query
if (!$studentCountResult) {
    die("ERROR: Could not acquire student count for class " . $classInfo['name']);
}

$studentCount = mysqli_fetch_assoc($studentCountResult);


// Display Class Info Banner
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>BMCC Resolve | Class Viewer</title>
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
            <button type="button" class="navButton" onclick="location.href='adminHome.php'">Home</button>
            <button type="button" class="navButton" onclick="location.href='adminConsoleClasses.php'">Console</button>
            <button type="button" class="navButton" onclick="location.href='adminProfile.php'">Profile</button>
            <button type="button" class="navButton" id="login" onclick="location.href='logout.php'">Log Out</button>
        </div>
    </nav>

    <!------------->
    <!-- Content -->
    <!------------->
    <div class='classInfo'>
        <button class='assignmentCornerButton classSettings' onclick='location.href="adminEdit.php?cID=<?php echo $classID; ?>"'></button>
        <p class='classesBlockHeader'>
            <strong>Class</strong>: <?php echo $classInfo['name']; ?> // 
            <strong>Section</strong>: <?php echo $classInfo['section']; ?> // 
            <strong>Semester</strong>: <?php echo $classInfo['semester']; ?> // 
            <strong>Year</strong>: <?php echo $classInfo['year']; ?> // 
            <strong>Students</strong>: <?php echo $studentCount['count']; ?>
        </p>
        <button class='assignmentCornerButton classAnnouncements' onclick='location.href="sendClassAnnouncement.php?cID=<?php echo $classID; ?>"'></button>
    </div>

    <?php
    // Fetch Assignments
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
                        <button class='assignmentCornerButton assignmentNotifications' onclick='location.href=\"sendAssignmentReminder.php?aID=$assignment[assignmentID]\"'></button>
                        <h2 class='classBlockItemInfo'>$assignment[title]</h2>
                        <button class='assignmentCornerButton assignmentSettings' onclick='location.href=\"facultyEditAssigment.php?aID=$assignment[assignmentID]\"'></button>
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
            ");

            // Submission View Button
            echo("
                    <button type='button' class='assignmentButton' onclick='location.href=\"AssignmentSubmissions.php?aID=$assignment[assignmentID]\"'>See Submissions</button>
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
