<!DOCTYPE html>

<!-- Andy Estevez / Smedly Moise -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC Resolve Project -->
<!-- Faculty Class Editor Page -->

<?php
    // PHP / Data Set Up
    session_start();

    include("config.php");
    include("functions.php");

    $user_data = check_faculty_login($conn);
    $classID = $_GET["cID"];

    // When A Form Is Submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // When Edit Class Form Is Submitted
        if (isset($_POST["formType"]) && $_POST["formType"] == "editClass") {
            $className = $_POST["className"];
            $classSection = $_POST["classSection"];
            $classYear = $_POST["classYear"];

            // Verify Inputs Not Empty
            if (!empty($className) && !empty($classSection) && !empty($classYear)) {
                // Get Class Semester
                if (isset($_POST["classSemester"]))
                    $classSemester = $_POST["classSemester"];
                else
                    $classSemester = $_POST["curSemester"];

                // Update Class Entry
                $query = "UPDATE classes AS c
                          SET c.name = '$className', c.section = '$classSection', c.semester = '$classSemester', c.year = '$classYear'
                          WHERE c.classID = $classID";

                // Verify Query Successful
                if (mysqli_query($conn, $query)) {
                    header("Location: facultyEditClass.php?cID=$classID");
                    die;
                } else {
                    die("ERROR: Class edition failed.");
                }
            }
            else {
                // Handle Empty Inputs Here
            }
        }
        // When Add Student Form Is Submitted
        else if (isset($_POST["formType"]) && $_POST["formType"] == "addStudent") {
            $studentEmail = $_POST["studentEmail"];

            // Verify Inputs Not Empty
            if (!empty($studentEmail)) {
                // Fetch StudentID
                $studentIDQuery = "SELECT studentID AS studentID
                                   FROM students AS s
                                   WHERE s.email = '$studentEmail';";

                $studentIDResult = mysqli_query($conn, $studentIDQuery);

                // If StudentID Found
                if ($studentIDResult && mysqli_num_rows($studentIDResult) > 0) {
                    $studentID = mysqli_fetch_assoc($studentIDResult);

                    // Check For Duplicates Within StuToClassMap
                    $query = "SELECT *
                              FROM stutoclassmap AS stcMap
                              WHERE stcMap.studentID = $studentID[studentID];";

                    $result = mysqli_query($conn, $query);

                    // If No Duplicates
                    if ($result && mysqli_num_rows($result) < 1) {
                        // Add Student Entry
                        $query = "INSERT INTO stutoclassmap (stutoclassmap.studentID, stutoclassmap.classID, stutoclassmap.grade)
                                  VALUES ('$studentID[studentID]', '$classID', 'INC');";

                        // Verify Query Successful
                        if (mysqli_query($conn, $query)) {
                            header("Location: facultyEditClass.php?cID=$classID");
                            die;
                        }
                        else {
                            die("ERROR: Student addition failed.");
                        }
                    }
                    else {
                        // Handle Duplicate Attempts Here
                    }
                }
                else {
                    // Handle Invalid Email Here
                }
            }
            else {
                // Handle Empty Inputs Here
            }
        }
    }
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <title>BMCC Resolve | Faculty | Class Editor</title>
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
        ?>

        <!-- Edit Class Form -->
        <?php
            echo("
                <div class='addClassFormDiv editor'>
                    <p class='loginHeader'>Edit Class</p>

                    <form class='loginForm' method='post'>
                        <input type='text' name='className' class='loginFormElement' placeholder='$classInfo[name]' value='$classInfo[name]'>
                        <input type='text' name='classSection' class='loginFormElement' placeholder='$classInfo[section]' value='$classInfo[section]'>

                        <!-- Class Date -->
                        <div class='classDateHolder'>
                            <!-- Semester Dropdown Menu -->
                            <select name='classSemester' class='loginFormElement classDateElement' id='classSemester'>
                                <option selected disabled value=''>Pick Semester</option>
                                <option value='Spring'>Spring</option>
                                <option value='Summer'>Summer</option>
                                <option value='Fall'>Fall</option>
                                <option value='Winter'>Winter</option>
                            </select>
                            
                            <!-- Year Of Class -->
                            <input type='text' name='classYear' class='loginFormElement classDateElement' id='classYear' placeholder='$classInfo[year]' value='$classInfo[year]'>
                        </div>

                        <input type='hidden' name='curSemester' value='$classInfo[semester]'>
                        <input type='hidden' name='formType' value='editClass'>

                        <input type='submit' value='Save Changes' class='loginFormButton'>
                    </form>
                </div>
            ");
        ?>

        <!-- Add Student Form -->
        <div class="classesBlock classEditor">
            <!-- View Header -->
            <div class="classesBlockHead">
                <h2 class="classesBlockHeader">Class Students</h2>
            </div>

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
                    // Scrollbar Style Fix
                    if (mysqli_num_rows($studentsResult) > 5)
                        echo("<div class='classesBlockBody' style='border-radius: 15px 0 0 15px'>");
                    else
                        echo("<div class='classesBlockBody'>");
                
                    // For Each Student
                    while ($assignedStudent = mysqli_fetch_assoc($studentsResult)) {
                        // Append Student To List
                        echo("
                            <div class='classBlockItem classEditor'>
                                <h4 class='classBlockItemInfo classEditor'><strong>$assignedStudent[username]</strong> ($assignedStudent[email], UserID: $assignedStudent[studentID])</h4>
                                <button class='assignmentCornerButton removeStudent' onclick='location.href=\"removeClassStudentConfirm.php?cID=$classID&sID=$assignedStudent[studentID]\"'></button>
                            </div>
                            <hr>
                        ");
                    }
                    
                    echo("</div>");
                }
                else {
                    // Display No Students Massage
                    echo("
                        <div class='classesBlockBody' style='display: flex; align-items: center; justify-content: center;'>
                            <p class='classBlockItemInfo'>You have not assigned any students to this class.</p>
                        </div>
                    ");
                }

                // Add Student Inputs
                echo("
                    <form method='post'>
                        <div class='classEditorForm'>
                            <input type='text' name='studentEmail' class='loginFormElement classEditor' placeholder='Enter Student Email'>

                            <input type='hidden' name='formType' value='addStudent'>

                            <input type='submit' value='Add Student' class='loginFormButton classEditor'>
                        </div>
                    </form>
                ")
            ?>
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