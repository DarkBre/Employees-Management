<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Employee
{
    public function all(): array
    {
        $this->seedIfEmpty();

        $statement = Database::connection()->query(
            'SELECT id, name, position, office, age, start_date, salary
             FROM employees
             ORDER BY id ASC'
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT id, name, position, office, age, start_date, salary
             FROM employees
             WHERE id = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $employee = $statement->fetch(PDO::FETCH_ASSOC);

        return $employee ?: null;
    }

    public function create(array $data): void
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO employees (name, position, office, age, start_date, salary)
             VALUES (:name, :position, :office, :age, :start_date, :salary)'
        );

        $statement->execute($this->bindData($data));
    }

    public function update(int $id, array $data): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE employees
             SET name = :name,
                 position = :position,
                 office = :office,
                 age = :age,
                 start_date = :start_date,
                 salary = :salary,
                 updated_at = NOW()
             WHERE id = :id'
        );

        $statement->execute(array_merge($this->bindData($data), ['id' => $id]));
    }

    public function delete(int $id): void
    {
        $statement = Database::connection()->prepare('DELETE FROM employees WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    private function seedIfEmpty(): void
    {
        $count = (int) Database::connection()->query('SELECT COUNT(*) FROM employees')->fetchColumn();
        if ($count > 0) {
            return;
        }

        foreach ($this->defaultEmployees() as $employee) {
            $this->create($employee);
        }
    }

    private function bindData(array $data): array
    {
        return [
            'name' => trim($data['name'] ?? ''),
            'position' => trim($data['position'] ?? ''),
            'office' => trim($data['office'] ?? ''),
            'age' => (int) ($data['age'] ?? 0),
            'start_date' => trim($data['start_date'] ?? ''),
            'salary' => trim($data['salary'] ?? ''),
        ];
    }

    private function defaultEmployees(): array
    {
        return [
            [
                'name' => 'Nguyễn Văn An',
                'position' => 'Nhân viên kinh doanh',
                'office' => 'Hà Nội',
                'age' => 28,
                'start_date' => '2024-01-15',
                'salary' => '12.000.000',
            ],
            [
                'name' => 'Trần Thị Bình',
                'position' => 'Kế toán viên',
                'office' => 'Đà Nẵng',
                'age' => 31,
                'start_date' => '2023-08-01',
                'salary' => '14.000.000',
            ],
        ];
    }
}
