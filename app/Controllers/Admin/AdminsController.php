<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Models\Admin;

class AdminsController
{
    /**
     * List of available permissions/modules.
     *
     * @return array
     */
    public static function permissionsList()
    {
        return [
            'dashboard' => 'داشبورد (مشاهده آمار)',
            'users' => 'مدیریت کاربران',
            'categories' => 'مدیریت دسته‌بندی‌ها',
            'products' => 'مدیریت محصولات',
            'media' => 'کتابخانه رسانه',
            'custom_fields' => 'مدیریت پارامترها',
            'orders' => 'مدیریت سفارشات',
            'settings' => 'تنظیمات سایت',
            'blog' => 'مدیریت بلاگ',
            'pages' => 'مدیریت صفحات',
            'faq' => 'مدیریت سوالات متداول',
            'reviews' => 'مدیریت نظرات',
        ];
    }

    /**
     * Ensure the current user is a Super Admin.
     */
    private function checkSuperAdmin()
    {
        $adminId = $_SESSION['admin_id'] ?? null;
        if (!$adminId) {
            redirect('/admin/login');
        }

        $admin = Admin::find($adminId);
        if (!$admin || !$admin->isSuperAdmin()) {
            // Using a simple 403 message or redirect with error
            header('HTTP/1.1 403 Forbidden');
            echo "<h1>403 Forbidden</h1><p>شما اجازه دسترسی به این بخش را ندارید.</p>";
            exit;
        }
    }

    public function index()
    {
        $this->checkSuperAdmin();

        $admins = Admin::all();
        return view('main', 'admins/index', [
            'title' => 'مدیریت مدیران',
            'admins' => $admins
        ]);
    }

    public function create()
    {
        $this->checkSuperAdmin();

        return view('main', 'admins/create', [
            'title' => 'افزودن مدیر جدید',
            'permissions_list' => self::permissionsList()
        ]);
    }

    public function store()
    {
        $this->checkSuperAdmin();

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $email = $_POST['email'] ?? '';
        $name = $_POST['name'] ?? '';
        $role = $_POST['role'] ?? '';
        $permissions = $_POST['permissions'] ?? []; // Array of selected permissions
        $status = $_POST['status'] ?? 'active';

        if (empty($username) || empty($password) || empty($email) || empty($role)) {
            return redirect_back_with_error('لطفا تمام فیلدهای ضروری (نام کاربری، رمز عبور، ایمیل، سمت) را پر کنید.');
        }

        if (empty($permissions)) {
             return redirect_back_with_error('لطفا حداقل یک دسترسی برای مدیر انتخاب کنید.');
        }

        // Check uniqueness
        if (Admin::findByUsername($username)) {
            return redirect_back_with_error('این نام کاربری قبلا ثبت شده است.');
        }

        // Create Admin
        $adminId = Admin::create([
            'username' => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $email,
            'name' => $name,
            'role' => $role,
            'is_super_admin' => 0, // Only manual DB access can make super admin for now to be safe
            'permissions' => $permissions,
            'status' => $status
        ]);

        if ($adminId) {
            return redirect_with_success('/admin/admins', 'مدیر با موفقیت ایجاد شد.');
        } else {
            return redirect_back_with_error('خطا در ایجاد مدیر.');
        }
    }

    public function edit($id)
    {
        $this->checkSuperAdmin();

        $admin = Admin::find($id);
        if (!$admin) {
            return redirect_back_with_error('مدیر یافت نشد.');
        }

        return view('main', 'admins/edit', [
            'title' => 'ویرایش مدیر',
            'admin' => $admin,
            'permissions_list' => self::permissionsList()
        ]);
    }

    public function update($id)
    {
        $this->checkSuperAdmin();

        $admin = Admin::find($id);
        if (!$admin) {
            return redirect_back_with_error('مدیر یافت نشد.');
        }

        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $name = $_POST['name'] ?? '';
        $role = $_POST['role'] ?? '';
        $permissions = $_POST['permissions'] ?? [];
        $status = $_POST['status'] ?? 'active';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($email) || empty($role)) {
            return redirect_back_with_error('لطفا تمام فیلدهای ضروری را پر کنید.');
        }

        if (empty($permissions)) {
             return redirect_back_with_error('لطفا حداقل یک دسترسی برای مدیر انتخاب کنید.');
        }

        $data = [
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'role' => $role,
            'status' => $status,
            'permissions' => $permissions,
            // Prevent changing is_super_admin via UI for safety, or allow if needed.
            // Assuming super admin status is protected.
            'is_super_admin' => $admin['is_super_admin']
        ];

        if (!empty($password)) {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if (Admin::update($id, $data)) {
            return redirect_with_success('/admin/admins', 'اطلاعات مدیر با موفقیت ویرایش شد.');
        } else {
            return redirect_back_with_error('خطا در ویرایش مدیر.');
        }
    }

    public function delete($id)
    {
        $this->checkSuperAdmin();

        $admin = Admin::find($id);
        if (!$admin) {
            return redirect_back_with_error('مدیر یافت نشد.');
        }

        // Prevent deleting self
        if ($admin->id == $_SESSION['admin_id']) {
            return redirect_back_with_error('شما نمی‌توانید حساب خود را حذف کنید.');
        }

        // Prevent deleting other super admins (optional safeguard)
        if (Admin::isSuperAdmin($admin)) {
             return redirect_back_with_error('امکان حذف مدیر کل وجود ندارد.');
        }

        if (Admin::delete($id)) {
            return redirect_with_success('/admin/admins', 'مدیر با موفقیت حذف شد.');
        } else {
            return redirect_back_with_error('خطا در حذف مدیر.');
        }
    }
}
