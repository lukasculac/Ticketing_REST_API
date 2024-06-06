<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending']);

        return [
            'worker_id' => Worker::factory(),
            'department' => $this->faker->randomElement(['IT', 'HR', 'Finance', 'Marketing', 'Sales']),
            'message' => $this->faker->sentence,
            'status' => $status,
            'priority' => $this->faker->randomElement(['low']),
            'opened_at' => $status === 'pending' ? NULL : $this->faker->dateTimeBetween('-1 year', 'now') ,
            'closed_at' => $status !== 'closed' ? NULL : $this->faker->dateTimeBetween('-1 year', 'now')
        ];
    }
}
