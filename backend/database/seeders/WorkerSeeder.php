<?php

namespace Database\Seeders;

use App\Models\File;
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
            ->count(10)
            ->has(
                Ticket::factory()
                    ->count(4)
                    ->state(function (array $attributes, Worker $worker) {
                        return ['worker_id' => $worker->id];
                    })
                    ->has(
                        File::factory()
                            ->count(2)
                            ->state(function (array $attributes, Ticket $ticket) {
                                return ['worker_id' => $ticket->worker_id];
                            })
                    )
            )
            ->create();



    }
}
