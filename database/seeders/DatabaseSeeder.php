<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $user = User::create([
        //     "name" => "user",
        //     "email" => "user@gmail.com",
        //     "password" => Hash::make("user"),
        // ]);
        // User::factory(10)->create();
        Task::factory(10)->create(["user_id" => '0196bbd2-679a-70a5-a46d-f36bf803c7ed']);
    }
}
