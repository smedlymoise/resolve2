
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
    $notificationID = $_POST["notificationID"];
    $facultyID = $_POST["facultyID"];
    $studentID = $_POST["studentID"];
    $classID = $_POST["classID"];
    $message = $_POST["Message"];
    $timestamp = $_POST["Timestamp"];
    $status = $_POST["Status"];

    // Insert data into the table
    $sql = "INSERT INTO notifications (notificationID, facultyID, studentID, classID, Message, Timestamp, Status) VALUES ('$notificationID', '$facultyID', '$studentID', '$classID', '$message', '$timestamp', '$status')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Announcement sent successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Send Announcement</title>
</head>
<body>
<!-- Header / Navigation Bar -->
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
                $classesQuery = "SELECT classID
                                FROM classes AS c";

                $studentsQuery = "SELECT DISTINCT s.*
                                FROM students AS s
                                INNER JOIN stutoclassmap AS stcMap
                                ON stcMap.studentID = s.studentID
                                WHERE stcMap.classID IN ($classesQuery)";

                $studentsResult = mysqli_query($conn, $studentsQuery);

                if ($studentsResult && mysqli_num_rows($studentsResult) > 0) {
                    if (mysqli_num_rows($studentsResult) > 3)
                        echo("<div class='classesBlockBody' style='border-radius: 15px 0 0 15px'>");
                     else
                         echo("<div class='classesBlockBody'>");

                    while ($assignedStudent = mysqli_fetch_assoc($studentsResult)) {
                        $classCountQuery = "SELECT COUNT(*) AS count
                                            FROM stutoclassmap AS stcMap
                                            WHERE stcMap.studentID = $assignedStudent[studentID]
                                            AND stcMap.classID IN ($classesQuery)";

                        $classCountResult = mysqli_query($conn, $classCountQuery);

                        if (!$classCountResult) {
                            die("Error: Could not acquire class count for student " . $assignedStudent['name']);
                        } else {
                            $classCount = mysqli_fetch_assoc($classCountResult)['count'];
                        }

                        echo("
                            <a href='adminStudentView.php?studentID=$assignedStudent[studentID]' class='classLink'>
                                <div class='studentBlockItem'>
                                    <h4 class='classBlockItemInfo'><strong>$assignedStudent[username]</strong> (Student ID: $assignedStudent[studentID] | Email: $assignedStudent[email])</h4>
                                    <h4 class='classBlockItemInfo'>Classes: $classCount</h4>
                                </div>
                            </a>
                            <hr>
                        ");
                    }

                    echo("</div>");
                } else {
                    echo("
                        <div class='studentsBlockBody'>
                            <p style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%)' class='studentBlockItemInfo'>No students are assigned to your classes.</p>
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

</body>
</html>




