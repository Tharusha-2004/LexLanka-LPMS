<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        |----------------------------------------------------------------------
        | Default Partner Account — LexLanka LPMS
        |----------------------------------------------------------------------
        |
        | This seed creates the initial Partner account used to log in and
        | bootstrap the system. Change the password after first login.
        |
        | Login credentials:
        |   Email:    partner@lexlanka.com
        |   Password: password
        |
        */
        User::updateOrCreate(
            ['email' => 'partner@lexlanka.com'],
            [
                'name'                 => 'Senior Partner',
                'password'             => Hash::make('password'),
                'role'                 => 'partner',
                'status'               => 'active',
                'branch'               => 'Colombo Head Office',
                'flat_appearance_rate' => 0.00,
            ]
        );
    }
}
