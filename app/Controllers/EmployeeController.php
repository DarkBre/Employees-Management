<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Employee;
use DateTime;

class EmployeeController extends Controller
{
    private Employee $employees;

    public function __construct()
    {
        $this->employees = new Employee();
    }

    public function index(): void
    {
        $this->requireAuth();

        $this->view('employees.index', [
            'title' => 'Quản lí nhân viên',
            'employees' => $this->employees->all(),
            'csrfToken' => $this->csrfToken(),
            'user' => $_SESSION['user'],
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->employees->create($this->employeeData());
        $this->redirect('/');
    }

    public function update(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->employees->update((int) ($_POST['id'] ?? 0), $this->employeeData());
        $this->redirect('/');
    }

    public function delete(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->employees->delete((int) ($_POST['id'] ?? 0));
        $this->redirect('/');
    }

    private function employeeData(): array
    {
        return [
            'name' => trim($_POST['name'] ?? ''),
            'position' => trim($_POST['position'] ?? ''),
            'office' => trim($_POST['office'] ?? ''),
            'age' => (int) ($_POST['age'] ?? 0),
            'start_date' => $this->normalizeDateForDatabase($_POST['start_date'] ?? ''),
            'salary' => trim($_POST['salary'] ?? ''),
        ];
    }

    private function normalizeDateForDatabase(string $date): string
    {
        $date = trim($date);
        $parsedDate = DateTime::createFromFormat('d/m/Y', $date);

        if (!$parsedDate || $parsedDate->format('d/m/Y') !== $date) {
            return $date;
        }

        return $parsedDate->format('Y-m-d');
    }
}
