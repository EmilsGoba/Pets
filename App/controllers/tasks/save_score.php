<?php
$config = require 'config.php';

// Create a database connection using the config
$dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $config['user'], $config['password'], $options);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

// Handle the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $solveTime = $_POST['solve_time'] ?? '';

    if (!empty($username) && is_numeric($solveTime)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, solve_time) VALUES (:username, :solve_time)");
            $stmt->execute(['username' => $username, 'solve_time' => (int)$solveTime]);

            echo json_encode(["success" => true, "message" => "Score submitted successfully!"]);
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "Error submitting score: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid input data."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
