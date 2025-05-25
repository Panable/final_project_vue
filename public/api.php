<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Database connection
$host = "feenix-mariadb.swin.edu.au";
$user = "s103866373";
$pwd = "kaibingoat";
$sql_db = "s103866373_db";

// Custom password hashing function for PHP 5.4
function hash_password($password) {
    // Generate a random salt
    $salt = substr(md5(uniqid(rand(), true)), 0, 8);
    // Hash the password with the salt
    $hash = md5($password . $salt);
    // Return both the hash and salt
    return array(
        'hash' => $hash,
        'salt' => $salt
    );
}

// Custom password verification function for PHP 5.4
function verify_password($password, $hash, $salt) {
    return md5($password . $salt) === $hash;
}

// Function to check if table exists
function table_exists($conn, $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    return $result->num_rows > 0;
}

// Health check endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'health') {
    try {
        $conn = new mysqli($host, $user, $pwd, $sql_db);
        
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }

        // Test the users table
        $result = $conn->query("SELECT 1 FROM users LIMIT 1");
        if ($result === false) {
            throw new Exception("Database query failed: " . $conn->error);
        }

        echo json_encode([
            "status" => "healthy",
            "message" => "API and database connection are working",
            "details" => [
                "database" => "connected",
                "users_table" => "accessible",
                "php_version" => PHP_VERSION
            ]
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "status" => "unhealthy",
            "message" => "API check failed",
            "error" => $e->getMessage()
        ]);
    }
    exit();
}

try {
    $conn = new mysqli($host, $user, $pwd, $sql_db);

    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Read JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    // Handle registration
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['action']) && $data['action'] === 'register') {
        if (!isset($data['username']) || !isset($data['password']) || !isset($data['email'])) {
            throw new Exception("Missing required fields");
        }

        // Validate role if provided, otherwise default to 'user'
        $role = isset($data['role']) && in_array($data['role'], ['admin', 'user']) 
            ? $data['role'] 
            : 'user';

        $username = $conn->real_escape_string($data['username']);
        $email = $conn->real_escape_string($data['email']);
        
        // Hash the password
        $password_data = hash_password($data['password']);
        $password_hash = $password_data['hash'];

        $sql = "INSERT INTO users (username, password_hash, email, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        $stmt->bind_param("ssss", $username, $password_hash, $email, $role);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true, 
                "message" => "Registration successful",
                "user" => [
                    "username" => $username,
                    "email" => $email,
                    "role" => $role
                ]
            ]);
        } else {
            throw new Exception("Registration failed: " . $stmt->error);
        }
        exit();
    }

    // Handle login
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['action']) && $data['action'] === 'login') {
        if (!isset($data['username']) || !isset($data['password'])) {
            throw new Exception("Missing username or password");
        }

        $username = $conn->real_escape_string($data['username']);
        $password = $data['password'];

        $sql = "SELECT id, username, password_hash, email, role FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Since we don't have salt in the database, we'll just compare the hashed password
            if (md5($password) === $user['password_hash']) {
                unset($user['password_hash']);
                echo json_encode([
                    "success" => true, 
                    "user" => $user
                ]);
            } else {
                throw new Exception("Invalid password");
            }
        } else {
            throw new Exception("User not found");
        }
        exit();
    }

    // If no valid action
    throw new Exception("Invalid request");

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
?> 