<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Files>
 */
class FilesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'original_file_name' => $this->faker->word . '.' . $this->faker->fileExtension, // Generates a random word and file extension
            'file_name' => Str::random(10) . '.' . $this->faker->fileExtension, // Generates a random string and file extension
            'file_size' => $this->faker->numberBetween(1000, 1000000), // Generates a random file size in bytes (adjust range as needed)
            'file_url' => $this->faker->url, // Generates a random URL
            'file_type' => $this->faker->mimeType, // Generates a random MIME type
            'title' => $this->faker->sentence, // Generates a random sentence
            'description' => $this->faker->paragraph, // Generates a random paragraph
            'sender_id' =>  \App\Models\User::factory(), // Creates a related User model and returns its ID.  Adjust if using a different model
            'receiver_id' => \App\Models\User::factory(), // Creates a related User model and returns its ID. Adjust if using a different model
            'subject' => $this->faker->sentence, // Generates a random sentence
            'dept_in_request' => $this->faker->company(), // Generates a random word (you might want to use a specific set of departments)
            'assigned_to' => \App\Models\User::factory(), // Creates a related User model and returns its ID. Adjust if using a different model
            'assigned_from' => \App\Models\User::factory(), // Creates a related User model and returns its ID. Adjust if using a different model
            'comment' => $this->faker->text, // Generates random text
            'archived' => $this->faker->boolean, // Generates a random boolean value
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'failed']), // Generates a random status from the given array
        ];
    }
}
