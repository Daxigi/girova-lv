<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);

        Category::create([
            'name' => 'Acero Blanco',
            'description' => 'Productos de acero blanco',
            'status' => true,
        ]);

        Category::create([
            'name' => 'Acero Dorado',
            'description' => 'Productos de acero dorado',
            'status' => true,
        ]);

        Type::create([
            'name' => 'Aro',
            'description' => 'Aros y pendientes',
            'status' => true,
        ]);

        Type::create([
            'name' => 'Anillo',
            'description' => 'Anillos',
            'status' => true,
        ]);

        $this->call(ProductSeeder::class);

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        $employee = User::factory()->create([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
        ]);
        $employee->assignRole('employee');

        $customer = User::factory()->create([
            'name' => 'customer one User',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password'),
        ]);
        $customer->assignRole('customer');

        $customer = User::factory()->create([
            'name' => 'customer two User',
            'email' => 'customer2@example.com',
            'password' => Hash::make('password'),
        ]);
        $customer->assignRole('customer');

        User::factory()->count(5)->create()->each(function ($user) {
            $user->assignRole('employee');
        });

        User::factory()->count(10)->create()->each(function ($user) {
            $user->assignRole('customer');
        });
    }
}
