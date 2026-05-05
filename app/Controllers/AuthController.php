<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth.login', [
            'title' => 'Đăng nhập',
            'csrfToken' => $this->csrfToken(),
            'error' => $_SESSION['error'] ?? null,
            'currentPage' => 'login',
        ], 'auth-admin');
        unset($_SESSION['error']);
    }

    public function login(): void
    {
        $this->verifyCsrf();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $user = (new User())->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Email hoặc mật khẩu không đúng.';
            $this->redirect('/login');
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
        ];

        $this->redirect('/');
    }

    public function showRegister(): void
    {
        $this->view('auth.register', [
            'title' => 'Đăng kí',
            'csrfToken' => $this->csrfToken(),
            'error' => $_SESSION['error'] ?? null,
            'currentPage' => 'register',
        ], 'auth-admin');
        unset($_SESSION['error']);
    }

    public function register(): void
    {
        $this->verifyCsrf();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin hợp lệ. Mật khẩu cần ít nhất 6 kí tự.';
            $this->redirect('/register');
        }

        if ($password !== $passwordConfirmation) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp.';
            $this->redirect('/register');
        }

        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            $_SESSION['error'] = 'Email này đã được đăng kí.';
            $this->redirect('/register');
        }

        $user = $userModel->create($name, $email, $password);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
        ];

        $this->redirect('/');
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('/login');
    }
}
