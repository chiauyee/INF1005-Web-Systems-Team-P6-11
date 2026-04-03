<?php

class Security {
    private static $pdo;

    public static function init($pdo) {
        self::$pdo = $pdo;
    }

    // Generate a CSRF token
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Validate CSRF token
    public static function validateCSRFToken($token) {
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }
        return true;
    }

    // Check rate limit for an action
    public static function checkRateLimit($identifier, $action, $maxAttempts = 3, $timeWindow = 3600) {
        // Clean up old entries
        $stmt = self::$pdo->prepare("DELETE FROM rate_limits WHERE last_attempt < DATE_SUB(NOW(), INTERVAL ? SECOND)");
        $stmt->execute([$timeWindow]);

        // Check current attempts
        $stmt = self::$pdo->prepare("
            SELECT attempts, first_attempt
            FROM rate_limits
            WHERE identifier = ? AND action = ? AND last_attempt > DATE_SUB(NOW(), INTERVAL ? SECOND)
        ");
        $stmt->execute([$identifier, $action, $timeWindow]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            if ($result['attempts'] >= $maxAttempts) {
                // Calculate remaining time
                $firstAttempt = strtotime($result['first_attempt']);
                $resetTime = $firstAttempt + $timeWindow;
                $remaining = $resetTime - time();

                return [
                    'allowed' => false,
                    'remaining_seconds' => max(0, $remaining),
                    'attempts' => $result['attempts']
                ];
            }

            // Update attempts
            $stmt = self::$pdo->prepare("
                UPDATE rate_limits
                SET attempts = attempts + 1, last_attempt = NOW()
                WHERE identifier = ? AND action = ?
            ");
            $stmt->execute([$identifier, $action]);

            return [
                'allowed' => true,
                'attempts' => $result['attempts'] + 1
            ];
        } else {
            // First attempt
            $stmt = self::$pdo->prepare("
                INSERT INTO rate_limits (identifier, action, attempts, first_attempt, last_attempt)
                VALUES (?, ?, 1, NOW(), NOW())
            ");
            $stmt->execute([$identifier, $action]);

            return [
                'allowed' => true,
                'attempts' => 1
            ];
        }
    }

    // Reset rate limit for an identifier and action
    public static function resetRateLimit($identifier, $action) {
        $stmt = self::$pdo->prepare("DELETE FROM rate_limits WHERE identifier = ? AND action = ?");
        $stmt->execute([$identifier, $action]);
    }

    // Check if password has been used before
    public static function checkPasswordHistory($userId, $newPassword, $historyCount = 5) {
        return false;
    }

    // Add password to history
    public static function addPasswordToHistory($userId, $passwordHash) {
        // Keep only last 5 passwords
        $stmt = self::$pdo->prepare("
            DELETE FROM password_history
            WHERE user_id = ? AND id NOT IN (
                SELECT id FROM (
                    SELECT id FROM password_history
                    WHERE user_id = ?
                    ORDER BY created_at DESC
                    LIMIT 4
                ) temp
            )
        ");
        $stmt->execute([$userId, $userId]);

        // Add new password
        $stmt = self::$pdo->prepare("INSERT INTO password_history (user_id, password_hash) VALUES (?, ?)");
        $stmt->execute([$userId, $passwordHash]);
    }

    // Get client IP address
    public static function getClientIP() {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_X_REAL_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        // Handle comma-separated IPs (from proxies)
        if (strpos($ip, ',') !== false) {
            $ip = trim(explode(',', $ip)[0]);
        }

        return $ip;
    }
}
?>
