<?php

use Illuminate\Database\Seeder;
use App\User;

class AdminUserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'admin';
        $user->email = 'admin@admin.com';
        $user->password = Hash::make('a');
        $user->type = User::INTERNAL;
        $user->status = User::APROBADO;

        $user->save();
    }
}
