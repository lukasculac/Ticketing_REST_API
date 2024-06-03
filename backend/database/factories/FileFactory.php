<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Ticket;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    protected $model = File::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'worker_id' => Worker::factory(),
            'file_name' => $this->faker->word,
            'path' => $this->faker->word,
        ];
    }
}
