<?php
require_once 'config/db.php';

class Auth {
    public static function register($username, $email, $password) {
        global $pdo;

         
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

         
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $email, $hashedPassword]);
    }

    public static function login($email, $password) {
        global $pdo;

        // Check if the user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Start a session and store user info
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }

        return false;
    }

    public static function logout() {
        session_start();
        session_unset();
        session_destroy();
    }
}
