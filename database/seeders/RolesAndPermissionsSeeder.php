<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        $roles = ['Admin', 'Manager', 'Sales', 'agent'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
