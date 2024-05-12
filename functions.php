<!-- Andy Estevez / Smedly Moise -->
<!-- BMCC Tech Innovation Hub Internship -->
<!-- Spring Semester 2024 -->
<!-- BMCC Resolve Project -->
<!-- PHP Functions Holder -->

<?php
    // Verify Student Account Login Status
    function check_student_login($conn) {
        if (isset($_SESSION["studentID"])) {
            $id = $_SESSION["studentID"];

            // Fetch User Info
            $query = "select * from students where studentID = '$id' limit 1";

            $result = mysqli_query($conn, $query);

            // Verify Query & Results Exist
            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
                return $user_data;
            }
        }

        header("Location: login.php");
        die;
    }

    // Verify Faculty Account Login Status
    function check_faculty_login($conn) {
        if (isset($_SESSION["facultyID"])) {
            $id = $_SESSION["facultyID"];

            // Fetch User Info
            $query = "select * from faculty where facultyID = '$id' limit 1";

            $result = mysqli_query($conn, $query);

            // Verify Query & Results Exist
            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
                return $user_data;
            }
        }

        header("Location: login.php");
        die;
    }

    // Verify Admin Account Login Status
    function check_admin_login($conn) {
        if (isset($_SESSION["adminID"])) {
            $id = $_SESSION["adminID"];

            // Fetch User Info
            $query = "select * from admins where adminID = '$id' limit 1";

            $result = mysqli_query($conn, $query);

            // Verify Query & Results Exist
            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
                return $user_data;
            }
        }

        header("Location: login.php");
        die;
    }

    // Generate Random Number (ID)
    function random_num($len) {
        $text = "";
        
        // Append Random Digits (0-9) 'len' Times
        for ($i = 0; $i < $len; $i++) {
            $text .= rand(0, 9);
        }

        return $text;
    }
?>