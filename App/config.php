<?php
// Establish database connection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $config = [
        "host" => "localhost",
        "dbname" => "pets",
        "user" => "root",
        "password" => "",
        "charset" => "utf8mb4"
    ];

    try {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $pdo = new PDO($dsn, $config['user'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve POST data
        $username = $_POST['username'];
        $solve_time = (int) $_POST['solve_time'];

        // Insert the score into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, solve_time) VALUES (:username, :solve_time)");
        $stmt->execute([':username' => $username, ':solve_time' => $solve_time]);

        echo json_encode(["success" => true, "message" => "Score submitted successfully!"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
