<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'علی',
            'family' => 'شریفی',
            'mobile' => '09111111111',
            'username' => 'admin',
            'password' => 'admin',
        ]);
    }
}
