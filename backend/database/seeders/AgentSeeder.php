<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Agent::create([
            'name' => 'Marko Markovic',
            'email' => 'marko@gmail.com',
            'department' => 'IT',
            'password' => bcrypt('password'),]);

        Agent::create([
            'name' => 'Ivan Ivanovic',
            'email' => 'ivan@gmail.com',
            'department' => 'HR',
            'password' => bcrypt('password'),]);

        Agent::create([
            'name' => 'Luka Lukic',
            'email' => 'luka@gmail.com',
            'department' => 'Finance',
            'password' => bcrypt('password'),]);

        Agent::create([
            'name' => 'Matej Matejic',
            'email' => 'matej@gmail.com',
            'department' => 'Marketing',
            'password' => bcrypt('password'),]);

        Agent::create([
            'name' => 'Leon Leonovic',
            'email' => 'leo@gmail.com',
            'department' => 'Sales',
            'password' => bcrypt('password'),]);
    }
}
