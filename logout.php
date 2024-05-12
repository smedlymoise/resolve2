<!-- Andy Estevez / Smedly Moise -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC Resolve Project -->
<!-- Log Out Handler -->

<?php
    // PHP / Data Set Up
    session_start();

    // If Student Logged In, Log Out
    if (isset($_SESSION["studentID"])) {
        unset($_SESSION["studentID"]);
    }

    // If Faculty Logged In, Log Out
    if (isset($_SESSION["facultyID"])) {
        unset($_SESSION["facultyID"]);
    }

    // If Admin Logged In, Log Out
    if (isset($_SESSION["adminID"])) {
        unset($_SESSION["adminID"]);
    }

    // Redirect To Landing Page
    header("Location: index.html");
    die;
?>