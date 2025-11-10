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
    <title> Guess The Song - RGMQ Prototype </title> 
    <link rel="stylesheet" href="game.css">
</head>

<body>
        <div class="game"> 

        <!-- Audio and Game controls -->
            <h1> 🎵 Guess The Song! 🎵 </h1>

            <audio id="songPlayer"></audio> 
            <button id="playBtn">▶ Play Clip</button>
            <button id="nextBtn" disabled>⏭ Next Round</button>

        <!-- Progress bar for audio clip -->
            <div class="progress-container">
                <progress id="audioProgress" value="0" max="100"></progress>
            </div>

            <form id="guessForm" class="guess-row">
                <input type="text" id="guessInput" placeholder="Enter song name"> 
                <button type="submit">Submit</button>
            </form> 

            <div id="feedback"></div> 

        <!-- Score/Round -->
            <div class="status">
                <span id="score">Score: 0</span>
                <span id="rounds">Round: 1/3</span>
            </div>

        <!-- Control Buttons -->
            <button id="restartBtn">🔄 Restart Game</button>
            <button onclick="window.top.location.href='menu.php'">🏠 Quit to Menu</button>
        </div> 

    <script>
        // DO not delete 
        const allSongs = ["add songs here"];

        let songs = [];      
        let clipStart = 0;
        let clipEnd = 0;
        let currentSong = null;
        let score = 0;
        let round = 0;
        let maxRounds = 3;

        const songPlayer = document.getElementById("songPlayer");
        const playBtn    = document.getElementById("playBtn");
        const nextBtn    = document.getElementById("nextBtn");
        const feedback   = document.getElementById("feedback");
        const scoreDiv   = document.getElementById("score");
        const roundsDiv  = document.getElementById("rounds");
        const restartBtn = document.getElementById("restartBtn");
        const audioProgress = document.getElementById("audioProgress");

            songPlayer.addEventListener("timeupdate", () => {
                if (songPlayer.duration > 0) {
                    audioProgress.value = (songPlayer.currentTime / songPlayer.duration) * 100;
                }
            });

            songPlayer.addEventListener("ended", () => {
                audioProgress.value = 0;
            });

        
        // Song shuffle
        function shuffleArray(array) {
            return array.sort(() => Math.random() - 0.5);
        }
        
        // Start Game
        function initGame() {
            songs = shuffleArray([...allSongs]); 
            score = 0;
            round = 0;
            currentSong = null;
            feedback.innerText = "Game started! Press ▶ Play Clip when ready.";
            scoreDiv.innerText = "Score: " + score;
            roundsDiv.innerText = "Round: " + round + " / " + maxRounds;
            nextBtn.disabled = true;
            document.getElementById("guessInput").value = "";
            songPlayer.pause();
            songPlayer.src = "";
        }

        // Load next song
        function loadNextSong() {
            round++;
            if (round > maxRounds) {
                feedback.innerText = "🏁 Game Over! Final Score: " + score + "/" + maxRounds;
                nextBtn.disabled = true;
                return;
            }
            currentSong = songs[round - 1]; 
            feedback.innerText = `Round ${round}: Click ▶ Play Clip to hear the song!`;
            nextBtn.disabled = true;
            roundsDiv.innerText = "Round: " + round + " / " + maxRounds;
        };

        // Play the current song
        playBtn.addEventListener("click", () => {
            if (!currentSong) {
                loadNextSong(); // force-load if somehow missing
            }

        
        // Start song at random PV
            songPlayer.src = currentSong.file;
            songPlayer.addEventListener("loadedmetadata", () => {
                clipStart = Math.random() * Math.min(30, songPlayer.duration - 5);
                clipEnd = clipStart + 5;

                songPlayer.currentTime = clipStart;
                songPlayer.play();

                setTimeout(() => songPlayer.pause(), 5000);
            }, { once: true });
        });

        // Making sure the progress bar takes 5 seconds
        songPlayer.addEventListener("timeupdate", () => {
            if (clipEnd > clipStart) {
                let progress = ((songPlayer.currentTime - clipStart) / (clipEnd - clipStart)) * 100;
                audioProgress.value = Math.min(Math.max(progress, 0), 100);
            }
        });

        songPlayer.addEventListener("ended", () => {
            audioProgress.value = 0;
        });

        // Submit guess
        document.getElementById("guessForm").addEventListener("submit", (e) => {
            e.preventDefault();

            if (!currentSong) {
                feedback.innerText = "🎶 Play a song first!";
                return;
            }

            let guess = document.getElementById("guessInput").value.trim().toLowerCase();

            if (guess === currentSong.title.toLowerCase()) {
                feedback.innerText = `✅ Correct! The song is "${currentSong.title}"`;
                score++;
            } else {
                feedback.innerText = `❌ Wrong! The song was: "${currentSong.title}"`;
            }

            scoreDiv.innerText  = "Score: " + score;
            document.getElementById("guessInput").value = "";

            if (round < maxRounds) {
                feedback.innerText += "\nPress ⏭ Next Round to continue.";
                nextBtn.disabled = false;
            } else {
                feedback.innerText += `\n🏁 Game Over! Final Score: ${score}/${maxRounds}. \nPress "🔄 Restart Game" to continue`;
                nextBtn.disabled = true;
            }
        });

        // Next round handler
        nextBtn.addEventListener("click", loadNextSong);

        // Restart game
        restartBtn.addEventListener("click", initGame);

        // Start first round
        initGame();
        loadNextSong();

        // Settings menu
        // Listen for messages from parent (menu.php)
        window.addEventListener("message", (event) => {
            if (event.data && event.data.maxRounds) {
                const newMaxRounds = parseInt(event.data.maxRounds);
                if (!isNaN(newMaxRounds) && newMaxRounds >= 1 && newMaxRounds <= 15) {
                    maxRounds = newMaxRounds;        // update game max rounds
                    roundsDiv.innerText = `Round: ${round} / ${maxRounds}`;
                    feedback.innerText = `⚙ Max rounds set to ${maxRounds}`;
                }
            }

            if (event.data && event.data.volume !== undefined) {
                const volume = parseFloat(event.data.volume);
                if (!isNaN(volume)) {
                    songPlayer.volume = volume;
                }
            }
        });

    </script>
</body>
</html>
