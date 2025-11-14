<?php

namespace App\Controllers\Admin;

use App\Models\Admin;

class LoginController
{
    /**
     * Show the admin login form.
     */
    public function index()
    {
        // If the user is already logged in, redirect to the dashboard
        if (isset($_SESSION['admin_id'])) {
            header('Location: /admin/dashboard');
            exit();
        }

        return view('auth', 'auth/login', ['title' => 'ورود به پنل مدیریت']);
    }

    /**
     * Handle the admin login attempt.
     */
    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $admin = Admin::findByUsername($username);

        if ($admin && password_verify($password, $admin['password'])) {
            // Password is correct, start the session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header('Location: /admin/dashboard');
            exit();
        } else {
            // Invalid credentials
            header('Location: /admin/login?error=1');
            exit();
        }
    }

    /**
     * Log the admin out.
     */
    public function logout()
    {
        session_destroy();
        header('Location: /admin/login');
        exit();
    }
}
