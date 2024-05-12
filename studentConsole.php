<!DOCTYPE html>

<!-- Andy Estevez / Smedly Moise -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC Resolve Project -->
<!-- Student Console Page -->

<?php
    // PHP / Data Set Up
    session_start();

    include("config.php");
    include("functions.php");

    $user_data = check_student_login($conn);
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <title>BMCC Resolve | Student | Console</title>
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

        <!-- Class List -->
        <div class="classesBlock">
            <!-- View Header -->
            <div class="classesBlockHead">
                <h2 class="classesBlockHeader">My Classes</h2>
            </div>

            <!-- Search Bar -->
            <input type="text" class="searchBar" id="searchBar" placeholder="Search">
            
            <?php
                // Fetch Student's Classes
                $classesQuery = "SELECT * 
                                 FROM stutoclassmap AS stcMap
                                 LEFT JOIN classes AS c
                                 ON stcMap.classID = c.classID 
                                 LEFT JOIN faculty AS f
                                 ON c.facultyID = f.facultyID 
                                 WHERE $user_data[studentID] = stcMap.studentID 
                                 ORDER BY c.year DESC, c.semester ASC;";

                $classesResult = mysqli_query($conn, $classesQuery);

                // Verify Query & Results Exist
                if ($classesResult && mysqli_num_rows($classesResult) > 0) {
                    // Scrollbar Style Fix
                    if (mysqli_num_rows($classesResult) > 3)
                        echo("<div class='classesBlockBody' style='border-radius: 15px 0 0 15px'>");
                    else
                        echo("<div class='classesBlockBody'>");

                    // For Each Class
                    while ($assignedClass = mysqli_fetch_assoc($classesResult)) {
                        // Append Class To List
                        echo("
                            <a href='studentClass.php?cID=$assignedClass[classID]' class='classLink'>
                                <div class='classBlockItem'>
                                    <h4 class='classBlockItemInfo'><strong>$assignedClass[name]</strong> ~ <strong>$assignedClass[username]</strong> ($assignedClass[semester] $assignedClass[year], $assignedClass[section])</h4>
                                    <h4 class='classBlockItemInfo'>Grade: $assignedClass[grade]</h4>
                                </div>
                            </a>
                            <hr>
                        ");
                    }

                    echo("</div>");
                }
                else {
                    // Display No Classes Message
                    echo("
                        <div class='classesBlockBody' style='display: flex; align-items: center; justify-content: center;'>
                            <p class='classBlockItemInfo'>You are not assigned to any classes.</p>
                        </div>
                    ");
                }
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