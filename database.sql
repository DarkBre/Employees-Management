CREATE DATABASE IF NOT EXISTS employee_manager
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE employee_manager;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS employees (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    position VARCHAR(120) NOT NULL,
    office VARCHAR(120) NOT NULL,
    age TINYINT UNSIGNED NOT NULL,
    start_date DATE NOT NULL,
    salary VARCHAR(50) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO employees (name, position, office, age, start_date, salary)
SELECT 'Nguyễn Văn An', 'Nhân viên kinh doanh', 'Hà Nội', 28, '2024-01-15', '12.000.000'
WHERE NOT EXISTS (
    SELECT 1 FROM employees WHERE name = 'Nguyễn Văn An' AND start_date = '2024-01-15'
);

INSERT INTO employees (name, position, office, age, start_date, salary)
SELECT 'Trần Thị Bình', 'Kế toán viên', 'Đà Nẵng', 31, '2023-08-01', '14.000.000'
WHERE NOT EXISTS (
    SELECT 1 FROM employees WHERE name = 'Trần Thị Bình' AND start_date = '2023-08-01'
);
