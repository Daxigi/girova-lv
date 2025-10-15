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

        // Crear usuario de prueba
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Asignar rol de admin
        $user->assignRole('admin');

        // Crear usuario customer de prueba
        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
        ]);

        // Asignar rol de customer
        $customer->assignRole('customer');
    }
}
