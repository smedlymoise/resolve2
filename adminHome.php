<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BMCC Resolve | Admin | Home</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <nav>
    <a href="facultyHome.php">
      <img class="BMCCLogo" src="Elements/bmcc-logo-resolve.png" alt="BMCC Logo" height="50px">
    </a>

    <div class="NavButtonsContainer">
      <button type="button" class="navButton" onclick="location.href='adminHome.php'">Home</button>
      <button type="button" class="navButton" onclick="location.href='adminConsoleClasses.php'">Console</button>
      <button type="button" class="navButton" onclick="location.href='adminProfile.php'">Profile</button>
      <button type="button" class="navButton" id="login" onclick="location.href='logout.php'">Log Out</button>
    </div>
  </nav>

  <div class="container">
  <div class="flex-item-1">
    <h2 id="welcome-title"> Welcome</h2>
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Reiciendis explicabo laudantium a officiis similique beatae asperiores, nemo est iure eaque eius ab numquam neque delectus perspiciatis expedita mollitia ipsum porro.</p>
  </div>

  <div class="flex-item-2">
    <h2 id="assignments-title">Pressing Assignments</h2>
      <hr>
    <?php
        // PHP / Data Set Up
        session_start();
        
        include("config.php");
        include("functions.php");
           
        // SQL query to select top 10 assignments due soonest
        $sql = "SELECT title, dueDate FROM assignments ORDER BY dueDate ASC LIMIT 10";
        $result = $conn->query($sql);
        
        // Fetch data and display in the format you want
        if ($result && $result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                // Output assignment title
                echo '<div class="assignment" style="padding: 10px; margin: 10px 0; border-radius: 20px; background-color: #002874;">';
                echo '<h3 class="assignment-title">' . $row["title"] . '</h3>';
                
                // Output assignment due date
                echo '<p class="due-date">Due: ' . $row["dueDate"] . '</p>';
                
                // Output the countdown span
                echo '<div class="countdown" id="countdown-' . $row["title"] . '"></div>';
                
                // JavaScript for countdown
                echo '<script>';
                echo 'function updateCountdown_' . $row["title"] . '() {';
                echo '    const now = new Date();';
                echo '    const dueDate = new Date("' . $row["dueDate"] . '");';
                echo '    const diffInMs = dueDate - now;';
                echo '';
                echo '    if (diffInMs <= 0) {';
                echo '        document.getElementById("countdown-' . $row["title"] . '").textContent = "Due";';
                echo '    } else {';
                echo '        const days = Math.floor(diffInMs / (1000 * 60 * 60 * 24));';
                echo '        const hours = Math.floor((diffInMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));';
                echo '        const minutes = Math.floor((diffInMs % (1000 * 60 * 60)) / (1000 * 60));';
                echo '        const seconds = Math.floor((diffInMs % (1000 * 60)) / 1000);';
                echo '';
                echo '        const formattedTime = days + "d " + hours + "h " + minutes + "m " + seconds + "s";';
                echo '        document.getElementById("countdown-' . $row["title"] . '").textContent = formattedTime;';
                echo '    }';
                echo '}';
                echo '';
                echo 'setInterval(updateCountdown_' . $row["title"] . ', 1000);';
                echo 'updateCountdown_' . $row["title"] . '(); // Initial call to update immediately';
                echo '</script>';
                
                echo '</div>';
            }
        } else {
            echo "0 results";
        }
        ?>
  </div>
</div>

<footer>
  <a class="footerLink" href="https://maps.app.goo.gl/87HcM8tEhsrWe9wH6" target="_blank">
    <p class="footerText">
      <u>Borough of Manhattan Community College <br>
        The City University of New York <br>
        199 Chambers Street <br>
        New York, NY 10007
      </u>
    </p>
  </a>
</footer>

<!-- IBM Watson Chatbot -->
<script>
            window.watsonAssistantChatOptions = {
              integrationID: "1db7fbd1-e4f7-4a21-8299-7b79b90d0406", // The ID of this integration.
              region: "us-east", // The region your integration is hosted in.
              serviceInstanceID: "ae83b918-3f7e-463e-a2ac-8327cd35ef06", // The ID of your service instance.
              onLoad: async (instance) => { await instance.render(); }
            };
            setTimeout(function(){
              const t=document.createElement('script');
              t.src="https://web-chat.global.assistant.watson.appdomain.cloud/versions/" + (window.watsonAssistantChatOptions.clientVersion || 'latest') + "/WatsonAssistantChatEntry.js";
              document.head.appendChild(t);
            });
 </script>


</body>
</html>
