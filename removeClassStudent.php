<!-- Andy Estevez / Smedly Moise -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC Resolve Project -->
<!-- Student Removal Handler -->

<?php
    // PHP / Data Set Up
    session_start();

    include("config.php");
    include("functions.php");

    $user_data = check_faculty_login($conn);
    $classID = $_GET["cID"];
    $studentID = $_GET["sID"];

    // Remove Student From Class Query
    $removeQuery = "DELETE FROM stutoclassmap
                    WHERE classID = $classID AND studentID = $studentID;";

    // Verify Query Successful
    if (mysqli_query($conn, $removeQuery)) {
        header("Location: facultyEditClass.php?cID=$classID");
    } 
    else {
        die("ERROR: Failed to remove student.");
    }
?>