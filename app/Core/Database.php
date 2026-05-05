<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        self::initializeDatabase();

        try {
            self::$connection = new PDO(self::databaseDsn(), DB_USER, DB_PASS, self::options());
        } catch (PDOException $exception) {
            self::renderConnectionError($exception->getMessage());
        }

        return self::$connection;
    }

    private static function initializeDatabase(): void
    {
        try {
            $pdo = new PDO(self::serverDsn(), DB_USER, DB_PASS, self::options());
            $databaseName = str_replace('`', '``', DB_NAME);
            $charset = DB_CHARSET;

            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET {$charset} COLLATE {$charset}_unicode_ci");
            $pdo->exec("USE `{$databaseName}`");
            $pdo->exec(
                'CREATE TABLE IF NOT EXISTS users (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(120) NOT NULL,
                    email VARCHAR(190) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
            );
        } catch (PDOException $exception) {
            self::renderConnectionError($exception->getMessage());
        }
    }

    private static function serverDsn(): string
    {
        return sprintf('mysql:host=%s;port=%s;charset=%s', DB_HOST, DB_PORT, DB_CHARSET);
    }

    private static function databaseDsn(): string
    {
        return sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', DB_HOST, DB_PORT, DB_NAME, DB_CHARSET);
    }

    private static function options(): array
    {
        return [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
    }

    private static function renderConnectionError(string $detail): void
    {
        http_response_code(500);
        $safeDetail = htmlspecialchars($detail, ENT_QUOTES, 'UTF-8');

        echo <<<HTML
<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lỗi kết nối cơ sở dữ liệu</title>
        <link href="/assets/css/styles.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <main class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card shadow-sm">
                        <div class="card-header bg-danger text-white">
                            Không thể kết nối cơ sở dữ liệu
                        </div>
                        <div class="card-body">
                            <p class="mb-3">Web cần MySQL để đăng nhập và đăng kí tài khoản.</p>
                            <ol class="mb-3">
                                <li>Mở <strong>XAMPP Control Panel</strong>.</li>
                                <li>Bấm <strong>Start</strong> ở dòng <strong>MySQL</strong>.</li>
                                <li>Tải lại trang web bằng <strong>Ctrl + F5</strong>.</li>
                            </ol>
                            <p class="mb-1"><strong>Cấu hình hiện tại:</strong></p>
                            <ul class="mb-3">
                                <li>Host: <code>{DB_HOST}</code></li>
                                <li>Port: <code>{DB_PORT}</code></li>
                                <li>Database: <code>{DB_NAME}</code></li>
                                <li>User: <code>{DB_USER}</code></li>
                            </ul>
                            <p class="text-muted small mb-0">Chi tiết kỹ thuật: {$safeDetail}</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
HTML;
        exit;
    }
}
