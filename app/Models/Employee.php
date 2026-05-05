<?php

namespace App\Models;

class Employee extends JsonModel
{
    protected string $fileName = 'employees.json';

    public function all(): array
    {
        $employees = $this->allRecords();
        if (empty($employees)) {
            $employees = $this->defaultEmployees();
            $this->saveRecords($employees);
        }

        return $employees;
    }

    public function find(int $id): ?array
    {
        foreach ($this->all() as $employee) {
            if ((int) $employee['id'] === $id) {
                return $employee;
            }
        }

        return null;
    }

    public function create(array $data): void
    {
        $employees = $this->all();
        $data['id'] = $this->nextId($employees);
        $employees[] = $data;
        $this->saveRecords($employees);
    }

    public function update(int $id, array $data): void
    {
        $employees = array_map(function (array $employee) use ($id, $data) {
            if ((int) $employee['id'] === $id) {
                return array_merge($employee, $data, ['id' => $id]);
            }

            return $employee;
        }, $this->all());

        $this->saveRecords($employees);
    }

    public function delete(int $id): void
    {
        $employees = array_filter($this->all(), fn (array $employee) => (int) $employee['id'] !== $id);
        $this->saveRecords($employees);
    }

    private function defaultEmployees(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Nguyễn Văn An',
                'position' => 'Nhân viên kinh doanh',
                'office' => 'Hà Nội',
                'age' => 28,
                'start_date' => '2024-01-15',
                'salary' => '12.000.000',
            ],
            [
                'id' => 2,
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
