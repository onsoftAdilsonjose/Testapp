<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;


class PermissionSeeder extends Seeder
{
    public function run()
    {








  $adminRole = Role::where('name', 'Admin')->first();


$viewPaymentPermission = Permission::create(['name' => 'view_payment','description'=>'View-payment','slug'=>'View-Payment']);
$createPaymentPermission = Permission::create(['name' => 'create_payment','description'=>'Create-payment','slug'=>'Create-Payment']);
$updatePaymentPermission = Permission::create(['name' => 'update_payment','description'=>'Update-payment','slug'=>'Update-Payment']);
$deletePaymentPermission = Permission::create(['name' => 'delete_payment','description'=>'Delete-payment','slug'=>'Delete-Payment']);



if ($adminRole) {
    $adminRole->permissions()->sync([
        $viewPaymentPermission->id,
        $createPaymentPermission->id,
        $updatePaymentPermission->id,
        $deletePaymentPermission->id,
    ]);
}






    }
}
