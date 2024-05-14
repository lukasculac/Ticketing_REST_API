<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\Worker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Worker::factory()
            ->count(30)
            ->has(
                Ticket::factory()
                    ->count(5)
                    ->hasFile(3),
                'ticket'
            )
            ->create();
    }
}
