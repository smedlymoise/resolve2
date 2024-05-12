// Wrap your code inside a DOMContentLoaded event listener
document.addEventListener("DOMContentLoaded", function() {
  // Add your event listener to the form
  document.getElementById("countdownForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission

    // Get the deadline datetime from the input
    const deadlineInput = document.getElementById("deadlineInput").value;
    const deadline = new Date(deadlineInput).getTime();

    // Calculate time remaining
    const now = new Date().getTime();
    const timeRemaining = deadline - now;

    // Update countdown display
    if (timeRemaining > 0) {
      const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
      const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

      const countdownContainer = document.getElementById("countdown-container");
      countdownContainer.innerHTML = `
        <p>Time remaining until deadline:</p>
        <p>${days} days ${hours} hours ${minutes} minutes ${seconds} seconds</p>
      `;
    } else {
      const countdownContainer = document.getElementById("countdown-container");
      countdownContainer.innerHTML = `<p>The deadline has passed.</p>`;
    }
  });
});
