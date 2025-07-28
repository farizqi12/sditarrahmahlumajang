<?php

namespace Database\Factories;

use App\Models\Submission;
use App\Models\Assignment;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Submission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assignment_id' => Assignment::factory(),
            'student_id' => Student::factory(),
            'submission_date' => $this->faker->dateTimeThisMonth(),
            'file_path' => $this->faker->boolean(50) ? $this->faker->url : null,
            'grade' => $this->faker->boolean(70) ? $this->faker->randomFloat(2, 0, 100) : null,
            'feedback' => $this->faker->boolean(30) ? $this->faker->paragraph : null,
        ];
    }
}
