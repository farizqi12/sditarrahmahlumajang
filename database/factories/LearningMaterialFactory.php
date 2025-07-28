<?php

namespace Database\Factories;

use App\Models\LearningMaterial;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LearningMaterialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LearningMaterial::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'class_id' => ClassModel::factory(),
            'subject_id' => Subject::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'file_path' => $this->faker->boolean(50) ? $this->faker->url : null,
            'url' => $this->faker->boolean(50) ? $this->faker->url : null,
            'type' => $this->faker->randomElement(['document', 'video', 'link', 'other']),
            'uploaded_by' => User::factory(),
        ];
    }
}
