<?php

namespace App\Controllers\Admin;

use App\Models\Admin;

class LoginController
{
    /**
     * Show the login form.
     */
    public function index()
    {
        // Use a different layout for the login page, without the sidebar.
        return view('login_layout', 'auth/login', ['title' => 'ورود به پنل']);
    }

    /**
     * Handle the login request.
     */
    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $admin = Admin::findByUsername($username);

        if ($admin && password_verify($password, $admin['password_hash'])) {
            // Store admin info in session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header('Location: ' . url('/'));
            exit();
        } else {
            // Redirect back to login with an error
            // In a real app, use flash messages for errors.
            header('Location: ' . url('/login?error=1'));
            exit();
        }
    }

    /**
     * Handle the logout request.
     */
    public function logout()
    {
        session_destroy();
        header('Location: ' . url('/login'));
        exit();
    }
}
