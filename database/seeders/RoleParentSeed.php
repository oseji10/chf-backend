<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoleParent;

class RoleParentSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create parent roles for the default roles that we already have
        //For super admin
        // RoleParent::create([
        //     'role_id' => 3,
        //     'parent_role_id' => 5,
        // ]);
        // RoleParent::create([
        //     'role_id' => 6,
        //     'parent_role_id' => 5,
        // ]);
        // RoleParent::create([
        //     'role_id' => 4,
        //     'parent_role_id' => 5,
        // ]);

        // For FMOH F&A
        RoleParent::create([
            'role_id' => 9,
            'parent_role_id' => 5,
        ]);

        //For coe admin
        // RoleParent::create([
        //     'role_id' => 2,
        //     'parent_role_id' => 3,
        // ]);
        // RoleParent::create([
        //     'role_id' => 6,
        //     'parent_role_id' => 3,
        // ]);

        //for chf admin
        // RoleParent::create([
        //     'role_id' => 7,
        //     'parent_role_id' => 4,
        // ]);


    }
}
