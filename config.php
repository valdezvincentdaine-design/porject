<?php
declare(strict_types=1);
session_start();

/* Update these values to match your XAMPP MySQL setup. */
const DB_HOST = '127.0.0.1';
const DB_NAME = 'phinma_canteen';
const DB_USER = 'root';
const DB_PASS = '';
const APP_NAME = 'PHINMA AU Canteen';
const ALLOWED_EMAIL_DOMAINS = ['phinmaed.com', 'phinma.edu.ph'];

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]
        );
    }
    return $pdo;
}
function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'); }
function redirect(string $path): never { header('Location: '.$path); exit; }
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void { if (!current_user()) redirect('auth.php'); }
function require_admin(): void { require_login(); if ((current_user()['role'] ?? '') !== 'admin') { http_response_code(403); exit('Access denied. Admin account required.'); } }
function csrf_token(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function check_csrf(): void { if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Session expired. Refresh the page and try again.'); } }
function money($n): string { return '₱'.number_format((float)$n, 2); }
function flash(string $message, string $type='success'): void { $_SESSION['flash'] = ['message'=>$message, 'type'=>$type]; }
function get_flash(): ?array { $f=$_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f; }
