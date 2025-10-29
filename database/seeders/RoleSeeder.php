<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetear cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles
        $adminRole = Role::create(['name' => 'admin']);
        $customerRole = Role::create(['name' => 'customer']);
        $employeeRole = Role::create(['name'=> 'employee']);

        // Asignar TODOS los permisos al admin
        $adminRole->givePermissionTo(Permission::all());

        // Asignar permisos limitados al customer
        $customerRole->givePermissionTo([
            'view products',
            'view orders',
        ]);

        $employeeRole->givePermissionTo([
            'view products',
            'create products',
            'edit products',
            'manage categories',
            'manage types',
            'view orders'
        ]);
    }
}
