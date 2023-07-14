<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ailment;
use App\Models\COE;
use App\Models\GeopoliticalZone;
use App\Models\IdentificationDocument;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\State;
use App\Models\User;
use App\Models\Wallet;
use Database\Factories\RoleFactory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class coeAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //Update some users to coe_admin
        $user=User::find(6);
        $user->roles()->attach([3]);
        $user->update(['coe_id' => 2]);
        $user->wallet()->update([
            'is_coe' => 1,
            'coe_id'=>2
        ]);

        $user=User::find(7);
        $user->roles()->attach([3]);
        $user->update(['coe_id' => 3]);
        $user->wallet()->update([
            'is_coe' => 1,
            'coe_id'=>3
        ]);

        $user=User::find(8);
        $user->roles()->attach([3]);
        $user->update(['coe_id' => 4]);
        $user->wallet()->update([
            'is_coe' => 1,
            'coe_id'=>4
        ]);

        $user=User::find(9);
        $user->roles()->attach([3]);
        $user->update(['coe_id' => 5]);
        $user->wallet()->update([
            'is_coe' => 1,
            'coe_id'=>5
        ]);

        $user=User::find(10);
        $user->roles()->attach([3]);
        $user->update(['coe_id' => 6]);
        $user->wallet()->update([
            'is_coe' => 1,
            'coe_id'=>6
        ]);

        $user=User::find(11);
        $user->roles()->attach([3]);
        $user->update(['coe_id' => 7]);
        $user->wallet()->update([
            'is_coe' => 1,
            'coe_id'=>7
        ]);

    }
}
