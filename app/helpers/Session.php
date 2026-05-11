<?php
class Session {
    
    public static function start() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has($key) {
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key) {
        unset($_SESSION[$key]);
    }
    
    public static function destroy() {
        $_SESSION = [];
        if(ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, 
                $params["path"], $params["domain"], 
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
    
    public static function isLoggedIn() {
        return self::has('user_id');
    }
    
    public static function isAdmin() {
        return self::get('role') === 'admin';
    }
    
    public static function requireLogin() {
        if(!self::isLoggedIn()) {
            header('Location: /wanbubu/login.php');
            exit;
        }
    }
    
    public static function requireAdmin() {
        if(!self::isAdmin()) {
            header('Location: /wanbubu/admin/login.php');
            exit;
        }
    }
    
    public static function flash($key, $message = null) {
        if($message !== null) {
            $_SESSION['flash'][$key] = $message;
        } else {
            $msg = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
    }
}