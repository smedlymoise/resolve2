<!DOCTYPE html>

<!-- Andy Estevez / Smedly Moise -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC Resolve Project -->
<!-- Faculty Assignment Editor Page -->

<?php 
    // PHP / Data Set Up
    session_start();

    include("config.php");
    include("functions.php");

    $user_data = check_faculty_login($conn);
    $classID = $_GET["cID"];
    $assignmentID = $_GET["aID"];

    // When Add Assignment Form Is Submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $assignmentTitle = $_POST["assignmentTitle"];
        $assignmentDescription = $_POST["assignmentDescription"];
        $assignmentDueDate = $_POST["assignmentDueDate"];
        // $selectedStudents = $_POST["selectedStudents"];

        $escapedTitle = str_replace(["'", '"'], ["\'", '\"'], $assignmentTitle);
        $escapedDescription = str_replace(["'", '"'], ["\'", '\"'], $assignmentDescription);

        // Verify Inputs Not Empty
        if (!empty($assignmentTitle) && !empty($assignmentDescription) && !empty($assignmentDueDate)) {
            // Update Assignment Entry
            $query = "UPDATE assignments AS a
                      SET a.title = '$escapedTitle', a.description = '$escapedDescription', a.dueDate = '$assignmentDueDate'
                      WHERE a.assignmentID = $assignmentID;";

            // Verify Query Successful
            if (mysqli_query($conn, $query)) {
                // If Students Were Selected
                // if (isset($selectedStudents)) {
                //     // Fetch Assignment ID
                //     $assignmentIDQuery = "SELECT assignmentID
                //                           FROM assignments AS a
                //                           WHERE a.classID = $classID AND a.title = '$assignmentTitle'
                //                           AND a.description = '$assignmentDescription' AND a.dueDate = '$assignmentDueDate';";
    
                //     $assignmentIDResult = mysqli_query($conn, $assignmentIDQuery);

                //     if (!$assignmentIDResult) {
                //         die("ERROR: Could not acquire assignment ID.");
                //     }

                //     $assignmentID = mysqli_fetch_assoc($assignmentIDResult)["assignmentID"];

                //     // For Each Selected Student
                //     foreach ($selectedStudents as $studentID) {
                //         $assignQuery = "INSERT INTO stutoassignmentmap (stutoassignmentmap.studentID, stutoassignmentmap.assignmentID,
                //                                                         stutoassignmentmap.classID, stutoassignmentmap.completionStatus)
                //                         VALUES ('$studentID', '$assignmentID', '$classID', '0');";

                //         // Assign Student To Assignment
                //         $assignResult = mysqli_query($conn, $assignQuery);

                //         if (!$assignResult) {
                //             die("ERROR: Failed to assign student to assignment.");
                //         }
                //     }
                // }
                // else {
                //     // Handle No Selected Students Here
                // }
            } 
            else {
                die("ERROR: Assignment edition failed.");
            }
        }
    }
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <title>BMCC Resolve | Faculty | Assignment Editor</title>
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

            // Fetch Assignment Info
            $assignmentQuery = "SELECT *
                                FROM assignments AS a
                                WHERE a.assignmentID = $assignmentID;";

            $assignmentResult = mysqli_query($conn, $assignmentQuery);

            // Verify Query
            if (!$assignmentResult)
                die("ERROR: Could not acquire assigment data for assignment with ID " + $assignmentID);

            $assignmentInfo = mysqli_fetch_assoc($assignmentResult);
        ?>

        <!-- Edit Assignment Form -->
        <div class="addClassFormDiv assignmentAdder">
            <p class="loginHeader">Edit Assignment</p>

            <form class="loginForm assignmentAdder" method="post">
                <div class="assignmentAdderSubBody">
                    <!-- Assignment Information -->
                    <div class="assignmentInfoHolder">
                        <div class="classDateHolder assignmentAdder">
                            <input type="text" name="assignmentTitle" class="loginFormElement title" value="<?php echo($assignmentInfo['title']); ?>" placeholder="Enter Assignment Title">
                            <input type="datetime-local" name="assignmentDueDate" class="loginFormElement" value="<?php echo($assignmentInfo['dueDate']); ?>">
                        </div>
        
                        <input type="text" name="assignmentDescription" class="loginFormElement desc" value="<?php echo($assignmentInfo['description']); ?>" placeholder="Enter Assignment Description">
                    </div>

                    <!-- Student List -->
                    <div class="assignmentStudentListHolder">
                        <p class="assignmentAdderSubHeader">Selected Students</p>

                        <div class="assignmentStudentListDiv">
                            <?php
                                // Fetch Class' Students
                                $studentsQuery = "SELECT *
                                                  FROM stutoclassmap AS stcMap
                                                  LEFT JOIN students AS s
                                                  ON stcMap.studentID = s.studentID
                                                  WHERE stcMap.classID = $classID;";
                                
                                $studentsResult = mysqli_query($conn, $studentsQuery);

                                // Verify Query & Results Exist
                                if ($studentsResult && mysqli_num_rows($studentsResult) > 0) {
                                    // For Each Student
                                    while ($classStudent = mysqli_fetch_assoc($studentsResult)) {
                                        // Append Student To List
                                        echo("
                                            <div class='assignmentStudentDiv'>
                                                <input type='checkbox' id='$classStudent[studentID]' name='selectedStudents[]' value='$classStudent[studentID]' class='assignmentStudentCheckbox'>
                                                <label for='$classStudent[studentID]' class='assignmentStudentLabel'>$classStudent[username] ($classStudent[studentID])</label>
                                            </div>
                                            <hr>
                                        ");
                                    }
                                }
                                else {
                                    // Display No Students Message
                                    echo("
                                        <div class='classesBlockBody' style='height: 100%; margin: 0 8px; border-width: 0; background-color: white; display: flex; align-items: center; justify-content: center;'>
                                            <p class='classBlockItemInfo' style='color: #003884'>You have not assigned any students to this class.</p>
                                        </div>
                                    ");
                                }
                            ?>
                        </div>
                    </div>
                </div>

                <input type="submit" value="Save Changes" class="loginFormButton assignmentAdder">
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