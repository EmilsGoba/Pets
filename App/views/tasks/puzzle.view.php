<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puzzle Game</title>
</head>
<body>
    <?php require "../App/views/components/head.php"; ?>
    <?php require "../App/views/components/navbar.php"; ?>

    <div class="puzzle-section">
        <h2>Puzzle Game</h2>
        <button id="restart-puzzle">Restart</button>
        <p id="timer-display">Time: 0s</p>
        <div class="puzzle-container">
            <div class="dropzone">
                <div class="grid" id="puzzle-grid">
                    <!-- Dropzone grid -->
                    <?php
                    $pieces = [];
                    for ($row = 0; $row < 3; $row++) {
                        for ($col = 0; $col < 5; $col++) {
                            $pieces[] = "<img src='/puzzle/piece_{$row}_{$col}.png' alt='Piece {$row},{$col}' class='piece' draggable='true' id='piece_{$row}_{$col}'>";
                        }
                    }
                    shuffle($pieces); // Shuffle the pieces
                    $index = 0;
                    for ($row = 0; $row < 3; $row++) {
                        for ($col = 0; $col < 5; $col++) {
                            echo "<div class='grid-cell' id='grid_{$row}_{$col}'>{$pieces[$index]}</div>";
                            $index++;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="congratulations" id="congratulations" style="display: none;">
            <img src="/puzzle/puzzle.jpg" alt="Completed Puzzle">
            <h2>Congratulations! You solved the puzzle!</h2>
        </div>
    </div>

    <script>
        let timer = 0;
        let timerInterval;
        let timerStarted = false;

        const gridCells = document.querySelectorAll(".grid-cell");

        gridCells.forEach(cell => {
            cell.addEventListener("dragover", e => e.preventDefault());
            cell.addEventListener("drop", function (e) {
                const id = e.dataTransfer.getData("id");
                const draggedPiece = document.getElementById(id);

                if (this.hasChildNodes()) {
                    // If the cell already has a piece, swap it with the dragged piece
                    const existingPiece = this.firstChild;
                    const currentParent = draggedPiece.parentElement;
                    this.appendChild(draggedPiece);
                    currentParent.appendChild(existingPiece);
                } else {
                    this.appendChild(draggedPiece);
                }

                checkCompletion();
            });
        });

        document.querySelectorAll(".piece").forEach(piece => {
            piece.addEventListener("dragstart", function (e) {
                e.dataTransfer.setData("id", e.target.id);
                if (!timerStarted) {
                    startTimer();
                    timerStarted = true;
                }
            });
        });

        function startTimer() {
            timer = 0;
            document.getElementById("timer-display").textContent = "Time: 0s";
            timerInterval = setInterval(() => {
                timer++;
                document.getElementById("timer-display").textContent = `Time: ${timer}s`;
            }, 1000);
        }

        function checkCompletion() {
            const piecesOrder = Array.from(document.querySelectorAll(".grid-cell")).map(cell =>
                cell.firstChild ? cell.firstChild.id : null
            );

            const correctOrder = [
                "piece_0_0", "piece_0_1", "piece_0_2", "piece_0_3", "piece_0_4",
                "piece_1_0", "piece_1_1", "piece_1_2", "piece_1_3", "piece_1_4",
                "piece_2_0", "piece_2_1", "piece_2_2", "piece_2_3", "piece_2_4"
            ];

            if (JSON.stringify(piecesOrder) === JSON.stringify(correctOrder)) {
                clearInterval(timerInterval);
                document.getElementById("congratulations").style.display = "block";
            }
        }

        document.getElementById("restart-puzzle").addEventListener("click", function () {
            clearInterval(timerInterval);
            timerStarted = false;
            document.getElementById("timer-display").textContent = "Time: 0s";
            document.getElementById("congratulations").style.display = "none";

            const grid = document.getElementById("puzzle-grid");
            const pieces = Array.from(document.querySelectorAll(".piece"));
            shuffleArray(pieces);

            grid.querySelectorAll(".grid-cell").forEach((cell, index) => {
                cell.innerHTML = ""; // Clear the cell
                cell.appendChild(pieces[index]); // Add a shuffled piece
            });
        });

        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
        }
    </script>
</body>
</html>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .puzzle-section {
        text-align: center;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        margin: 20px auto;
        max-width: 900px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(5, 60px);
        grid-gap: 5px;
        justify-content: center;
        margin-top: 20px;
    }

    .grid-cell {
        width: 60px;
        height: 60px;
        border: 1px dashed #ddd;
        background-color: #e9e9e9;
    }

    .piece {
        width: 60px;
        height: 60px;
        border: 1px solid #ddd;
        border-radius: 5px;
        cursor: grab;
    }

    button {
        margin: 10px;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #0056b3;
    }

    p#timer-display {
        font-size: 18px;
        color: #333;
    }

    .congratulations {
        text-align: center;
        margin-top: 20px;
    }

    .congratulations img {
        width: 300px;
        border-radius: 10px;
        margin-bottom: 10px;
    }

    .congratulations h2 {
        color: #4caf50;
        font-size: 24px;
    }
</style>
