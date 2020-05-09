<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role = Role::create(['name' => 'administrator']);
        $moderatorRole = Role::create(['name'=>'moderator']);
        $attendeeRole  = Role::create(['name'=>'attendee']);
        $moderatorRole->givePermissionTo('moderate');
        $attendeeRole->givePermissionTo('None');
        $role->givePermissionTo('users_manage');
        $role->givePermissionTo('master_manage');
    }
}
