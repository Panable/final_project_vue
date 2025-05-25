<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

session_start();

// Database connection
$host = "feenix-mariadb.swin.edu.au";
$user = "s103866373";
$pwd = "kaibingoat";
$sql_db = "s103866373_db";

// Custom password hashing function for PHP 5.4
function hash_password($password) {
    // Use a fixed salt
    $salt = "myfixed123";
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

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Function to require login
function require_login() {
    if (!is_logged_in()) {
        http_response_code(401);
        echo json_encode(["error" => "Authentication required"]);
        exit();
    }
}

// Function to require admin
function require_admin() {
    require_login();
    if (!is_admin()) {
        http_response_code(403);
        echo json_encode(["error" => "Admin access required"]);
        exit();
    }
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
        
        // Hash the password using our function
        $password_data = hash_password($password);
        $password_hash = $password_data['hash'];

        $sql = "SELECT id, username, password_hash, email, role FROM users WHERE username = ? AND password_hash = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        $stmt->bind_param("ss", $username, $password_hash);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            unset($user['password_hash']);
            echo json_encode([
                "success" => true, 
                "user" => $user
            ]);
        } else {
            throw new Exception("Invalid username or password");
        }
        exit();
    }

    // Handle logout
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['action']) && $data['action'] === 'logout') {
        session_destroy();
        echo json_encode(["success" => true, "message" => "Logged out successfully"]);
        exit();
    }

    // Handle get current user
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'current_user') {
        if (is_logged_in()) {
            echo json_encode([
                "success" => true,
                "user" => [
                    "id" => $_SESSION['user_id'],
                    "username" => $_SESSION['username'],
                    "role" => $_SESSION['role']
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "user" => null]);
        }
        exit();
    }

    // Handle posts CRUD operations
    if (is_logged_in()) {
        // Get all posts
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'posts') {
            $sql = "SELECT p.*, COUNT(l.id) as like_count, 
                   EXISTS(SELECT 1 FROM likes WHERE user_id = ? AND post_id = p.id) as user_liked 
                   FROM posts p 
                   LEFT JOIN likes l ON p.id = l.post_id 
                   GROUP BY p.id 
                   ORDER BY p.id DESC";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $posts = [];
            while ($row = $result->fetch_assoc()) {
                $posts[] = $row;
            }
            
            echo json_encode(["success" => true, "posts" => $posts]);
            exit();
        }

        // Create post (admin only)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['action']) && $data['action'] === 'create_post') {
            require_admin();
            
            if (!isset($data['title']) || !isset($data['content'])) {
                throw new Exception("Missing title or content");
            }

            $title = $conn->real_escape_string($data['title']);
            $content = $conn->real_escape_string($data['content']);

            $sql = "INSERT INTO posts (title, content) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $title, $content);

            if ($stmt->execute()) {
                echo json_encode([
                    "success" => true,
                    "message" => "Post created successfully",
                    "post_id" => $conn->insert_id
                ]);
            } else {
                throw new Exception("Failed to create post");
            }
            exit();
        }

        // Update post (admin only)
        if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($data['action']) && $data['action'] === 'update_post') {
            require_admin();
            
            if (!isset($data['id']) || !isset($data['title']) || !isset($data['content'])) {
                throw new Exception("Missing required fields");
            }

            $id = (int)$data['id'];
            $title = $conn->real_escape_string($data['title']);
            $content = $conn->real_escape_string($data['content']);

            $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $title, $content, $id);

            if ($stmt->execute()) {
                echo json_encode([
                    "success" => true,
                    "message" => "Post updated successfully"
                ]);
            } else {
                throw new Exception("Failed to update post");
            }
            exit();
        }

        // Delete post (admin only)
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($data['action']) && $data['action'] === 'delete_post') {
            require_admin();
            
            if (!isset($data['id'])) {
                throw new Exception("Missing post ID");
            }

            $id = (int)$data['id'];

            // First delete likes
            $sql = "DELETE FROM likes WHERE post_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            // Then delete post
            $sql = "DELETE FROM posts WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode([
                    "success" => true,
                    "message" => "Post deleted successfully"
                ]);
            } else {
                throw new Exception("Failed to delete post");
            }
            exit();
        }

        // Toggle like
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['action']) && $data['action'] === 'toggle_like') {
            if (!isset($data['post_id'])) {
                throw new Exception("Missing post ID");
            }

            $post_id = (int)$data['post_id'];
            $user_id = $_SESSION['user_id'];

            // Check if already liked
            $sql = "SELECT id FROM likes WHERE user_id = ? AND post_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $post_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Unlike
                $sql = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $user_id, $post_id);
                $stmt->execute();
                echo json_encode(["success" => true, "action" => "unliked"]);
            } else {
                // Like
                $sql = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $user_id, $post_id);
                $stmt->execute();
                echo json_encode(["success" => true, "action" => "liked"]);
            }
            exit();
        }
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