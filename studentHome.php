<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>BMCC Resolve | Student | Home</title>
</head>
<body>
     <!-- Header / Navigation Bar -->
     <nav>
        <a href="https://www.bmcc.cuny.edu" target="_blank" onclick="return confirm('This will take you to the main BMCC page')">
            <img class="BMCCLogo" src="Elements\bmcc-logo-resolve.png" alt="BMCC Logo" height="50px">
        </a>
        <div class="NavButtonsContainer">
            <button type="button" class="navButton" onclick="location.href='studentHome.php'">Home</button>
            <button type="button" class="navButton" onclick="location.href='studentConsole.php'">Console</button>
            <button type="button" class="navButton" onclick="location.href='studentProfile.php'">Profile</button>
            <button type="button" class="navButton" id="login" onclick="location.href='logout.php'">Log Out</button>
        </div>
    </nav>
   

    <!--<div class="progress_box">-->
        <!--<h2>Progress</h2>-->
        <!--<div class="classes_container">-->
        <!--  <span class="classes">PHYS 215</span>-->
         <!-- <span class="classes">CSC 301</span>-->
         <!-- <span class="classes">ENG 111</span>-->
          <!--<span class="classes">GYM 101</span>-->
         <!-- <span class="classes">ART 108</span>-->
        <!--</div>-->
     <!-- </div>-->
      

  <div class="container">
  <div class="flex-item-1">
    <h2 id="welcome-title"> Inc grade info</h2>
    <p>Term’s work incomplete. Instructor has reasonable expectation that the student can receive a passing grade after completing the missing assignment(s) and agrees to work with the student to make up the missing work. Student must also agree with the faculty to make the missing work before the INC deadline which is published on BMCC Academic Calendar on the web. The “INC” grade reverts to an “FIN” if a change is not made by deadline.</p>
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