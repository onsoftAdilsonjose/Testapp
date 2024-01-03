<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {


    	      // Create "admin" role
        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrator role with full access',
        ]);
        // Create "user" role
        $professorRole = Role::create([
            'name' => 'Professor',
            'slug' => 'professor',
            'description' => 'teaching a requisite number of graduate classes',
        ]);
  
        // Create "admin" role
        $encarregadoRole = Role::create([
            'name' => 'Encarregado',
            'slug' => 'encarregado',
            'description' => 'Being a Guardian includes making sure that the children are fed',
        ]);
        // Create "admin" role
        $estudanteRole = Role::create([
            'name' => 'Estudante',
            'slug' => 'estudante',
            'description' => 'As learners, students play a crucial and active role in education',
        ]);
        // Get all permissions 
               // Create "user" role
        $funcionarioRole = Role::create([
            'name' => 'Funcionario',
            'slug' => 'funcionario',
            'description' => 'to do their work carefully and seriously',
        ]);
        $allPermissions = Permission::all();

        // Assign all permissions to the "admin" role
        $adminRole->permissions()->attach($allPermissions->pluck('id')->toArray());
    }
}
