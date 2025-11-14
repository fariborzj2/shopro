<?php

namespace App\Controllers\Admin;

use App\Models\User;

class UsersController
{
    /**
     * Show a list of all users.
     */
    public function index()
    {
        $users = User::all();
        return view('main', 'users/index', [
            'title' => 'مدیریت کاربران',
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('main', 'users/create', [
            'title' => 'افزودن کاربر جدید'
        ]);
    }

    /**
     * Store a new user in the database.
     */
    public function store()
    {
        // Basic validation
        if (empty($_POST['name']) || empty($_POST['mobile'])) {
            redirect_back_with_error('Name and mobile are required.');
        }

        User::create([
            'name' => $_POST['name'],
            'mobile' => $_POST['mobile'],
            'status' => $_POST['status'] ?? 'active'
        ]);

        header('Location: /users');
        exit();
    }

    /**
     * Show the form for editing a specific user.
     *
     * @param int $id
     */
    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            // Handle user not found
            redirect_back_with_error('User not found.');
        }

        return view('main', 'users/edit', [
            'title' => 'ویرایش کاربر',
            'user' => $user
        ]);
    }

    /**
     * Update an existing user in the database.
     *
     * @param int $id
     */
    public function update($id)
    {
        $user = User::find($id);
        if (!$user) {
            redirect_back_with_error('User not found.');
        }

        // Basic validation
        if (empty($_POST['name']) || empty($_POST['mobile'])) {
            redirect_back_with_error('Name and mobile are required.');
        }

        User::update($id, [
            'name' => $_POST['name'],
            'mobile' => $_POST['mobile'],
            'status' => $_POST['status'] ?? 'active'
        ]);

        header('Location: /users');
        exit();
    }

    /**
     * Delete a user.
     *
     * @param int $id
     */
    public function delete($id)
    {
        // In a real app, you'd want to use POST for deletion and add CSRF protection.
        // For simplicity, we'll use GET for now as defined in routes.
        User::delete($id);
        header('Location: /users');
        exit();
    }
}
