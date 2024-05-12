<?php
session_start();

include("config.php");
include("functions.php");

$user_data = check_admin_login($conn);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Faculty Swap</title>
</head>
<body>
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


<div class="addClassFormDiv assignmentAdder">
    <p class="loginHeader">Swap Faculty </p>
    <form class="loginForm" method="post">
        <input type="text" name="faculty1" id="faculty1" list="faculty1List" placeholder="Enter first faculty..." required>
        <datalist id="faculty1List">
            <?php foreach ($facultyList as $faculty) : ?>
                <option value="<?php echo $faculty['username']; ?>"><?php echo $faculty['username']; ?></option>
            <?php endforeach; ?>
        </datalist><br><br>

        <input type="text" name="faculty2" id="faculty2" list="faculty2List" placeholder="Enter second faculty..." required>
        <datalist id="faculty2List">
            <?php foreach ($facultyList as $faculty) : ?>
                <option value="<?php echo $faculty['username']; ?>"><?php echo $faculty['username']; ?></option>
            <?php endforeach; ?>
        </datalist><br><br>

        <input type="submit" value="Swap Faculty" class="loginFormButton">
    </form>
</div>

<!-- JS for suggestion -->
<script>
    const facultyInputs = document.querySelectorAll('input[list]');
    facultyInputs.forEach(input => {
        const dataList = document.getElementById(input.getAttribute('list'));
        input.addEventListener('input', (event) => {
            const filter = event.target.value.toLowerCase();
            const options = dataList.querySelectorAll('option');
            for (let i = 0; i < options.length; i++) {
                const optionText = options[i].textContent.toLowerCase();
                options[i].style.display = optionText.indexOf(filter) !== -1 ? 'block' : 'none';
            }
        });
    });
</script>

<!-- process form -->
<?php
// Process form submission 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faculty1 = $_POST['faculty1']; // Username from the first datalist
    $faculty2 = $_POST['faculty2']; // Username from the second datalist

    // Function to retrieve faculty ID based on username 
    function getFacultyID($username) {
        global $conn; 

        $sql = "SELECT facultyID FROM faculty WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            return $row['facultyID'];
        } else {
            return null;  // Handle case where username is not found
        }
    }

    // Get faculty IDs based on usernames
    $faculty1ID = getFacultyID($faculty1);
    $faculty2ID = getFacultyID($faculty2);

    // Validate form data (optional)
    $errorMessage = "";
    if (empty($faculty1ID) || empty($faculty2ID)) {
        $errorMessage = "Please select both faculty members.";
    }

    if (empty($errorMessage)) {
        // Swap faculty IDs in assignments table
        $sql = "UPDATE assignments SET facultyID = 
          CASE WHEN facultyID = ? THEN ? ELSE ? END
          WHERE facultyID IN (?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("iiiii", $faculty1ID, $faculty2ID, $faculty1ID, $faculty1ID, $faculty2ID);

            if ($stmt->execute()) {
                $successMessage = "Teachers swapped successfully!";
            } else {
                $errorMessage = "Error swapping teachers: " . $conn->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }
}
?>


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
