<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tblmadminusers')->insert([
            'firstname' => Str::random(10),
            'lastname' => Str::random(10),
            'email' => 'richard@trakastar.com',
            'password' => Hash::make('richard'),
        ]);

    }
}
