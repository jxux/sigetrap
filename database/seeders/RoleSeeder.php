<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder{

    public function run(){

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role1 = Role::create(['name' => 'Master']);
        $role2 = Role::create(['name' => 'Administradores']);
        $role3 = Role::create(['name' => 'Basico']);
        $role4 = Role::create(['name' => 'Supervisor']);

        Permission::create(['name' => 'index','description'=> 'Ver Dashboard'])->syncRoles($role1, $role2, $role3, $role4);

        Permission::create(['name' => 'users.index','description'=> 'Ver listado de usuarios'])->syncRoles($role1, $role2);
        Permission::create(['name' => 'users.edit','description'=> 'Asignar un rol'])->syncRoles($role1, $role2);
        Permission::create(['name' => 'users.create','description'=> 'Crear usuarios'])->syncRoles($role1, $role2);

        Permission::create(['name' => 'roles.index','description'=> 'Ver listado de roles'])->syncRoles($role1, $role2);
        // Permission::create(['name' => 'roles.create','description'=> 'Crear rol'])->syncRoles($role1, $role2);

        Permission::create(['name' => 'accounts.index','description'=> 'Ver listado de Cuentas'])->syncRoles($role1, $role2);
        Permission::create(['name' => 'accounts.create','description'=> 'Crear Cuentas'])->syncRoles($role1, $role2);
        Permission::create(['name' => 'accounts.edit','description'=> 'Editar Cuentas'])->syncRoles($role1, $role2);
        Permission::create(['name' => 'accounts.destroy','description'=> 'Eliminar Cuentas'])->syncRoles($role1, $role2);

        Permission::create(['name' => 'costs.index','description'=> 'Ver listado de centros de costos'])->syncRoles($role1, $role2);
        Permission::create(['name' => 'costs.create','description'=> 'Crear centros de costos'])->syncRoles($role1, $role2);
        Permission::create(['name' => 'costs.edit','description'=> 'Editar centros de costos'])->syncRoles($role1, $role2);
        Permission::create(['name' => 'costs.destroy','description'=> 'Eliminar centros de costos'])->syncRoles($role1, $role2);
        
        Permission::create(['name' => 'persons.index','description'=> 'Ver listado de clientes'])->syncRoles($role1, $role2);
        Permission::create(['name' => 'persons.create','description'=> 'Crear clientes'])->syncRoles($role1, $role2);
        Permission::create(['name' => 'persons.edit','description'=> 'Editar clientes'])->syncRoles($role1, $role2);
        Permission::create(['name' => 'persons.destroy','description'=> 'Eliminar clientes'])->syncRoles($role1, $role2);
        
        Permission::create(['name' => 'binnacles.index','description'=> 'Ver listado de bitacoras'])->syncRoles($role1, $role2, $role3, $role4);
        Permission::create(['name' => 'binnacles.create','description'=> 'Crear bitacoras'])->syncRoles($role1, $role2, $role3, $role4);
        Permission::create(['name' => 'binnacles.edit','description'=> 'Editar bitacoras'])->syncRoles($role1, $role2, $role3, $role4);
        Permission::create(['name' => 'binnacles.destroy','description'=> 'Eliminar bitacoras'])->syncRoles($role1, $role2, $role3, $role4);

        Permission::create(['name' => 'reviewers.index','description'=> 'Ver listado de bitacoras para observacion'])->syncRoles($role1, $role2, $role4);
        Permission::create(['name' => 'reviewers.create','description'=> 'Crear observaciones en bitacoras'])->syncRoles($role1, $role2, $role4);
        Permission::create(['name' => 'reviewers.edit','description'=> 'Editar observaciones en bitacoras'])->syncRoles($role1, $role2, $role4);
        Permission::create(['name' => 'reviewers.destroy','description'=> 'Eliminar observaciones en bitacoras'])->syncRoles($role1, $role2, $role4);

    }
}
