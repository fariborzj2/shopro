<?php

namespace App\Controllers;

use App\Core\Database;

class UsersController
{
    /**
     * Show a list of all users.
     */
    public function index()
    {
        // For now, we'll use mock data.
        // Later, this will come from the database.
        // $users = Database::query("SELECT * FROM users")->fetchAll();

        $users = [
            ['id' => 1, 'name' => 'علی رضایی', 'mobile' => '09123456789', 'status' => 'فعال', 'created_at' => '2023-10-26'],
            ['id' => 2, 'name' => 'مریم احمدی', 'mobile' => '09129876543', 'status' => 'مسدود', 'created_at' => '2023-10-25'],
            ['id' => 3, 'name' => 'رضا حسینی', 'mobile' => '09121112233', 'status' => 'فعال', 'created_at' => '2023-10-24'],
        ];

        return view('main', 'users/index', [
            'title' => 'مدیریت کاربران',
            'users' => $users
        ]);
    }

    /**
     * Show the form for editing a specific user.
     *
     * @param int $id
     */
    public function edit($id)
    {
        echo "<h1>Editing user with ID: {$id}</h1>";
    }
}
