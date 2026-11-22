<?php

// main connection file for both admin & front end

// Default database settings for a local XAMPP install. On a live server, put
// the real settings in connection/config.php (copy config.example.php).
// config.php is ignored by git, so passwords never end up on GitHub.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onlinefoodphp";
if (is_file(__DIR__ . '/config.php')) {
    include __DIR__ . '/config.php';
}

// Never show PHP errors to visitors, but write them to the server's error log
// (in XAMPP: xampp/php/logs/php_error_log) so problems can still be found.
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

// One session for the whole site, with safer cookie settings.
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    session_start();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $db = mysqli_connect($servername, $username, $password, $dbname);
} catch (mysqli_sql_exception $ex) {
    error_log("Database connection failed: " . $ex->getMessage());
    http_response_code(500);
    die("Database connection failed. Make sure MySQL is running and the '" . $dbname . "' database is imported.");
}

mysqli_set_charset($db, "utf8mb4");

// CSRF protection: every form that changes data includes csrf_field(), and the
// code that handles it checks csrf_valid() before doing anything.
if (!function_exists('csrf_token')) {
    function csrf_token()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    function csrf_field()
    {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }

    function csrf_valid()
    {
        return isset($_POST['csrf_token'], $_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], (string) $_POST['csrf_token']);
    }
}

if (!defined('CSRF_ERROR')) {
    define('CSRF_ERROR', 'Your session expired. Please reload the page and try again.');
}

// Escape a value for safe output inside HTML text or attributes.
if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

// Check a password against a stored hash. Accepts modern password_hash()
// hashes and the legacy MD5 hashes from the seed data (stored in upper or
// lower case).
if (!function_exists('verify_password')) {
    function verify_password($password, $storedHash)
    {
        if (password_verify($password, $storedHash)) {
            return true;
        }
        return strlen($storedHash) === 32 && hash_equals(strtolower($storedHash), md5($password));
    }
}

// Save an uploaded image into $targetDir and return its new file name.
// On failure returns null and sets $error to a message for the user.
if (!function_exists('save_uploaded_image')) {
    function save_uploaded_image($file, $targetDir, &$error)
    {
        $error = null;
        if (empty($file) || !isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            $error = 'Please select an image.';
            return null;
        }
        if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE || $file['size'] >= 1000000) {
            $error = 'Max image size is 1024kb! Try a different image.';
            return null;
        }
        if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
            $error = 'Image upload failed. Please try again.';
            return null;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, array('jpg', 'jpeg', 'png', 'gif'), true) || @getimagesize($file['tmp_name']) === false) {
            $error = 'Invalid image! Only png, jpg and gif files are accepted.';
            return null;
        }

        $newName = uniqid() . '.' . $extension;
        if (!move_uploaded_file($file['tmp_name'], rtrim($targetDir, '/') . '/' . $newName)) {
            $error = 'Could not save the image. Check that the upload folder is writable.';
            return null;
        }
        return $newName;
    }
}
