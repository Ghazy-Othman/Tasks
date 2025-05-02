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
        $user = User::create([
            "name"=> "user",
            "email"=> "user@gmail.com",
            "password"=> Hash::make("user"),
        ]) ;

        Task::factory(10)->create(["user_id" => $user->user_id]) ;
    }
}
