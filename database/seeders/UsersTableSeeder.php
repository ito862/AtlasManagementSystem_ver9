<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                'id' => 1,
                'over_name' => 'コンパス',
                'under_name' => '太郎',
                'over_name_kana' => 'コンパス',
                'under_name_kana' => 'タロウ',
                'mail_address' => 'kaishukadai@email.com',
                'sex' => '1',
                'birth_day' => '2000-01-01',
                'role' => '3',
                'password' => Hash::make('test8833'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]
        );
    }
}
