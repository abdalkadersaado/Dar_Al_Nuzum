<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $user = new User();

        $user->name = 'Mr.Admin';
        $user->email = 'admin@gmail.com';
        $user->password = Hash::make('12345678');
        $user->role_id = 1;
        $user->created_at = Carbon::now()->toDateTimeString();
        $user->save();
    }
}
