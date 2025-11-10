<?php
session_start();
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    // Not logged in → redirect to login page
    header("Location: login.php");
    exit(); // Stop executing the rest of the page
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Main Menu - RGMQ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


</body>
</html>

<div class="layout">

    <!-- Menu sidebar -->
    <div class="menu">
        <img src="" alt="Logo" class="logo">
        <h1 class="welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>

    <!-- Side Button -->
        <button onclick="startGame()" class="alt1"> ▶ Start Game </button>
        <button class="alt2 settingsBtn"> ⚙ Settings</button>
        <button class="alt2" id="instructionsBtn"> ❓ Instructions</button>
        <button class="alt1 leaderboardBtn"> 🏆 High Scores </button>

        <form action="login.php" method="GET">
            <button class="alt1">🚪 Logout</button>
        </form>
    </div>

    <!-- Game screen container -->
    <div class="game-screen">
            <iframe src="game.php" id="gameFrame"></iframe>
        </div>

        <!-- Settings Modal -->
        <div id="settingsModal" class="modal">
        <div class="modal-content">
            <h2>Settings</h2>

            <!-- Volume Control -->
            <label for="volumeSlider">Volume:</label>
            <input type="range" id="volumeSlider" min="0" max="1" step="0.01" value="0.5">

            <!-- Max Rounds -->
            <label for="roundsInput">Max Rounds (1-15):</label>
            <input type="number" id="roundsInput" min="1" max="15" value="3">

            <div class="modal-buttons">
            <button id="saveSettings">Save</button>
            <button id="closeSettings">Cancel</button>
            </div>
        </div>
        </div>
        <!-- Instructions Modal -->
        <div id="instructionsModal" class="modal">
            <div class="modal-content">
                <h2>How to Play</h2>
                <p> 🎵 The game will play a random 5-second music clip from a random rhythm game. </p>
                <p> ⌨️ Type your guess in the input box. </p>
                <p> ✅ Correct guesses score points. </p>
                <p> ⚙  You can change the max rounds in the settings menu. </p>
                <p> 🏆 Play until the max rounds are finished </p>

                <div class="modal-buttons">
                <button id="closeInstructions">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        function startGame() {
            const iframe = document.getElementById('gameFrame');

            // Add class to make it slide into view
            iframe.classList.add('visible');
        }
            // Settings 
            const settingsModal = document.getElementById("settingsModal");
            const settingsBtn = document.querySelector(".settingsBtn"); // Settings button in line 48
            const closeSettings = document.getElementById("closeSettings");
            const saveSettings = document.getElementById("saveSettings");

            // Open modal
            settingsBtn.addEventListener("click", () => {
            settingsModal.style.display = "flex";
            });

            // Close modal
            closeSettings.addEventListener("click", () => {
            settingsModal.style.display = "none";
            });

            // Save settings
            saveSettings.addEventListener("click", () => {
            const volume = document.getElementById("volumeSlider").value;
            const maxRounds = document.getElementById("roundsInput").value;

            // Send settings to game iframe
            const gameFrame = document.getElementById("gameFrame");
            gameFrame.contentWindow.postMessage({ volume, maxRounds }, "*");

            settingsModal.style.display = "none";
            });



            // Instructions modal
            const instructionsModal = document.getElementById("instructionsModal");
            const instructionsBtn = document.getElementById("instructionsBtn");
            const closeInstructions = document.getElementById("closeInstructions");

            // Open instructions
            instructionsBtn.addEventListener("click", () => {
            instructionsModal.style.display = "flex";
            });

            // Close instructions
            closeInstructions.addEventListener("click", () => {
            instructionsModal.style.display = "none";
            });


    </script>

    

</body>
</html>

