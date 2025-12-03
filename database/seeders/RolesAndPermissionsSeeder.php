<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'view customers', 'create customers', 'edit customers', 'delete customers',
            'view products', 'create products', 'edit products', 'delete products',
            'view invoices', 'create invoices', 'edit invoices', 'delete invoices',
            'view payments', 'create payments', 'edit payments', 'delete payments',
            'export reports', 'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        $accountant = Role::firstOrCreate(['name' => 'Accountant']);
        $accountant->givePermissionTo([
            'view customers', 'view products',
            'view invoices', 'create invoices', 'edit invoices', 'view payments', 'create payments', 'edit payments', 'export reports',
        ]);

        $sales = Role::firstOrCreate(['name' => 'Sales']);
        $sales->givePermissionTo([
            'view customers', 'view products',
            'create invoices', 'view invoices',
        ]);
    }
}
