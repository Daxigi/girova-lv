<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Type;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);

        // Crear categorías
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

        // Crear tipos
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

        // Crear productos
        $this->call(ProductSeeder::class);

        // Crear 1 usuario admin
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Crear 1 usuario employee
        $employee = User::factory()->create([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
        ]);
        $employee->assignRole('employee');

        // Crear primer usuario customer
        $customer = User::factory()->create([
            'name' => 'customer one User',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password'),
        ]);
        $customer->assignRole('customer');

        // Crear segundo usuario customer
        $customer = User::factory()->create([
            'name' => 'customer two User',
            'email' => 'customer2@example.com',
            'password' => Hash::make('password'),
        ]);
        $customer->assignRole('customer');

        // Crear 5 usuarios employee
        User::factory()->count(5)->create()->each(function ($user) {
            $user->assignRole('employee');
        });

        // Crear 10 usuarios customer
        User::factory()->count(10)->create()->each(function ($user) {
            $user->assignRole('customer');
        });
    }
}
